<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    public function render(): View|Closure|string
    {
        $menu = [
            ['label' => 'Profilo', 'url' => url('/profile/edit')],
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Impostazioni', 'url' => url('#')],
        ];


        return view('components.navbar',[
            'menu' => $menu,
        ]);
    }
}
