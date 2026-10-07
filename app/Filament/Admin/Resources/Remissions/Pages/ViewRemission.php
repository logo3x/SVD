<?php

namespace App\Filament\Admin\Resources\Remissions\Pages;

use App\Actions\SendRemissionEmailAction;
use App\Filament\Admin\Resources\Remissions\RemissionResource;
use App\Models\Remission;
use App\Services\RemissionInvoicePdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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

            Action::make('resend')
                ->label('Reenviar copia')
                ->icon('heroicon-o-envelope')
                ->color('primary')
                ->schema([
                    TextInput::make('extra_email')
                        ->label('Correo adicional (opcional)')
                        ->email()
                        ->placeholder('cliente.copia@ejemplo.com'),
                ])
                ->action(function (array $data): void {
                    /** @var Remission $remission */
                    $remission = $this->record;
                    $sentTo = app(SendRemissionEmailAction::class)
                        ->execute($remission, $data['extra_email'] ?? null, isCopy: true);

                    $sentTo === null
                        ? Notification::make()
                            ->title('No se pudo enviar la copia')
                            ->body('Revise la configuración de correo del servidor.')
                            ->danger()
                            ->send()
                        : Notification::make()
                            ->title('Copia enviada')
                            ->body('Copia enviada a '.$sentTo.' destinatarios.')
                            ->success()
                            ->send();
                })
                ->modalSubmitActionLabel('Enviar copia'),

            EditAction::make(),
        ];
    }
}
