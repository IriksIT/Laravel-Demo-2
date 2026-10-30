<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TaskListLayout extends Table
{
    public $target = 'tasks';

    public function columns(): array
    {
        return [
            TD::make('title', 'Taak')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Task $task) => Link::make($task->title)
                    ->route('platform.tasks.edit', $task)),

            TD::make('project_id', 'Project')
                ->filter(Relation::make()->fromModel(Project::class, 'name'))
                ->render(fn (Task $task) => $task->project?->name),

            TD::make('assignee_id', 'Toegewezen aan')
                ->filter(Relation::make()->fromModel(User::class, 'name'))
                ->render(fn (Task $task) => $task->assignee?->name),

            TD::make('status', 'Status')
                ->sort()
                ->filter(Select::make()->options(Task::STATUSES)->empty('Alle'))
                ->render(fn (Task $task) => $task->statusLabel()),

            TD::make('priority', 'Prioriteit')
                ->sort()
                ->filter(Select::make()->options(Task::PRIORITIES)->empty('Alle'))
                ->render(fn (Task $task) => $task->priorityLabel()),

            TD::make('hours', 'Uren')
                ->align(TD::ALIGN_RIGHT)
                ->render(fn (Task $task) => $task->spent_hours.' / '.$task->estimated_hours),

            TD::make('due_at', 'Deadline')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->render(fn (Task $task) => $task->due_at?->format('d-m-Y')),
        ];
    }
}
