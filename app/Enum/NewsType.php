<?php

namespace App\Enum;

enum NewsType: string
{
    case FOLLOW_REQUEST = 'Follow request';
    case POST_LIKE = 'Post like';
    case COMMENT_LIKE = 'Comment like';
    case COMMENT = 'Comment';
}
