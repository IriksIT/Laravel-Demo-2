<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Project;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ProjectEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('project.name')->title('Naam')->required(),

            Group::make([
                Relation::make('project.client_id')
                    ->title('Klant')
                    ->fromModel(Client::class, 'company')
                    ->required(),

                Relation::make('project.manager_id')
                    ->title('Projectleider')
                    ->fromModel(User::class, 'name'),

                Select::make('project.status')
                    ->title('Status')
                    ->options(Project::STATUSES),
            ]),

            Group::make([
                Input::make('project.budget')->title('Budget (€)')->type('number')->step(0.01)->min(0),
                Input::make('project.hourly_rate')->title('Uurtarief (€)')->type('number')->step(0.01)->min(0),
                DateTimer::make('project.starts_at')->title('Startdatum')->format('Y-m-d')->allowInput(),
                DateTimer::make('project.deadline')->title('Deadline')->format('Y-m-d')->allowInput(),
            ]),

            TextArea::make('project.description')->title('Omschrijving')->rows(5),
        ];
    }
}
