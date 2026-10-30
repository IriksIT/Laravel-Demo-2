<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use App\Orchid\Layouts\Project\ProjectListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ProjectListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'projects' => Project::with(['client', 'manager'])
                ->filters()
                ->defaultSort('deadline')
                ->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Projecten';
    }

    public function description(): ?string
    {
        return 'Alle projecten met status, budget en deadline.';
    }

    public function permission(): ?iterable
    {
        return ['platform.crm.projects'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Nieuw project')
                ->icon('bs.plus-circle')
                ->route('platform.projects.create'),
        ];
    }

    public function layout(): iterable
    {
        return [ProjectListLayout::class];
    }
}
