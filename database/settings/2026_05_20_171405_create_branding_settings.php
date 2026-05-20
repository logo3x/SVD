<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('branding.company_name', 'SVD');
        $this->migrator->add('branding.company_tagline', 'Sistema de Ventas y Despachos');
        $this->migrator->add('branding.primary_phone', '(317) 667 8676');
        $this->migrator->add('branding.secondary_phone', '(311) 226 1339');
        $this->migrator->add('branding.city', 'Barrancabermeja');
        $this->migrator->add('branding.address', 'Colombia');
        $this->migrator->add('branding.email_inbox', 'remisiones@example.com');
        $this->migrator->add('branding.email_cash', 'remisioncontado@example.com');
        $this->migrator->add('branding.email_cash_for_billing', 'remisioncontadofact@example.com');
        $this->migrator->add('branding.email_credit', 'remisioncredito@example.com');
    }
};
