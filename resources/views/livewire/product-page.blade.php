<div class="bg-gray-50 min-h-screen pb-24">

    {{-- Topo --}}
    <div class=" px-4 py-3 flex items-center gap-3 ">
        <a href="javascript:history.back()"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    <div class="max-w-lg mx-auto px-4 py-6">

        {{-- Imagem --}}
        <div class="bg-white rounded-2xl p-6 flex items-center justify-center mb-4">
            @php
                $imagens = is_array($product->images) ? $product->images : [];
                $img = $imagens[0] ?? null;
            @endphp
            @if ($img)
                <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}"
                    class="w-full max-h-64 object-contain">
            @else
                <div class="w-full h-48 bg-gray-100 rounded-xl flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
        </div>

        {{-- Info do produto --}}
        <div class="bg-white rounded-2xl p-4 mb-4">
            <h1 class="text-base font-bold text-gray-900 uppercase">{{ $product->name }}</h1>
            <div class="flex items-center gap-2 mt-1">
                <p class="text-lg font-bold" style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </p>
                @if ($product->original_price)
                    <p class="text-sm text-gray-400 line-through">
                        R$ {{ number_format($product->original_price, 2, ',', '.') }}
                    </p>
                    <span class="text-xs font-bold text-white px-1.5 py-0.5 rounded-lg"
                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                        -{{ $product->discount_percentage }}%
                    </span>
                @endif
            </div>
            @if ($product->description)
                <p class="text-sm text-gray-500 mt-2">{{ $product->description }}</p>
            @endif
        </div>

        {{-- Observação --}}
        <div class="bg-white rounded-2xl p-4 mb-4">
            <h2 class="text-sm font-bold text-gray-700 mb-2">Observações</h2>
            <textarea wire:model="note" rows="3" placeholder="Digite alguma observação..."
                class="w-full text-sm text-gray-600 placeholder-gray-400 border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-1 resize-none"
                style="focus:ring-color: {{ $tenant->primary_color ?? '#00b050' }}">
            </textarea>
        </div>

    </div>

    {{-- Barra inferior --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-4 py-3">
        <div class="max-w-lg mx-auto flex items-center justify-between gap-4">

            {{-- Quantidade --}}
            <div class="flex items-center gap-3 bg-gray-100 rounded-xl px-3 py-2">
                <button wire:click="decrementar"
                    class="w-6 h-6 flex items-center justify-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>
                <span class="text-sm font-bold text-gray-800 w-4 text-center">{{ $quantity }}</span>
                <button wire:click="incrementar"
                    class="w-6 h-6 flex items-center justify-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            {{-- Total + Botão --}}
            <div class="flex items-center gap-3 flex-1">
                <div>
                    <p class="text-xs text-gray-400">Total</p>
                    <p class="text-base font-bold text-gray-900">
                        R$ {{ number_format($product->price * $quantity, 2, ',', '.') }}
                    </p>
                </div>
                <button wire:click="adicionar"
                    class="flex-1 py-3 rounded-xl text-sm font-bold text-white transition-all duration-300 flex items-center justify-center gap-2"
                    style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                    @if ($adicionado)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Adicionado!
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Adicionar
                    @endif
                </button>
            </div>

        </div>
    </div>

</div>


<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('resetarBotao', () => {
            setTimeout(() => {
                @this.call('resetarBotao');
            }, 2000);
        });
    });
</script>
