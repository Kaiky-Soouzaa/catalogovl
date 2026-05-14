<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Livewire\Component;

class HomePage extends Component
{
    public User $tenant;
    public string $busca = '';
    public int $totalItens = 0;
    public float $totalValor = 0;
    public array $adicionados = [];
    public array $quantidades = [];

    protected $listeners = ['cartUpdated' => 'atualizarCarrinho'];

    public function mount(int $userId): void
    {
        $this->tenant = User::findOrFail($userId);
        app()->instance('tenant', $this->tenant);
        $this->atualizarCarrinho();
    }

    public function atualizarCarrinho(): void
    {
        $cart = Cart::with('items.product')
            ->where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        $this->totalItens = $cart ? $cart->items->sum('quantity') : 0;
        $this->totalValor = $cart ? $cart->items->sum(fn($i) => $i->product->price * $i->quantity) : 0;

        if ($cart) {
            $this->adicionados = $cart->items->pluck('product_id')->toArray();
            $this->quantidades = $cart->items->pluck('quantity', 'product_id')->toArray();
        } else {
            $this->adicionados = [];
            $this->quantidades = [];
        }
    }

    public function adicionarRapido(int $productId): void
    {
        $sessionId = session()->getId();

        $cart = Cart::firstOrCreate([
            'user_id'    => $this->tenant->id,
            'session_id' => $sessionId,
        ]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $productId,
                'quantity'   => 1,
                'note'       => '',
            ]);
        }

        $this->atualizarCarrinho();
        $this->dispatch('cartUpdated');
    }

    public function incrementarHome(int $productId): void
    {
        $cart = Cart::where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        if (!$cart) return;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($item) $item->increment('quantity');

        $this->atualizarCarrinho();
    }

    public function decrementarHome(int $productId): void
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
    }

    public function render()
    {
        $categorias = Category::with(['products' => function ($query) {
            $query->where('is_active', true)->orderBy('name');
        }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $produtosOferta = Product::where('is_active', true)
            ->where('on_sale', true)
            ->whereNotNull('original_price')
            ->when($this->busca, fn($q) => $q->where('name', 'like', "%{$this->busca}%"))
            ->orderBy('name')
            ->get();

        if ($this->busca) {
            $categorias = Category::with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('name', 'like', "%{$this->busca}%")
                    ->orderBy('name');
            }])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('livewire.home-page', compact('categorias', 'produtosOferta'))
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
