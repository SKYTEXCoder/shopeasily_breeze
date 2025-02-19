<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'phone_number',
        'password',
        'is_admin',
        'image',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    protected static function booted()
    {
        // Before creating a new user
        static::creating(function ($user) {
            if (empty($user->first_name) && empty($user->last_name)) {
                $parts = explode(' ', $user->name, 2);
                $user->first_name = $parts[0] ?? null;
                $user->last_name = $parts[1] ?? null;
            }
        });

        // Before updating an existing user
        /*static::updating(function ($user) {
            $user->name = $user->first_name . ' ' . $user->last_name;
        });*/
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function reviews() {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }

    public function comments() {
        return $this->hasMany(ProductComment::class, 'user_id');
    }

    public function cart() {
        return $this->hasMany(Cart::class);
    }

    public function shipping_information() {
        return $this->hasOne(ShippingInformation::class);
    }

    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }

    /* public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['first_name']) && empty($this->attributes['last_name'])) {
            $parts = explode(' ', $value, 2);
            $this->attributes['first_name'] = $parts[0] ?? null;
            $this->attributes['last_name'] = $parts[1] ?? null;
        }
    } */

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin && $this->hasVerifiedEmail();
    }
}
