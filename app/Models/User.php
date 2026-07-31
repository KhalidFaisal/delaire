<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** @var string Table name for registration and login. */
    protected $table = 'user_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'address',
        'shipping_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserOrder::class, 'user_id');
    }

    public function returns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserReturn::class, 'user_id');
    }

    public function wishlists(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserWishlist::class, 'user_id');
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserReview::class, 'user_id');
    }

    /**
     * Get the resolved URL for the user's avatar.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar || trim($this->avatar) === '' || strtolower(trim($this->avatar)) === 'null') {
            return asset('main_view/assets/img/user.png');
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            try {
                $size = \Illuminate\Support\Facades\Storage::disk('public')->size($this->avatar);
                if ($size > 0) {
                    return asset('storage/' . $this->avatar);
                }
            } catch (\Exception $e) {
                // If there's an error getting file size, fall back to default
            }
        }

        return asset('main_view/assets/img/user.png');
    }
}
