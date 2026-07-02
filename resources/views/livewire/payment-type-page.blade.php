<div class="bg-gray-50 min-h-screen">
    <div class=" px-4 py-3 flex items-center gap-3 ">
        <a href="{{ url($tenant->slug . '/finalizar') }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-base font-bold text-gray-800">Opções de pagamento</h1>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

        <button wire:click="selecionar('na_retirada')"
            class="w-full bg-white rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                <svg class="w-6 h-6" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-800">
                    Pagar na {{ $tipoEntrega === 'entrega' ? 'entrega' : 'retirada' }}
                </p>
                <p class="text-xs text-gray-500">
                    Pague no momento da {{ $tipoEntrega === 'entrega' ? 'entrega' : 'retirada' }}
                </p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

    </div>
</div>
