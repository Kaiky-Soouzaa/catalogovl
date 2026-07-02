<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class DashboardStats extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-stats';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'newOrders' => Order::where('status', 'novo')->count(),
            'processingOrders' => Order::where('status', 'em processamento')->count(),
            'sentOrders' => Order::where('status', 'enviado')->count(),
            'totalRevenue' => Order::sum('grand_total'),
        ];
    }
}
