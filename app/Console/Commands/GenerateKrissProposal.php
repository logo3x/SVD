<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

#[Signature('svd:propuesta-kriss {--output= : Ruta de salida (por defecto: Escritorio del usuario)}')]
#[Description('Genera la propuesta económica en PDF dirigida a Agua Kriss')]
class GenerateKrissProposal extends Command
{
    public function handle(): int
    {
        $fecha = Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
        $propuestaNumero = 'SVD-'.Carbon::now()->format('Ymd').'-KRISS';

        $data = [
            'fecha' => $fecha,
            'propuesta_numero' => $propuestaNumero,
            'proveedor_nombre' => 'Luis Oviedo',
            'proveedor_email' => 'lgoviedo17@hotmail.com',
            'proveedor_telefono' => '+57 314 369 3735',
        ];

        $pdf = Pdf::loadView('pdf.propuesta-kriss', $data)->setPaper('letter');

        $output = $this->option('output')
            ?: $this->resolveDesktop().DIRECTORY_SEPARATOR.'Propuesta-SVD-Agua-Kriss.pdf';

        $pdf->save($output);

        $size = number_format(filesize($output) / 1024, 1);
        $this->info('✓ Propuesta generada.');
        $this->line("  Archivo:  <fg=cyan>{$output}</> ({$size} KB)");
        $this->line("  Número:   <fg=cyan>{$propuestaNumero}</>");
        $this->line("  Fecha:    <fg=cyan>{$fecha}</>");

        return self::SUCCESS;
    }

    private function resolveDesktop(): string
    {
        $home = $_SERVER['USERPROFILE'] ?? $_SERVER['HOME'] ?? sys_get_temp_dir();
        foreach (['Desktop', 'Escritorio'] as $dir) {
            $candidate = $home.DIRECTORY_SEPARATOR.$dir;
            if (is_dir($candidate)) {
                return $candidate;
            }
        }

        return $home;
    }
}
