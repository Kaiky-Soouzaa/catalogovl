<div>
    @if ($open)
        <div wire:click.self="fechar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6 sm:p-8">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-xl relative max-h-[90vh] overflow-y-auto">

                <button wire:click="fechar"
                    class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700 cursor-pointer z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex justify-center pt-8 pb-2">
                    <img src="{{ $logoUrl }}" alt="Logo" class="h-10 w-auto object-contain">
                </div>

                <div class="px-8 pb-10">
                    {{-- STEP 1: identificador --}}
                    @if ($step === 'check')
                        <div class="text-center mb-8">
                            <h2 class="text-lg font-bold">Informe seu e-mail ou CPF</h2>
                            <p class="text-sm text-gray-500 mt-2">Entre para ver preços e fazer seu pedido</p>
                        </div>

                        <form wire:submit="verificarIdentificador">
                            <input type="text" wire:model="identificador"
                                placeholder="Digite seu e-mail, CPF ou CNPJ"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none mb-3">
                            @error('identificador')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror

                            <button type="submit"
                                class="w-full py-3 rounded-lg text-sm font-bold text-white cursor-pointer mt-6"
                                style="background-color: {{ $primaryColor }}">
                                Continuar
                            </button>
                        </form>
                    @endif

                    {{-- STEP 2: login --}}
                    @if ($step === 'login')
                        <div class="text-center mb-8">
                            <h2 class="text-lg font-bold">Bem-vindo de volta</h2>
                            <p class="text-sm text-gray-500 mt-2">{{ $identificador }}</p>
                        </div>

                        <form wire:submit="login">
                            <div x-data="{ show: false }" class="relative mb-3">
                                <input :type="show ? 'text' : 'password'" wire:model="senha"
                                    placeholder="Digite sua senha"
                                    class="w-full px-4 py-3 pr-11 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.066 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            @error('senha')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror

                            <button type="submit"
                                class="w-full py-3 rounded-lg text-sm font-bold text-white cursor-pointer mt-6"
                                style="background-color: {{ $primaryColor }}">
                                Entrar
                            </button>
                            <button type="button" wire:click="voltarParaCheck"
                                class="w-full text-center text-sm text-gray-500 mt-5 cursor-pointer">
                                Voltar
                            </button>
                        </form>
                    @endif

                    {{-- STEP 3: cadastro - credenciais --}}
                    @if ($step === 'register-credentials')
                        <div class="text-center mb-8">
                            <h2 class="text-lg font-bold">Cadastre-se</h2>
                            <p class="text-sm text-gray-500 mt-2">Informe um e-mail e uma senha</p>
                        </div>

                        <form wire:submit="avancarCredenciais" class="space-y-5">
                            <div>
                                <label class="text-xs font-semibold text-gray-500">E-mail</label>
                                <input type="email" wire:model="email"
                                    class="w-full mt-1.5 px-4 py-3 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                @error('email')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500">Senha</label>
                                <div x-data="{ show: false }" class="relative mt-1.5">
                                    <input :type="show ? 'text' : 'password'" wire:model="novaSenha"
                                        class="w-full px-4 py-3 pr-11 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                    <button type="button" @click="show = !show"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.066 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">Mínimo de 6 caracteres</p>
                                @error('novaSenha')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500">Confirme a senha</label>
                                <div x-data="{ show: false }" class="relative mt-1.5">
                                    <input :type="show ? 'text' : 'password'" wire:model="confirmarSenha"
                                        class="w-full px-4 py-3 pr-11 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                    <button type="button" @click="show = !show"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.066 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-3 rounded-lg text-sm font-bold text-white cursor-pointer mt-4"
                                style="background-color: {{ $primaryColor }}">
                                Avançar
                            </button>
                            <p class="text-center text-sm text-gray-500 mt-5">
                                Já tem uma conta?
                                <button type="button" wire:click="voltarParaCheck" class="font-bold cursor-pointer"
                                    style="color: {{ $primaryColor }}">
                                    Fazer login
                                </button>
                            </p>
                        </form>
                    @endif

                    {{-- STEP 4: cadastro - dados pessoais (mais compacto, tem mais campos) --}}
                    @if ($step === 'register-details')
                        <h2 class="text-lg font-bold text-center mb-5">Cadastre-se</h2>

                        <div class="flex border-b border-gray-100 mb-5">
                            <button type="button" wire:click="$set('tipo', 'pf')"
                                class="flex-1 py-2.5 text-sm font-semibold border-b-2 transition-colors duration-200 cursor-pointer"
                                style="{{ $tipo === 'pf' ? 'color: ' . $primaryColor . '; border-color: ' . $primaryColor . ';' : 'color: #9ca3af; border-color: transparent;' }}">
                                Pessoa Física
                            </button>
                            <button type="button" wire:click="$set('tipo', 'pj')"
                                class="flex-1 py-2.5 text-sm font-semibold border-b-2 transition-colors duration-200 cursor-pointer"
                                style="{{ $tipo === 'pj' ? 'color: ' . $primaryColor . '; border-color: ' . $primaryColor . ';' : 'color: #9ca3af; border-color: transparent;' }}">
                                Pessoa Jurídica
                            </button>
                        </div>

                        <form wire:submit="finalizarCadastro" class="space-y-3.5">
                            <div>
                                <label
                                    class="text-xs font-semibold text-gray-500">{{ $tipo === 'pf' ? 'Nome completo' : 'Razão Social' }}</label>
                                <input type="text" wire:model="nome"
                                    class="w-full mt-1 px-4 py-2.5 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                @error('nome')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="text-xs font-semibold text-gray-500">{{ $tipo === 'pf' ? 'CPF' : 'CNPJ' }}</label>
                                <input type="text" wire:model="cpfCnpj"
                                    class="w-full mt-1 px-4 py-2.5 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                @error('cpfCnpj')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500">Telefone</label>
                                <input type="text" wire:model="telefone"
                                    class="w-full mt-1 px-4 py-2.5 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                            </div>

                            @if ($tipo === 'pf')
                                <div>
                                    <label class="text-xs font-semibold text-gray-500">Data de nascimento</label>
                                    <input type="date" wire:model="dataNascimento"
                                        class="w-full mt-1 px-4 py-2.5 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500">Sexo</label>
                                    <select wire:model="sexo"
                                        class="w-full mt-1 px-4 py-2.5 border border-gray-200 rounded-xl text-base sm:text-sm focus:outline-none">
                                        <option value="">Selecione</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="feminino">Feminino</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                            @endif

                            <label class="flex items-start gap-2.5 text-sm cursor-pointer">
                                <input type="checkbox" wire:model="aceitouTermos" class="mt-1">
                                <span>Aceito os termos de uso e a política de privacidade</span>
                            </label>
                            @error('aceitouTermos')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror

                            <button type="submit"
                                class="w-full py-3 rounded-lg text-sm font-bold text-white cursor-pointer mt-2"
                                style="background-color: {{ $primaryColor }}">
                                Criar conta
                            </button>
                            <p class="text-center text-sm text-gray-500 mt-3">
                                Já tem uma conta?
                                <button type="button" wire:click="voltarParaCheck" class="font-bold cursor-pointer"
                                    style="color: {{ $primaryColor }}">
                                    Fazer login
                                </button>
                            </p>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
