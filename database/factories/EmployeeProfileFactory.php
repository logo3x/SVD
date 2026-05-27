<?php

namespace Database\Factories;

use App\Enums\EmploymentStatus;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmployeeProfile>
 */
class EmployeeProfileFactory extends Factory
{
    protected $model = EmployeeProfile::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'national_id' => fake()->numerify('##########'),
            'job_title' => fake()->jobTitle(),
            'phone' => fake()->numerify('60########'),
            'whatsapp' => fake()->numerify('3#########'),
            'birth_date' => fake()->dateTimeBetween('-50 years', '-20 years'),
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'marital_status' => fake()->randomElement(['single', 'married', 'divorced', 'widowed']),
            'children' => fake()->numberBetween(0, 4),
            'employment_link' => fake()->randomElement(['Independiente', 'Formal']),
            'employment_status' => EmploymentStatus::Active->value,
            'contract_start' => fake()->dateTimeBetween('-3 years', '-1 month'),
            'retired_at' => null,
            'blood_type' => fake()->randomElement(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']),
            'eps' => fake()->randomElement(['Sura', 'Sanitas', 'Nueva EPS', 'Coomeva', 'Compensar']),
            'afp' => fake()->randomElement(['Protección', 'Porvenir', 'Colfondos', 'Colpensiones']),
            'arl' => fake()->randomElement(['Sura', 'Positiva', 'Colmena', 'Bolívar']),
            'bank_account' => fake()->numerify('############'),
        ];
    }

    /**
     * Empleado sin usuario del sistema (RRHH sin acceso/login).
     * Usa full_name/email propios en lugar del usuario vinculado.
     */
    public function withoutUser(): static
    {
        return $this->state(fn (): array => [
            'user_id' => null,
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
        ]);
    }
}
