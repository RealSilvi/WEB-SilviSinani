<?php

namespace App\Enums;
enum MessageType: string
{
    case TEXTUAL = 'Textual';
    case IMAGE_URL = 'ImageUrl';
    case VIDEO_URL = 'VideoUrl';
    case POST_ID = 'PostId';
}
