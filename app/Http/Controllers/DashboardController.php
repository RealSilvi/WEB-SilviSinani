<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        if ($profile && $profile->user_id !== $user->id) {
            abort(404);
        }

        $profile = $profile ?? $user->getDefaultProfile();
        $profile->loadCount('news');

        return view('pages.dashboard._profile', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $profile,
        ]);
    }
}
