<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentType;
use App\Models\Remission;
use App\Settings\BrandingSettings;

class RemissionEmailRouter
{
    public function __construct(private BrandingSettings $branding) {}

    /**
     * Devuelve la lista de destinatarios "to" para una remisión, según su tipo de pago.
     * Siempre incluye el buzón principal + el correo del cliente cuando exista.
     *
     * @return array<int, string>
     */
    public function recipientsFor(Remission $remission, ?string $extra = null): array
    {
        $recipients = [$this->branding->email_inbox];

        match ($remission->payment_type) {
            PaymentType::Cash => $recipients[] = $this->branding->email_cash,
            PaymentType::CashForBilling => $recipients[] = $this->branding->email_cash_for_billing,
            PaymentType::Credit => $recipients[] = $this->branding->email_credit,
            default => null,
        };

        if ($remission->client?->email) {
            $recipients[] = $remission->client->email;
        }

        if ($extra) {
            $recipients[] = $extra;
        }

        return array_values(array_unique(array_filter($recipients)));
    }
}
