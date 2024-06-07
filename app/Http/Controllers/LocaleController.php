<?php

namespace App\Http\Controllers;


use App;
use App\Enum\FriendshipStatus;
use App\Enum\FriendshipType;
use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        if (!in_array($locale, config('app.available_locales'))) {
            abort(400);
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        $segments = str_replace(url('/'), '', url()->previous());
        $segments = array_filter(explode('/', $segments));

        foreach (config('app.available_locales') as $explicitLanguage) {
            if ($segments[1] === $explicitLanguage) {
                array_shift($segments);
                array_unshift($segments, $locale);
                return redirect()->to(implode('/', $segments));
            }
        }

        array_unshift($segments, $locale);
        return redirect()->to(implode('/', $segments));

    }

}
