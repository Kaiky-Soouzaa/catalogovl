<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ProductToolbar extends Widget
{
    protected static string $view = 'filament.widgets.product-toolbar';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;
}
