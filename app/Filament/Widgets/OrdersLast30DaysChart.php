<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OrdersLast30DaysChart extends ChartWidget
{
    protected static ?string $heading = 'Pedidos dos Últimos 30 Dias';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = [
        'md' => 8,
        'xl' => 8,
    ];

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {

            $day = Carbon::today()->subDays($i);

            $labels[] = $day->format('d/m');

            $data[] = Order::whereDate('created_at', $day)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pedidos',
                    'data' => $data,

                    'borderColor' => '#F97316',
                    'backgroundColor' => 'rgba(249,115,22,.15)',

                    'fill' => true,
                    'tension' => .45,

                    'pointRadius' => 3,
                    'pointHoverRadius' => 5,
                    'pointBackgroundColor' => '#F97316',

                    'borderWidth' => 3,
                ],
            ],

            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [

            'plugins' => [

                'legend' => [
                    'display' => false,
                ],

            ],

            'scales' => [

                'y' => [

                    'beginAtZero' => true,

                    'grid' => [
                        'display' => true,
                    ],

                ],

                'x' => [

                    'grid' => [
                        'display' => false,
                    ],

                ],

            ],

            'elements' => [

                'line' => [
                    'borderJoinStyle' => 'round',
                ],

            ],

            'maintainAspectRatio' => false,

        ];
    }
}
