<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrderStatusApexChart extends ApexChartWidget
{
    protected static ?string $chartId = 'orderStatusApexChart';

    protected static ?string $heading = 'Status dos Pedidos';

    protected static ?int $sort = 7;


    protected int|string|array $columnSpan = [
        'md' => 5,
        'xl' => 5,
    ];

    protected function getOptions(): array
    {
        $novo = Order::where('status', 'novo')->count();
        $processando = Order::where('status', 'em processamento')->count();
        $enviado = Order::where('status', 'enviado')->count();
        $entregue = Order::where('status', 'entregue')->count();
        $cancelado = Order::where('status', 'cancelado')->count();

        return [

            'chart' => [
                'type' => 'donut',
                'height' => 240,
                'toolbar' => [
                    'show' => false,
                ],
            ],

            'series' => [
                $novo,
                $processando,
                $enviado,
                $entregue,
                $cancelado,
            ],

            'labels' => [
                'Novo',
                'Em Processamento',
                'Enviado',
                'Entregue',
                'Cancelado',
            ],

            'colors' => [
                '#3B82F6',
                '#F59E0B',
                '#22C55E',
                '#10B981',
                '#EF4444',
            ],

            'legend' => [
                'position' => 'right',
                'fontFamily' => 'inherit',
                'fontSize' => '14px',
            ],

            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '72%',
                        'labels' => [

                            'show' => true,

                            'total' => [
                                'show' => true,
                                'label' => 'Total',
                                'fontSize' => '18px',
                            ],

                        ],
                    ],
                ],
            ],

            'stroke' => [
                'width' => 0,
            ],

            'dataLabels' => [
                'enabled' => false,
            ],

        ];
    }
}
