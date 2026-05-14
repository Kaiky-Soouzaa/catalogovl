<div class="bg-gray-50 min-h-screen pb-24">

    {{-- Topo --}}
    <div class="px-4 py-3 flex items-center gap-3  border-gray-100">
        <a href="{{ url($tenant->id) }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-base font-bold text-gray-800">Carrinho</h1>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

        @forelse($cartItems as $item)
            <div class="bg-white rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100">

                {{-- Imagem --}}
                @php
                    $imagens = is_array($item->product->images) ? $item->product->images : [];
                    $img = $imagens[0] ?? null;
                @endphp
                @if ($img)
                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $item->product->name }}"
                        class="w-20 h-20 object-contain flex-shrink-0">
                @else
                    <div class="w-20 h-20 bg-gray-100 rounded-xl flex-shrink-0"></div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-800 uppercase leading-snug">
                        {{ $item->product->name }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->product->category->name ?? '' }}</p>
                    <p class="text-base font-bold mt-2" style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                        R$ {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}
                    </p>
                    @if ($item->quantity > 1)
                        <p class="text-xs text-gray-400">
                            R$ {{ number_format($item->product->price, 2, ',', '.') }} cada
                        </p>
                    @endif
                    @if ($item->note)
                        <p class="text-xs text-gray-400 mt-1 italic">{{ $item->note }}</p>
                    @endif
                </div>

                {{-- Quantidade + Remover --}}
                <div class="flex flex-col items-end gap-3">
                    {{-- Lixeira --}}
                    <button wire:click="remover({{ $item->id }})"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>

                    {{-- Quantidade --}}
                    <div class="flex items-center gap-1 bg-gray-100 rounded-xl px-1 py-1">
                        <button wire:click="decrementar({{ $item->id }})"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 hover:bg-white hover:shadow-sm hover:text-gray-900 transition-all duration-200 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                        <span class="text-sm font-bold text-gray-800 w-6 text-center">{{ $item->quantity }}</span>
                        <button wire:click="incrementar({{ $item->id }})"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 hover:bg-white hover:shadow-sm hover:text-gray-900 transition-all duration-200 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm font-medium">Seu carrinho está vazio</p>
                <a href="{{ url($tenant->id) }}" class="text-xs font-semibold mt-2 inline-block"
                    style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                    Ver produtos →
                </a>
            </div>
        @endforelse

        {{-- Botão adicionar mais itens --}}
        @if ($cartItems->count() > 0)
            <a href="{{ url($tenant->id) }}"
                class="w-full py-3 rounded-2xl text-sm font-semibold border-2 flex items-center justify-center gap-2 transition-all duration-200 hover:bg-gray-50"
                style="color: {{ $tenant->primary_color ?? '#00b050' }}; border-color: {{ $tenant->primary_color ?? '#00b050' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Adicionar mais itens
            </a>
        @endif

    </div>

    {{-- Barra inferior --}}
    @if ($cartItems->count() > 0)
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-4 py-3">
            <div class="max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-lg font-bold text-gray-900">Total:</span>
                    <span class="text-lg font-bold text-gray-900">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </span>
                </div>
                <a href="{{ url($tenant->id . '/finalizar') }}"
                    class="w-full py-3 rounded-2xl text-sm font-bold text-white flex items-center justify-center transition-all duration-200"
                    style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                    Continuar →
                </a>
            </div>
        </div>
    @endif

</div>
