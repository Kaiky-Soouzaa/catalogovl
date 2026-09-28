<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrdersPage extends Component
{
    public User $tenant;

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);

        // Segunda barreira: o middleware já protege a rota, mas o componente
        // não depende disso
        $cliente = Auth::guard('cliente')->user();

        abort_unless(
            $cliente && (int) $cliente->tenant_id === (int) $this->tenant->id,
            403
        );
    }

    public function render()
    {
        $cliente = Auth::guard('cliente')->user();

        // Só os pedidos desta loja E deste cliente
        $orders = Order::with('items.product')
            ->where('user_id', $this->tenant->id)
            ->where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.orders-page', compact('orders'))
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
