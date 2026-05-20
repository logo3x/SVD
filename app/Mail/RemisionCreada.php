<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Remission;
use App\Services\RemissionInvoicePdf;
use App\Settings\BrandingSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RemisionCreada extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Remission $remission,
        public bool $isCopy = false,
    ) {
        $this->remission->loadMissing(['client', 'user', 'products']);
    }

    public function envelope(): Envelope
    {
        $prefix = $this->isCopy ? '[COPIA] ' : '';

        return new Envelope(
            subject: $prefix.'Remisión #'.$this->remission->id.' · '.($this->remission->client?->name ?? 'Cliente'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.remission-created',
            with: [
                'remission' => $this->remission,
                'isCopy' => $this->isCopy,
                'branding' => app(BrandingSettings::class),
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = app(RemissionInvoicePdf::class);

        return [
            Attachment::fromData(fn () => $pdf->asString($this->remission), $pdf->filename($this->remission))
                ->withMime('application/pdf'),
        ];
    }
}
