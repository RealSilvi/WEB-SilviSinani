<?php

namespace App\Enum;

enum FriendshipType: string
{
    case FOLLOWING = 'following';
    case FOLLOWER = 'follower';
}
