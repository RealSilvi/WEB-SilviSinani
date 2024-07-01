<?php

namespace App\Http\Controllers;

use App\Enum\FriendshipType;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FriendshipsController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile, FriendshipType $friendshipType): mixed
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
            'followers' => $friendshipType === FriendshipType::FOLLOWER,
        ]);
    }
}
