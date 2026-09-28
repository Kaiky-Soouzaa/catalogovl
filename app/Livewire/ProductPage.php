<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Cliente;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductPage extends Component
{
    public User $tenant;
    public Product $product;
    public string $note = '';
    public int $quantity = 1;
    public bool $adicionado = false;

    public function mount(string $slug, string $produto_slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);

        // O middleware da rota já barra visitante; aqui o componente não depende disso
        abort_unless($this->clienteAtual(), 403);

        $this->product = Product::where('slug', $produto_slug)->where('is_active', true)->firstOrFail();
    }

    /**
     * Cliente logado E pertencente a esta loja.
     */
    protected function clienteAtual(): ?Cliente
    {
        $cliente = Auth::guard('cliente')->user();

        return $cliente && (int) $cliente->tenant_id === (int) $this->tenant->id
            ? $cliente
            : null;
    }

    public function incrementar(): void
    {
        $this->quantity = min($this->quantity + 1, 999);
    }

    public function decrementar(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function adicionar(): void
    {
        if (! $this->clienteAtual()) {
            $this->redirect(url($this->tenant->slug . '?login=1'));
            return;
        }

        // $quantity e $note são públicos: o navegador consegue alterar.
        // Por isso são limitados aqui, no servidor.
        $quantidade = max(1, min($this->quantity, 999));
        $nota = mb_substr(trim($this->note), 0, 255);

        $cart = Cart::firstOrCreate([
            'user_id'    => $this->tenant->id,
            'session_id' => session()->getId(),
        ]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantidade);

            if ($nota !== '') {
                $item->update(['note' => $nota]);
            }
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $this->product->id,
                'quantity'   => $quantidade,
                'note'       => $nota,
            ]);
        }

        $this->adicionado = true;
        $this->quantity = 1;
        $this->note = '';

        $this->dispatch('cartUpdated'); // atualiza o badge do header
        $this->dispatch('resetarBotao');
    }

    public function resetarBotao(): void
    {
        $this->adicionado = false;
    }

    public function render()
    {
        return view('livewire.product-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
