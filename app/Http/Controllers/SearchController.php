<?php

namespace App\Http\Controllers;


use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        if ($profile && $profile->user_id !== $user->id) {
            abort(404);
        }

        $profile = $profile ?? $user->getDefaultProfile();

        $profiles = Profile::search($request->search)->get();

        return view('pages.search._profile', [
            'user' => $user,
            'profile' => $profile,
            'profiles' => $profiles,
        ]);
    }

}
