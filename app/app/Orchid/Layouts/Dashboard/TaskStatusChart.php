<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dashboard;

use Orchid\Screen\Layouts\Chart;

class TaskStatusChart extends Chart
{
    protected $target = 'taskChart';

    protected $title = 'Taken per status';

    protected $type = self::TYPE_PERCENTAGE;

    protected $height = 300;
}
