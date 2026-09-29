<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Services\PrecoCliente;

class OfertasPage extends Component
{
    public User $tenant;
    public array $adicionados = [];
    public array $quantidades = [];

    protected $listeners = ['cartUpdated' => 'atualizarCarrinho'];

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);

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
        $clienteLogado = $this->clienteLogado();
        $precos = PrecoCliente::para($clienteLogado ? Auth::guard('cliente')->user() : null);
        $idsNegociados = $precos->idsNegociadosComDesconto();

        $produtos = Product::where('is_active', true)
            ->where(function ($q) use ($idsNegociados) {
                $q->where(function ($g) {
                    $g->where('on_sale', true)->whereNotNull('original_price');
                })->orWhereIn('id', $idsNegociados);
            })
            ->orderBy('name')
            ->get()
            ->when($clienteLogado, fn($c) => $c->filter(fn($p) => $precos->emOferta($p))->values());

        return view('livewire.ofertas-page', compact('produtos', 'clienteLogado', 'precos'))
            ->layout('components.layouts.checkout', ['tenant' => $this->tenant, 'title' => 'Ofertas']);
    }
}
