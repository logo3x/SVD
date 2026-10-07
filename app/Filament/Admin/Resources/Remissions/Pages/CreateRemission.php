<?php

namespace App\Filament\Admin\Resources\Remissions\Pages;

use App\Actions\SendRemissionEmailAction;
use App\Enums\RemissionStatus;
use App\Filament\Admin\Resources\Remissions\RemissionResource;
use App\Filament\Concerns\HandlesSignatureDataUrl;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRemission extends CreateRecord
{
    use HandlesSignatureDataUrl;

    protected static string $resource = RemissionResource::class;

    /** Buffer del data URI hasta el afterCreate. */
    protected ?string $signatureDataUrl = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Guarda el data URI y NO lo persiste como columna del modelo.
        $this->signatureDataUrl = $data['signature_data_url'] ?? null;
        unset($data['signature_data_url']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Las líneas las guarda el Repeater (relación items); el total se calcula después.
        $this->record->recalculateTotal();

        // Si firmaron en pantalla, guarda el PNG como media file.
        $this->persistSignatureDataUrl($this->record, $this->signatureDataUrl);

        if ($this->record->status !== RemissionStatus::Confirmed) {
            return;
        }

        $sentTo = app(SendRemissionEmailAction::class)->execute($this->record->fresh(['client', 'user', 'products']));

        $sentTo === null
            ? Notification::make()
                ->title('Remisión guardada, pero el correo no se pudo enviar')
                ->body('Puede reenviarlo desde el detalle con "Reenviar copia".')
                ->warning()
                ->send()
            : Notification::make()
                ->title('Comprobante enviado')
                ->body('Email enviado a '.$sentTo.' destinatarios.')
                ->success()
                ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
