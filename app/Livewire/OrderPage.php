<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderPage extends Component
{
    public User $tenant;
    public Order $order;

    public function mount(string $slug, int $orderId): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);

        $cliente = Auth::guard('cliente')->user();

        abort_unless(
            $cliente && (int) $cliente->tenant_id === (int) $this->tenant->id,
            403
        );

        // O pedido precisa ser desta loja E deste cliente.
        // Se não for, responde 404 (não revela que o pedido existe).
        $this->order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', $this->tenant->id)
            ->where('cliente_id', $cliente->id)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.order-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
