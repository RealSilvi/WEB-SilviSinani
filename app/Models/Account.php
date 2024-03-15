<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Account extends Model
{
    use HasFactory;

    protected $guarded=[];

    /**
     * @return BelongsTo<User>
     */
    public function user():BelongsTo
    {
       return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Chat,Account>
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class,'chat_account');
    }

    /**
     * @return HasMany<Message>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class,);
    }


}
