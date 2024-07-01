<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Post $resource
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'image' => $this->resource->image,
            'description' => $this->resource->description,
            'profileId' => $this->resource->profile_id,
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'likesCount' => $this->whenCounted('likes', $this->resource->likes_count),
            'likes' => ProfileResource::collection($this->whenLoaded('likes')),
            'commentsCount' => $this->whenCounted('comments', $this->resource->comments_count),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'topComments' => CommentResource::collection($this->whenLoaded('topComments')),
        ];
    }
}
