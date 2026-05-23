<?php

declare(strict_types=1);

namespace Tests\Feature\Performance;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LandingPagePerformanceTest extends TestCase
{
    public function test_landing_page_responds_quickly_and_renders_brand_identity(): void
    {
        $this->withoutVite();

        // Warm-up para evitar flake por cold-start del kernel + view compiler.
        $this->get('/');

        $start = microtime(true);
        $response = $this->get('/');
        $duration = microtime(true) - $start;

        $response->assertOk();
        $response->assertSee('SVD');
        $response->assertSee('Sistema de Ventas y Despachos');
        $response->assertSee('HIELO');
        $response->assertSeeText('Hielo', false);
        $response->assertSeeText('Agua', false);
        $response->assertSee('Barrancabermeja', false);
        $response->assertSee('/admin/login');
        $response->assertSee('/vendedor/login');

        $this->assertLessThan(
            1.0,
            $duration,
            sprintf('Landing debe responder en menos de 1s (tardó %.3fs).', $duration)
        );
    }

    public function test_landing_page_uses_no_database_queries(): void
    {
        $this->withoutVite();

        DB::enableQueryLog();
        $this->get('/')->assertOk();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertCount(
            0,
            $queries,
            'La landing es estática y no debe golpear la base de datos.'
        );
    }
}
