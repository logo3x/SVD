<?php

namespace Database\Factories;

use App\Enums\DeliveryRoute;
use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use App\Models\Client;
use App\Models\Remission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Remission>
 */
class RemissionFactory extends Factory
{
    protected $model = Remission::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'issued_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'route' => fake()->randomElement(DeliveryRoute::cases())->value,
            'payment_type' => fake()->randomElement(PaymentType::cases())->value,
            'status' => RemissionStatus::Confirmed->value,
            'observations' => fake()->optional()->sentence(),
            'gps_location' => null,
            'total_amount' => 0,
        ];
    }
}
