<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public const TYPE_PRIVATE = 1;
    public const TYPE_GROUP = 2;

    protected $fillable = [
        'name',
        'creator_id',
        'type',
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, Participant::class, 'conversation_id', 'user_id')->withTimestamps();
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'conversation_id');
    }

}
