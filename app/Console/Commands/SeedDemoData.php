<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\DemoDataSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('svd:demo-data {--fresh : Borra remisiones, clientes y vendedores demo antes de generar}')]
#[Description('Genera datos de demostración (clientes, vendedores, remisiones) para visualizar el funcionamiento. Seguro de correr en cualquier entorno.')]
class SeedDemoData extends Command
{
    public function handle(): int
    {
        if (! $this->option('no-interaction') && ! $this->confirm('Esto agregará datos de DEMOSTRACIÓN a la base actual. ¿Continuar?', true)) {
            $this->warn('Cancelado.');

            return self::SUCCESS;
        }

        $this->info('Generando datos de demostración...');
        $this->call(DemoDataSeeder::class);

        $this->newLine();
        $this->info('✓ Datos de demostración generados.');
        $this->line('  Revisa el dashboard de /admin para ver las gráficas pobladas.');

        return self::SUCCESS;
    }
}
