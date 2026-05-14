<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Livewire\Component;

class CartPage extends Component
{
    public User $tenant;
    public $cartItems = [];
    public float $total = 0;

    public function mount(int $userId): void
    {
        $this->tenant = User::findOrFail($userId);
        app()->instance('tenant', $this->tenant);
        $this->carregarCarrinho();
    }

    public function carregarCarrinho(): void
    {
        $cart = Cart::with('items.product')
            ->where('user_id', $this->tenant->id)
            ->where('session_id', session()->getId())
            ->first();

        $this->cartItems = $cart ? $cart->items->load('product') : collect();
        $this->total = $this->cartItems->sum(fn($i) => $i->product->price * $i->quantity);
    }

    public function incrementar(int $itemId): void
    {
        $item = CartItem::findOrFail($itemId);
        $item->increment('quantity');
        $this->carregarCarrinho();
    }

    public function decrementar(int $itemId): void
    {
        $item = CartItem::findOrFail($itemId);
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }
        $this->carregarCarrinho();
    }

    public function remover(int $itemId): void
    {
        CartItem::findOrFail($itemId)->delete();
        $this->carregarCarrinho();
    }

    public function render()
    {
        return view('livewire.cart-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
