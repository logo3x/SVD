<?php

namespace Tests\Feature;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Mail\RemisionCreada;
use App\Models\Client;
use App\Models\Product;
use App\Models\Remission;
use App\Models\User;
use Database\Seeders\MasterProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class RemissionEmailTest extends TestCase
{
    use RefreshDatabase;

    private Client $client;

    private Product $product;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(MasterProductCatalogSeeder::class);
        $this->client = Client::factory()->create();
        $this->product = Product::where('sku', 'H-5000')->firstOrFail();
        $this->user = User::factory()->create();
    }

    public function test_el_correo_de_remision_se_renderiza(): void
    {
        $remission = $this->createRemission();

        $html = (new RemisionCreada($remission))->render();

        $this->assertStringContainsString('#'.$remission->id, $html);
        $this->assertStringContainsString($this->product->name, $html);
        $this->assertStringContainsString('25.500', $html);
    }

    public function test_la_copia_del_correo_se_renderiza(): void
    {
        $html = (new RemisionCreada($this->createRemission(), isCopy: true))->render();

        $this->assertStringContainsString('COPIA', $html);
    }

    public function test_el_correo_se_envia_con_el_pdf_adjunto(): void
    {
        $remission = $this->createRemission();

        Mail::to('cliente@example.com')->send(new RemisionCreada($remission));

        $sent = app('mailer')->getSymfonyTransport()->messages()->sole()->getOriginalMessage();
        $attachment = collect($sent->getAttachments())->sole();

        $this->assertSame('application/pdf', $attachment->getMediaType().'/'.$attachment->getMediaSubtype());
        $this->assertStringStartsWith('%PDF', $attachment->getBody());
    }

    public function test_la_api_guarda_la_remision_aunque_falle_el_envio_del_correo(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP caído'));

        $token = $this->user->createToken('phpunit')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/remissions', [
                'client_id' => $this->client->id,
                'route' => 'route_03',
                'payment_type' => PaymentType::Credit->value,
                'items' => [
                    ['product_id' => $this->product->id, 'quantity' => 3, 'unit_price_snapshot' => 8500],
                ],
            ])
            ->assertCreated()
            ->assertJsonPath('data.total_amount', 25500);

        $this->assertSame(1, Remission::count());
    }

    private function createRemission(): Remission
    {
        $remission = Remission::create([
            'client_id' => $this->client->id,
            'user_id' => $this->user->id,
            'issued_at' => now(),
            'route' => 'route_01',
            'payment_type' => PaymentType::Credit->value,
            'status' => RemissionStatus::Confirmed->value,
            'total_amount' => 25500,
        ]);

        $remission->products()->attach($this->product->id, [
            'quantity' => 3,
            'unit_price_snapshot' => 8500,
            'subtotal' => 25500,
        ]);

        return $remission->fresh(['client', 'user', 'products']);
    }
}
