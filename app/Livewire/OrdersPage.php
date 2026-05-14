<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class OrdersPage extends Component
{
    public User $tenant;
    public $orders;

    public function mount(int $userId): void
    {
        $this->tenant = User::findOrFail($userId);
        app()->instance('tenant', $this->tenant);

        // Busca pelos IDs salvos na sessão
        $orderIds = session()->get('orders_' . $userId, []);

        if (!empty($orderIds)) {
            $this->orders = Order::with('items.product')
                ->where('user_id', $userId)
                ->whereIn('id', $orderIds)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->orders = collect();
        }
    }

    public function render()
    {
        return view('livewire.orders-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
