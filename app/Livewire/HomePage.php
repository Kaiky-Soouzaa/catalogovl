<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class HomePage extends Component
{
    public User $tenant;
    public string $categoriaAtiva = '';

    public function mount(int $userId): void
    {
        $this->tenant = User::findOrFail($userId);
        app()->instance('tenant', $this->tenant);

    }

    public function render()
    {
        $categorias = Category::with(['products' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.home-page', compact('categorias'))
            ->layout('components.layouts.app', ['tenant' => $this->tenant]);
    }
}