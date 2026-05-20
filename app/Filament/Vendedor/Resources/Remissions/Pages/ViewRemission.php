<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Resources\Remissions\Pages;

use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use App\Models\Remission;
use App\Services\RemissionInvoicePdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewRemission extends ViewRecord
{
    protected static string $resource = RemissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Imprimir PDF')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function (): StreamedResponse {
                    /** @var Remission $remission */
                    $remission = $this->record;
                    $pdf = app(RemissionInvoicePdf::class);

                    return response()->streamDownload(
                        fn () => print $pdf->asString($remission),
                        $pdf->filename($remission),
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
        ];
    }
}
