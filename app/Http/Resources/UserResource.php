<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'firstName' => $this->resource->first_name,
            'lastName' => $this->resource->last_name,
            'dateOfBirth' => $this->resource->date_of_birth,
            'email' => $this->resource->email,
            'profilesCount' => $this->whenCounted('profiles', $this->resource->profiles_count),
            'profiles' => ProfileResource::collection($this->whenLoaded('profiles')),
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
        ];
    }
}
