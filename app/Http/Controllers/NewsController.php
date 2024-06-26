<?php

namespace App\Http\Controllers;


use App\Actions\Profile\SeeAllNewsAction;
use App\Enum\FriendshipType;
use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
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
