<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('svd:manual {--output= : Ruta personalizada de salida (default: docs/SVD-minimanual.pdf)}')]
#[Description('Genera el minimanual de usuario en PDF (funcionalidades, casos de uso, credenciales)')]
class GenerateMinimanual extends Command
{
    public function handle(): int
    {
        $output = $this->option('output') ?: base_path('docs/SVD-minimanual.pdf');

        File::ensureDirectoryExists(dirname($output));

        $this->info('Renderizando minimanual...');

        $pdf = Pdf::loadView('pdf.minimanual')->setPaper('letter');
        File::put($output, $pdf->output());

        $size = number_format(File::size($output) / 1024, 1);

        $this->newLine();
        $this->info('✓ Minimanual generado correctamente.');
        $this->line("  Ubicación: <fg=cyan>{$output}</>");
        $this->line("  Tamaño:    <fg=cyan>{$size} KB</>");
        $this->newLine();
        $this->line('Para regenerarlo tras cambios: <fg=yellow>php artisan svd:manual</>');

        return self::SUCCESS;
    }
}
