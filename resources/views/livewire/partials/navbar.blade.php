@php
    $primaryColor = app('tenant')?->primary_color ?? '#d97706';
    $categorias = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
@endphp

<header class="flex z-50 sticky top-0 flex-col w-full bg-white shadow-md">

    {{-- ===== LINHA 1: Logo + Hamburguer (mobile) + Pedido ===== --}}
    <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8 py-3 flex items-center justify-between">

        {{-- Logo --}}
        <a href="#" aria-label="Brand">
            <img src="{{ app('tenant')->logo_url ? Storage::url(app('tenant')->logo_url) : asset('assets/images/vl-sistemas.png') }}"
                alt="Logo" class="h-12 w-auto object-contain">
        </a>

        <div class="flex items-center gap-3">

            {{-- Pedido --}}
            <a class="font-medium flex items-center text-gray-500 transition-colors duration-200" href="/cart"
                onmouseover="this.style.color='{{ $primaryColor }}';" onmouseout="this.style.color='';">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="mr-1">Pedido</span>
                <span class="py-0.5 px-1.5 rounded-full text-xs font-medium text-white"
                    style="background-color: {{ $primaryColor }};">4</span>
            </a>

            {{-- Botão hamburguer — só no mobile --}}
            <button id="menu-toggle" onclick="toggleMenu()"
                class="md:hidden flex justify-center items-center w-9 h-9 rounded-lg border transition-colors duration-200"
                style="border-color: {{ $primaryColor }}; color: {{ $primaryColor }};"
                onmouseover="this.style.backgroundColor='{{ $primaryColor }}'; this.style.color='#fff';"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='{{ $primaryColor }}';"
                aria-label="Menu">
                {{-- Ícone hamburguer --}}
                <svg id="icon-open" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="3" x2="21" y1="6" y2="6" />
                    <line x1="3" x2="21" y1="12" y2="12" />
                    <line x1="3" x2="21" y1="18" y2="18" />
                </svg>
                {{-- Ícone X --}}
                <svg id="icon-close" class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>

        </div>
    </nav>

    {{-- ===== LINHA 2: Categorias — Desktop (scroll horizontal) ===== --}}
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

    {{-- ===== MENU MOBILE: Categorias em lista vertical ===== --}}
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
