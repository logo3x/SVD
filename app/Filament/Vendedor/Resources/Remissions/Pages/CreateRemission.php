<?php

declare(strict_types=1);

namespace App\Filament\Vendedor\Resources\Remissions\Pages;

use App\Enums\RemissionStatus;
use App\Filament\Vendedor\Resources\Remissions\RemissionResource;
use App\Mail\RemisionCreada;
use App\Services\RemissionEmailRouter;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateRemission extends CreateRecord
{
    protected static string $resource = RemissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['total_amount'] = collect($data['items'] ?? [])->sum('subtotal');

        return $data;
    }

    protected function afterCreate(): void
    {
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
