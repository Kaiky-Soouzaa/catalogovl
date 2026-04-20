<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number as SupportNumber;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Novos Pedidos', Order::query()->where('status', 'novo')->count()),
            Stat::make('Pedidos em Processamento', Order::query()->where('status', 'em processamento')->count()),
            Stat::make('Pedidos Enviados', Order::query()->where('status', 'enviado')->count()),
            Stat::make('Valor Total Pedidos', SupportNumber::currency(Order::query()->sum('grand_total'), 'BRL'))
        ];
    }
}
