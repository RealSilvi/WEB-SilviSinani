<?php

namespace App\Models;

use App\Enum\NewsType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;


/**
 *
 * @property int $id
 * @property NewsType $type
 * @property string|null $title
 * @property string|null $body
 * @property bool $seen
 * @property \Illuminate\Support\Carbon|null $seen_at
 * @property int $profile_id
 * @property int $from
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Profile $profile
 */
class News extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'seen_at' => 'datetime',
        'seen' => 'boolean',
        'created_at' => 'datetime',
        'updated_ad' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function from(): MorphTo
    {
        return $this->morphTo();
    }
}
