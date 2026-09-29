@php
    $primaryColor = $tenant?->primary_color ?? '#d97706';
    $tenantId = $tenant?->id;
    $logoUrl = $tenant?->logo_url ? Storage::url($tenant->logo_url) : asset('assets/images/vl-sistemas.jpeg');
@endphp

<div>

    <header class="flex z-50 sticky top-0 flex-col w-full bg-white shadow-sm">

        {{-- ===== LINHA 1: Logo + Busca + Pedido + Hamburguer ===== --}}
        <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ url($tenant->slug) }}" aria-label="Brand" class="flex-shrink-0">
                <img src="{{ $logoUrl }}" alt="Logo" class="h-12 w-auto object-contain">
            </a>

            {{-- ===== BUSCA ===== --}}
            <div class="flex-1 max-w-[40rem] relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="busca" type="text" placeholder="Buscar produtos..."
                    class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-xl text-base sm:text-sm text-gray-600 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-200"
                    style="border: 2px solid transparent;"
                    onfocus="this.style.borderColor='{{ $primaryColor }}'; this.style.backgroundColor='white';"
                    onblur="this.style.borderColor='transparent'; this.style.backgroundColor='rgb(243 244 246)';"
                    onkeydown="if(event.key==='Enter' && this.value.trim()) window.location='{{ url($tenantId . '/buscar') }}?q='+encodeURIComponent(this.value.trim())">

                {{-- Lista suspensa de resultados --}}
                @if ($mostrarResultados)
                    <div
                        class="absolute top-full left-0 right-0 mt-1 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">

                        @if ($resultados->isEmpty())
                            <div class="px-2 py-2 text-center text-sm text-gray-400">
                                Nenhum produto encontrado
                            </div>
                        @else
                            @foreach ($resultados as $produto)
                                @php
                                    $imagens = is_array($produto->images) ? $produto->images : [];
                                    $img = $imagens[0] ?? null;
                                @endphp
                                <a href="{{ url($tenant->slug . '/produto/' . $produto->slug) }}"
                                    wire:click="fecharBusca"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-50 last:border-0">

                                    @if ($img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $produto->name }}"
                                            class="w-12 h-12 object-contain flex-shrink-0 rounded-lg bg-gray-50">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate uppercase">
                                            {{ $produto->name }}
                                        </p>
                                        {{-- <p class="text-xs text-gray-400">{{ $produto->category->name ?? '' }}</p> --}}
                                    </div>

                                    @if ($clienteLogado)
                                        @php
                                            $precoFinal = $precos->preco($produto);
                                            $precoOriginal = $precos->precoOriginal($produto);
                                        @endphp
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-sm font-bold" style="color: {{ $primaryColor }}">
                                                R$ {{ number_format($precoFinal, 2, ',', '.') }}
                                            </p>
                                            @if ($precoOriginal && $precoOriginal > $precoFinal)
                                                <p class="text-xs text-gray-400 line-through">
                                                    R$ {{ number_format($precoOriginal, 2, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                </a>
                            @endforeach
                        @endif

                    </div>
                @endif
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">

                {{-- Badge do carrinho (só desktop) --}}
                @if ($totalItens > 0)
                    <button wire:click="abrirCarrinho"
                        class="hidden md:flex relative items-center gap-2 rounded-full px-4 py-2.5 text-white font-bold text-sm transition-all duration-200 cursor-pointer hover:scale-105 hover:brightness-90 hover:shadow-lg"
                        style="background-color: {{ $primaryColor }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>R$ {{ number_format($totalValor, 2, ',', '.') }}</span>

                        <span
                            class="absolute -top-2 -right-2 flex items-center justify-center w-5 h-5 rounded-full bg-white text-xs font-bold shadow"
                            style="color: {{ $primaryColor }}">
                            {{ $totalItens }}
                        </span>
                    </button>
                @endif

                {{-- Login / conta do cliente --}}
                <div class="flex items-center relative" x-data="{ menu: false }" @click.outside="menu = false">
                    @if ($clienteLogado)
                        <button type="button" @click="menu = !menu"
                            class="flex items-center gap-1.5 border border-gray-300 rounded-full px-3 sm:px-4 py-2 text-sm font-bold text-gray-700 cursor-pointer">
                            <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span class="hidden sm:inline">Olá, {{ Auth::guard('cliente')->user()->nome }}</span>
                        </button>

                        <div x-show="menu" x-transition style="display: none;"
                            class="absolute right-0 top-full mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">
                            <a href="{{ url($tenant->slug . '/pedidos') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Meus pedidos
                            </a>
                            <button type="button" wire:click="sair"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 cursor-pointer">
                                Sair
                            </button>
                        </div>
                    @else
                        <button type="button" wire:click="$dispatch('abrir-modal-login')"
                            class="flex items-center gap-1.5 border border-gray-300 rounded-full px-3 sm:px-4 py-2 text-sm font-bold text-gray-600 cursor-pointer transition-colors duration-200"
                            onmouseover="this.style.borderColor='{{ $primaryColor }}'; this.style.color='{{ $primaryColor }}';"
                            onmouseout="this.style.borderColor=''; this.style.color='';">
                            <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <span class="hidden sm:inline">Olá, Entrar</span>
                        </button>
                    @endif
                </div>

                {{-- Pedido --}}
                {{-- <a class="font-medium flex items-center text-gray-500 transition-colors duration-200"
                    href="{{ url($tenant->slug . '/pedidos') }}" onmouseover="this.style.color='{{ $primaryColor }}';"
                    onmouseout="this.style.color='';">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span class="mr-1 hidden sm:inline">Pedido</span>
                </a> --}}

                {{-- Hamburguer mobile --}}
                @if ($mostrarCategorias)
                    <button id="menu-toggle" onclick="toggleMenu()"
                        class="md:hidden flex justify-center items-center w-9 h-9 rounded-lg border transition-colors duration-200"
                        style="border-color: {{ $primaryColor }}; color: {{ $primaryColor }};"
                        onmouseover="this.style.backgroundColor='{{ $primaryColor }}'; this.style.color='#fff';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='{{ $primaryColor }}';"
                        aria-label="Menu">
                        <svg id="icon-open" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <line x1="3" x2="21" y1="6" y2="6" />
                            <line x1="3" x2="21" y1="12" y2="12" />
                            <line x1="3" x2="21" y1="18" y2="18" />
                        </svg>
                        <svg id="icon-close" class="w-4 h-4 hidden" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </nav>


        {{-- ===== LINHA 2: Categorias Desktop ===== --}}


        @if ($mostrarCategorias)

            <div class="hidden md:block border-t border-gray-100 w-full">
                <div class="max-w-[85rem] mx-auto px-4 md:px-6 lg:px-8 overflow-x-auto">
                    <div class="flex whitespace-nowrap">
                        @foreach ($categorias as $categoria)
                            <a href="#categoria-{{ $categoria->slug }}"
                                onclick="scrollToCategoria('{{ $categoria->slug }}'); return false;"
                                onmouseover="this.style.color='{{ $primaryColor }}'; this.style.borderBottomColor='{{ $primaryColor }}';"
                                onmouseout="this.style.color=''; this.style.borderBottomColor='transparent';"
                                class="px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 transition-all duration-200 cursor-pointer">
                                {{ $categoria->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif




        {{-- ===== MENU MOBILE ===== --}}
        @if ($mostrarCategorias)
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-100 w-full bg-white">
                <div class="max-w-[85rem] mx-auto px-4 py-2 flex flex-col">
                    @foreach ($categorias as $categoria)
                        <a href="#categoria-{{ $categoria->slug }}"
                            onclick="scrollToCategoria('{{ $categoria->slug }}'); fecharMenu(); return false;"
                            class="py-3 text-sm font-medium border-b border-gray-100 text-gray-600 transition-colors duration-200 last:border-0"
                            onmouseover="this.style.color='{{ $primaryColor }}';" onmouseout="this.style.color='';">
                            {{ $categoria->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </header>

    {{-- ===== Overlay + Drawer do carrinho (responsivo: tela cheia no mobile, painel lateral no desktop) ===== --}}
    @if ($carrinhoAberto)
        <div wire:click="fecharCarrinho" class="hidden md:block fixed inset-0 bg-black/50 z-[60]"></div>
    @endif

    <div
        class="fixed inset-0 w-full h-full md:inset-auto md:top-0 md:right-0 md:h-full md:w-96 bg-white z-[70] shadow-2xl flex flex-col transition-transform duration-300
    {{ $carrinhoAberto ? 'translate-y-0 md:translate-x-0' : 'translate-y-full md:translate-y-0 md:translate-x-full' }}">

        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <button wire:click="fecharCarrinho" class="md:hidden text-gray-500 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h2 class="text-lg font-bold">Carrinho</h2>
            <span class="text-sm text-gray-400">{{ $totalItens }}
                {{ $totalItens == 1 ? 'produto' : 'produtos' }}</span>
            <button wire:click="fecharCarrinho"
                class="hidden md:block text-2xl leading-none text-gray-400 hover:text-gray-600 cursor-pointer">&times;</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse ($cartItensDetalhe as $item)
                <div class="bg-white rounded-2xl p-4 flex items-center gap-4 shadow-sm border border-gray-100">
                    @if ($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                            class="w-16 h-16 object-contain flex-shrink-0">
                    @else
                        <div class="w-16 h-16 bg-gray-100 rounded-xl flex-shrink-0"></div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 uppercase leading-snug truncate">{{ $item['name'] }}
                        </p>
                        @if ($item['category'])
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item['category'] }}</p>
                        @endif
                        <p class="text-base font-bold mt-1" style="color: {{ $primaryColor }}">
                            R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                        </p>
                        @if ($item['quantity'] > 1)
                            <p class="text-xs text-gray-400">R$ {{ number_format($item['price'], 2, ',', '.') }} cada
                            </p>
                        @endif
                        @if ($item['note'])
                            <p class="text-xs text-gray-400 mt-1 italic">{{ $item['note'] }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                        <button wire:click="removerItem({{ $item['product_id'] }})"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all duration-200 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        <div class="flex items-center gap-1 bg-gray-100 rounded-xl px-1 py-1">
                            <button wire:click="decrementarItem({{ $item['product_id'] }})"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 hover:bg-white hover:shadow-sm hover:text-gray-900 transition-all duration-200 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 12H4" />
                                </svg>
                            </button>
                            <span
                                class="text-sm font-bold text-gray-800 w-6 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementarItem({{ $item['product_id'] }})"
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
                    <a href="{{ url($tenant->slug) }}" wire:click="fecharCarrinho"
                        class="text-xs font-semibold mt-2 inline-block" style="color: {{ $primaryColor }}">
                        Ver produtos →
                    </a>
                </div>
            @endforelse

            @if (count($cartItensDetalhe) > 0)
                <a href="{{ url($tenant->slug) }}" wire:click="fecharCarrinho"
                    class="w-full py-3 rounded-2xl text-sm font-semibold border-2 flex items-center justify-center gap-2 transition-all duration-200 hover:bg-gray-50"
                    style="color: {{ $primaryColor }}; border-color: {{ $primaryColor }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar mais itens
                </a>
            @endif
        </div>


        <div class="border-t border-gray-200 p-4">
            <div class="flex justify-between mb-3">
                <span class="font-semibold">Total</span>
                <span class="font-bold">R$ {{ number_format($totalValor, 2, ',', '.') }}</span>
            </div>
            <a href="{{ url($tenant->slug . '/finalizar') }}"
                class="block text-center rounded-xl px-4 py-3 font-bold text-white"
                style="background-color: {{ $primaryColor }}">
                Finalizar pedido →
            </a>
        </div>
    </div>

    <livewire:auth-modal :tenant-id="$tenant->id" :primary-color="$primaryColor" :logo-url="$logoUrl" />

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('icon-open');
            const iconClose = document.getElementById('icon-close');
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        }

        function fecharMenu() {
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('icon-open');
            const iconClose = document.getElementById('icon-close');
            menu.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }

        function scrollToCategoria(slug) {
            setTimeout(() => {
                const el = document.getElementById('categoria-' + slug);
                if (!el) return;
                const navbarHeight = document.querySelector('header').offsetHeight;
                const y = el.getBoundingClientRect().top + window.scrollY - navbarHeight - 16;
                window.scrollTo({
                    top: y,
                    behavior: 'smooth'
                });
            }, 50);
        }
    </script>

</div>
