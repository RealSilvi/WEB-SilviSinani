<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\News;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property News $resource
 */
class NewsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'body' => $this->resource->body,
            'type' => $this->resource->type,
            'seen' => $this->resource->seen,
            'seenAt' => $this->resource->seen_at,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'profileId' => $this->resource->profile_id,
            'fromId' => $this->resource->from_id,
            'fromType' => $this->resource->from_type,
            'from' => match ($this->resource->from_type) {
                Profile::class => new ProfileResource($this->whenLoaded('from')),
                Comment::class => new CommentResource($this->whenLoaded('from')),
                Post::class => new PostResource($this->whenLoaded('from')),
            },
            'profile' => new ProfileResource($this->whenLoaded('profile')),
        ];
    }
}
