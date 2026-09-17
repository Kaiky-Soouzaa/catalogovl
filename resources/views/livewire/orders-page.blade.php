<div class="bg-gray-50 min-h-screen pb-10">

    {{-- Topo --}}
    <div class="px-4 py-3 flex items-center gap-3 ">
        <a href="{{ url($tenant->slug) }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        {{-- <h1 class="text-base font-bold text-gray-800">Meus Pedidos</h1> --}}
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

        @forelse($orders as $order)
            <a href="{{ url($tenant->slug . '/pedido/' . $order->id) }}"
                class="block bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">

                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold text-gray-800">Pedido #{{ $order->id }}</p>
                        <span
                            class="w-2 h-2 rounded-full
                            {{ $order->status === 'novo' ? 'bg-blue-400' : '' }}
                            {{ $order->status === 'em processamento' ? 'bg-yellow-400' : '' }}
                            {{ $order->status === 'enviado' ? 'bg-purple-400' : '' }}
                            {{ $order->status === 'entregue' ? 'bg-green-400' : '' }}
                            {{ $order->status === 'cancelado' ? 'bg-red-400' : '' }}">
                        </span>
                    </div>
                    <span class="text-xs text-gray-400">
                        {{ $order->created_at->format('d/m/Y') }} às {{ $order->created_at->format('H\hi') }}
                    </span>
                </div>

                {{-- Itens resumidos --}}
                <p class="text-xs text-gray-500 mb-3 line-clamp-1">
                    {{ $order->items->map(fn($i) => $i->quantity . 'x ' . $i->product->name)->join(', ') }}
                </p>

                <div class="flex items-center justify-between">
                    <span
                        class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $order->status === 'novo' ? 'bg-blue-50 text-blue-600' : '' }}
                        {{ $order->status === 'em processamento' ? 'bg-yellow-50 text-yellow-600' : '' }}
                        {{ $order->status === 'enviado' ? 'bg-purple-50 text-purple-600' : '' }}
                        {{ $order->status === 'entregue' ? 'bg-green-50 text-green-600' : '' }}
                        {{ $order->status === 'cancelado' ? 'bg-red-50 text-red-600' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <p class="text-sm font-bold text-gray-800">
                        R$ {{ number_format($order->grand_total, 2, ',', '.') }}
                    </p>
                </div>

            </a>
        @empty
            <div class="text-center py-16 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>

                <p class="text-sm font-medium">Nenhum pedido encontrado</p>
                <a href="{{ url($tenant->slug) }}" class="text-xs font-semibold mt-2 inline-block"
                    style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                    Ver produtos →
                </a>
            </div>
        @endforelse

    </div>

</div>
