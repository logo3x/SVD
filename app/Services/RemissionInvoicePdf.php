<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Remission;
use App\Settings\BrandingSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfInstance;
use Illuminate\Support\Facades\Storage;

class RemissionInvoicePdf
{
    public function __construct(private BrandingSettings $branding) {}

    public function render(Remission $remission): PdfInstance
    {
        $remission->loadMissing(['client', 'user', 'products', 'media']);

        $signature = $remission->getFirstMedia('signature');
        $signaturePath = $signature ? $signature->getPath() : null;

        return Pdf::loadView('pdf.remission', [
            'remission' => $remission,
            'branding' => $this->branding,
            'signaturePath' => $signaturePath,
            'logoPath' => $this->resolveLogoPath(),
        ])->setPaper('letter');
    }

    public function asString(Remission $remission): string
    {
        return $this->render($remission)->output();
    }

    public function filename(Remission $remission): string
    {
        return 'remision-'.str_pad((string) $remission->id, 6, '0', STR_PAD_LEFT).'.pdf';
    }

    /**
     * Devuelve el path absoluto del logo en disco, o null si no hay
     * logo configurado. DomPDF necesita un path file:// o absoluto
     * para embeber imágenes.
     */
    private function resolveLogoPath(): ?string
    {
        $path = $this->branding->logo_path;

        if (! $path) {
            return null;
        }

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->path($path);
    }
}
