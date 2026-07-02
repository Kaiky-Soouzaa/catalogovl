<div class="bg-gray-50 min-h-screen pb-10">

    <div class="px-4 py-3 flex items-center gap-3 ">
        <a href="{{ url($tenant->slug . '/finalizar/metodo') }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-base font-bold text-gray-800">Insira seus dados</h1>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-4 space-y-4">

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

        {{-- Endereço se entrega --}}
        @if ($tipoEntrega === 'entrega')
            <div class="pt-2">
                <p class="text-sm font-bold text-gray-700 mb-3">Endereço de entrega</p>
                <div class="space-y-3">
                    <div>
                        <input wire:model="rua" type="text" placeholder="Rua *"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                            onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                            onblur="this.style.borderColor='rgb(229 231 235)'">
                        @error('rua')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
                    <div>
                        <input wire:model="cidade" type="text" placeholder="Cidade *"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200"
                            onfocus="this.style.borderColor='{{ $tenant->primary_color ?? '#00b050' }}'"
                            onblur="this.style.borderColor='rgb(229 231 235)'">
                        @error('cidade')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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

        <button wire:click="confirmar"
            class="w-full py-4 rounded-2xl text-sm font-bold text-white transition-all duration-200"
            style="background-color: {{ $tenant->primary_color ?? '#00b050' }}">
            Confirmar
        </button>

    </div>
</div>
