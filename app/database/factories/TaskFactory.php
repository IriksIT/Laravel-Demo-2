<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $estimated = $this->faker->randomElement([1, 2, 4, 8, 16, 24, 40]);

        return [
            'project_id'      => Project::factory(),
            'title'           => ucfirst($this->faker->randomElement(['Ontwerp', 'Bouw', 'Test', 'Review', 'Documenteer', 'Implementeer', 'Refactor'])).' '.$this->faker->words(2, true),
            'description'     => $this->faker->optional(0.7)->paragraph(),
            'status'          => $this->faker->randomElement(array_keys(Task::STATUSES)),
            'priority'        => $this->faker->randomElement(array_keys(Task::PRIORITIES)),
            'estimated_hours' => $estimated,
            'spent_hours'     => $this->faker->randomFloat(1, 0, $estimated * 1.3),
            'due_at'          => $this->faker->dateTimeBetween('-1 month', '+3 months'),
        ];
    }
}
