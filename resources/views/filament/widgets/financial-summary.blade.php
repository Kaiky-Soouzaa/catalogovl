<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Resumo Financeiro
        </x-slot>

        <div class="financial-summary-grid">

            @php
                $cards = [
                    [
                        'label' => 'Faturamento Hoje',
                        'value' => 'R$ ' . number_format($todayRevenue, 2, ',', '.'),
                        'icon' => '$',
                        'bg' => '#dcfce7',
                        'color' => '#16a34a',
                    ],
                    [
                        'label' => 'Faturamento do Mês',
                        'value' => 'R$ ' . number_format($monthRevenue, 2, ',', '.'),
                        'icon' => 'R$',
                        'bg' => '#dbeafe',
                        'color' => '#2563eb',
                    ],
                    [
                        'label' => 'Ticket Médio',
                        'value' => 'R$ ' . number_format($averageTicket, 2, ',', '.'),
                        'icon' => '%',
                        'bg' => '#f3e8ff',
                        'color' => '#9333ea',
                    ],
                    [
                        'label' => 'Pedidos Hoje',
                        'value' => $todayOrders,
                        'icon' => 'cart',
                        'bg' => '#ffedd5',
                        'color' => '#ea580c',
                    ],
                    [
                        'label' => 'Pedidos PIX',
                        'value' => $pixOrders,
                        'icon' => 'PIX',
                        'bg' => '#d1fae5',
                        'color' => '#059669',
                    ],
                    [
                        'label' => 'Pendentes',
                        'value' => $pendingPayments,
                        'icon' => '!',
                        'bg' => '#fef3c7',
                        'color' => '#d97706',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="financial-summary-card">
                    <div class="financial-summary-info">
                        <p class="financial-summary-label">
                            {{ $card['label'] }}
                        </p>

                        <p class="financial-summary-value"
                            style="color: {{ $card['label'] === 'Pendentes' ? '#ea580c' : '#111827' }};">
                            {{ $card['value'] }}
                        </p>
                    </div>

                    <div class="financial-summary-icon"
                        style="background: {{ $card['bg'] }}; color: {{ $card['color'] }};">
                        @if ($card['icon'] === 'cart')
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.25 3h1.5l1.5 12.75A2.25 2.25 0 0 0 7.5 18h9.75a2.25 2.25 0 0 0 2.2-1.76L21 8.25H5.25" />
                                <circle cx="8.25" cy="20.25" r="1.25" fill="currentColor" />
                                <circle cx="17.25" cy="20.25" r="1.25" fill="currentColor" />
                            </svg>
                        @else
                            {{ $card['icon'] }}
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
