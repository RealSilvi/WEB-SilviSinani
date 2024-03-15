<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Message $resource
 */
class MessageResource extends JsonResource
{
    use ResourceSerialization;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type->value,
            'body' => $this->resource->body,
            'seen' => $this->resource->seen,
            'chatId' => $this->resource->chat_id,
            'senderId' => $this->resource->sender_id,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'chat' => ChatResource::make($this->whenLoaded('chat')),
            'sender' => ChatMemberResource::make($this->whenLoaded('sender')),
        ];
    }
}
