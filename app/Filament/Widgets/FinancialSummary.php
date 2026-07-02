<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;

class FinancialSummary extends Widget
{
    protected static string $view = 'filament.widgets.financial-summary';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 5,
    ];

    protected static ?int $sort = 5;

    public function getViewData(): array
    {
        return [
            'todayRevenue' => Order::whereDate('created_at', today())->sum('grand_total'),
            'monthRevenue' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('grand_total'),
            'averageTicket' => Order::avg('grand_total'),
            'todayOrders' => Order::whereDate('created_at', today())->count(),
            'pixOrders' => Order::where('payment_method', 'pix')->count(),
            'pendingPayments' => Order::where('payment_status', 'pendente')->count(),
        ];
    }
}
