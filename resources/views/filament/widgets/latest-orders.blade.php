<x-filament-widgets::widget>
    <div wire:poll.5s x-data="{
        lastId: null,
        unlocked: false,
    
        init() {
            this.lastId = Number(this.$refs.latestOrder.dataset.id || 0)
    
            document.addEventListener('click', () => {
                this.unlocked = true
            }, { once: true })
    
            setInterval(() => {
                let currentId = Number(this.$refs.latestOrder.dataset.id || 0)
    
                if (this.lastId && currentId > this.lastId && this.unlocked) {
                    this.$refs.sound.currentTime = 0
                    this.$refs.sound.play()
                }
    
                this.lastId = currentId
            }, 1500)
        }
    }">
        <audio x-ref="sound" src="{{ asset('sounds/new-order.mp3') }}" preload="auto"></audio>

        <div x-ref="latestOrder" data-id="{{ $orders->first()?->id ?? 0 }}" style="display:none;"></div>

        <x-filament::section style="height: 499px;">
            <x-slot name="heading">
                Últimos Pedidos
            </x-slot>

            <div style="height:100%;display:flex;flex-direction:column;">
                <div class="latest-orders-scroll"
                    style="flex:1;width:100%;border:1px solid #e5e7eb;border-radius:18px;background:#fff;overflow:hidden;">
                    <table class="latest-orders-table"
                        style="width:100%;border-collapse:collapse;font-size:14px;table-layout:auto;">
                        <thead style="background:#f8fafc;color:#64748b;">
                            <tr>
                                <th style="padding:14px;text-align:left;">Pedido</th>
                                <th style="padding:14px;text-align:left;">Cliente</th>
                                <th style="padding:14px;text-align:left;">Valor</th>
                                <th style="padding:14px;text-align:left;">Status</th>
                                <th style="padding:14px;text-align:left;">Data</th>
                                <th style="padding:14px;text-align:center;"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $customerName = $order->customer_name ?? 'Cliente';

                                    if (strlen($customerName) > 18) {
                                        $customerName = substr($customerName, 0, 18) . '...';
                                    }

                                    $dateLabel = $order->created_at->isToday()
                                        ? 'Hoje'
                                        : ($order->created_at->isYesterday()
                                            ? 'Ontem'
                                            : $order->created_at->format('d/m'));

                                    $statusColor = match ($order->status) {
                                        'novo' => ['bg' => '#dbeafe', 'text' => '#2563eb'],
                                        'em processamento' => ['bg' => '#fef3c7', 'text' => '#d97706'],
                                        'enviado', 'entregue' => ['bg' => '#dcfce7', 'text' => '#16a34a'],
                                        'cancelado' => ['bg' => '#fee2e2', 'text' => '#dc2626'],
                                        default => ['bg' => '#f1f5f9', 'text' => '#64748b'],
                                    };
                                @endphp

                                <tr style="border-top:1px solid #e5e7eb;">
                                    <td style="padding:18px;font-weight:700;color:#111827;">
                                        #{{ $order->id }}
                                    </td>

                                    <td style="padding:18px;font-weight:600;">
                                        {{ $customerName }}
                                    </td>

                                    <td style="padding:18px;font-weight:800;color:#16a34a;">
                                        R$ {{ number_format($order->grand_total, 2, ',', '.') }}
                                    </td>



                                    <td style="padding:18px;">
                                        <span
                                            style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:{{ $statusColor['bg'] }};color:{{ $statusColor['text'] }};font-weight:700;font-size:12px;">
                                            <span
                                                style="width:7px;height:7px;border-radius:50%;background:{{ $statusColor['text'] }};"></span>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>

                                    <td style="padding:18px;">
                                        <div style="font-weight:700;color:#111827;">
                                            {{ $dateLabel }}
                                        </div>

                                        <div style="color:#64748b;font-size:12px;">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>

                                    <td style="padding:18px;text-align:center;">
                                        <a href="{{ \App\Filament\Resources\OrderResource::getUrl('view', ['record' => $order]) }}"
                                            style="width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#fff7ed;color:#f97316;text-decoration:none;">
                                            <x-heroicon-o-eye class="w-5 h-5" />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:40px;text-align:center;color:#64748b;">
                                        Nenhum pedido encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>
