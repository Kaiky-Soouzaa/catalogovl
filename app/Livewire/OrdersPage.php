<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class OrdersPage extends Component
{
    public User $tenant;
    public $orders;

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);

        $orderIds = session()->get('orders_' . $this->tenant->id, []);

        if (!empty($orderIds)) {
            $this->orders = Order::with('items.product')
                ->where('user_id', $this->tenant->id)
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
