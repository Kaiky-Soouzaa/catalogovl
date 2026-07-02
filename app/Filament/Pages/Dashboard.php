<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\OrdersLast30DaysApexChart;
use App\Filament\Widgets\OrderStatusApexChart;
use App\Filament\Widgets\LatestOrders;
use App\Filament\Widgets\FinancialSummary;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';



    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'xl' => 12,
        ];
    }

    public function getWidgets(): array
    {
        return [
            DashboardStats::class,
            LatestOrders::class,
            FinancialSummary::class,


            OrdersLast30DaysApexChart::class,


        ];
    }
}
