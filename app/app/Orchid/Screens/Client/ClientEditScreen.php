<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Client;

use App\Models\Client;
use App\Models\Project;
use App\Orchid\Layouts\Client\ClientEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Currency;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ClientEditScreen extends Screen
{
    public ?Client $client = null;

    public function query(Client $client): iterable
    {
        return [
            'client'   => $client,
            'projects' => $client->exists ? $client->projects()->latest()->get() : collect(),
        ];
    }

    public function name(): ?string
    {
        return $this->client?->exists ? $this->client->company : 'Nieuwe klant';
    }

    public function description(): ?string
    {
        return 'Klantgegevens en bijbehorende projecten.';
    }

    public function permission(): ?iterable
    {
        return ['platform.crm.clients'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Opslaan')->icon('bs.check-circle')->method('save'),

            Button::make('Verwijderen')
                ->icon('bs.trash3')
                ->confirm('Alle projecten van deze klant worden ook verwijderd. Doorgaan?')
                ->method('remove')
                ->canSee($this->client?->exists ?? false),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(ClientEditLayout::class)
                ->title('Klantgegevens')
                ->description('Bedrijfs- en contactgegevens.'),

            Layout::table('projects', [
                TD::make('name', 'Project')
                    ->render(fn (Project $project) => Link::make($project->name)
                        ->route('platform.projects.edit', $project)),
                TD::make('status', 'Status')
                    ->render(fn (Project $project) => $project->statusLabel()),
                TD::make('budget', 'Budget')
                    ->align(TD::ALIGN_RIGHT)
                    ->usingComponent(Currency::class, symbol: '€ '),
                TD::make('deadline', 'Deadline')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Project $project) => $project->deadline?->format('d-m-Y')),
            ])->title('Projecten')
              ->canSee($this->client?->exists ?? false),
        ];
    }

    public function save(Client $client, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client.company'      => ['required', 'string', 'max:255'],
            'client.contact_name' => ['required', 'string', 'max:255'],
            'client.email'        => ['required', 'email', Rule::unique(Client::class, 'email')->ignore($client)],
            'client.phone'        => ['nullable', 'string', 'max:50'],
            'client.city'         => ['nullable', 'string', 'max:100'],
            'client.industry'     => ['nullable', 'string', 'max:100'],
            'client.status'       => ['required', Rule::in(array_keys(Client::STATUSES))],
        ])['client'];

        $client->fill($data)->save();

        Toast::info('Klant opgeslagen.');

        return redirect()->route('platform.clients');
    }

    public function remove(Client $client): RedirectResponse
    {
        $client->delete();

        Toast::info('Klant verwijderd.');

        return redirect()->route('platform.clients');
    }
}
