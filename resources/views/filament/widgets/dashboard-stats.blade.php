<x-filament-widgets::widget>
    <div class="dashboard-stats-grid"
        style="
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:20px;
        ">

        @php
            $cards = [
                [
                    'title' => 'Novos Pedidos',
                    'value' => $newOrders,
                    'color' => '#f97316',
                    'bg' => '#ffedd5',
                    'icon' => 'cart',
                    'line' => 'M0 32 C18 28, 22 12, 42 18 S65 34, 83 18 S115 22, 134 12 S160 28, 180 18',
                ],
                [
                    'title' => 'Em Processamento',
                    'value' => $processingOrders,
                    'color' => '#f59e0b',
                    'bg' => '#fef3c7',
                    'icon' => 'gear',
                    'line' => 'M0 34 C18 22, 35 30, 50 18 S82 10, 105 25 S135 35, 180 15',
                ],
                [
                    'title' => 'Pedidos Enviados',
                    'value' => $sentOrders,
                    'color' => '#22c55e',
                    'bg' => '#dcfce7',
                    'icon' => 'truck',
                    'line' => 'M0 34 C25 30, 45 32, 65 25 S100 15, 125 22 S155 36, 180 18',
                ],
                [
                    'title' => 'Valor Total Pedidos',
                    'value' => 'R$ ' . number_format($totalRevenue, 2, ',', '.'),
                    'color' => '#8b5cf6',
                    'bg' => '#ede9fe',
                    'icon' => 'money',
                    'line' => 'M0 34 C28 32, 50 34, 72 28 S112 30, 135 22 S160 25, 180 10',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div
                style="
                    position:relative;
                    overflow:hidden;
                    min-height:132px;
                    border:1px solid #e5e7eb;
                    border-radius:18px;
                    background:#ffffff;
                    padding:20px 22px;
                    box-shadow:0 8px 24px rgba(15,23,42,.04);
                ">

                <div style="display:flex;gap:16px;align-items:flex-start;">
                    <div
                        style="
                            width:48px;
                            height:48px;
                            min-width:48px;
                            border-radius:14px;
                            background:{{ $card['bg'] }};
                            color:{{ $card['color'] }};
                            display:flex;
                            align-items:center;
                            justify-content:center;
                        ">

                        @if ($card['icon'] === 'cart')
                            <svg width="25" height="25" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.25 3h1.5l1.5 12.75A2.25 2.25 0 0 0 7.5 18h9.75a2.25 2.25 0 0 0 2.2-1.76L21 8.25H5.25" />
                                <circle cx="8.25" cy="20.25" r="1.25" fill="currentColor" />
                                <circle cx="17.25" cy="20.25" r="1.25" fill="currentColor" />
                            </svg>
                        @elseif ($card['icon'] === 'gear')
                            <x-heroicon-o-arrow-path class="w-7 h-7" />
                        @elseif ($card['icon'] === 'truck')
                            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h11v10H3V7Zm11 4h4l3 3v3h-7v-6ZM7 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm11 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            </svg>
                        @else
                            <span style="font-size:26px;font-weight:800;">$</span>
                        @endif
                    </div>

                    <div style="min-width:0;">
                        <p
                            style="
                                font-size:14px;
                                color:#6b7280;
                                margin:0;
                                font-weight:600;
                                line-height:1.25;
                            ">
                            {{ $card['title'] }}
                        </p>

                        <p
                            style="
                                font-size:30px;
                                font-weight:800;
                                color:#020617;
                                margin:8px 0 0;
                                line-height:1;
                            ">
                            {{ $card['value'] }}
                        </p>
                    </div>
                </div>

                <svg viewBox="0 0 180 44"
                    style="
                        position:absolute;
                        right:16px;
                        bottom:10px;
                        width:130px;
                        height:44px;
                    ">
                    <path d="{{ $card['line'] }}" fill="none" stroke="{{ $card['color'] }}" stroke-width="3"
                        stroke-linecap="round" />
                </svg>
            </div>
        @endforeach

    </div>
</x-filament-widgets::widget>
