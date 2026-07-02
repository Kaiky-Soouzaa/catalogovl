<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class LatestOrders extends Widget
{
    protected static string $view = 'filament.widgets.latest-orders';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 7,
    ];

    public function getViewData(): array
    {
        return [
            'orders' => Order::query()
                ->latest()
                ->limit(4)
                ->get(),
        ];
    }
}
