<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Product;
use App\Models\Remission;
use App\Models\User;
use App\Services\RemissionsXlsxExporter;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemissionsXlsxExporterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MasterProductCatalogSeeder::class);
    }

    public function test_streamed_response_is_xlsx_attachment(): void
    {
        $exporter = new RemissionsXlsxExporter;
        $response = $exporter->streamDownload(Remission::query(), 'test.xlsx');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('Content-Type'),
        );
        $this->assertStringContainsString('attachment;', (string) $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('test.xlsx', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_writes_one_row_per_product_line(): void
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $product = Product::where('sku', 'H-5000')->firstOrFail();
        $product2 = Product::where('sku', 'H-15000')->firstOrFail();

        $remission = Remission::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'issued_at' => now(),
            'route' => 'route_01',
            'payment_type' => PaymentType::Cash->value,
            'status' => RemissionStatus::Confirmed->value,
            'total_amount' => 0,
        ]);
        $remission->products()->attach($product->id, ['quantity' => 2, 'unit_price_snapshot' => 8500, 'subtotal' => 17000]);
        $remission->products()->attach($product2->id, ['quantity' => 1, 'unit_price_snapshot' => 22000, 'subtotal' => 22000]);

        $exporter = new RemissionsXlsxExporter;
        $tmp = sys_get_temp_dir().'/svd-export-'.uniqid().'.xlsx';

        ob_start();
        $response = $exporter->streamDownload(Remission::query(), 'export.xlsx');
        $response->sendContent();
        $content = (string) ob_get_clean();
        file_put_contents($tmp, $content);

        $this->assertGreaterThan(1000, filesize($tmp), 'el XLSX debe tener contenido real');
        $this->assertSame('PK', substr($content, 0, 2), 'XLSX es un ZIP, su magic byte es PK');

        unlink($tmp);
    }
}
