<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class DeliveryPage extends Component
{
    public User $tenant;

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);
    }

    public function selecionar(string $tipo): void
    {
        session()->put('checkout_entrega_' . $this->tenant->id, $tipo);
        $this->redirect(url($this->tenant->slug . '/finalizar/pagamento'));
    }

    public function render()
    {
        return view('livewire.delivery-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
