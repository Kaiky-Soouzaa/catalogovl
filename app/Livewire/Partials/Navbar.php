<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class Navbar extends Component
{
    public string $busca = '';
    public bool $mostrarResultados = false;
    public ?User $tenant = null;

    public function mount(): void
    {
        try {
            $this->tenant = app('tenant');
        } catch (\Exception $e) {
            $this->tenant = null;
        }
    }

    public function updatedBusca(): void
    {
        $this->mostrarResultados = strlen($this->busca) >= 2;
    }

    public function fecharBusca(): void
    {
        $this->busca = '';
        $this->mostrarResultados = false;
    }

    public function render()
    {
        $categorias = Category::where('is_active', true)->orderBy('name')->get();
        $tenant = $this->tenant ?? app('tenant');

        $resultados = collect();

        if ($this->mostrarResultados && strlen($this->busca) >= 2 && $tenant) {
            $resultados = Product::where('is_active', true)
                ->where('name', 'like', "%{$this->busca}%")
                ->limit(8)
                ->get();
        }

        return view('livewire.partials.navbar', compact('tenant', 'categorias', 'resultados'));
    }
}
