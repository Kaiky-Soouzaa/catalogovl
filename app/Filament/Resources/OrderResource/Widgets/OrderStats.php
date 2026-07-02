<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class OrderStats extends Widget
{
    protected static string $view = 'filament.resources.order-resource.widgets.order-stats';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '5s';

    public function getViewData(): array
    {
        return [
            'totalOrders' => Order::count(),
            'newOrders' => Order::where('status', 'novo')->count(),
            'processingOrders' => Order::where('status', 'em processamento')->count(),
            'sentOrders' => Order::where('status', 'enviado')->count(),
            'canceledOrders' => Order::where('status', 'cancelado')->count(),
            'currentStatus' => request()->query('status'),
        ];
    }
}
