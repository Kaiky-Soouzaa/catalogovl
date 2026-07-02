<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class PaymentMethodPage extends Component
{
    public User $tenant;

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);
    }

    public function selecionar(string $metodo): void
    {
        session()->put('checkout_metodo_' . $this->tenant->id, $metodo);
        $this->redirect(url($this->tenant->slug . '/finalizar/dados'));
    }

    public function render()
    {
        return view('livewire.payment-method-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
