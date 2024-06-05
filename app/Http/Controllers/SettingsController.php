<?php

namespace App\Http\Controllers;


use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        if ($profile && $profile->user_id !== $user->id) {
            abort(404);
        }

        $profile = $profile ?? $user->getDefaultProfile();

        $profile->loadCount('news');

        return view('pages.settings._profile', [
            'user' => $user,
            'authProfile' => $profile,
            'profile' => $profile,
        ]);
    }

}
