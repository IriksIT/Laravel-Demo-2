<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class TaskEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('task.title')->title('Titel')->required(),

            Group::make([
                Relation::make('task.project_id')
                    ->title('Project')
                    ->fromModel(Project::class, 'name')
                    ->required(),

                Relation::make('task.assignee_id')
                    ->title('Toegewezen aan')
                    ->fromModel(User::class, 'name'),
            ]),

            Group::make([
                Select::make('task.status')->title('Status')->options(Task::STATUSES),
                Select::make('task.priority')->title('Prioriteit')->options(Task::PRIORITIES),
                DateTimer::make('task.due_at')->title('Deadline')->format('Y-m-d')->allowInput(),
            ]),

            Group::make([
                Input::make('task.estimated_hours')->title('Geschatte uren')->type('number')->step(0.5)->min(0),
                Input::make('task.spent_hours')->title('Bestede uren')->type('number')->step(0.5)->min(0),
            ]),

            TextArea::make('task.description')->title('Omschrijving')->rows(5),
        ];
    }
}
