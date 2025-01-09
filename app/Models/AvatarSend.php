<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvatarSend extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'avatar_id'];

    // Define relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function avatar()
    {
        return $this->belongsTo(Avatar::class, 'avatar_id');
    }
}
