<?php

namespace App\Livewire\Partials;

use Livewire\Component;

class Footer extends Component
{
    public function render()
    {
        $tenant = app('tenant');
        return view('livewire.partials.footer', compact('tenant'));
    }
}
