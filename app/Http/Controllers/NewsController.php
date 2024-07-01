<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;

class NewsController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        $profile = $profile ?? $user->getDefaultProfile();

        $profile->loadCount('news');

        return view('pages.news._profile', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $profile,
        ]);
    }
}
