<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image extends Component
{
    public function __construct(public string $filter = '')
    {
    }


    public function render(): View|Closure|string
    {
        $src = 'https://source.unsplash.com/random';
        $alt = 'placeholder';

        $src = $src . '?' . $this->filter;

        return view('components.image', [
            'placeholder_src' => $src,
            'placeholder_alt' => $alt,
        ]);
    }
}
