<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class PaymentTypePage extends Component
{
    public User $tenant;
    public string $tipoEntrega = '';

    public function mount(string $slug): void
    {
        $this->tenant = User::where('slug', $slug)->firstOrFail();
        app()->instance('tenant', $this->tenant);
        $this->tipoEntrega = session()->get('checkout_entrega_' . $this->tenant->id, 'retirada'); // ← id
    }

    public function selecionar(string $tipo): void
    {
        session()->put('checkout_pagamento_tipo_' . $this->tenant->id, $tipo); // ← id
        $this->redirect(url($this->tenant->slug . '/finalizar/metodo'));
    }

    public function render()
    {
        return view('livewire.payment-type-page')
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}
