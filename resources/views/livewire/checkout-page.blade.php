@php
    $primaryColor = $tenant->primary_color ?? '#00b050';
@endphp

<div class="bg-gray-50 min-h-screen pb-10">

    {{-- Barra topo --}}
    <div class="px-4 py-4 flex items-center gap-3" style="background-color: {{ $primaryColor }}">
        <a href="{{ url($tenant->slug) }}" class="text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white font-bold">Voltar para loja</h1>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-6">

        {{-- Breadcrumb de steps --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="flex-1">
                <div class="h-1 rounded-full mb-2"
                    style="background-color: {{ $step === 'agendamento' ? $primaryColor : '#e5e7eb' }}"></div>
                <span class="text-sm font-semibold flex items-center gap-1"
                    style="color: {{ $step === 'agendamento' ? $primaryColor : '#9ca3af' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Agendamento
                </span>
            </div>
            <div class="flex-1">
                <div class="h-1 rounded-full mb-2"
                    style="background-color: {{ $step === 'pagamento' ? $primaryColor : '#e5e7eb' }}"></div>
                <span class="text-sm font-semibold flex items-center gap-1"
                    style="color: {{ $step === 'pagamento' ? $primaryColor : '#9ca3af' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Pagamento
                </span>
            </div>
        </div>

        @if ($step === 'agendamento')
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

                {{-- Abas Receber em casa / Retirar na loja --}}
                <div class="flex border-b border-gray-100">
                    <button wire:click="selecionarTipoEntrega('entrega')"
                        class="flex-1 py-4 text-sm font-semibold border-b-2 transition-colors duration-200 cursor-pointer"
                        style="{{ $tipoEntrega === 'entrega' ? 'color: ' . $primaryColor . '; border-color: ' . $primaryColor . ';' : 'color: #9ca3af; border-color: transparent;' }}">
                        Receber em casa
                    </button>
                    <button wire:click="selecionarTipoEntrega('retirada')"
                        class="flex-1 py-4 text-sm font-semibold border-b-2 transition-colors duration-200 cursor-pointer"
                        style="{{ $tipoEntrega === 'retirada' ? 'color: ' . $primaryColor . '; border-color: ' . $primaryColor . ';' : 'color: #9ca3af; border-color: transparent;' }}">
                        Retirar na loja
                    </button>
                </div>

                @if ($tipoEntrega === 'entrega')
                    {{-- Campos de endereço --}}
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Rua</label>
                            <input type="text" wire:model="rua"
                                class="w-full mt-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none"
                                style="focus:border-color: {{ $primaryColor }}">
                            @error('rua')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-500">Número</label>
                                <input type="text" wire:model="numero"
                                    class="w-full mt-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none">
                                @error('numero')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500">Bairro</label>
                                <input type="text" wire:model="bairro"
                                    class="w-full mt-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none">
                                @error('bairro')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Cidade</label>
                            <input type="text" wire:model="cidade"
                                class="w-full mt-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none">
                            @error('cidade')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500">Referência (opcional)</label>
                            <input type="text" wire:model="referencia"
                                class="w-full mt-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none">
                        </div>
                    </div>
                @else
                    {{-- Retirada na loja --}}
                    <div class="p-5">
                        <p class="text-xs font-semibold text-gray-500 mb-2">Retirar em:</p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-700">
                                {{ $tenant->address ?? 'Endereço da loja não configurado' }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <button wire:click="irParaPagamento"
                class="w-full mt-6 py-3 rounded-2xl text-sm font-bold text-white cursor-pointer"
                style="background-color: {{ $primaryColor }}">
                Continuar →
            </button>
        @endif

        @if ($step === 'pagamento')
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden p-5 space-y-5">

                {{-- Dados do cliente --}}
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Nome</label>
                        <input type="text" wire:model="nome"
                            class="w-full mt-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none">
                        @error('nome')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Telefone</label>
                        <input type="text" wire:model="telefone"
                            class="w-full mt-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none">
                        @error('telefone')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Forma de pagamento</p>
                    <div class="space-y-2">
                        @foreach (['pix' => 'Pix', 'cartao' => 'Cartão', 'dinheiro' => 'Dinheiro'] as $key => $label)
                            <button type="button" wire:click="selecionarMetodoPagamento('{{ $key }}')"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl border text-sm font-medium transition-colors duration-200 cursor-pointer"
                                style="{{ $metodoPagamento === $key ? 'border-color: ' . $primaryColor . '; background-color: ' . $primaryColor . '10;' : 'border-color: #e5e7eb;' }}">
                                {{ $label }}
                                @if ($metodoPagamento === $key)
                                    <svg class="w-5 h-5" fill="none" stroke="{{ $primaryColor }}"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    @error('metodoPagamento')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button wire:click="voltarParaAgendamento"
                    class="px-5 py-3 rounded-2xl text-sm font-bold border border-gray-200 text-gray-600 cursor-pointer">
                    ← Voltar
                </button>
                <button wire:click="confirmar"
                    class="flex-1 py-3 rounded-2xl text-sm font-bold text-white cursor-pointer"
                    style="background-color: {{ $primaryColor }}">
                    Confirmar pedido →
                </button>
            </div>
        @endif

    </div>
</div>
