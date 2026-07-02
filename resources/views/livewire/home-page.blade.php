<div class="bg-gray-50 min-h-screen pb-24">

    {{-- ===== ENDEREÇO DA LOJA ===== --}}
    @if ($tenant->address)
        <div class="px-4 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" style="color: {{ $tenant->primary_color ?? '#00b050' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    <div>
                        <p class="text-xs text-gray-500">
                            {{ $tenant->address }}{{ $tenant->city ? ', ' . $tenant->city : '' }}{{ $tenant->state ? ' - ' . $tenant->state : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 py-4 space-y-6">

        {{-- ===== BANNER OFERTAS ===== --}}
        @if ($produtosOferta->count() > 0)
            <div class="rounded-2xl p-4 flex gap-3 overflow-x-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
                style="background: linear-gradient(135deg, {{ $tenant->primary_color ?? '#00b050' }}15, {{ $tenant->primary_color ?? '#00b050' }}08)">

                <div class="flex-shrink-0 flex flex-col justify-center min-w-[140px]">
                    <p class="text-base font-bold text-gray-800 leading-tight">Ofertas imperdíveis pra você!</p>
                    <p class="text-xs text-gray-500 mt-1">Aproveite os melhores preços</p>
                </div>

                <div
                    class="flex gap-3 overflow-x-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] pb-1">
                    @foreach ($produtosOferta->take(5) as $produto)
                        @php
                            $imagens = is_array($produto->images) ? $produto->images : [];
                            $img = $imagens[0] ?? null;
                        @endphp

                        <div class="flex-shrink-0 bg-white rounded-xl p-3 w-36 shadow-sm cursor-pointer"
                            onclick="window.location='{{ url($tenant->slug . '/produto/' . $produto->slug) }}'">

                            @if ($img)
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $produto->name }}"
                                    class="w-14 h-14 object-contain mx-auto">
                            @else
                                <div class="w-14 h-14 bg-gray-100 rounded-lg mx-auto"></div>
                            @endif

                            <p class="text-xs font-medium text-gray-700 mt-2 line-clamp-2 leading-tight">
                                {{ $produto->name }}
                            </p>

                            <p class="text-sm font-bold mt-1" style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                R$ {{ number_format($produto->price, 2, ',', '.') }}
                            </p>

                            @if ($produto->original_price)
                                <p class="text-xs text-gray-400 line-through">
                                    R$ {{ number_format($produto->original_price, 2, ',', '.') }}
                                </p>
                                <span class="text-xs font-bold text-red-500">
                                    -{{ $produto->discount_percentage }}%
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===== PRODUTOS EM OFERTA ===== --}}
        @if ($produtosOferta->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold text-gray-800">Produtos em oferta</h2>
                </div>

                <div class="relative -mx-4 px-4">
                    <div id="scroll-ofertas" onscroll="handleProductScroll('ofertas')"
                        class="flex gap-3 overflow-x-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] pb-2 scroll-smooth">

                        @foreach ($produtosOferta as $produto)
                            @php
                                $imagens = is_array($produto->images) ? $produto->images : [];
                                $img = $imagens[0] ?? null;
                            @endphp

                            <div class="flex-shrink-0 w-40 bg-white rounded-2xl p-3 shadow-sm border border-gray-100 cursor-pointer flex flex-col"
                                onclick="window.location='{{ url($tenant->slug . '/produto/' . $produto->slug) }}'">

                                <div class="relative">
                                    @if ($produto->discount_percentage > 0)
                                        <span
                                            class="absolute top-0 left-0 text-xs font-bold text-white px-1.5 py-0.5 rounded-lg"
                                            style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                            -{{ $produto->discount_percentage }}%
                                        </span>
                                    @endif

                                    @if ($img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $produto->name }}"
                                            class="w-full h-28 object-contain">
                                    @else
                                        <div
                                            class="w-full h-28 bg-gray-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col flex-1">
                                    <p
                                        class="text-xs font-medium text-gray-700 mt-2 line-clamp-2 leading-tight uppercase">
                                        {{ $produto->name }}
                                    </p>

                                    <div class="mt-auto pt-1">
                                        <p class="text-sm font-bold text-gray-900">
                                            R$ {{ number_format($produto->price, 2, ',', '.') }}
                                        </p>

                                        @if ($produto->original_price)
                                            <p class="text-xs text-gray-400 line-through">
                                                R$ {{ number_format($produto->original_price, 2, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2" onclick="event.stopPropagation()">
                                    @if (in_array($produto->id, $adicionados))
                                        <div class="w-full flex items-center justify-between rounded-xl border px-1 py-0.5"
                                            style="border-color: {{ $tenant->primary_color ?? '#00b050' }}">

                                            <button wire:click.stop="decrementarHome({{ $produto->id }})"
                                                class="w-6 h-6 flex items-center justify-center rounded-lg transition-colors duration-200"
                                                style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>

                                            <span class="text-xs font-bold"
                                                style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                {{ $quantidades[$produto->id] ?? 1 }}
                                            </span>

                                            <button wire:click.stop="incrementarHome({{ $produto->id }})"
                                                class="w-6 h-6 flex items-center justify-center rounded-lg transition-colors duration-200"
                                                style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <button wire:click.stop="adicionarRapido({{ $produto->id }})"
                                            class="w-full py-1.5 rounded-xl text-xs font-semibold text-white transition-all duration-200 flex items-center justify-center gap-1"
                                            style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Adicionar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="left-ofertas" onclick="scrollProductLeft('ofertas')"
                        class="hidden absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 items-center justify-center rounded-full bg-white hover:bg-gray-100 transition-colors shadow-md z-10">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button type="button" id="right-ofertas" onclick="scrollProductRight('ofertas')"
                        class="hidden absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 items-center justify-center rounded-full bg-white hover:bg-gray-100 transition-colors shadow-md z-10">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- ===== SEÇÕES POR CATEGORIA ===== --}}
        @forelse($categorias as $categoria)
            @if ($categoria->products->count() > 0)
                <div id="categoria-{{ $categoria->slug }}">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-gray-800">{{ $categoria->name }}</h2>
                    </div>

                    <div class="relative -mx-4 px-4">
                        <div id="scroll-categoria-{{ $categoria->id }}"
                            onscroll="handleProductScroll('categoria-{{ $categoria->id }}')"
                            class="flex gap-3 overflow-x-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] pb-2 scroll-smooth">

                            @foreach ($categoria->products as $produto)
                                @php
                                    $imagens = is_array($produto->images) ? $produto->images : [];
                                    $img = $imagens[0] ?? null;
                                @endphp

                                <div class="flex-shrink-0 w-40 bg-white rounded-2xl p-3 shadow-sm border border-gray-100 cursor-pointer flex flex-col"
                                    onclick="window.location='{{ url($tenant->slug . '/produto/' . $produto->slug) }}'">

                                    <div class="relative">
                                        @if ($produto->discount_percentage > 0)
                                            <span
                                                class="absolute top-0 left-0 text-xs font-bold text-white px-1.5 py-0.5 rounded-lg"
                                                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                -{{ $produto->discount_percentage }}%
                                            </span>
                                        @endif

                                        @if ($img)
                                            <img src="{{ asset('storage/' . $img) }}" alt="{{ $produto->name }}"
                                                class="w-full h-28 object-contain">
                                        @else
                                            <div
                                                class="w-full h-28 bg-gray-100 rounded-xl flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col flex-1">
                                        <p
                                            class="text-xs font-medium text-gray-700 mt-2 line-clamp-2 leading-tight uppercase">
                                            {{ $produto->name }}
                                        </p>

                                        <div class="mt-auto pt-1">
                                            <p class="text-sm font-bold text-gray-900">
                                                R$ {{ number_format($produto->price, 2, ',', '.') }}
                                            </p>

                                            @if ($produto->original_price)
                                                <p class="text-xs text-gray-400 line-through">
                                                    R$ {{ number_format($produto->original_price, 2, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-2" onclick="event.stopPropagation()">
                                        @if (in_array($produto->id, $adicionados))
                                            <div class="w-full flex items-center justify-between rounded-xl border px-1 py-0.5"
                                                style="border-color: {{ $tenant->primary_color ?? '#00b050' }}">

                                                <button wire:click.stop="decrementarHome({{ $produto->id }})"
                                                    class="w-6 h-6 flex items-center justify-center rounded-lg transition-colors duration-200"
                                                    style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>

                                                <span class="text-xs font-bold"
                                                    style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                    {{ $quantidades[$produto->id] ?? 1 }}
                                                </span>

                                                <button wire:click.stop="incrementarHome({{ $produto->id }})"
                                                    class="w-6 h-6 flex items-center justify-center rounded-lg transition-colors duration-200"
                                                    style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            <button wire:click.stop="adicionarRapido({{ $produto->id }})"
                                                class="w-full py-1.5 rounded-xl text-xs font-semibold text-white transition-all duration-200 flex items-center justify-center gap-1"
                                                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Adicionar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" id="left-categoria-{{ $categoria->id }}"
                            onclick="scrollProductLeft('categoria-{{ $categoria->id }}')"
                            class="hidden absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 items-center justify-center rounded-full bg-white hover:bg-gray-100 transition-colors shadow-md z-10">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <button type="button" id="right-categoria-{{ $categoria->id }}"
                            onclick="scrollProductRight('categoria-{{ $categoria->id }}')"
                            class="hidden absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 items-center justify-center rounded-full bg-white hover:bg-gray-100 transition-colors shadow-md z-10">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-16 text-gray-400">
                <p class="text-sm">Nenhuma categoria cadastrada.</p>
            </div>
        @endforelse

    </div>

    {{-- ===== BARRA INFERIOR CARRINHO ===== --}}
    @if ($totalItens > 0)
        <div class="fixed bottom-0 left-0 right-0 z-50 px-4 pb-15">
            <div class="max-w-2xl mx-auto rounded-2xl p-4 flex items-center justify-between shadow-2xl text-white"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">

                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>

                    <div>
                        <p class="text-sm font-bold">
                            {{ $totalItens }} {{ $totalItens == 1 ? 'item' : 'itens' }}
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-xs opacity-80">Total do pedido</p>
                    <p class="text-lg font-bold">
                        R$ {{ number_format($totalValor, 2, ',', '.') }}
                    </p>
                </div>

                <a href="{{ url($tenant->slug . '/carrinho') }}"
                    class="bg-white rounded-xl px-4 py-2 text-sm font-bold transition-all duration-200"
                    style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                    Finalizar pedido →
                </a>
            </div>
        </div>
    @endif

    {{-- ===== SCRIPT DAS SETAS ===== --}}
    <script>
        function getProductScrollElements(id) {
            return {
                scroll: document.getElementById('scroll-' + id),
                left: document.getElementById('left-' + id),
                right: document.getElementById('right-' + id),
            };
        }

        function showButton(button) {
            if (!button) return;
            button.classList.remove('hidden');
            button.classList.add('flex');
        }

        function hideButton(button) {
            if (!button) return;
            button.classList.add('hidden');
            button.classList.remove('flex');
        }

        function handleProductScroll(id) {
            const elements = getProductScrollElements(id);

            if (!elements.scroll || !elements.left || !elements.right) {
                return;
            }

            const maxScroll = elements.scroll.scrollWidth - elements.scroll.clientWidth;

            if (maxScroll <= 5) {
                hideButton(elements.left);
                hideButton(elements.right);
                return;
            }

            if (elements.scroll.scrollLeft > 5) {
                showButton(elements.left);
            } else {
                hideButton(elements.left);
            }

            if (elements.scroll.scrollLeft < maxScroll - 5) {
                showButton(elements.right);
            } else {
                hideButton(elements.right);
            }
        }

        function scrollProductRight(id) {
            const elements = getProductScrollElements(id);

            if (!elements.scroll) {
                return;
            }

            elements.scroll.scrollBy({
                left: 180,
                behavior: 'smooth'
            });

            setTimeout(function() {
                handleProductScroll(id);
            }, 300);
        }

        function scrollProductLeft(id) {
            const elements = getProductScrollElements(id);

            if (!elements.scroll) {
                return;
            }

            elements.scroll.scrollBy({
                left: -180,
                behavior: 'smooth'
            });

            setTimeout(function() {
                handleProductScroll(id);
            }, 300);
        }

        function initProductScrollButtons() {
            handleProductScroll('ofertas');

            @foreach ($categorias as $categoria)
                handleProductScroll('categoria-{{ $categoria->id }}');
            @endforeach
        }

        document.addEventListener('DOMContentLoaded', initProductScrollButtons);
        document.addEventListener('livewire:navigated', initProductScrollButtons);

        setTimeout(initProductScrollButtons, 500);
    </script>

</div>
