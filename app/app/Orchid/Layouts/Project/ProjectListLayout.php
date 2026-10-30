<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Project;

use App\Models\Client;
use App\Models\Project;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Currency;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProjectListLayout extends Table
{
    public $target = 'projects';

    public function columns(): array
    {
        return [
            TD::make('name', 'Project')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Project $project) => Link::make($project->name)
                    ->route('platform.projects.edit', $project)),

            TD::make('client_id', 'Klant')
                ->filter(Relation::make()->fromModel(Client::class, 'company'))
                ->render(fn (Project $project) => $project->client?->company),

            TD::make('manager', 'Projectleider')
                ->render(fn (Project $project) => $project->manager?->name),

            TD::make('status', 'Status')
                ->sort()
                ->filter(Select::make()->options(Project::STATUSES)->empty('Alle'))
                ->render(fn (Project $project) => $project->statusLabel()),

            TD::make('progress', 'Voortgang')
                ->align(TD::ALIGN_RIGHT)
                ->render(fn (Project $project) => $project->progress().'%'),

            TD::make('budget', 'Budget')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->usingComponent(Currency::class, symbol: '€ '),

            TD::make('deadline', 'Deadline')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->render(fn (Project $project) => $project->deadline?->format('d-m-Y')),
        ];
    }
}
