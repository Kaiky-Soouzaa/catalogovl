<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Services\PrecoCliente;

class CategoryPage extends Component
{
    public User $tenant;
    public Category $categoria;
    public array $adicionados = [];
    public array $quantidades = [];

    protected $listeners = ['cartUpdated' => 'atualizarCarrinho'];

    public function mount(string $slug, string $categoriaSlug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);

        $this->categoria = Category::where('slug', $categoriaSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $this->atualizarCarrinho();
    }

    /**
     * Cliente logado E pertencente a esta loja.
     */
    protected function clienteLogado(): bool
    {
        $cliente = Auth::guard('cliente')->user();

        return $cliente !== null
            && (int) $cliente->tenant_id === (int) $this->tenant->id;
    }

    public function atualizarCarrinho(): void
    {
        if (! $this->clienteLogado()) {
            $this->adicionados = [];
            $this->quantidades = [];
            return;
        }

        $cart = Cart::with('items')
            ->where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

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
        if (! $this->clienteLogado()) {
            $this->dispatch('abrir-modal-login');
            return;
        }

        $cart = Cart::firstOrCreate([
            'user_id'    => $this->tenant->id,
            'session_id' => session()->getId(),
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
        if (! $this->clienteLogado()) {
            $this->dispatch('abrir-modal-login');
            return;
        }

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

    public function decrementarHome(int $productId): void
    {
        if (! $this->clienteLogado()) {
            $this->dispatch('abrir-modal-login');
            return;
        }

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

    public function render()
    {
        $produtos = $this->categoria->products()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $clienteLogado = $this->clienteLogado();
        $precos = PrecoCliente::para($clienteLogado ? Auth::guard('cliente')->user() : null);

        return view('livewire.category-page', compact('produtos', 'clienteLogado', 'precos'))
            ->layout('components.layouts.app', ['tenant' => $this->tenant, 'title' => $this->categoria->name]);
    }
}
