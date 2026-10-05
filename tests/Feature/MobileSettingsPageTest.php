<?php

namespace Tests\Feature;

use App\Filament\Admin\Pages\MobileSettingsPage;
use App\Models\User;
use App\Settings\MobileSettings;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MobileSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('super_admin', 'web');
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $this->actingAs($superAdmin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_guarda_la_configuracion_con_el_mensaje_de_anuncio_vacio(): void
    {
        Livewire::test(MobileSettingsPage::class)
            ->set('data.announcement_message', null)
            ->set('data.api_base_url', 'https://aguakriss.com/api/v1')
            ->call('save')
            ->assertHasNoErrors();

        $settings = app(MobileSettings::class);
        $settings->refresh();

        $this->assertSame('https://aguakriss.com/api/v1', $settings->api_base_url);
        $this->assertSame('', $settings->announcement_message);
    }

    public function test_guarda_el_mensaje_de_anuncio_cuando_tiene_texto(): void
    {
        Livewire::test(MobileSettingsPage::class)
            ->set('data.announcement_enabled', true)
            ->set('data.announcement_message', 'Nueva versión disponible')
            ->call('save')
            ->assertHasNoErrors();

        $settings = app(MobileSettings::class);
        $settings->refresh();

        $this->assertTrue($settings->announcement_enabled);
        $this->assertSame('Nueva versión disponible', $settings->announcement_message);
    }

    public function test_api_base_url_invalida_no_se_guarda(): void
    {
        Livewire::test(MobileSettingsPage::class)
            ->set('data.api_base_url', 'no-es-una-url')
            ->call('save')
            ->assertHasErrors(['data.api_base_url']);
    }
}
