<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'hobby',
        'instagram',
        'phone_number',
        'friendship_reason',
        'regist_price',
        'coin',
        'image',
        'visibility'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hobby' => "array"
        ];
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // In the User model (App\Models\User.php)

    public function sentRequests()
    {
        return $this->hasMany(Friend::class, 'user_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id');
    }
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted');
    }

    public function friendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', 'pending');
    }

    public function avatars()
    {
        return $this->belongsToMany(Avatar::class, 'pivot_avatar');
    }

    public function sentAvatars()
    {
        return $this->hasMany(AvatarSend::class, 'sender_id');
    }

    public function receivedAvatars()
    {
        return $this->hasMany(AvatarSend::class, 'receiver_id');
    }

}
