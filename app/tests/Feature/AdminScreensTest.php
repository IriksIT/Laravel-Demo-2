<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScreensTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_seeder_creates_demo_data(): void
    {
        $this->assertGreaterThan(0, Client::count());
        $this->assertGreaterThan(0, Project::count());
        $this->assertGreaterThan(0, Task::count());
        $this->assertGreaterThan(0, Invoice::count());
    }

    public function test_admin_screens_render(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $routes = [
            route('platform.main'),
            route('platform.clients'),
            route('platform.clients.create'),
            route('platform.clients.edit', Client::first()),
            route('platform.projects'),
            route('platform.projects.create'),
            route('platform.projects.edit', Project::first()),
            route('platform.tasks'),
            route('platform.tasks.create'),
            route('platform.tasks.edit', Task::first()),
            route('platform.invoices'),
            route('platform.systems.users'),
        ];

        foreach ($routes as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_admin_can_update_task_and_mark_invoice_paid(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $task = Task::first();
        $invoice = Invoice::where('status', '!=', 'paid')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('platform.tasks.edit', [$task, 'method' => 'save']), [
                'task' => [
                    'title'           => 'Bijgewerkte taak',
                    'project_id'      => $task->project_id,
                    'assignee_id'     => $task->assignee_id,
                    'status'          => 'done',
                    'priority'        => 'high',
                    'due_at'          => '2026-12-01',
                    'estimated_hours' => 8,
                    'spent_hours'     => 6.5,
                    'description'     => 'Test',
                ],
            ])->assertRedirect(route('platform.tasks'));

        $this->assertSame('done', $task->fresh()->status);

        $this->actingAs($admin)
            ->post(route('platform.invoices', ['method' => 'markPaid']), ['id' => $invoice->id])
            ->assertRedirect();

        $this->assertSame('paid', $invoice->fresh()->status);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('platform.projects'))->assertRedirect();
    }
}
