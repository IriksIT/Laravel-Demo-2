<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Client;

use App\Models\Client;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ClientEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Group::make([
                Input::make('client.company')->title('Bedrijf')->required(),
                Input::make('client.contact_name')->title('Contactpersoon')->required(),
            ]),

            Group::make([
                Input::make('client.email')->title('E-mail')->type('email')->required(),
                Input::make('client.phone')->title('Telefoon'),
            ]),

            Group::make([
                Input::make('client.city')->title('Plaats'),
                Input::make('client.industry')->title('Branche'),
                Select::make('client.status')->title('Status')->options(Client::STATUSES),
            ]),
        ];
    }
}
