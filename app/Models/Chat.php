<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Chat extends Model
{
    use HasFactory;

    /**
     * @return HasMany<ChatMember>
     */
    public function members(): HasMany
    {
        return $this->hasMany(ChatMember::class);
    }

    /**
     * @return HasMany<Message>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMember::class);
    }
}
