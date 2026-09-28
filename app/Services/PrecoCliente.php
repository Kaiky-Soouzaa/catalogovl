<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\ClientePreco;
use App\Models\Product;
use Illuminate\Support\Collection;

class PrecoCliente
{
    /** Preços negociados deste cliente, indexados por product_id (uma consulta só). */
    protected Collection $negociados;

    public function __construct(protected ?Cliente $cliente)
    {
        $this->negociados = $cliente
            ? ClientePreco::where('cliente_id', $cliente->id)->get()->keyBy('product_id')
            : collect();
    }

    public static function para(?Cliente $cliente): self
    {
        return new self($cliente);
    }

    public function preco(Product $produto): float
    {
        $negociado = $this->negociados->get($produto->id);

        return (float) ($negociado ? $negociado->price : $produto->price);
    }

    public function precoOriginal(Product $produto): ?float
    {
        $negociado = $this->negociados->get($produto->id);
        $original = $negociado ? $negociado->original_price : $produto->original_price;

        return $original !== null ? (float) $original : null;
    }

    public function desconto(Product $produto): int
    {
        $original = $this->precoOriginal($produto);
        $preco = $this->preco($produto);

        if (! $original || $original <= $preco) {
            return 0;
        }

        return (int) round((1 - $preco / $original) * 100);
    }

    public function emOferta(Product $produto): bool
    {
        // Sem preço negociado, vale a regra atual: o produto precisa estar marcado como on_sale
        if (! $this->negociados->has($produto->id) && ! $produto->on_sale) {
            return false;
        }

        return $this->desconto($produto) > 0;
    }

    /** IDs dos produtos em que este cliente tem preço negociado com desconto. */
    public function idsNegociadosComDesconto(): array
    {
        return $this->negociados
            ->filter(fn($n) => $n->original_price !== null && (float) $n->original_price > (float) $n->price)
            ->keys()
            ->all();
    }
}
