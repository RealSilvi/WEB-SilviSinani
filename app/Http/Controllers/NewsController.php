<?php

namespace App\Http\Controllers;


use App\Enum\FriendshipStatus;
use App\Enum\FriendshipType;
use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        $profile = $profile ?? $user->getDefaultProfile();

        $pendingFollowers = $profile->pendingFollowers()->get();


        $friendshipType = FriendshipType::FOLLOWER;

        return view('pages.news._profile', [
            'user' => $user,
            'profile' => $profile,
            'pendingFollowers' => $pendingFollowers,
            'friendshipType' => $friendshipType
        ]);
    }

}
