<?php

namespace App\Http\Resources;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Profile $resource
 */
class ProfileResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        return [
            'id' => $this->resource->id,
            'nickname' => $this->resource->nickname,
            'mainImage' => $this->resource->main_image,
            'secondaryImage' => $this->resource->secondary_image,
            'dateOfBirth' => $this->resource->date_of_birth,
            'default' => $this->resource->default,
            'userId' => $this->resource->user_id,
            'type' => $this->resource->type,
            'breed' => $this->resource->breed,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'bio' => $this->resource->bio,
        ];
    }
}
