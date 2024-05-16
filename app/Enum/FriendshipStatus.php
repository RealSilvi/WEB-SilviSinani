<?php

namespace App\Enum;

enum FriendshipStatus: string
{
    case ACCEPTED = 'Accepted';
    case NONE = 'none';
    case WAITING = 'Waiting';
}
