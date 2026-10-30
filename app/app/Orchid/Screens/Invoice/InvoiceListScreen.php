<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Invoice;

use App\Models\Invoice;
use App\Orchid\Layouts\Invoice\InvoiceListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class InvoiceListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'invoices' => Invoice::with('project.client')
                ->filters()
                ->defaultSort('issued_at', 'desc')
                ->paginate(15),
            'metrics' => [
                'open'    => ['value' => '€ '.number_format((float) Invoice::whereIn('status', ['sent', 'overdue'])->sum('amount'), 0, ',', '.')],
                'overdue' => ['value' => Invoice::where('status', 'overdue')->count()],
                'paid'    => ['value' => '€ '.number_format((float) Invoice::where('status', 'paid')->sum('amount'), 0, ',', '.')],
            ],
        ];
    }

    public function name(): ?string
    {
        return 'Facturen';
    }

    public function description(): ?string
    {
        return 'Facturatie per project.';
    }

    public function permission(): ?iterable
    {
        return ['platform.crm.invoices'];
    }

    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Openstaand'      => 'metrics.open',
                'Te laat'         => 'metrics.overdue',
                'Betaald (totaal)' => 'metrics.paid',
            ]),
            InvoiceListLayout::class,
        ];
    }

    public function markPaid(Request $request): void
    {
        $invoice = Invoice::findOrFail($request->get('id'));
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);

        Toast::info('Factuur '.$invoice->number.' gemarkeerd als betaald.');
    }
}
