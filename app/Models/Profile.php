<?php

namespace App\Models;

use App\Enum\ProfileType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'bio' => $this->bio,
        ];
    }

}
