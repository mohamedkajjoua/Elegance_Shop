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
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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
            'last_login' => 'datetime',
            // 'is_active' => 'boolean',
        ];
    }

    //get full name
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }



    /**
     * check roles
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * check one of the multiple roles
     *
     * @param array<string> $roles
     * @return bool
     */

    public function hasAnyRoles(array $role): bool
    {
        return in_array($this->role, $role, true);
    }

    /**
     * Check all roles
     *
     * @param array<string> $roles
     * @return bool
     */


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }
    /**
     * get role
     */

    public function getRole(): string
    {
        return $this->role;
    }

    //  PERMISSIONS
    public function hasPermission(string $permission): bool
    {
        try {

            [$module, $action] = explode('.', $permission, 2);
        } catch (\Exception $se) {
            return false;
        }
        $permission = config('permissions')[$this->role] ?? [];

        return isset($permission[$module]) && in_array($action, $permission[$module], true);
    }

    /**
     * Check at least onr of the permissions
     *
     * @param array<string> $permissions
     * @return bool
     */

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check all permissions
     *
     * @param array<string> $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Gaining full user privileges
     *
     * @return array<string>
     */
    public function getAllPermissions(): array
    {
        $config = config('permissions');

        $rolePermissions = $config[$this->role] ?? [];
        $flattened = [];

        /**
         * ex:
         * ['module' => ['action 1','action 2']]
         * to
         * ['module.action 1','module.action 2']
         *
         */
        foreach ($rolePermissions as $module => $actions) {
            if (is_array($actions)) {
                foreach ($actions as $action) {
                    $flattened[] = "{$module}.{$action}";
                }
            }
        }

        return $flattened;
    }
    /**
     * Obtaining permissions is organized by module.
     *
     * @return array<string, array<string>>
     */
    public function getPermissionsByModule(): array
    {
        $config = config('permissions');
        return $config[$this->role] ?? [];
    }

    /**
     * Checking that the user does not have permission
     *
     * @param string $permission
     * @return bool
     */
    public function isNotAllowedTo(string $permission): bool
    {
        return !$this->hasPermission($permission);
    }

    /**
     * Gaining permissions for a specific module
     *
     * @param string $module
     * @return array<string>
     */
    public function getModulePermissions(string $module): array
    {
        $config = config('permissions');
        $rolePermissions = $config[$this->role] ?? [];

        return $rolePermissions[$module] ?? [];
    }

    //---------------------------------------------

    //JWT

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role,
            'full_name' => $this->full_name,
            'permissions' => $this->getAllPermissions(),
            'permission_by_module' => $this->getPermissionsByModule()
        ];
    }

    //--------------------




    // change role

    public function changeRole(string $role): void
    {
        $this->update(['role' => $role]);
    }

    // user Debugging

    public function __toString(): string
    {
        return "{$this->full_name} ({$this->role})";
    }
}
