<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class OrderPage extends Component
{
    public User $tenant;
    public Order $order;
    public string $whatsapp = '';

    public function mount(int $userId, int $orderId): void
    {
        $this->tenant = User::findOrFail($userId);

        $this->whatsapp = preg_replace('/\D/', '', $this->tenant->whatsapp ?? '');

        if (strlen($this->whatsapp) === 11) {
            $this->whatsapp = '55' . $this->whatsapp;
        }

        app()->instance('tenant', $this->tenant);

        $this->order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function enviarWhatsApp(): void
    {
        $this->dispatch('abrirWhatsApp');
    }

    public function render()
    {
        return view('livewire.order-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
