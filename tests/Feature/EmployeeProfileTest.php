<?php

namespace Tests\Feature;

use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_empleado_con_usuario_muestra_el_nombre_del_usuario(): void
    {
        $user = User::factory()->create(['name' => 'Carlos Pérez']);
        $employee = EmployeeProfile::factory()->create(['user_id' => $user->id]);

        $this->assertSame('Carlos Pérez', $employee->displayName());
        $this->assertSame($user->email, $employee->displayEmail());
    }

    public function test_empleado_sin_usuario_es_valido_y_usa_su_propio_nombre(): void
    {
        $employee = EmployeeProfile::factory()->withoutUser()->create([
            'full_name' => 'Ana Gómez',
            'email' => 'ana@empresa.test',
        ]);

        $this->assertNull($employee->user_id);
        $this->assertNull($employee->user);
        $this->assertSame('Ana Gómez', $employee->displayName());
        $this->assertSame('ana@empresa.test', $employee->displayEmail());
    }

    public function test_pueden_existir_varios_empleados_sin_usuario(): void
    {
        EmployeeProfile::factory()->withoutUser()->count(3)->create();

        $this->assertSame(3, EmployeeProfile::whereNull('user_id')->count());
    }

    public function test_empleado_sin_nombre_devuelve_placeholder(): void
    {
        $employee = EmployeeProfile::factory()->withoutUser()->create([
            'full_name' => null,
            'email' => null,
        ]);

        $this->assertSame('Sin nombre', $employee->displayName());
        $this->assertNull($employee->displayEmail());
    }
}
