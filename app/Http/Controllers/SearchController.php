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

        $profile = $profile ?? $user->getDefaultProfile();

        /** @var \Illuminate\Database\Eloquent\Collection<array-key,\App\Models\Profile> $profiles */
        $profiles = Profile::search($request->search)->get();

        $profile->loadCount('news');

        return view('pages.search._profile', [
            'user' => $user,
            'profile' => $profile,
            'profiles' => $profiles,
        ]);
    }

}
