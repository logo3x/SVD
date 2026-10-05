<?php

namespace App\Filament\Admin\Resources\Remissions\Pages;

use App\Filament\Admin\Resources\Remissions\RemissionResource;
use App\Filament\Concerns\HandlesSignatureDataUrl;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRemission extends EditRecord
{
    use HandlesSignatureDataUrl;

    protected static string $resource = RemissionResource::class;

    /** Buffer del data URI hasta el afterSave. */
    protected ?string $signatureDataUrl = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_amount'] = collect($data['items'] ?? [])->sum('subtotal');

        $this->signatureDataUrl = $data['signature_data_url'] ?? null;
        unset($data['signature_data_url']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->persistSignatureDataUrl($this->record, $this->signatureDataUrl);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
