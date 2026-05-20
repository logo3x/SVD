<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Remission;
use App\Settings\BrandingSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfInstance;

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
}
