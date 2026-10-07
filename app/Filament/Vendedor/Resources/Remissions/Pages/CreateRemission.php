<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Resources\Remissions\Pages;

use App\Enums\RemissionStatus;
use App\Filament\Concerns\HandlesSignatureDataUrl;
use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use App\Mail\RemisionCreada;
use App\Services\RemissionEmailRouter;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
