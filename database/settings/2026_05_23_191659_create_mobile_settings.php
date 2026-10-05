<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mobile.min_app_version', '1.0.0');
        $this->migrator->add('mobile.force_update_version', '0.0.0');
        $this->migrator->add('mobile.maintenance_mode', false);
        $this->migrator->add('mobile.maintenance_message', 'Plataforma en mantenimiento. Vuelve en unos minutos.');
        $this->migrator->add('mobile.announcement_enabled', false);
        $this->migrator->add('mobile.announcement_message', '');
        $this->migrator->add('mobile.default_token_ttl_days', 0);
        $this->migrator->add('mobile.api_base_url', 'https://svd.example.com/api/v1');
    }
};
