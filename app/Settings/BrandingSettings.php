<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class BrandingSettings extends Settings
{
    public string $company_name;

    public string $company_tagline;

    public string $primary_phone;

    public string $secondary_phone;

    public string $city;

    public string $address;

    /** Buzón principal donde siempre se copia */
    public string $email_inbox;

    /** Buzones específicos por tipo de pago */
    public string $email_cash;

    public string $email_cash_for_billing;

    public string $email_credit;

    public static function group(): string
    {
        return 'branding';
    }
}
