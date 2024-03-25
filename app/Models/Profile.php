<?php

namespace App\Models;

use App\Enum\ProfileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'type' => ProfileType::class,
    ];

    /**
     * @return BelongsTo<User,Profile>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
