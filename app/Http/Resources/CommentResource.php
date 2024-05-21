<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Comment $resource
 */
class CommentResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'body' => $this->resource->body,
            'postId' => $this->resource->post_id,
            'post' => new PostResource($this->whenLoaded('post')),
            'profileId' => $this->resource->profile_id,
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'likesCount' => $this->whenCounted('likes', $this->resource->likes_count),
            'likes' => ProfileResource::collection($this->whenLoaded('likes')),
        ];
    }
}
