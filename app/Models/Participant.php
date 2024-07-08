<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_message_id',
        'seen_message_id',
        'pinned_at',
        'created_at',
        'updated_at',
    ];

    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'id', 'conversation_id');
    }

    public function messageSeen()
    {
        return $this->hasOne(Message::class, 'id', 'seen_message_id');
    }

    public function messageLast()
    {
        return $this->hasOne(Message::class, 'id', 'last_message_id');
    }

}
