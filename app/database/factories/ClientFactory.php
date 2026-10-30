<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company'      => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'email'        => $this->faker->unique()->companyEmail(),
            'phone'        => $this->faker->phoneNumber(),
            'city'         => $this->faker->city(),
            'industry'     => $this->faker->randomElement(['Retail', 'Zorg', 'Logistiek', 'Onderwijs', 'Financieel', 'Overheid', 'Bouw']),
            'status'       => $this->faker->randomElement(array_keys(Client::STATUSES)),
        ];
    }
}
