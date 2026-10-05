<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use Spatie\MediaLibrary\HasMedia;

/**
 * Procesa un data URI (base64 PNG) generado por el signature pad
 * y lo guarda en la media collection 'signature' del modelo.
 *
 * Usado por CreateRemission y EditRemission para soportar tanto
 * "firmar en pantalla" como "subir imagen" en el mismo form.
 */
trait HandlesSignatureDataUrl
{
    /**
     * Si viene `signature_data_url` con un PNG base64, lo persiste como
     * archivo en la colección 'signature'. Se debe llamar después de
     * crear/actualizar la remisión (afterCreate / afterSave).
     */
    protected function persistSignatureDataUrl(HasMedia $remission, ?string $dataUrl): void
    {
        if (! $dataUrl || ! str_starts_with($dataUrl, 'data:image/')) {
            return;
        }

        // Extrae el contenido base64 del data URI.
        $parts = explode(',', $dataUrl, 2);
        if (count($parts) !== 2) {
            return;
        }

        $binary = base64_decode($parts[1], true);
        if ($binary === false || $binary === '') {
            return;
        }

        $remission->clearMediaCollection('signature');
        $remission
            ->addMediaFromString($binary)
            ->usingFileName('firma-'.$remission->getKey().'-'.time().'.png')
            ->toMediaCollection('signature', 'local');
    }
}
