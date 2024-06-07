<?php

namespace App\Http\Controllers;

use App\Enum\FriendshipStatus;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request, ?Profile $profile = null): mixed
    {
        $user = $request->user();

        $profile = $profile ?? $user->getDefaultProfile();

        $authProfile = Profile::query()->where('nickname', $request->query('authProfile'))->first() ?? $profile;

        $ownership = $authProfile->id == $profile->id;

        $friendshipStatus = $authProfile->sentRequests()->find($profile) ?
            ($authProfile->following()->find($profile) ? FriendshipStatus::ACCEPTED : FriendshipStatus::WAITING) :
            FriendshipStatus::NONE;

        $friendshipRequestForm = $this->getFriendshipRequestFormInfo($user, $profile, $friendshipStatus, $authProfile);

        $quickEditImagesForm = $this->getQuickEditImagesForm($user, $authProfile);

        $profile->loadCount(['followers', 'following', 'news']);

        return view('pages.profiles._profile', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
            'ownership' => $ownership,
            'friendshipRequestForm' => $friendshipRequestForm,
            'quickEditImagesForm' => $quickEditImagesForm,

        ]);
    }

    private function getFriendshipRequestFormInfo(User $user, Profile $profile, FriendshipStatus $friendshipStatus, Profile $authProfile): array
    {
        return $friendshipStatus == FriendshipStatus::NONE ?
            [
                'id' => 'follow_request_form',
                'submitLabel' => __('Follow'),
                'method' => 'POST',
                'action' => route('users.profiles.following.store', [
                    'user' => $user->id,
                    'profile' => $authProfile->id
                ]),
                'onSuccessMessage'=> __('messages.send_follow_request.on_success'),
                'onFailMessage'=> __('messages.send_follow_request.on_fail')
            ] :
            [
                'id' => 'unfollow_request_form',
                'method' => 'DELETE',
                'submitLabel' => $friendshipStatus == \App\Enum\FriendshipStatus::WAITING ? __('Waiting') : __('Unfollow'),
                'action' => route('users.profiles.following.destroy', [
                    'user' => $user->id,
                    'profile' => $authProfile->id,
                    'following' => $profile->id
                ]),
                'onSuccessMessage'=> __('messages.delete_following.on_success'),
                'onFailMessage'=> __('messages.delete_following.on_fail')
            ];
    }

    private function getQuickEditImagesForm(User $user, Profile $authProfile): array
    {
        return [
            'id' => 'quick_edit_image_form',
            'submitLabel' => __('Edit'),
            'method' => 'Save',
            'action' => route('users.profiles.update', [
                'user' => $user->id,
                'profile' => $authProfile->id
            ]),
            'onSuccessMessage'=> __('messages.edit_image.on_success'),
            'onFailMessage'=> __('messages.edit_image.on_fail')
        ];
    }

}
