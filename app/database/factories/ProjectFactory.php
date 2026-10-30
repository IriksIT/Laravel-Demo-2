<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-8 months', '+1 month');

        return [
            'client_id'   => Client::factory(),
            'name'        => $this->faker->randomElement(['Website', 'Webshop', 'App', 'API', 'Migratie', 'Dashboard', 'Portaal']).' '.ucfirst($this->faker->word()),
            'description' => $this->faker->paragraph(),
            'status'      => $this->faker->randomElement(array_keys(Project::STATUSES)),
            'budget'      => $this->faker->randomElement([5000, 12500, 25000, 40000, 75000, 120000]),
            'hourly_rate' => $this->faker->randomElement([75, 85, 95, 110]),
            'starts_at'   => $start,
            'deadline'    => (clone $start)->modify('+'.rand(1, 9).' months'),
        ];
    }
}
