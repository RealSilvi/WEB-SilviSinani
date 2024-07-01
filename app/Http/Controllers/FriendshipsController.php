<?php

namespace App\Http\Controllers;


use App\Enum\FriendshipStatus;
use App\Enum\FriendshipType;
use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FriendshipsController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile = null, FriendshipType $friendshipType): mixed
    {
        $user = $request->user();

        $profile = $profile ?? $user->getDefaultProfile();

        $authProfile = Profile::query()->where('nickname', $request->query('authProfile'))->first() ?? $profile;
        $ownership = $authProfile == $profile;

        $authProfile->loadCount('news');

        return view('pages.profiles.friendships._friendshipType', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
            'ownership' => $ownership,
            'followers' => $friendshipType === FriendshipType::FOLLOWER
        ]);
    }

}
