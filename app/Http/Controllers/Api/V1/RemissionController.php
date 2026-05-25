<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RemissionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SignatureRequest;
use App\Http\Requests\Api\V1\StoreRemissionRequest;
use App\Http\Resources\Api\V1\RemissionResource;
use App\Mail\RemisionCreada;
use App\Models\Remission;
use App\Services\RemissionEmailRouter;
use App\Services\RemissionInvoicePdf;
use App\Services\RemissionsXlsxExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RemissionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $remissions = Remission::query()
            ->with(['client:id,name,nit', 'user:id,name'])
            ->when($request->date('from'), fn (Builder $q, $d) => $q->whereDate('issued_at', '>=', $d))
            ->when($request->date('to'), fn (Builder $q, $d) => $q->whereDate('issued_at', '<=', $d))
            ->when($request->integer('client_id'), fn (Builder $q, $v) => $q->where('client_id', $v))
            ->when($request->boolean('mine'), fn (Builder $q) => $q->where('user_id', $request->user()->id))
            ->orderByDesc('issued_at')
            ->paginate(25);

        return RemissionResource::collection($remissions);
    }

    public function store(StoreRemissionRequest $request): RemissionResource
    {
        $payload = $request->validated();

        $remission = DB::transaction(function () use ($payload, $request) {
            $items = collect($payload['items'])->map(fn (array $item) => [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
                'unit_price_snapshot' => (int) $item['unit_price_snapshot'],
                'subtotal' => (int) $item['quantity'] * (int) $item['unit_price_snapshot'],
            ]);

            $remission = Remission::create([
                'client_id' => $payload['client_id'],
                'user_id' => $request->user()->id,
                'issued_at' => $payload['issued_at'] ?? now(),
                'route' => $payload['route'],
                'payment_type' => $payload['payment_type'],
                'status' => $payload['status'] ?? RemissionStatus::Confirmed->value,
                'observations' => $payload['observations'] ?? null,
                'gps_lat' => $payload['gps_lat'] ?? null,
                'gps_lng' => $payload['gps_lng'] ?? null,
                'total_amount' => $items->sum('subtotal'),
            ]);

            foreach ($items as $item) {
                $remission->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price_snapshot' => $item['unit_price_snapshot'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            return $remission;
        });

        $remission->load(['client', 'user', 'products']);

        if ($remission->status === RemissionStatus::Confirmed) {
            $recipients = app(RemissionEmailRouter::class)->recipientsFor($remission);
            Mail::to($recipients)->queue(new RemisionCreada($remission));
        }

        return RemissionResource::make($remission);
    }

    public function show(Remission $remission): RemissionResource
    {
        return RemissionResource::make($remission->load(['client', 'user', 'products']));
    }

    /**
     * Descarga el PDF del comprobante de una remisión.
     * Mismo formato que se ve en /admin/remissions/{id} → Imprimir PDF.
     * Scope: super_admin = todas; resto = sólo las propias.
     */
    public function pdf(Request $request, Remission $remission): Response
    {
        $user = $request->user();
        if (! $user->hasRole('super_admin') && $remission->user_id !== $user->id) {
            abort(403, 'No tienes acceso a esta remisión.');
        }

        $pdf = app(RemissionInvoicePdf::class);
        $binary = $pdf->asString($remission);

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$pdf->filename($remission).'"',
            'Content-Length' => (string) strlen($binary),
        ]);
    }

    public function signature(SignatureRequest $request, Remission $remission): RemissionResource
    {
        $remission->clearMediaCollection('signature');
        $remission->addMedia($request->file('signature'))
            ->toMediaCollection('signature', 'local');

        return RemissionResource::make($remission->load(['client', 'user', 'products']));
    }

    /**
     * Exporta las remisiones del vendedor logueado como XLSX.
     *
     * Filtros opcionales (mismos que GET /remissions):
     *   ?from=2026-05-01  &to=2026-05-31  &payment_type=credit  &status=confirmed  &all=1
     *
     * Por defecto el export está scoped al token (sólo las del vendedor).
     * Pasar `all=1` requiere que el usuario sea super_admin — útil para
     * un admin que llame el endpoint desde una herramienta interna.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Remission::query()
            ->when($request->date('from'), fn (Builder $q, $d) => $q->whereDate('issued_at', '>=', $d))
            ->when($request->date('to'), fn (Builder $q, $d) => $q->whereDate('issued_at', '<=', $d))
            ->when($request->integer('client_id'), fn (Builder $q, $v) => $q->where('client_id', $v))
            ->when($request->input('payment_type'), fn (Builder $q, $v) => $q->where('payment_type', $v))
            ->when($request->input('status'), fn (Builder $q, $v) => $q->where('status', $v))
            ->orderByDesc('issued_at');

        $isSuper = $request->user()->hasRole('super_admin');
        if (! $isSuper || ! $request->boolean('all')) {
            $query->where('user_id', $request->user()->id);
        }

        $scope = ($isSuper && $request->boolean('all')) ? 'todas' : 'mis';
        $filename = "{$scope}-remisiones-".now()->format('Ymd-His').'.xlsx';

        return app(RemissionsXlsxExporter::class)->streamDownload($query, $filename);
    }
}
