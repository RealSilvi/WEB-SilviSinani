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

        $quickEditImagesForm = $this->getQuickEditImagesForm($user, $authProfile);

        $profile->loadCount(['followers', 'following']);

        $authProfile->loadCount('news');

        return view('pages.profiles._profile', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
            'ownership' => $ownership,
            'quickEditImagesForm' => $quickEditImagesForm,

        ]);
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
