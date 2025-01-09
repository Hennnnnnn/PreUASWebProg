<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $table = 'avatar';
    protected $fillable = ['image', 'price'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'pivot_avatar');
    }
}
