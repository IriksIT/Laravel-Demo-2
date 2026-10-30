<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Task;

use App\Models\Task;
use App\Orchid\Layouts\Task\TaskListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class TaskListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'tasks' => Task::with(['project', 'assignee'])
                ->filters()
                ->defaultSort('due_at')
                ->paginate(20),
        ];
    }

    public function name(): ?string
    {
        return 'Taken';
    }

    public function description(): ?string
    {
        return 'Alle taken over alle projecten heen.';
    }

    public function permission(): ?iterable
    {
        return ['platform.crm.projects'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Nieuwe taak')
                ->icon('bs.plus-circle')
                ->route('platform.tasks.create'),
        ];
    }

    public function layout(): iterable
    {
        return [TaskListLayout::class];
    }
}
