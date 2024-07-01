<?php

namespace App\View\Components;

use App\Models\Profile;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchProfileItem extends Component
{
    public function __construct(public Profile $searchProfile, public Profile $authProfile) {}

    public function render(): View|Closure|string
    {
        return view('components.search-profile-item', [
            'searchProfile' => $this->searchProfile,
            'authProfile' => $this->authProfile,
        ]);
    }
}
