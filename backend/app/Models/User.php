<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Order;

use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Cart;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Addresse::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
        'last_login'
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
        ];
    }

    //check roles

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    //  PERMISSIONS
    public function hasPermission(string $permission): bool
    {
        [$module, $action] = explode('.', $permission);

        $permissions = config('permissions')[$this->role] ?? [];

        return isset($permissions[$module]) &&
            in_array($action, $permissions[$module]);
    }
}
