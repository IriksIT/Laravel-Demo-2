<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Project;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use App\Orchid\Layouts\Project\ProjectEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Currency;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProjectEditScreen extends Screen
{
    public ?Project $project = null;

    public function query(Project $project): iterable
    {
        return [
            'project'  => $project,
            'tasks'    => $project->exists ? $project->tasks()->with('assignee')->orderBy('due_at')->get() : collect(),
            'invoices' => $project->exists ? $project->invoices()->latest('issued_at')->get() : collect(),
            'metrics'  => $project->exists ? [
                'progress' => ['value' => $project->progress().'%'],
                'tasks'    => ['value' => $project->tasks()->count()],
                'hours'    => ['value' => number_format((float) $project->tasks()->sum('spent_hours'), 1, ',', '.')],
                'invoiced' => ['value' => '€ '.number_format((float) $project->invoices()->sum('amount'), 0, ',', '.')],
            ] : [],
        ];
    }

    public function name(): ?string
    {
        return $this->project?->exists ? $this->project->name : 'Nieuw project';
    }

    public function description(): ?string
    {
        return 'Projectgegevens, taken en facturen.';
    }

    public function permission(): ?iterable
    {
        return ['platform.crm.projects'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Opslaan')->icon('bs.check-circle')->method('save'),

            Button::make('Verwijderen')
                ->icon('bs.trash3')
                ->confirm('Alle taken en facturen van dit project worden ook verwijderd. Doorgaan?')
                ->method('remove')
                ->canSee($this->project?->exists ?? false),
        ];
    }

    public function layout(): iterable
    {
        $exists = $this->project?->exists ?? false;

        return [
            Layout::metrics([
                'Voortgang'     => 'metrics.progress',
                'Taken'         => 'metrics.tasks',
                'Uren besteed'  => 'metrics.hours',
                'Gefactureerd'  => 'metrics.invoiced',
            ])->canSee($exists),

            Layout::block(ProjectEditLayout::class)
                ->title('Projectgegevens')
                ->description('Klant, status, budget en planning.'),

            Layout::table('tasks', [
                TD::make('title', 'Taak')
                    ->render(fn (Task $task) => Link::make($task->title)
                        ->route('platform.tasks.edit', $task)),
                TD::make('assignee', 'Toegewezen aan')
                    ->render(fn (Task $task) => $task->assignee?->name),
                TD::make('status', 'Status')
                    ->render(fn (Task $task) => $task->statusLabel()),
                TD::make('priority', 'Prioriteit')
                    ->render(fn (Task $task) => $task->priorityLabel()),
                TD::make('hours', 'Uren')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Task $task) => $task->spent_hours.' / '.$task->estimated_hours),
                TD::make('due_at', 'Deadline')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Task $task) => $task->due_at?->format('d-m-Y')),
            ])->title('Taken')->canSee($exists),

            Layout::table('invoices', [
                TD::make('number', 'Factuur'),
                TD::make('amount', 'Bedrag')
                    ->align(TD::ALIGN_RIGHT)
                    ->usingComponent(Currency::class, symbol: '€ '),
                TD::make('status', 'Status')
                    ->render(fn (Invoice $invoice) => $invoice->statusLabel()),
                TD::make('issued_at', 'Factuurdatum')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Invoice $invoice) => $invoice->issued_at?->format('d-m-Y')),
                TD::make('due_at', 'Vervaldatum')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Invoice $invoice) => $invoice->due_at?->format('d-m-Y')),
            ])->title('Facturen')->canSee($exists),
        ];
    }

    public function save(Project $project, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project.name'        => ['required', 'string', 'max:255'],
            'project.client_id'   => ['required', 'exists:clients,id'],
            'project.manager_id'  => ['nullable', 'exists:users,id'],
            'project.status'      => ['required', Rule::in(array_keys(Project::STATUSES))],
            'project.budget'      => ['nullable', 'numeric', 'min:0'],
            'project.hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'project.starts_at'   => ['nullable', 'date'],
            'project.deadline'    => ['nullable', 'date'],
            'project.description' => ['nullable', 'string'],
        ])['project'];

        $project->fill($data)->save();

        Toast::info('Project opgeslagen.');

        return redirect()->route('platform.projects');
    }

    public function remove(Project $project): RedirectResponse
    {
        $project->delete();

        Toast::info('Project verwijderd.');

        return redirect()->route('platform.projects');
    }
}
