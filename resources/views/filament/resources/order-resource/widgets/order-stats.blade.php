<x-filament-widgets::widget>
    <div class="order-stats-grid" x-data="{ currentStatus: new URLSearchParams(window.location.search).get('status') || '' }">
        @php
            $cards = [
                [
                    'title' => 'Todos os Pedidos',
                    'value' => $totalOrders,
                    'status' => '',
                    'color' => '#f97316',
                    'bg' => '#ffedd5',
                    'icon' => 'cart',
                    'line' => 'M0 32 C18 28, 22 12, 42 18 S65 34, 83 18 S115 22, 134 12 S160 28, 180 18',
                ],
                [
                    'title' => 'Novos Pedidos',
                    'value' => $newOrders,
                    'status' => 'novo',
                    'color' => '#22c55e',
                    'bg' => '#dcfce7',
                    'icon' => 'plus',
                    'line' => 'M0 34 C25 30, 45 32, 65 25 S100 15, 125 22 S155 36, 180 18',
                ],
                [
                    'title' => 'Em Processamento',
                    'value' => $processingOrders,
                    'status' => 'em processamento',
                    'color' => '#f59e0b',
                    'bg' => '#fef3c7',
                    'icon' => 'gear',
                    'line' => 'M0 34 C18 22, 35 30, 50 18 S82 10, 105 25 S135 35, 180 15',
                ],
                [
                    'title' => 'Pedidos Enviados',
                    'value' => $sentOrders,
                    'status' => 'enviado',
                    'color' => '#2563eb',
                    'bg' => '#dbeafe',
                    'icon' => 'truck',
                    'line' => 'M0 34 C25 30, 45 32, 65 25 S100 15, 125 22 S155 36, 180 18',
                ],
                [
                    'title' => 'Cancelados',
                    'value' => $canceledOrders,
                    'status' => 'cancelado',
                    'color' => '#ef4444',
                    'bg' => '#fee2e2',
                    'icon' => 'x',
                    'line' => 'M0 34 C28 32, 50 34, 72 28 S112 30, 135 22 S160 25, 180 10',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            @php
                $url = $card['status']
                    ? \App\Filament\Resources\OrderResource::getUrl('index') . '?status=' . urlencode($card['status'])
                    : \App\Filament\Resources\OrderResource::getUrl('index');
            @endphp

            <a href="{{ $url }}" class="order-dashboard-card"
                x-bind:class="currentStatus === '{{ $card['status'] }}' ? 'order-dashboard-card-active' : ''"
                style="--card-color: {{ $card['color'] }}; --card-bg: {{ $card['bg'] }};">
                <div style="display:flex;gap:16px;align-items:flex-start;">
                    <div class="order-dashboard-icon">
                        @if ($card['icon'] === 'cart')
                            <svg width="25" height="25" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.25 3h1.5l1.5 12.75A2.25 2.25 0 0 0 7.5 18h9.75a2.25 2.25 0 0 0 2.2-1.76L21 8.25H5.25" />
                                <circle cx="8.25" cy="20.25" r="1.25" fill="currentColor" />
                                <circle cx="17.25" cy="20.25" r="1.25" fill="currentColor" />
                            </svg>
                        @elseif ($card['icon'] === 'plus')
                            <x-heroicon-o-plus-circle class="w-7 h-7" />
                        @elseif ($card['icon'] === 'gear')
                            <x-heroicon-o-arrow-path class="w-7 h-7" />
                        @elseif ($card['icon'] === 'truck')
                            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h11v10H3V7Zm11 4h4l3 3v3h-7v-6ZM7 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm11 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            </svg>
                        @else
                            <x-heroicon-o-x-circle class="w-7 h-7" />
                        @endif
                    </div>

                    <div style="min-width:0;">
                        <p class="order-dashboard-title">
                            {{ $card['title'] }}
                        </p>

                        <p class="order-dashboard-value">
                            {{ $card['value'] }}
                        </p>
                    </div>
                </div>

                <svg viewBox="0 0 180 44" class="order-dashboard-line">
                    <path d="{{ $card['line'] }}" fill="none" stroke="{{ $card['color'] }}" stroke-width="3"
                        stroke-linecap="round" />
                </svg>
            </a>
        @endforeach
    </div>
</x-filament-widgets::widget>
