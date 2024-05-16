<?php

namespace App\Http\Controllers;


use App\Enum\FriendshipStatus;
use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        $profile = $profile ?? $user->getDefaultProfile();

        $profile->loadCount(['followers', 'following']);

        $authProfile = Profile::query()->where('nickname', $request->query('authProfile'))->first() ?? $profile;

        $ownership = $authProfile == $profile;

        $friendshipStatus = $authProfile->sentRequests()->find($profile) ?
            ($authProfile->following()->find($profile) ? FriendshipStatus::ACCEPTED : FriendshipStatus::WAITING) :
            FriendshipStatus::NONE;

        return view('pages.profiles._profile', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
            'ownership' => $ownership,
            'friendshipStatus' => $friendshipStatus,

        ]);
    }

}
