<?php

namespace App\Models;

use App\Enum\ProfileType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

/**
 *
 *
 * @property int $id
 * @property string $nickname
 * @property string|null $main_image
 * @property string|null $secondary_image
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property int $default
 * @property int $user_id
 * @property ProfileType $type
 * @property string|null $breed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property string $bio
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $receivedRequests
 * @property-read int|null $received_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $sentRequests
 * @property-read int|null $sent_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $following
 * @property-read int|null $following_count
 */
class Profile extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    protected $casts = [
        'type' => ProfileType::class,
        'default' => 'bool',
        'created_at' => 'date:Y-m-d',
        'updated_ad' => 'date:Y-m-d',
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

    public function pendingFollowers(): BelongsToMany
    {
        return $this->receivedRequests()->wherePivot('accepted', false);
    }

    public function friends(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->followers()->get()->merge($this->following()->get())->unique('id');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'bio' => $this->bio,
            'type' => $this->type,
            'breed' => $this->breed,
        ];
    }

}
