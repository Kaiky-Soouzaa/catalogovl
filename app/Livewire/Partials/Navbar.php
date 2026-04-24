<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Models\Category;

class Navbar extends Component
{


    public function render()
    {

        $categorias = Category::where('is_active', true)->orderBy('name')->get();


        $tenant = app('tenant');
        return view('livewire.partials.navbar', compact('tenant', 'categorias'));
    }
}
