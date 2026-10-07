<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Resources\Remissions\Pages;

use App\Actions\SendRemissionEmailAction;
use App\Enums\RemissionStatus;
use App\Filament\Concerns\HandlesSignatureDataUrl;
use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateRemission extends CreateRecord
{
    use HandlesSignatureDataUrl;

    protected static string $resource = RemissionResource::class;

    /** Buffer del data URI hasta el afterCreate. */
    protected ?string $signatureDataUrl = null;

    public function getTitle(): string
    {
        return 'Nueva Remisión';
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->label('Crear remisión');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->label('Cancelar');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        $this->signatureDataUrl = $data['signature_data_url'] ?? null;
        unset($data['signature_data_url']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Las líneas las guarda el Repeater (relación items); el total se calcula después.
        $this->record->recalculateTotal();

        $this->persistSignatureDataUrl($this->record, $this->signatureDataUrl);

        if ($this->record->status !== RemissionStatus::Confirmed) {
            return;
        }

        $sentTo = app(SendRemissionEmailAction::class)->execute($this->record->fresh(['client', 'user', 'products']));

        $sentTo === null
            ? Notification::make()
                ->title('Remisión guardada, pero el correo no se pudo enviar')
                ->body('Avise al administrador para reenviar el comprobante.')
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
