<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'image',
        'is_admin',
        'phone_number',
        'email',
        'email_verified_at',
        'password',
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

    /* protected static function booted()
    {
        // Before creating a new user
        static::creating(function ($user) {
            $user->name = $user->first_name . ' ' . $user->last_name;
        });

        // Before updating an existing user
        static::updating(function ($user) {
            $user->name = $user->first_name . ' ' . $user->last_name;
        });
    } */

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

    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setNameAttribute($value)
    {
        $parts = explode(' ', $value, 2);
        $this->first_name = $parts[0] ?? null;
        $this->last_name = $parts[1] ?? null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin && $this->hasVerifiedEmail();
    }
}
