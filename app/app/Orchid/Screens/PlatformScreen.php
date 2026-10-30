<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use App\Orchid\Layouts\Dashboard\InvoiceChart;
use App\Orchid\Layouts\Dashboard\TaskStatusChart;
use App\Orchid\Layouts\Task\TaskListLayout;
use Illuminate\Support\Carbon;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    public function query(): iterable
    {
        $months = collect(range(5, 0))
            ->map(fn (int $i) => Carbon::now()->startOfMonth()->subMonths($i));

        $invoiced = $months->map(fn (Carbon $month) => (float) Invoice::query()
            ->whereBetween('issued_at', [$month, $month->copy()->endOfMonth()])
            ->sum('amount'));

        $taskCounts = Task::query()
            ->selectRaw('status, count(*) as aantal')
            ->groupBy('status')
            ->pluck('aantal', 'status');

        return [
            'metrics' => [
                'clients'  => ['value' => Client::where('status', 'active')->count()],
                'projects' => ['value' => Project::where('status', 'active')->count()],
                'tasks'    => ['value' => Task::where('status', '!=', 'done')->count()],
                'overdue'  => ['value' => Task::where('status', '!=', 'done')->whereDate('due_at', '<', now())->count()],
                'open'     => ['value' => '€ '.number_format((float) Invoice::whereIn('status', ['sent', 'overdue'])->sum('amount'), 0, ',', '.')],
                'hours'    => ['value' => number_format((float) Task::sum('spent_hours'), 0, ',', '.')],
            ],
            'invoiceChart' => [
                [
                    'name'   => 'Gefactureerd (€)',
                    'values' => $invoiced->map(fn ($v) => round($v))->values()->all(),
                    'labels' => $months->map(fn (Carbon $m) => $m->translatedFormat('M Y'))->values()->all(),
                ],
            ],
            'taskChart' => [
                [
                    'name'   => 'Taken',
                    'values' => collect(Task::STATUSES)->keys()->map(fn ($s) => $taskCounts[$s] ?? 0)->all(),
                    'labels' => array_values(Task::STATUSES),
                ],
            ],
            'tasks' => Task::with(['project', 'assignee'])
                ->where('status', '!=', 'done')
                ->orderBy('due_at')
                ->limit(8)
                ->get(),
        ];
    }

    public function name(): ?string
    {
        return 'Dashboard';
    }

    public function description(): ?string
    {
        return 'Overzicht van klanten, projecten, taken en facturatie.';
    }

    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Actieve klanten'   => 'metrics.clients',
                'Lopende projecten' => 'metrics.projects',
                'Open taken'        => 'metrics.tasks',
                'Taken over deadline' => 'metrics.overdue',
            ]),

            Layout::metrics([
                'Openstaande facturen' => 'metrics.open',
                'Uren geregistreerd'   => 'metrics.hours',
            ]),

            Layout::columns([
                InvoiceChart::class,
                TaskStatusChart::class,
            ]),

            Layout::block(TaskListLayout::class)
                ->title('Eerstvolgende deadlines')
                ->description('Open taken gesorteerd op deadline.'),
        ];
    }
}
