<?php

namespace App\Models;

use App\Enum\ProfileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;


/**
 *
 * @property int $id
 * @property string $nickname
 * @property string|null $bio
 * @property string|null $main_image
 * @property string|null $secondary_image
 * @property string|null $date_of_birth
 * @property bool $default
 * @property int $user_id
 * @property ProfileType $type
 * @property string|null $breed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\News> $allNews
 * @property-read int|null $all_news_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $commentLikes
 * @property-read int|null $comment_likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $following
 * @property-read int|null $following_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\News> $news
 * @property-read int|null $news_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $pendingFollowers
 * @property-read int|null $pending_followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $postLikes
 * @property-read int|null $post_likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $receivedRequests
 * @property-read int|null $received_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $sentRequests
 * @property-read int|null $sent_requests_count
 * @property-read \App\Models\User $user
 */
class Profile extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    protected $casts = [
        'type' => ProfileType::class,
        'default' => 'boolean',
        'created_at' => 'datetime',
        'updated_ad' => 'datetime',
    ];

    /**
     * @return BelongsTo<User,Profile>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receivedRequests(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'friendships', 'following_id', 'follower_id');
    }

    public function sentRequests(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'friendships', 'follower_id', 'following_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->receivedRequests()->wherePivot('accepted', true);
    }

    public function following(): BelongsToMany
    {
        return $this->sentRequests()->wherePivot('accepted', true);
    }

    public function allNews(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function news(): HasMany
    {
        return $this->allNews()->where('seen', false);
    }

    public function generatedNews(): MorphMany
    {
        return $this->morphMany(News::class, 'from');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function lastPost(): HasOne
    {
        return $this->hasOne(Post::class)->latest();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function postLikes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_likes', 'profile_id', 'post_id');
    }

    public function commentLikes(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'comment_likes', 'profile_id', 'comment_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'type' => $this->type,
            'nickname' => $this->nickname,
        ];
    }

}
