<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
        'username',
        'name',
        'email',
        'password',
        'full_name',
        'role_id',
        'divisi_id',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role associated with the user (legacy, for backward compatibility).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the divisi associated with the user (legacy, for backward compatibility).
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Get all roles for the user (with optional divisi filter).
     * Uses the unified user_divisi_role pivot table.
     */
    public function roles(?int $divisiId = null): BelongsToMany
    {
        $query = $this->belongsToMany(Role::class, 'user_divisi_role')
            ->withPivot('divisi_id', 'is_primary')
            ->withTimestamps();

        if ($divisiId !== null) {
            $query->wherePivot('divisi_id', $divisiId);
        }

        return $query;
    }

    /**
     * Get the primary role for the user.
     * Returns the role marked as primary or falls back to legacy role_id.
     */
    public function primaryRole()
    {
        $primaryEntry = DB::table('user_divisi_role')
            ->where('user_id', $this->id)
            ->where('is_primary', true)
            ->first();

        if ($primaryEntry) {
            return Role::find($primaryEntry->role_id);
        }

        // Fallback to legacy role_id
        return $this->role;
    }

    /**
     * Get all divisions for the user (with optional role filter).
     * Uses the unified user_divisi_role pivot table.
     */
    public function divisis(?int $roleId = null): BelongsToMany
    {
        $query = $this->belongsToMany(Divisi::class, 'user_divisi_role')
            ->withPivot('role_id', 'is_primary')
            ->withTimestamps();

        if ($roleId !== null) {
            $query->wherePivot('role_id', $roleId);
        }

        return $query;
    }

    /**
     * Get the primary division for the user.
     * Returns the division marked as primary or falls back to legacy divisi_id.
     */
    public function primaryDivision()
    {
        $primaryEntry = DB::table('user_divisi_role')
            ->where('user_id', $this->id)
            ->where('is_primary', true)
            ->first();

        if ($primaryEntry && $primaryEntry->divisi_id) {
            return Divisi::find($primaryEntry->divisi_id);
        }

        // Fallback to legacy divisi_id
        return $this->divisi;
    }

    /**
     * Get all division IDs the user has access to.
     */
    public function divisionIds()
    {
        // First try to get from new pivot table
        $pivotIds = DB::table('user_divisi_role')
            ->where('user_id', $this->id)
            ->whereNotNull('divisi_id')
            ->pluck('divisi_id')
            ->unique()
            ->toArray();

        if (!empty($pivotIds)) {
            return $pivotIds;
        }

        // Fallback to legacy divisi_id
        return $this->divisi_id ? [$this->divisi_id] : [];
    }

    /**
     * Get all role+division combinations for the user.
     * Returns collection of ['role_id', 'divisi_id', 'is_primary']
     */
    public function roleDivisiCombinations()
    {
        return DB::table('user_divisi_role')
            ->where('user_id', $this->id)
            ->get(['role_id', 'divisi_id', 'is_primary']);
    }

    /**
     * Check if user has specific role in a specific division.
     */
    public function hasRoleInDivisi($roleName, $divisiId)
    {
        return DB::table('user_divisi_role')
            ->join('roles', 'user_divisi_role.role_id', '=', 'roles.id')
            ->where('user_divisi_role.user_id', $this->id)
            ->where('user_divisi_role.divisi_id', $divisiId)
            ->where('roles.name', $roleName)
            ->exists();
    }

    /**
     * Get the pengajuan dana created by the user.
     */
    public function pengajuanDana()
    {
        return $this->hasMany(PengajuanDana::class, 'created_by');
    }

    /**
     * Get the approvals assigned to the user.
     */
    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approver_id');
    }

    /**
     * Check if user has specific role.
     * Checks both new pivot table and legacy role_id.
     */
    public function hasRole($roleName)
    {
        // Check new pivot table first
        $hasRoleInPivot = DB::table('user_divisi_role')
            ->join('roles', 'user_divisi_role.role_id', '=', 'roles.id')
            ->where('user_divisi_role.user_id', $this->id)
            ->where('roles.name', $roleName)
            ->exists();

        if ($hasRoleInPivot) {
            return true;
        }

        // Fallback to legacy role_id
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user has any of the specified roles.
     * Checks both new pivot table and legacy role_id.
     */
    public function hasAnyRole($roleNames)
    {
        if (is_string($roleNames)) {
            $roleNames = explode(',', $roleNames);
        }

        // Check new pivot table first
        $hasAnyInPivot = DB::table('user_divisi_role')
            ->join('roles', 'user_divisi_role.role_id', '=', 'roles.id')
            ->where('user_divisi_role.user_id', $this->id)
            ->whereIn('roles.name', $roleNames)
            ->exists();

        if ($hasAnyInPivot) {
            return true;
        }

        // Fallback to legacy role_id
        if (!$this->role) {
            return false;
        }

        return in_array($this->role->name, $roleNames);
    }

    /**
     * Check if user has specific permission.
     */
    public function hasPermission($permission)
    {
        // Superadmin has all permissions
        if ($this->hasRole('superadmin')) {
            return true;
        }

        // Check all user's roles for the permission
        $roles = DB::table('user_divisi_role')
            ->join('roles', 'user_divisi_role.role_id', '=', 'roles.id')
            ->where('user_divisi_role.user_id', $this->id)
            ->pluck('roles.permissions');

        foreach ($roles as $rolePermissions) {
            $permissions = json_decode($rolePermissions, true) ?? [];
            if (in_array('*', $permissions) || in_array($permission, $permissions)) {
                return true;
            }
        }

        // Fallback to legacy role
        if ($this->role) {
            $permissions = $this->role->permissions ?? [];
            if (in_array('*', $permissions) || in_array($permission, $permissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a role+division combination to the user.
     *
     * @param int $roleId The role ID
     * @param int|null $divisiId The division ID (null for general role without division)
     * @param bool $isPrimary Whether this is the primary combination
     * @return bool
     */
    public function addRoleDivisi($roleId, ?int $divisiId = null, bool $isPrimary = false)
    {
        // If setting as primary, remove primary flag from other combinations
        if ($isPrimary) {
            DB::table('user_divisi_role')
                ->where('user_id', $this->id)
                ->update(['is_primary' => false]);
        }

        return DB::table('user_divisi_role')->insert([
            'user_id' => $this->id,
            'divisi_id' => $divisiId,
            'role_id' => $roleId,
            'is_primary' => $isPrimary,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Remove a role+division combination from the user.
     */
    public function removeRoleDivisi($roleId, ?int $divisiId = null)
    {
        $query = DB::table('user_divisi_role')
            ->where('user_id', $this->id)
            ->where('role_id', $roleId);

        if ($divisiId !== null) {
            $query->where('divisi_id', $divisiId);
        } else {
            $query->whereNull('divisi_id');
        }

        return $query->delete();
    }

    /**
     * Remove all role+division combinations for a specific role.
     */
    public function removeRole($roleId)
    {
        return DB::table('user_divisi_role')
            ->where('user_id', $this->id)
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * Remove all role+division combinations for a specific division.
     */
    public function removeDivision($divisiId)
    {
        return DB::table('user_divisi_role')
            ->where('user_id', $this->id)
            ->where('divisi_id', $divisiId)
            ->delete();
    }

    /**
     * Sync role+division combinations for the user.
     *
     * @param array $combinations Array of ['role_id' => int, 'divisi_id' => int|null]
     * @param array|null $primaryCombination The primary combination ['role_id' => int, 'divisi_id' => int|null]
     * @return void
     */
    public function syncRoleDivisi(array $combinations, ?array $primaryCombination = null)
    {
        // Clear existing combinations
        DB::table('user_divisi_role')->where('user_id', $this->id)->delete();

        // Insert new combinations
        foreach ($combinations as $combination) {
            $roleId = $combination['role_id'];
            $divisiId = $combination['divisi_id'] ?? null;
            $isPrimary = false;

            // Check if this is the primary combination
            if ($primaryCombination) {
                $isPrimary = $primaryCombination['role_id'] == $roleId
                    && ($primaryCombination['divisi_id'] ?? null) == $divisiId;
            }

            DB::table('user_divisi_role')->insert([
                'user_id' => $this->id,
                'divisi_id' => $divisiId,
                'role_id' => $roleId,
                'is_primary' => $isPrimary,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Legacy method - kept for backward compatibility.
     * Adds a role to the user (without specific division).
     */
    public function addRole($roleId, bool $isPrimary = false)
    {
        return $this->addRoleDivisi($roleId, null, $isPrimary);
    }

    /**
     * Legacy method - kept for backward compatibility.
     * Syncs roles for the user (without specific divisions).
     */
    public function syncRoles(array $roleIds, ?int $primaryRoleId = null)
    {
        $combinations = array_map(fn($id) => ['role_id' => $id, 'divisi_id' => null], $roleIds);
        $primary = $primaryRoleId ? ['role_id' => $primaryRoleId, 'divisi_id' => null] : null;
        $this->syncRoleDivisi($combinations, $primary);
    }

    /**
     * Legacy method - kept for backward compatibility.
     * Adds a division to the user (without specific role).
     * Note: This requires getting the user's current role_id.
     */
    public function addDivision($divisiId, bool $isPrimary = false)
    {
        // Try to use user's current role_id from users table
        $roleId = $this->role_id;

        if (!$roleId) {
            throw new \Exception('Cannot add division without a role. Use addRoleDivisi() instead.');
        }

        return $this->addRoleDivisi($roleId, $divisiId, $isPrimary);
    }

    /**
     * Legacy method - kept for backward compatibility.
     * Syncs divisions for the user (without specific roles).
     */
    public function syncDivisions(array $divisiIds, ?int $primaryDivisiId = null)
    {
        // Try to use user's current role_id from users table
        $roleId = $this->role_id;

        if (!$roleId) {
            throw new \Exception('Cannot sync divisions without a role. Use syncRoleDivisi() instead.');
        }

        $combinations = array_map(fn($id) => ['role_id' => $roleId, 'divisi_id' => $id], $divisiIds);
        $primary = $primaryDivisiId ? ['role_id' => $roleId, 'divisi_id' => $primaryDivisiId] : null;
        $this->syncRoleDivisi($combinations, $primary);
    }
}
