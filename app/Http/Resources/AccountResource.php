<?php

namespace App\Http\Resources;

use App\Models\Account;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Account $resource
 */
class AccountResource extends JsonResource
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
            'userId' => $this->resource->user_id,
            'user' =>  UserResource::make($this->whenLoaded('user')),
            'chats' => ChatResource::collection($this->whenLoaded('chats')),
            'chatCount' => $this->whenCounted('chats'),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'messagesCount' => $this->whenCounted('messages'),
        ];
    }
}
