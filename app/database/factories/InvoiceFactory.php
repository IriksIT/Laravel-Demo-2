<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $issued = $this->faker->dateTimeBetween('-6 months', 'now');
        $status = $this->faker->randomElement(array_keys(Invoice::STATUSES));

        return [
            'number'     => 'F'.$issued->format('Y').'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'project_id' => Project::factory(),
            'amount'     => $this->faker->randomFloat(2, 500, 25000),
            'status'     => $status,
            'issued_at'  => $issued,
            'due_at'     => (clone $issued)->modify('+30 days'),
            'paid_at'    => $status === 'paid' ? (clone $issued)->modify('+'.rand(3, 28).' days') : null,
        ];
    }
}
