<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Client;

use App\Models\Client;
use App\Orchid\Layouts\Client\ClientListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ClientListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'clients' => Client::withCount('projects')
                ->filters()
                ->defaultSort('company')
                ->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Klanten';
    }

    public function description(): ?string
    {
        return 'Alle klanten en leads.';
    }

    public function permission(): ?iterable
    {
        return ['platform.crm.clients'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Nieuwe klant')
                ->icon('bs.plus-circle')
                ->route('platform.clients.create'),
        ];
    }

    public function layout(): iterable
    {
        return [ClientListLayout::class];
    }

    public function remove(Request $request): void
    {
        Client::findOrFail($request->get('id'))->delete();

        Toast::info('Klant verwijderd.');
    }
}
