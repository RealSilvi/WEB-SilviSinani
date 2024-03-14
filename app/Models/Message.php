<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property string $type
 * @property string $body
 * @property int $seen
 * @property int $chat_id
 * @property int $sender_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Message extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<Chat,Message>
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * @return BelongsTo<ChatMember,Message>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(ChatMember::class);
    }
}
