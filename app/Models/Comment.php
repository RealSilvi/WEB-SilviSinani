<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 *
 *
 * @property int $id
 * @property string $body
 * @property int $post_id
 * @property int $profile_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Profile> $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\Post $post
 * @property-read \App\Models\Profile $profile
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_ad' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'comment_likes', 'comment_id', 'profile_id');
    }
}
