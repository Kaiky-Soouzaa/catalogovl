@php
    $primaryColor = $tenant?->primary_color ?? '#d97706';
    $tenantId = $tenant?->id;
    $logoUrl = $tenant?->logo_url ? Storage::url($tenant->logo_url) : asset('assets/images/vl-sistemas.png');
@endphp

<header class="flex z-50 sticky top-0 flex-col w-full bg-white shadow-md">

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
                class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-xl text-sm text-gray-600 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-200"
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
                            <a href="{{ url($tenant->slug . '/produto/' . $produto->slug) }}" wire:click="fecharBusca"
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
                                    <p class="text-sm font-medium text-gray-800 truncate uppercase">{{ $produto->name }}
                                    </p>
                                    {{-- <p class="text-xs text-gray-400">{{ $produto->category->name ?? '' }}</p> --}}
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-bold" style="color: {{ $primaryColor }}">
                                        R$ {{ number_format($produto->price, 2, ',', '.') }}
                                    </p>
                                    @if ($produto->original_price)
                                        <p class="text-xs text-gray-400 line-through">
                                            R$ {{ number_format($produto->original_price, 2, ',', '.') }}
                                        </p>
                                    @endif
                                </div>

                            </a>
                        @endforeach

                        {{-- <a href="{{ url($tenantId . '/buscar') }}?q={{ urlencode($busca) }}" wire:click="fecharBusca"
                            class="flex items-center justify-center gap-1 px-4 py-3 text-sm font-semibold transition-colors"
                            style="color: {{ $primaryColor }}">
                            Ver todos os resultados para "{{ $busca }}"
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a> --}}
                    @endif

                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">

            {{-- Pedido --}}
            <a class="font-medium flex items-center text-gray-500 transition-colors duration-200"
                href="{{ url($tenant->slug . '/pedidos') }}" onmouseover="this.style.color='{{ $primaryColor }}';"
                onmouseout="this.style.color='';">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="mr-1 hidden sm:inline">Pedido</span>
            </a>

            {{-- Hamburguer mobile --}}
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
                <svg id="icon-close" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>

        </div>
    </nav>

    {{-- ===== LINHA 2: Categorias Desktop ===== --}}
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

    {{-- ===== MENU MOBILE ===== --}}
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

</header>

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
