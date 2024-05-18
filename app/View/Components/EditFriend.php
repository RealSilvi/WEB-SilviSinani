<?php

namespace App\View\Components;

use App\Enum\FriendshipType;
use App\Models\Profile;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditFriend extends Component
{
    public function __construct(public Profile $friendProfile, public Profile $authProfile, public FriendshipType $friendshipType, public ?bool $onlyDelete = false)
    {
    }

    public function render(): View|Closure|string
    {
        $user = auth()->user();

        $sendFollowRequestFormInfo = [
            'id' => 'send_follow_request_form',
            'method' => 'POST',
            'action' => route('users.profiles.following.store', [
                'user' => $user->id,
                'profile' => $this->authProfile->id
            ]),
        ];

        $acceptFollowerFormInfo = [
            'id' => 'accept_follower_form',
            'method' => 'POST',
            'action' => route('users.profiles.followers.store', [
                'user' => $user->id,
                'profile' => $this->authProfile->id,
            ]),
        ];

        $declineFollowerFormInfo = [
            'id' => 'decline_follower_form',
            'method' => 'DELETE',
            'action' => route('users.profiles.followers.destroy', [
                'user' => $user->id,
                'profile' => $this->authProfile->id,
                'follower' => $this->friendProfile->id,
            ]),
        ];

        $deleteFollowRequestFormInfo = [
            'id' => 'delete_follow_request_form',
            'method' => 'DELETE',
            'action' => route('users.profiles.following.destroy', [
                'user' => $user->id,
                'profile' => $this->authProfile->id,
                'following' => $this->friendProfile->id
            ]),
        ];

        $storeFriendForm = $this->friendshipType == FriendshipType::FOLLOWER ? $acceptFollowerFormInfo : $sendFollowRequestFormInfo;
        $deleteFriendForm = $this->friendshipType == FriendshipType::FOLLOWER ? $declineFollowerFormInfo : $deleteFollowRequestFormInfo;

        return view('components.edit-friend', [
            'friendProfile' => $this->friendProfile,
            'authProfile' => $this->authProfile,
            'onlyDelete' => $this->onlyDelete,
            'storeFriendForm' => $storeFriendForm,
            'deleteFriendForm' => $deleteFriendForm,
        ]);
    }
}
