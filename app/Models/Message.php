<?php

namespace App\Models;

use App\Enums\MessageType;
use App\Http\Resources\MessageResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property MessageType $type
 * @property string $body
 * @property boolean $seen
 * @property boolean $deleted
 * @property int $chat_id
 * @property int $sender_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Message extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts = [
        'type' => MessageType::class,
        'seen' => 'boolean'
    ];

    /**
     * @return BelongsTo<Chat,Message>
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * @return BelongsTo<Account,Message>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Account::class,'sender_id');
    }
}
