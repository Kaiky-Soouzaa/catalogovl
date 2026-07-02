<div class="bg-gray-50 min-h-screen">
    <div class=" px-4 py-3 flex items-center gap-3 ">
        <a href="{{ url($tenant->slug . '/carrinho') }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-base font-bold text-gray-800">Opções de entrega</h1>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

        <button wire:click="selecionar('entrega')"
            class="w-full bg-white rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                <svg class="w-6 h-6" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-800">Entrega</p>
                <p class="text-xs text-gray-500">Receba em seu endereço</p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <button wire:click="selecionar('retirada')"
            class="w-full bg-white rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                <svg class="w-6 h-6" style="color: {{ $tenant->primary_color ?? '#00b050' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 2.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-800">Retirada</p>
                <p class="text-xs text-gray-500">Retire no estabelecimento</p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

    </div>
</div>
