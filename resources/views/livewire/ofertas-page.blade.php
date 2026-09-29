@php
    $primaryColor = $tenant->primary_color ?? '#00b050';

@endphp

<div class="bg-gray-50 min-h-screen pb-24">

    {{-- Topo com voltar --}}
    {{-- <div class="bg-white border-b border-gray-100 px-4 py-3 flex items-center gap-3">
        <a href="{{ url($tenant->slug) }}" class="text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-base font-bold text-gray-800">Ofertas</h1>
    </div> --}}

    {{-- Breadcrumb --}}
    <div class="px-4 py-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="{{ url($tenant->slug) }}" class="hover:text-gray-600">Início</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="font-semibold" style="color: {{ $primaryColor }}">Ofertas</span>
            </div>
        </div>
    </div>

    {{-- Grid de produtos --}}
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm">
            @if ($produtos->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="text-sm">Nenhum produto em oferta no momento.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($produtos as $produto)
                        @php
                            $imagens = is_array($produto->images) ? $produto->images : [];
                            $img = $imagens[0] ?? null;
                        @endphp

                        <div class="cursor-pointer"
                            @if ($clienteLogado) onclick="window.location='{{ url($tenant->slug . '/produto/' . $produto->slug) }}'"
                            @else
                                onclick="Livewire.dispatch('abrir-modal-login')" @endif>

                            <div class="relative">
                                @if ($clienteLogado && $produto->discount_percentage > 0)
                                    <span
                                        class="absolute top-0 left-0 text-xs font-bold text-white px-1.5 py-0.5 rounded-lg"
                                        style="background-color: {{ $primaryColor }}">
                                        -{{ $produto->discount_percentage }}%
                                    </span>
                                @endif

                                @if ($img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $produto->name }}"
                                        class="w-full h-32 object-contain">
                                @else
                                    <div class="w-full h-32 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            @if ($clienteLogado)
                                <div class="relative z-10 flex justify-end mt-1 mb-1" x-data="{ show: false, timer: null }">
                                    <div x-show="show" x-transition
                                        class="flex items-center gap-1 rounded-full text-white shadow-md px-1 py-1"
                                        style="background-color: {{ $primaryColor }}" onclick="event.stopPropagation()">
                                        <button wire:click.stop="decrementarHome({{ $produto->id }})"
                                            x-on:click="clearTimeout(timer); timer = setTimeout(() => show = false, 2000)"
                                            class="w-7 h-7 flex items-center justify-center cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span
                                            class="text-sm font-bold w-5 text-center">{{ $quantidades[$produto->id] ?? 1 }}</span>
                                        <button wire:click.stop="incrementarHome({{ $produto->id }})"
                                            x-on:click="clearTimeout(timer); timer = setTimeout(() => show = false, 2000)"
                                            class="w-7 h-7 flex items-center justify-center cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                    <button x-show="!show"
                                        wire:click.stop="{{ in_array($produto->id, $adicionados) ? 'incrementarHome(' . $produto->id . ')' : 'adicionarRapido(' . $produto->id . ')' }}"
                                        x-on:click="show = true; clearTimeout(timer); timer = setTimeout(() => show = false, 2000)"
                                        class="w-9 h-9 rounded-full text-white flex items-center justify-center shadow-md cursor-pointer"
                                        style="background-color: {{ $primaryColor }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <div class="relative z-10 flex justify-end mt-1 mb-1">
                                    <button type="button"
                                        onclick="event.stopPropagation(); Livewire.dispatch('abrir-modal-login')"
                                        class="w-9 h-9 rounded-full text-white flex items-center justify-center shadow-md cursor-pointer"
                                        style="background-color: {{ $primaryColor }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <div class="mt-1">
                                @if ($clienteLogado)
                                    <p class="text-sm font-bold text-gray-900">
                                        R$ {{ number_format($produto->price, 2, ',', '.') }}
                                    </p>
                                    @if ($produto->original_price)
                                        <p class="text-xs text-gray-400 line-through">
                                            R$ {{ number_format($produto->original_price, 2, ',', '.') }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-xs font-semibold" style="color: {{ $primaryColor }}">
                                        Entrar para ver o preço
                                    </p>
                                @endif
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-tight">
                                    {{ $produto->name }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


</div>
