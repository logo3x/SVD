<?php

namespace Database\Factories;

use App\Enums\DeliveryPoint;
use App\Enums\PaymentType;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'nit' => fake()->numerify('9########-#'),
            'manager_name' => fake()->name(),
            'description' => fake()->optional()->sentence(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'phone' => fake()->numerify('60########'),
            'whatsapp' => fake()->numerify('3#########'),
            'email' => fake()->unique()->companyEmail(),
            'social_networks' => null,
            'delivery_point' => fake()->randomElement(DeliveryPoint::cases())->value,
            'payment_type' => fake()->randomElement(PaymentType::cases())->value,
            'contract_start' => fake()->dateTimeBetween('-2 years', '-1 month'),
            'contract_end' => fake()->dateTimeBetween('+1 month', '+2 years'),
            'is_active' => true,
            'notes' => null,
        ];
    }
}
