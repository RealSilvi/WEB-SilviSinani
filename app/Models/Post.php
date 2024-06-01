<?php

namespace App\Models;

use App\Http\Resources\PostResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 *
 *
 * @property int $id
 * @property string|null $image
 * @property string|null $description
 * @property int $profile_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Profile> $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\Profile $profile
 */
class Post extends Model
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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function topComments(): HasMany
    {
        return $this->comments()
            ->with('likes')
            ->withCount('likes')
            ->orderBy('likes_count', 'desc');
//            ->limit(2);

    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'post_likes', 'post_id', 'profile_id');
    }
}
