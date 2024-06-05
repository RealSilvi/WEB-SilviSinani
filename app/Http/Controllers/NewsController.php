<?php

namespace App\Http\Controllers;


use App\Actions\Profile\SeeAllNewsAction;
use App\Enum\FriendshipType;
use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        $profile = $profile ?? $user->getDefaultProfile();

        $pendingFollowers = $profile->pendingFollowers()->get();

        $friendshipType = FriendshipType::FOLLOWER;

        $profile->loadCount('news');

        app(SeeAllNewsAction::class)->execute($user, $profile);

        return view('pages.news._profile', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $profile,
            'pendingFollowers' => $pendingFollowers,
            'friendshipType' => $friendshipType
        ]);
    }

}
