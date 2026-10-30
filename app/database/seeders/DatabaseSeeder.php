<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        // Admin user for the Orchid dashboard (/admin)
        User::createAdmin('Admin', 'admin@example.com', 'password');

        $team = User::factory()->count(6)->create();

        $clients = Client::factory()->count(18)->create();

        $clients->each(function (Client $client) use ($team) {
            Project::factory()
                ->count(rand(1, 3))
                ->create(['client_id' => $client->id, 'manager_id' => $team->random()->id])
                ->each(function (Project $project) use ($team) {
                    Task::factory()
                        ->count(rand(4, 12))
                        ->create(['project_id' => $project->id, 'assignee_id' => $team->random()->id]);

                    Invoice::factory()
                        ->count(rand(0, 4))
                        ->create(['project_id' => $project->id]);
                });
        });
    }
}
