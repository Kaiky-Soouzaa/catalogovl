<x-filament-widgets::widget>
    <div class="product-stats-grid">

        @php
            $cards = [
                [
                    'title' => 'Total de Produtos',
                    'value' => $totalProducts,
                    'desc' => 'cadastrados',
                    'color' => '#f97316',
                    'bg' => '#ffedd5',
                    'icon' => 'bag',
                ],
                [
                    'title' => 'À Venda',
                    'value' => $onSaleProducts,
                    'desc' => 'produtos',
                    'color' => '#16a34a',
                    'bg' => '#dcfce7',
                    'icon' => 'check',
                ],
                [
                    'title' => 'Em Estoque',
                    'value' => $inStockProducts,
                    'desc' => 'produtos',
                    'color' => '#4f46e5',
                    'bg' => '#ede9fe',
                    'icon' => 'box',
                ],
                [
                    'title' => 'Sem Estoque',
                    'value' => $outOfStockProducts,
                    'desc' => 'produtos',
                    'color' => '#ef4444',
                    'bg' => '#fee2e2',
                    'icon' => 'x',
                ],
                [
                    'title' => 'Em Destaque',
                    'value' => $featuredProducts,
                    'desc' => 'produtos',
                    'color' => '#2563eb',
                    'bg' => '#dbeafe',
                    'icon' => 'tag',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="product-stat-card">
                <div
                    style="
                        width:54px;
                        height:54px;
                        min-width:54px;
                        border-radius:16px;
                        background:{{ $card['bg'] }};
                        color:{{ $card['color'] }};
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">
                    @if ($card['icon'] === 'bag')
                        <x-heroicon-o-shopping-bag class="w-7 h-7" />
                    @elseif ($card['icon'] === 'check')
                        <x-heroicon-o-check-circle class="w-7 h-7" />
                    @elseif ($card['icon'] === 'box')
                        <x-heroicon-o-archive-box class="w-7 h-7" />
                    @elseif ($card['icon'] === 'x')
                        <x-heroicon-o-x-circle class="w-7 h-7" />
                    @else
                        <x-heroicon-o-tag class="w-7 h-7" />
                    @endif
                </div>

                <div style="min-width:0;">
                    <p style="font-size:14px;color:#6b7280;margin:0;font-weight:600;">
                        {{ $card['title'] }}
                    </p>

                    <p style="font-size:30px;font-weight:800;color:#020617;margin:6px 0 0;line-height:1;">
                        {{ $card['value'] }}
                    </p>

                    <p style="font-size:14px;color:#64748b;margin:7px 0 0;">
                        {{ $card['desc'] }}
                    </p>
                </div>
            </div>
        @endforeach

    </div>
</x-filament-widgets::widget>
