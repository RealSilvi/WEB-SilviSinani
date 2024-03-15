<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\ChatMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Chat $resource
 */
class ChatResource extends JsonResource
{
    use ResourceSerialization;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'members' => ChatMember::collection($this->whenLoaded('members')),
            'membersCount' => $this->whenCounted('members'),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'messagesCount' => $this->whenCounted('messages'),
        ];
    }
}
