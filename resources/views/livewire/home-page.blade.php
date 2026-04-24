<div>
    <div class="max-w-7xl mx-auto px-4 py-6 space-y-10">

        @forelse($categorias as $categoria)

            {{-- Âncora para o scroll da navbar --}}
            <section id="categoria-{{ $categoria->slug }}">

                {{-- Título da categoria --}}
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    {{ $categoria->name }}
                </h2>

                {{-- Produtos da categoria --}}
                @if ($categoria->products->isEmpty())
                    <p class="text-sm text-gray-400 py-4">Nenhum produto nessa categoria.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($categoria->products as $produto)
                            <div
                                class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow duration-200">

                                {{-- Info --}}
                                <div class="flex-1 min-w-0 pr-4">
                                    <p class="text-xs font-bold text-gray-800 uppercase tracking-wide leading-snug">
                                        {{ $produto->name }}
                                    </p>
                                    <p class="text-base font-semibold text-gray-900 mt-2">
                                        R$ {{ number_format($produto->price, 2, ',', '.') }}
                                    </p>
                                    <div class="flex gap-1 mt-2">
                                        @if ($produto->on_sale)
                                            <span
                                                class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">Oferta</span>
                                        @endif
                                        @if (!$produto->in_stock)
                                            <span
                                                class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">Sem
                                                estoque</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Imagem --}}
                                <div class="flex-shrink-0">
                                    @php
                                        $imagens = is_array($produto->images) ? $produto->images : [];
                                        $primeiraImagem = $imagens[0] ?? null;
                                    @endphp
                                    @if ($primeiraImagem)
                                        <img src="{{ asset('storage/' . $primeiraImagem) }}" alt="{{ $produto->name }}"
                                            class="w-16 h-16 object-contain" loading="lazy">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif

            </section>

        @empty
            <div class="text-center py-16 text-gray-400">
                <p class="text-sm">Nenhuma categoria cadastrada.</p>
            </div>
        @endforelse

    </div>
</div>
