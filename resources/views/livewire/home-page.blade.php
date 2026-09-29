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

        {{-- ===== CARROSSEL DE BANNERS ===== --}}
        @if ($banners->count() > 0)
            <div class="relative rounded-2xl overflow-hidden" x-data="{
                slide: 0,
                total: {{ $banners->count() }},
                touchStartX: 0,
                next() { this.slide = (this.slide + 1) % this.total },
                prev() { this.slide = (this.slide - 1 + this.total) % this.total },
                handleTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX },
                handleTouchEnd(e) {
                    const touchEndX = e.changedTouches[0].screenX;
                    const diff = this.touchStartX - touchEndX;
                    if (Math.abs(diff) > 40) {
                        diff > 0 ? this.next() : this.prev();
                    }
                }
            }"
                @touchstart="handleTouchStart($event)" @touchend="handleTouchEnd($event)">
                <div class="flex transition-transform duration-500 ease-out"
                    :style="`transform: translateX(-${slide * 100}%)`">
                    @foreach ($banners as $banner)
                        <div class="w-full flex-shrink-0">
                            @if ($banner->link)
                                <a href="{{ $banner->link }}">
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner"
                                        class="w-full h-auto object-cover">
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner"
                                    class="w-full h-auto object-cover">
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($banners->count() > 1)
                    <button @click="prev()"
                        class="hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white items-center justify-center shadow-md cursor-pointer hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="next()"
                        class="hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white items-center justify-center shadow-md cursor-pointer hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                        <template x-for="i in total" :key="i">
                            <button @click="slide = i - 1"
                                class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                                :style="slide === i - 1 ?
                                    'width: 1.5rem; background-color: {{ $tenant->primary_color ?? '#00b050' }};' :
                                    'width: 0.375rem; background-color: white; opacity: 0.6;'"></button>
                        </template>
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== PRODUTOS EM OFERTA ===== --}}
        @if ($produtosOferta->count() > 0)
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold text-gray-800">Produtos em oferta</h2>
                    <a href="{{ url($tenant->slug . '/ofertas') }}"
                        class="text-sm font-semibold flex items-center gap-1"
                        style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                        Ver todos
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="flex flex-nowrap overflow-hidden">
                    @foreach ($produtosOferta->take(6) as $produto)
                        @php
                            $imagens = is_array($produto->images) ? $produto->images : [];
                            $img = $imagens[0] ?? null;
                        @endphp

                        <div class="flex-shrink-0 basis-1/2 sm:basis-1/3 md:basis-1/4 lg:basis-1/5 xl:basis-1/6 p-3 cursor-pointer flex flex-col"
                            @if ($clienteLogado) onclick="window.location='{{ url($tenant->slug . '/produto/' . $produto->slug) }}'"
                            @else
                                onclick="Livewire.dispatch('abrir-modal-login')" @endif>

                            <div class="relative">
                                @if ($clienteLogado && $precos->desconto($produto) > 0)
                                    <span
                                        class="absolute top-0 left-0 text-xs font-bold text-white px-1.5 py-0.5 rounded-lg"
                                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                        -{{ $precos->desconto($produto) }}%
                                    </span>
                                @endif

                                @if ($img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $produto->name }}"
                                        class="w-full h-28 object-contain">
                                @else
                                    <div class="w-full h-28 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            @if ($clienteLogado)
                                <div class="relative z-10 flex justify-end mt-1 mb-1" x-data="{ show: false, timer: null }">
                                    <div x-show="show" x-transition
                                        class="flex items-center gap-1 rounded-full text-white shadow-md px-1 py-1"
                                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}"
                                        onclick="event.stopPropagation()">
                                        <button wire:click.stop="decrementarHome({{ $produto->id }})"
                                            x-on:click="{{ ($quantidades[$produto->id] ?? 1) <= 1 ? 'clearTimeout(timer); show = false;' : 'clearTimeout(timer); timer = setTimeout(() => show = false, 2000);' }}"
                                            class="w-7 h-7 flex items-center justify-center cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span
                                            class="text-sm font-bold w-5 text-center">{{ $quantidades[$produto->id] ?? 1 }}</span>
                                        <button wire:click.stop="incrementarHome({{ $produto->id }})"
                                            x-on:click="clearTimeout(timer); timer = setTimeout(() => show = false, 2000)"
                                            class="w-7 h-7 flex items-center justify-center cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                    <button x-show="!show"
                                        x-transition:enter="transition ease-out duration-300 delay-100"
                                        x-transition:enter-start="opacity-0 scale-75"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        wire:click.stop="{{ in_array($produto->id, $adicionados) ? 'incrementarHome(' . $produto->id . ')' : 'adicionarRapido(' . $produto->id . ')' }}"
                                        x-on:click="show = true; clearTimeout(timer); timer = setTimeout(() => show = false, 2000)"
                                        class="w-9 h-9 rounded-full text-white flex items-center justify-center shadow-md cursor-pointer"
                                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <div class="relative z-10 flex justify-end mt-1 mb-1">
                                    <button type="button"
                                        onclick="event.stopPropagation(); Livewire.dispatch('abrir-modal-login')"
                                        class="w-9 h-9 rounded-full text-white flex items-center justify-center shadow-md cursor-pointer"
                                        style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <div class="flex flex-col flex-1">
                                @if ($clienteLogado)
                                    <p class="text-sm font-bold text-gray-900">
                                        R$ {{ number_format($precos->preco($produto), 2, ',', '.') }}
                                    </p>

                                    @if ($precos->precoOriginal($produto) && $precos->precoOriginal($produto) > $precos->preco($produto))
                                        <p class="text-xs text-gray-400 line-through">
                                            R$ {{ number_format($precos->precoOriginal($produto), 2, ',', '.') }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-xs font-semibold"
                                        style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                        Entrar para ver o preço
                                    </p>
                                @endif

                                <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-tight">
                                    {{ $produto->name }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===== SEÇÕES POR CATEGORIA ===== --}}
        @forelse($categorias as $categoria)
            @if ($categoria->products->count() > 0)
                <div id="categoria-{{ $categoria->slug }}" class="bg-white rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-gray-800">{{ $categoria->name }}</h2>
                        <a href="{{ url($tenant->slug . '/categoria/' . $categoria->slug) }}"
                            class="text-sm font-semibold flex items-center gap-1"
                            style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                            Ver todos
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="flex flex-nowrap overflow-hidden">
                        @foreach ($categoria->products->take(6) as $produto)
                            @php
                                $imagens = is_array($produto->images) ? $produto->images : [];
                                $img = $imagens[0] ?? null;
                            @endphp

                            <div class="flex-shrink-0 basis-1/2 sm:basis-1/3 md:basis-1/4 lg:basis-1/5 xl:basis-1/6 p-3 cursor-pointer flex flex-col"
                                @if ($clienteLogado) onclick="window.location='{{ url($tenant->slug . '/produto/' . $produto->slug) }}'"
                                @else
                                    onclick="Livewire.dispatch('abrir-modal-login')" @endif>

                                <div class="relative">
                                    @if ($clienteLogado && $precos->desconto($produto) > 0)
                                        <span
                                            class="absolute top-0 left-0 text-xs font-bold text-white px-1.5 py-0.5 rounded-lg"
                                            style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                            -{{ $precos->desconto($produto) }}%
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
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                @if ($clienteLogado)
                                    <div class="relative z-10 flex justify-end mt-1 mb-1" x-data="{ show: false, timer: null }">
                                        <div x-show="show" x-transition
                                            class="flex items-center gap-1 rounded-full text-white shadow-md px-1 py-1"
                                            style="background-color: {{ $tenant->primary_color ?? '#00b050' }}"
                                            onclick="event.stopPropagation()">
                                            <button wire:click.stop="decrementarHome({{ $produto->id }})"
                                                x-on:click="{{ ($quantidades[$produto->id] ?? 1) <= 1 ? 'clearTimeout(timer); show = false;' : 'clearTimeout(timer); timer = setTimeout(() => show = false, 2000);' }}"
                                                class="w-7 h-7 flex items-center justify-center cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            <span
                                                class="text-sm font-bold w-5 text-center">{{ $quantidades[$produto->id] ?? 1 }}</span>
                                            <button wire:click.stop="incrementarHome({{ $produto->id }})"
                                                x-on:click="clearTimeout(timer); timer = setTimeout(() => show = false, 2000)"
                                                class="w-7 h-7 flex items-center justify-center cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                        <button x-show="!show"
                                            x-transition:enter="transition ease-out duration-300 delay-100"
                                            x-transition:enter-start="opacity-0 scale-75"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            wire:click.stop="{{ in_array($produto->id, $adicionados) ? 'incrementarHome(' . $produto->id . ')' : 'adicionarRapido(' . $produto->id . ')' }}"
                                            x-on:click="show = true; clearTimeout(timer); timer = setTimeout(() => show = false, 2000)"
                                            class="w-9 h-9 rounded-full text-white flex items-center justify-center shadow-md cursor-pointer"
                                            style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="relative z-10 flex justify-end mt-1 mb-1">
                                        <button type="button"
                                            onclick="event.stopPropagation(); Livewire.dispatch('abrir-modal-login')"
                                            class="w-9 h-9 rounded-full text-white flex items-center justify-center shadow-md cursor-pointer"
                                            style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                <div class="flex flex-col flex-1">
                                    @if ($clienteLogado)
                                        <p class="text-sm font-bold text-gray-900">
                                            R$ {{ number_format($precos->preco($produto), 2, ',', '.') }}
                                        </p>

                                        @if ($precos->precoOriginal($produto) && $precos->precoOriginal($produto) > $precos->preco($produto))
                                            <p class="text-xs text-gray-400 line-through">
                                                R$ {{ number_format($precos->precoOriginal($produto), 2, ',', '.') }}
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-xs font-semibold"
                                            style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                                            Entrar para ver o preço
                                        </p>
                                    @endif

                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-tight">
                                        {{ $produto->name }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
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
        {{-- MOBILE: barra flutuante, agora abre o drawer/tela cheia do carrinho --}}
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 px-4 pb-15">
            <button wire:click="$dispatch('abrirCarrinhoGlobal')"
                class="w-full max-w-2xl mx-auto rounded-2xl p-4 flex items-center justify-between shadow-2xl text-white cursor-pointer"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">

                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>

                    <div>
                        <p class="text-sm font-bold text-left">
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

                <span class="bg-white rounded-xl px-4 py-2 text-sm font-bold"
                    style="color: {{ $tenant->primary_color ?? '#00b050' }}">
                    Ver carrinho →
                </span>
            </button>
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
