<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Invoice;
use App\Models\Task;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Dashboard')
                ->icon('bs.speedometer2')
                ->title('CRM')
                ->route(config('platform.index')),

            Menu::make('Klanten')
                ->icon('bs.building')
                ->route('platform.clients')
                ->permission('platform.crm.clients')
                ->active(['*/clients', '*/clients/*']),

            Menu::make('Projecten')
                ->icon('bs.kanban')
                ->route('platform.projects')
                ->permission('platform.crm.projects')
                ->active(['*/projects', '*/projects/*']),

            Menu::make('Taken')
                ->icon('bs.list-check')
                ->route('platform.tasks')
                ->permission('platform.crm.projects')
                ->active(['*/tasks', '*/tasks/*'])
                ->badge(fn () => Task::where('status', '!=', 'done')->whereDate('due_at', '<', now())->count(), Color::DANGER),

            Menu::make('Facturen')
                ->icon('bs.receipt')
                ->route('platform.invoices')
                ->permission('platform.crm.invoices')
                ->badge(fn () => Invoice::where('status', 'overdue')->count(), Color::WARNING)
                ->divider(),

            Menu::make(__('Users'))
                ->icon('bs.person-gear')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make('Orchid documentatie')
                ->title('Docs')
                ->icon('bs.box-arrow-up-right')
                ->url('https://orchid.software/en/docs')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group('CRM')
                ->addPermission('platform.crm.clients', 'Klanten')
                ->addPermission('platform.crm.projects', 'Projecten & taken')
                ->addPermission('platform.crm.invoices', 'Facturen'),

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
