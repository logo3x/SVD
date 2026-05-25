<?php

namespace App\Filament\Admin\Resources\Remissions\Pages;

use App\Enums\RemissionStatus;
use App\Filament\Admin\Resources\Remissions\RemissionResource;
use App\Filament\Concerns\HandlesSignatureDataUrl;
use App\Mail\RemisionCreada;
use App\Services\RemissionEmailRouter;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateRemission extends CreateRecord
{
    use HandlesSignatureDataUrl;

    protected static string $resource = RemissionResource::class;

    /** Buffer del data URI hasta el afterCreate. */
    protected ?string $signatureDataUrl = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_amount'] = collect($data['items'] ?? [])->sum('subtotal');

        // Guarda el data URI y NO lo persiste como columna del modelo.
        $this->signatureDataUrl = $data['signature_data_url'] ?? null;
        unset($data['signature_data_url']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Si firmaron en pantalla, guarda el PNG como media file.
        $this->persistSignatureDataUrl($this->record, $this->signatureDataUrl);

        if ($this->record->status !== RemissionStatus::Confirmed) {
            return;
        }

        $recipients = app(RemissionEmailRouter::class)->recipientsFor($this->record);

        Mail::to($recipients)->queue(new RemisionCreada($this->record->fresh(['client', 'user', 'products'])));

        Notification::make()
            ->title('Comprobante enviado')
            ->body('Email encolado a '.count($recipients).' destinatarios.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
