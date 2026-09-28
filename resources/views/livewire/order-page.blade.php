<div class="bg-gray-50 min-h-screen pb-10">

    {{-- Gera o link WhatsApp no topo --}}
    @php
        $linkWhatsapp = '#';
        if ($tenant->whatsapp) {
            $whatsapp = preg_replace('/\D/', '', $tenant->whatsapp);

            $itens = $order->items
                ->map(function ($i) {
                    $nome = strip_tags($i->product->name ?? 'Produto');
                    return "• {$i->quantity}x {$nome} - R$ " . number_format($i->total_amount ?? 0, 2, ',', '.');
                })
                ->toArray();

            $entrega =
                $order->delivery_type === 'entrega'
                    ? 'Entrega: ' . ($order->delivery_address ?? 'Não informado')
                    : 'Retirada no estabelecimento';

            $pagamento = match ($order->payment_method) {
                'pix' => 'PIX',
                'dinheiro' => 'Dinheiro',
                'cartao', 'cartão' => 'Cartão',
                default => ucfirst($order->payment_method ?? ''),
            };

            $mensagem = implode("\n", [
                '*NOVO PEDIDO*',
                '',
                '*Pedido:* #' . $order->id,
                '*Data:* ' . $order->created_at->format('d/m/Y H:i'),
                '',
                '*Cliente:* ' . ($order->customer_name ?? ''),
                '*Telefone:* ' . ($order->customer_phone ?? ''),
                '',
                '*Itens:*',
                ...$itens,
                '',
                '*Total:* R$ ' . number_format($order->grand_total ?? 0, 2, ',', '.'),
                '*Pagamento:* ' . $pagamento,
                '',
                $entrega,
            ]);

            $linkWhatsapp = 'https://wa.me/' . $whatsapp . '?text=' . urlencode($mensagem);
        }
    @endphp

    {{-- Topo --}}
    <div class="px-4 py-3 flex items-center gap-3">
        <a href="{{ url($tenant->slug) }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        {{-- <h1 class="text-base font-bold text-gray-800">Detalhes do pedido</h1> --}}
    </div>

    <div class="max-w-lg mx-auto px-4 py-4 space-y-3">

        {{-- Card principal --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">

            <div class="flex items-center gap-2 mb-4">
                <p class="text-lg font-bold text-gray-800">Pedido {{ $order->id }}</p>
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
            </div>
            <p class="text-xs text-gray-400 -mt-3 mb-4">
                {{ $order->created_at->format('d/m/Y') }} às {{ $order->created_at->format('H\hi') }}
            </p>

            {{-- Botão WhatsApp --}}
            @if ($tenant->whatsapp)
                <a href="{{ $linkWhatsapp }}" target="_blank" rel="noopener noreferrer"
                    class="w-full py-3 rounded-xl text-sm font-bold text-white flex items-center justify-center gap-2 mb-5 transition-all duration-200"
                    style="background-color: #25D366">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Enviar pedido no WhatsApp
                </a>
            @endif

            {{-- Itens --}}
            <div class="space-y-3 border-t border-gray-100 pt-4">
                @foreach ($order->items as $item)
                    <div class="flex justify-between items-start">
                        <div class="flex gap-2">
                            <span class="text-sm text-gray-500">{{ $item->quantity }}x</span>
                            <span
                                class="text-sm font-medium text-gray-800">{{ $item->product?->name ?? 'Produto removido' }}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-800">
                            R$ {{ number_format($item->total_amount, 2, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Total --}}
            <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between items-center">
                <span class="text-base font-bold text-gray-800">TOTAL:</span>
                <span class="text-base font-bold text-gray-800">
                    R$ {{ number_format($order->grand_total, 2, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- Pagamento --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-2">
                Pagamento na {{ $order->delivery_type === 'entrega' ? 'entrega' : 'retirada' }}
            </p>
            <div class="flex items-center gap-2">
                @if ($order->payment_method === 'pix')
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                        <svg class="w-4 h-4" style="color: {{ $tenant->primary_color ?? '#00b050' }}"
                            viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M11.9 2.1L7.5 6.5H4.8c-.7 0-1.3.6-1.3 1.3v2.7L1 13l2.5 2.5v2.7c0 .7.6 1.3 1.3 1.3h2.7l4.4 4.4 4.4-4.4h2.7c.7 0 1.3-.6 1.3-1.3v-2.7L23 13l-2.5-2.5V7.8c0-.7-.6-1.3-1.3-1.3h-2.7L11.9 2.1zm0 2.8l3 3-3 3-3-3 3-3zm-6.4 4.4h2.1l2.2 2.2-2.2 2.2H5.5v-2.1l.7-.7-.7-.7V9.3zm12.8 0v2.1l-.7.7.7.7v2.1h-2.1l-2.2-2.2 2.2-2.2h2.1zm-7.5 3.5l3 3-3 3-3-3 3-3z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">Pagamento via PIX</span>
                @elseif($order->payment_method === 'dinheiro')
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                        <svg class="w-4 h-4" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">Pagamento em dinheiro</span>
                @else
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                        <svg class="w-4 h-4" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">Pagamento no cartão</span>
                @endif
            </div>
        </div>

        {{-- Entrega --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            @if ($order->delivery_type === 'entrega')
                <p class="text-xs text-gray-400 mb-1">Entrega em domicílio</p>
                <p class="text-sm font-medium text-gray-800">{{ $order->delivery_address }}</p>
            @else
                <p class="text-xs text-gray-400 mb-1">Retirada no estabelecimento</p>
                @if ($tenant->address)
                    <p class="text-sm font-medium text-gray-800 uppercase">{{ $tenant->address }}</p>
                    <p class="text-sm text-gray-600 uppercase">
                        {{ $tenant->neighborhood ? $tenant->neighborhood . ', ' : '' }}{{ $tenant->city }}{{ $tenant->state ? ' - ' . $tenant->state : '' }}
                    </p>
                @endif
            @endif
        </div>

    </div>

</div>
