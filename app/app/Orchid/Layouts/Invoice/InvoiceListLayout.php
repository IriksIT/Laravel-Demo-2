<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Invoice;

use App\Models\Invoice;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Currency;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class InvoiceListLayout extends Table
{
    public $target = 'invoices';

    public function columns(): array
    {
        return [
            TD::make('number', 'Factuurnummer')
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('project', 'Project')
                ->render(fn (Invoice $invoice) => Link::make($invoice->project?->name ?? '-')
                    ->route('platform.projects.edit', $invoice->project)),

            TD::make('client', 'Klant')
                ->render(fn (Invoice $invoice) => $invoice->project?->client?->company),

            TD::make('amount', 'Bedrag')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->usingComponent(Currency::class, symbol: '€ '),

            TD::make('status', 'Status')
                ->sort()
                ->filter(Select::make()->options(Invoice::STATUSES)->empty('Alle'))
                ->render(fn (Invoice $invoice) => $invoice->statusLabel()),

            TD::make('issued_at', 'Factuurdatum')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->render(fn (Invoice $invoice) => $invoice->issued_at?->format('d-m-Y')),

            TD::make('due_at', 'Vervaldatum')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->render(fn (Invoice $invoice) => $invoice->due_at?->format('d-m-Y')),

            TD::make('Acties')
                ->align(TD::ALIGN_CENTER)
                ->width('140px')
                ->render(fn (Invoice $invoice) => Button::make('Markeer betaald')
                    ->icon('bs.check2-circle')
                    ->method('markPaid', ['id' => $invoice->id])
                    ->canSee($invoice->status !== 'paid')),
        ];
    }
}
