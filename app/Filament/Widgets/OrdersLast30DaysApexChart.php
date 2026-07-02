<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrdersLast30DaysApexChart extends ApexChartWidget
{
    protected static ?string $chartId = 'ordersLast30DaysApexChart';

    protected static ?string $heading = 'Pedidos dos Últimos 30 Dias';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);

            $labels[] = $day->format('d/m');
            $data[] = Order::whereDate('created_at', $day)->count();
        }

        return [
            'chart' => [
                'type' => 'area',
                'height' => 250,
                'toolbar' => [
                    'show' => false,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
                'fontFamily' => 'inherit',
                'dropShadow' => [
                    'enabled' => false,
                ],
            ],

            'series' => [
                [
                    'name' => 'Pedidos',
                    'data' => $data,
                ],
            ],

            'colors' => ['#F97316'],

            'stroke' => [
                'curve' => 'smooth',
                'width' => 4,
                'lineCap' => 'round',
            ],

            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'opacityFrom' => 0.45,
                    'opacityTo' => 0.03,
                ],
            ],

            'markers' => [
                'size' => 0,
                'hover' => [
                    'size' => 6,
                ],
            ],

            'xaxis' => [
                'categories' => $labels,
                'tickAmount' => 6,
                'axisBorder' => [
                    'show' => false,
                ],
                'axisTicks' => [
                    'show' => false,
                ],
                'labels' => [
                    'rotate' => 0,
                    'style' => [
                        'colors' => '#64748B',
                        'fontSize' => '12px',
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],

            'yaxis' => [
                'show' => false,
            ],

            'grid' => [
                'show' => true,
                'borderColor' => '#E5E7EB',
                'strokeDashArray' => 4,
                'xaxis' => [
                    'lines' => [
                        'show' => false,
                    ],
                ],
                'yaxis' => [
                    'lines' => [
                        'show' => true,
                    ],
                ],
                'padding' => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 0,
                    'left' => 10,
                ],
            ],

            'dataLabels' => [
                'enabled' => false,
            ],

            'legend' => [
                'show' => false,
            ],

            'tooltip' => [
                'theme' => 'light',
                'style' => [
                    'fontFamily' => 'inherit',
                ],
                'y' => [
                    'formatter' => 'function (value) { return value + " pedidos"; }',
                ],
            ],
        ];
    }
}
