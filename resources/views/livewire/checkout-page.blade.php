<div class="bg-gray-50 min-h-screen pb-10">

    {{-- ===== ETAPA: ENTREGA ===== --}}
    @if ($etapa === 'entrega')
        <div class=" px-4 py-3 flex items-center gap-3 ">
            <a href="{{ url($tenant->id . '/carrinho') }}"
                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-base font-bold text-gray-800">Opções de entrega</h1>
        </div>

        <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

            {{-- Entrega --}}
            <button wire:click="selecionarEntrega('entrega')"
                class="w-full bg-white rounded-2xl p-10 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
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

            {{-- Retirada --}}
            <button wire:click="selecionarEntrega('retirada')"
                class="w-full bg-white rounded-2xl p-10 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
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
    @endif

    {{-- ===== ETAPA: PAGAMENTO ===== --}}
    @if ($etapa === 'pagamento')
        <div class="px-4 py-3 flex items-center gap-3 ">
            <button wire:click="voltar"
                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-base font-bold text-gray-800">Opções de pagamento</h1>
        </div>

        <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

            {{-- Pagar na retirada/entrega --}}
            <button wire:click="selecionarTipoPagamento('na_retirada')"
                class="w-full bg-white rounded-2xl p-10 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
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
    @endif

    {{-- ===== ETAPA: MÉTODO DE PAGAMENTO ===== --}}
    @if ($etapa === 'metodo_pagamento')
        <div class="px-4 py-3 flex items-center gap-3">
            <button wire:click="voltar"
                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-base font-bold text-gray-800">Método de pagamento</h1>
        </div>

        <div class="max-w-2xl mx-auto px-4 py-4 space-y-3">

            {{-- PIX --}}
            <button wire:click="selecionarMetodoPagamento('pix')"
                class="w-full bg-white rounded-2xl p-10 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                    style="background-color: {{ $tenant->primary_color ?? '#00b050' }}20">
                    <svg class="w-6 h-6" style="color: {{ $tenant->primary_color ?? '#00b050' }}"
                        viewBox="0 0 24 24" fill="currentColor">
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

            {{-- Dinheiro --}}
            <button wire:click="selecionarMetodoPagamento('dinheiro')"
                class="w-full bg-white rounded-2xl p-10 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
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

            {{-- Cartão --}}
            <button wire:click="selecionarMetodoPagamento('cartao')"
                class="w-full bg-white rounded-2xl p-10 flex items-center gap-4 shadow-sm border border-gray-100 hover:border-gray-300 transition-all duration-200 text-left">
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
    @endif

    {{-- ===== ETAPA: DADOS DO CLIENTE ===== --}}
    @if ($etapa === 'dados')
        <div class=" px-4 py-3 flex items-center gap-3 ">
            <button wire:click="voltar"
                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-base font-bold text-gray-800">Insira seus dados</h1>
        </div>

        <div class="max-w-lg mx-auto px-4 py-4 space-y-4">

            {{-- Nome --}}
            <div>
                <label class="text-sm font-semibold text-gray-700 mb-1 block">
                    Nome <span class="text-red-500">*</span>
                </label>
                <input wire:model="nome" type="text" placeholder="Insira seu nome"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                    onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                    onblur="this.style.borderColor='rgb(229 231 235)'">
                @error('nome')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Telefone --}}
            <div>
                <label class="text-sm font-semibold text-gray-700 mb-1 block">
                    Telefone <span class="text-red-500">*</span>
                </label>
                <input wire:model="telefone" type="tel" placeholder="(00) 00000-0000"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                    onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                    onblur="this.style.borderColor='rgb(229 231 235)'">
                @error('telefone')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- E-mail --}}
            <div>
                <label class="text-sm font-semibold text-gray-700 mb-1 block">E-mail</label>
                <input wire:model="email" type="email" placeholder="Insira seu e-mail"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                    onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                    onblur="this.style.borderColor='rgb(229 231 235)'">
            </div>

            {{-- CPF --}}
            <div>
                <label class="text-sm font-semibold text-gray-700 mb-1 block">CPF</label>
                <input wire:model="cpf" type="text" placeholder="Insira seu CPF"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                    onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                    onblur="this.style.borderColor='rgb(229 231 235)'">
            </div>

            {{-- Endereço (só se entrega) --}}
            @if ($tipoEntrega === 'entrega')
                <div class="pt-2">
                    <p class="text-sm font-bold text-gray-700 mb-3">Endereço de entrega</p>

                    <div class="space-y-3">
                        <input wire:model="rua" type="text" placeholder="Rua *"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                            onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                            onblur="this.style.borderColor='rgb(229 231 235)'">
                        @error('rua')
                            <p class="text-xs text-red-500 -mt-2">{{ $message }}</p>
                        @enderror

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input wire:model="numero" type="text" placeholder="Número *"
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                                    onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                                    onblur="this.style.borderColor='rgb(229 231 235)'">
                                @error('numero')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input wire:model="bairro" type="text" placeholder="Bairro *"
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                                    onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                                    onblur="this.style.borderColor='rgb(229 231 235)'">
                                @error('bairro')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <input wire:model="cidade" type="text" placeholder="Cidade *"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                            onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                            onblur="this.style.borderColor='rgb(229 231 235)'">
                        @error('cidade')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror

                        <input wire:model="referencia" type="text" placeholder="Ponto de referência"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                            onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                            onblur="this.style.borderColor='rgb(229 231 235)'">
                    </div>
                </div>
            @endif

            {{-- Resumo --}}
            <div class="bg-white rounded-2xl p-4 border border-gray-100 space-y-2">
                <p class="text-xs font-bold text-gray-500 uppercase">Resumo do pedido</p>
                @foreach ($cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $item->quantity }}x {{ $item->product->name }}</span>
                        <span class="font-medium">R$
                            {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="border-t border-gray-100 pt-2 flex justify-between font-bold">
                    <span>Total</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
                <div class="text-xs text-gray-500 space-y-0.5 pt-1">
                    <p>Entrega: {{ $tipoEntrega === 'entrega' ? 'Em domicílio' : 'Retirada no local' }}</p>
                    <p>Pagamento: {{ ucfirst($metodoPagamento) }}</p>
                </div>
            </div>

            {{-- Botão confirmar --}}
            <button wire:click="confirmar"
                class="w-full py-4 rounded-2xl text-sm font-bold text-white transition-all duration-200"
                style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
                Confirmar
            </button>

        </div>
    @endif

</div>
