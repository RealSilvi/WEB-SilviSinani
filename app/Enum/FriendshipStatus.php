<?php

namespace App\Enum;

enum FriendshipStatus: string
{
    case ACCEPTED = 'Accepted';
    case NONE = 'None';
    case WAITING = 'Waiting';
}
