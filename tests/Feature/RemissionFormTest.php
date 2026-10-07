<?php

namespace Tests\Feature;

use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Filament\Admin\Resources\Remissions\Pages\CreateRemission as AdminCreateRemission;
use App\Filament\Admin\Resources\Remissions\Pages\EditRemission as AdminEditRemission;
use App\Filament\Vendedor\Resources\Remissions\Pages\CreateRemission as VendedorCreateRemission;
use App\Models\Client;
use App\Models\Product;
use App\Models\Remission;
use App\Models\User;
use Database\Seeders\MasterProductCatalogSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RemissionFormTest extends TestCase
{
    use RefreshDatabase;

    private Client $client;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->seed(MasterProductCatalogSeeder::class);

        $this->client = Client::factory()->create(['is_active' => true]);
        $this->product = $this->client->products()->wherePivot('is_available', true)->firstOrFail();
    }

    public function test_admin_crea_remision_con_productos_sin_crear_productos_nuevos(): void
    {
        $this->actingAsRoleInPanel('super_admin', 'admin');
        $productCountBefore = Product::count();

        Livewire::test(AdminCreateRemission::class)
            ->fillForm($this->formData(quantity: 3, unitPrice: 8500))
            ->call('create')
            ->assertHasNoFormErrors();

        $remission = Remission::sole();

        $this->assertSame($productCountBefore, Product::count(), 'no debe crear productos nuevos');
        $this->assertDatabaseHas('remission_product', [
            'remission_id' => $remission->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
            'unit_price_snapshot' => 8500,
            'subtotal' => 25500,
        ]);
        $this->assertSame(25500, (int) $remission->total_amount);
    }

    public function test_vendedor_crea_remision_desde_su_panel(): void
    {
        $seller = $this->actingAsRoleInPanel('seller', 'vendedor');

        Livewire::test(VendedorCreateRemission::class)
            ->fillForm($this->formData(quantity: 2, unitPrice: 4000, withSellerFields: false))
            ->call('create')
            ->assertHasNoFormErrors();

        $remission = Remission::sole();

        $this->assertSame($seller->id, $remission->user_id);
        $this->assertSame(1, $remission->items()->count());
        $this->assertSame(8000, (int) $remission->total_amount);
    }

    public function test_el_subtotal_se_recalcula_en_el_servidor(): void
    {
        $this->actingAsRoleInPanel('super_admin', 'admin');

        $data = $this->formData(quantity: 4, unitPrice: 1000);
        $data['items']['line-1']['subtotal'] = 1;

        Livewire::test(AdminCreateRemission::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoFormErrors();

        $remission = Remission::sole();

        $this->assertSame(4000, (int) $remission->items()->sole()->subtotal);
        $this->assertSame(4000, (int) $remission->total_amount);
    }

    public function test_admin_edita_cantidades_y_actualiza_el_total(): void
    {
        $admin = $this->actingAsRoleInPanel('super_admin', 'admin');

        $remission = Remission::create([
            'client_id' => $this->client->id,
            'user_id' => $admin->id,
            'issued_at' => now(),
            'route' => 'route_01',
            'payment_type' => PaymentType::Credit->value,
            'status' => RemissionStatus::Confirmed->value,
            'total_amount' => 5000,
        ]);
        $remission->products()->attach($this->product->id, [
            'quantity' => 1,
            'unit_price_snapshot' => 5000,
            'subtotal' => 5000,
        ]);

        $component = Livewire::test(AdminEditRemission::class, ['record' => $remission->getRouteKey()]);
        $itemKey = array_key_first($component->get('data.items'));

        $component
            ->set("data.items.{$itemKey}.quantity", 3)
            ->call('save')
            ->assertHasNoFormErrors();

        $remission->refresh();

        $this->assertSame(1, $remission->items()->count());
        $this->assertSame(15000, (int) $remission->items()->sole()->subtotal);
        $this->assertSame(15000, (int) $remission->total_amount);
    }

    public function test_remision_sin_productos_no_se_guarda(): void
    {
        $this->actingAsRoleInPanel('super_admin', 'admin');

        $data = $this->formData(quantity: 1, unitPrice: 1000);
        $data['items'] = [];

        Livewire::test(AdminCreateRemission::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasFormErrors(['items']);

        $this->assertSame(0, Remission::count());
    }

    private function actingAsRoleInPanel(string $role, string $panel): User
    {
        $roleModel = Role::findOrCreate($role, 'web');
        foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
            $roleModel->givePermissionTo(Permission::findOrCreate("{$ability}:Remission", 'web'));
        }

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user);
        Filament::setCurrentPanel(Filament::getPanel($panel));

        return $user;
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(int $quantity, int $unitPrice, bool $withSellerFields = true): array
    {
        $data = [
            'client_id' => $this->client->id,
            'payment_type' => PaymentType::Credit->value,
            'route' => 'route_01',
            'issued_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                'line-1' => [
                    'product_id' => $this->product->id,
                    'quantity' => $quantity,
                    'unit_price_snapshot' => $unitPrice,
                    'subtotal' => $quantity * $unitPrice,
                ],
            ],
        ];

        if ($withSellerFields) {
            $data['status'] = RemissionStatus::Confirmed->value;
        }

        return $data;
    }
}
