<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dashboard;

use Orchid\Screen\Layouts\Chart;

class InvoiceChart extends Chart
{
    protected $target = 'invoiceChart';

    protected $title = 'Gefactureerd per maand';

    protected $type = self::TYPE_LINE;

    protected $height = 300;
}
