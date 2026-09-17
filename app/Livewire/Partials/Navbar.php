<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;

class Navbar extends Component
{
    public string $busca = '';
    public bool $mostrarResultados = false;
    public ?User $tenant = null;

    public int $totalItens = 0;
    public float $totalValor = 0;
    public bool $carrinhoAberto = false;
    public array $cartItensDetalhe = [];

    protected $listeners = [
        'cartUpdated'         => 'atualizarCarrinho',
        'abrirCarrinhoGlobal' => 'abrirCarrinho',
    ];

    public function mount(): void
    {
        try {
            $this->tenant = app('tenant');
        } catch (\Exception $e) {
            $this->tenant = null;
        }

        $this->atualizarCarrinho();
    }

    public function atualizarCarrinho(): void
    {
        if (!$this->tenant) return;

        $cart = Cart::with('items.product')
            ->where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        $this->totalItens = $cart ? $cart->items->sum('quantity') : 0;
        $this->totalValor = $cart ? $cart->items->sum(fn($i) => $i->product->price * $i->quantity) : 0;

        $this->cartItensDetalhe = $cart
            ? $cart->items->map(fn($item) => [
                'product_id' => $item->product_id,
                'name'       => $item->product->name,
                'category'   => $item->product->category->name ?? null,
                'price'      => $item->product->price,
                'quantity'   => $item->quantity,
                'image'      => is_array($item->product->images) ? ($item->product->images[0] ?? null) : null,
                'note'       => $item->note,
            ])->toArray()
            : [];
    }

    public function abrirCarrinho(): void
    {
        $this->carrinhoAberto = true;
    }

    public function fecharCarrinho(): void
    {
        $this->carrinhoAberto = false;
    }

    public function incrementarItem(int $productId): void
    {
        $cart = Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        if (!$cart) return;

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->increment('quantity');

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function decrementarItem(int $productId): void
    {
        $cart = Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        if (!$cart) return;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (!$item) return;

        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function removerItem(int $productId): void
    {
        $cart = Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        if (!$cart) return;

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->delete();

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function updatedBusca(): void
    {
        $this->mostrarResultados = strlen($this->busca) >= 2;
    }

    public function fecharBusca(): void
    {
        $this->busca = '';
        $this->mostrarResultados = false;
    }

    public function render()
    {
        $categorias = Category::where('is_active', true)->orderBy('name')->get();
        $tenant = $this->tenant ?? app('tenant');

        $resultados = collect();

        if ($this->mostrarResultados && strlen($this->busca) >= 2 && $tenant) {
            $resultados = Product::where('is_active', true)
                ->where('name', 'like', "%{$this->busca}%")
                ->limit(8)
                ->get();
        }

        return view('livewire.partials.navbar', compact('tenant', 'categorias', 'resultados'));
    }
}
