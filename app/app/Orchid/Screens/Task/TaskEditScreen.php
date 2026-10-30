<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Task;

use App\Models\Task;
use App\Orchid\Layouts\Task\TaskEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class TaskEditScreen extends Screen
{
    public ?Task $task = null;

    public function query(Task $task): iterable
    {
        return ['task' => $task];
    }

    public function name(): ?string
    {
        return $this->task?->exists ? $this->task->title : 'Nieuwe taak';
    }

    public function description(): ?string
    {
        return 'Status, prioriteit, uren en planning van een taak.';
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
                ->confirm('Weet je zeker dat je deze taak wilt verwijderen?')
                ->method('remove')
                ->canSee($this->task?->exists ?? false),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(TaskEditLayout::class)
                ->title('Taakgegevens'),
        ];
    }

    public function save(Task $task, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'task.title'           => ['required', 'string', 'max:255'],
            'task.project_id'      => ['required', 'exists:projects,id'],
            'task.assignee_id'     => ['nullable', 'exists:users,id'],
            'task.status'          => ['required', Rule::in(array_keys(Task::STATUSES))],
            'task.priority'        => ['required', Rule::in(array_keys(Task::PRIORITIES))],
            'task.due_at'          => ['nullable', 'date'],
            'task.estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'task.spent_hours'     => ['nullable', 'numeric', 'min:0'],
            'task.description'     => ['nullable', 'string'],
        ])['task'];

        $task->fill($data)->save();

        Toast::info('Taak opgeslagen.');

        return redirect()->route('platform.tasks');
    }

    public function remove(Task $task): RedirectResponse
    {
        $task->delete();

        Toast::info('Taak verwijderd.');

        return redirect()->route('platform.tasks');
    }
}
