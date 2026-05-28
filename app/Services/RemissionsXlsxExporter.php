<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Remission;
use Illuminate\Contracts\Database\Eloquent\Builder;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RemissionsXlsxExporter
{
    /**
     * Stream un XLSX a partir de un query builder o eloquent query.
     */
    public function streamDownload(Builder $query, string $filename = 'remisiones.xlsx'): StreamedResponse
    {
        // OpenSpout escribe temporales para armar el XLSX. En IIS,
        // sys_get_temp_dir() no es escribible para el usuario del pool, lo
        // que rompe la descarga. Usamos storage/app/temp (con permisos).
        $tempFolder = storage_path('app/temp');
        if (! is_dir($tempFolder)) {
            mkdir($tempFolder, 0775, true);
        }

        $options = new Options;
        $options->setTempFolder($tempFolder);

        $response = new StreamedResponse(function () use ($query, $options): void {
            $writer = new Writer($options);
            $writer->openToFile('php://output');

            $writer->addRow($this->headerRow());

            $query
                ->with(['client:id,name,nit', 'user:id,name', 'products'])
                ->chunkById(200, function ($chunk) use ($writer): void {
                    foreach ($chunk as $remission) {
                        foreach ($this->rowsFor($remission) as $row) {
                            $writer->addRow($row);
                        }
                    }
                });

            $writer->close();
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');

        return $response;
    }

    private function headerRow(): Row
    {
        $border = new Border(new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN));
        $style = (new Style)
            ->setFontBold()
            ->setFontColor(Color::WHITE)
            ->setBackgroundColor(Color::DARK_BLUE)
            ->setBorder($border);

        return Row::fromValues([
            'Remisión',
            'Fecha',
            'Cliente',
            'NIT',
            'Vendedor',
            'Tipo de pago',
            'Ruta',
            'Estado',
            'Producto',
            'SKU',
            'Cantidad',
            'Precio unitario',
            'Subtotal',
            'Total remisión',
            'Observaciones',
        ], $style);
    }

    /**
     * Una fila por producto. Si la remisión no tiene productos, una fila vacía.
     *
     * @return array<int, Row>
     */
    private function rowsFor(Remission $remission): array
    {
        if ($remission->products->isEmpty()) {
            return [Row::fromValues([
                '#'.$remission->id,
                $remission->issued_at?->format('Y-m-d H:i'),
                $remission->client?->name,
                $remission->client?->nit,
                $remission->user?->name,
                $remission->payment_type?->getLabel(),
                $remission->route?->getLabel(),
                $remission->status?->getLabel(),
                '', '', '', '', '',
                $remission->total_amount,
                $remission->observations,
            ])];
        }

        return $remission->products->map(fn ($product) => Row::fromValues([
            '#'.$remission->id,
            $remission->issued_at?->format('Y-m-d H:i'),
            $remission->client?->name,
            $remission->client?->nit,
            $remission->user?->name,
            $remission->payment_type?->getLabel(),
            $remission->route?->getLabel(),
            $remission->status?->getLabel(),
            $product->name,
            $product->sku,
            $product->pivot->quantity,
            $product->pivot->unit_price_snapshot,
            $product->pivot->subtotal,
            $remission->total_amount,
            $remission->observations,
        ]))->all();
    }
}
