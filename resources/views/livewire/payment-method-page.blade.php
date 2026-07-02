<div class="bg-gray-50 min-h-screen">
    <div class=" px-4 py-3 flex items-center gap-3">
        <a href="{{ url($tenant->slug . '/finalizar/pagamento') }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-base font-bold text-gray-800">Método de pagamento</h1>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

        <button wire:click="selecionar('pix')"
            class="w-full bg-white rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                <svg class="w-6 h-6" style="color: {{ $tenant->primary_color ?? '#00b050' }}" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M11.9 2.1L7.5 6.5H4.8c-.7 0-1.3.6-1.3 1.3v2.7L1 13l2.5 2.5v2.7c0 .7.6 1.3 1.3 1.3h2.7l4.4 4.4 4.4-4.4h2.7c.7 0 1.3-.6 1.3-1.3v-2.7L23 13l-2.5-2.5V7.8c0-.7-.6-1.3-1.3-1.3h-2.7L11.9 2.1zm0 2.8l3 3-3 3-3-3 3-3zm-6.4 4.4h2.1l2.2 2.2-2.2 2.2H5.5v-2.1l.7-.7-.7-.7V9.3zm12.8 0v2.1l-.7.7.7.7v2.1h-2.1l-2.2-2.2 2.2-2.2h2.1zm-7.5 3.5l3 3-3 3-3-3 3-3z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-800">PIX</p>
                <p class="text-xs text-gray-500">Pagamento instantâneo</p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <button wire:click="selecionar('dinheiro')"
            class="w-full bg-white rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                <svg class="w-6 h-6" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-800">Dinheiro</p>
                <p class="text-xs text-gray-500">Pagamento em espécie</p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <button wire:click="selecionar('cartao')"
            class="w-full bg-white rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                <svg class="w-6 h-6" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-800">Cartão</p>
                <p class="text-xs text-gray-500">Crédito ou débito</p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

    </div>
</div>
