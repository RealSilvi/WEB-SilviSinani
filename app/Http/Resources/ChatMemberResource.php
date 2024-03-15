<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\ChatMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ChatMember $resource
 */
class ChatMemberResource extends JsonResource
{
    use ResourceSerialization;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'chatId' => $this->resource->chat_id,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'chat' =>  ChatResource::make($this->whenLoaded('chat')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'messagesCount' => $this->whenCounted('messages'),
        ];
    }
}
