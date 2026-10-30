<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Client;

use App\Models\Client;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ClientListLayout extends Table
{
    public $target = 'clients';

    public function columns(): array
    {
        return [
            TD::make('company', 'Bedrijf')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Client $client) => Link::make($client->company)
                    ->route('platform.clients.edit', $client)),

            TD::make('contact_name', 'Contactpersoon')
                ->sort()
                ->filter(Input::make()),

            TD::make('email', 'E-mail'),

            TD::make('city', 'Plaats')
                ->sort()
                ->filter(Input::make()),

            TD::make('industry', 'Branche'),

            TD::make('status', 'Status')
                ->sort()
                ->filter(Select::make()->options(Client::STATUSES)->empty('Alle'))
                ->render(fn (Client $client) => $client->statusLabel()),

            TD::make('projects_count', 'Projecten')
                ->align(TD::ALIGN_RIGHT),

            TD::make('Acties')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Client $client) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Bewerken')
                            ->route('platform.clients.edit', $client)
                            ->icon('bs.pencil'),
                        Button::make('Verwijderen')
                            ->icon('bs.trash3')
                            ->confirm('Alle projecten van deze klant worden ook verwijderd. Doorgaan?')
                            ->method('remove', ['id' => $client->id]),
                    ])),
        ];
    }
}
