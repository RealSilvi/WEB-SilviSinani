<?php

namespace App\Models;

use App\Enum\NewsType;
use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use phpDocumentor\Reflection\Types\Boolean;

/**
 *
 *
 * @property int $id
 * @property NewsType $type
 * @property int $profile_id
 * @property BelongsTo<Profile> $profile
 * @property int $from
 * @property boolean $seen
 * @property Date $seen_at
 * @property string $title
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class News extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'type' => NewsType::class,
        'seen_at' => 'date',
        'seen' => 'boolean'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
