<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
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
        $this->product = Product::where('slug', $produto_slug)->where('is_active', true)->firstOrFail();
    }

    public function incrementar(): void
    {
        $this->quantity++;
    }

    public function decrementar(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function adicionar(): void
    {
        $sessionId = session()->getId();

        $cart = Cart::firstOrCreate([
            'user_id'    => $this->tenant->id,
            'session_id' => $sessionId,
        ]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $this->quantity);

            if ($this->note) {
                $item->update(['note' => $this->note]);
            }
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $this->product->id,
                'quantity'   => $this->quantity,
                'note'       => $this->note,
            ]);
        }


        $this->adicionado = true;


        $this->quantity = 1;


        $this->note = '';


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
