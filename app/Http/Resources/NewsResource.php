<?php

namespace App\Http\Resources;

use App\Models\News;
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
            'seen' =>  $this->resource->seen,
            'seenAt' =>  $this->resource->seen_at,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'profileId' => $this->resource->profile_id,
            'from' => $this->resource->from,
            'profile' => new ProfileResource($this->whenLoaded('profile')),
        ];
    }
}
