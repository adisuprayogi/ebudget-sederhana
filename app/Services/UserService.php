<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Divisi;
use App\Models\PengajuanDana;
use App\Models\Approval;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Create new user
     */
    public static function createUser($data)
    {
        DB::beginTransaction();
        try {
            // Validate required fields
            if (!isset($data['email']) || !isset($data['full_name']) || !isset($data['role_id'])) {
                throw new \Exception('Email, full name, and role are required');
            }

            // Check if email already exists
            if (User::where('email', $data['email'])->exists()) {
                throw new \Exception('Email already exists');
            }

            // Generate username if not provided
            $username = $data['username'] ?? self::generateUsername($data['full_name']);

            // Check if username already exists
            if (User::where('username', $username)->exists()) {
                throw new \Exception('Username already exists');
            }

            // Create user
            $user = User::create([
                'full_name' => $data['full_name'],
                'username' => $username,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'role_id' => $data['role_id'],
                'divisi_id' => $data['divisi_id'] ?? null,
                'password' => Hash::make($data['password'] ?? 'password'),
                'is_active' => $data['is_active'] ?? true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign role
            $role = Role::find($data['role_id']);
            if ($role) {
                $user->roles()->attach($role->id);
            }

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update user
     */
    public static function updateUser(User $user, $data)
    {
        DB::beginTransaction();
        try {
            // Check email uniqueness if changed
            if (isset($data['email']) && $data['email'] !== $user->email) {
                if (User::where('email', $data['email'])->where('id', '!=', $user->id)->exists()) {
                    throw new \Exception('Email already exists');
                }
            }

            // Check username uniqueness if changed
            if (isset($data['username']) && $data['username'] !== $user->username) {
                if (User::where('username', $data['username'])->where('id', '!=', $user->id)->exists()) {
                    throw new \Exception('Username already exists');
                }
            }

            // Update user data
            $updateData = [
                'updated_at' => now(),
            ];

            if (isset($data['full_name'])) {
                $updateData['full_name'] = $data['full_name'];
            }

            if (isset($data['username'])) {
                $updateData['username'] = $data['username'];
            }

            if (isset($data['email'])) {
                $updateData['email'] = $data['email'];
            }

            if (isset($data['phone'])) {
                $updateData['phone'] = $data['phone'];
            }

            if (isset($data['role_id'])) {
                $updateData['role_id'] = $data['role_id'];

                // Update role assignment
                $user->roles()->sync([$data['role_id']]);
            }

            if (isset($data['divisi_id'])) {
                $updateData['divisi_id'] = $data['divisi_id'];
            }

            if (isset($data['is_active'])) {
                $updateData['is_active'] = $data['is_active'];
            }

            if (isset($data['password']) && !empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update user', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete user (soft delete)
     */
    public static function deleteUser(User $user)
    {
        DB::beginTransaction();
        try {
            // Check if user has related records
            $pengajuanCount = PengajuanDana::where('created_by', $user->id)->count();
            $approvalCount = Approval::where('approver_id', $user->id)->count();

            if ($pengajuanCount > 0 || $approvalCount > 0) {
                throw new \Exception('Cannot delete user with existing records');
            }

            // Deactivate user instead of deleting
            $user->update([
                'is_active' => false,
                'email' => 'deleted_' . time() . '_' . $user->email,
                'username' => 'deleted_' . time() . '_' . $user->username,
                'updated_at' => now(),
            ]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete user', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            throw $e;
        }
    }

    /**
     * Get user statistics
     */
    public static function getUserStatistics()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'by_role' => User::with('role')
                ->whereHas('role')
                ->get()
                ->groupBy('role.name')
                ->map->count(),
            'by_divisi' => User::with('divisi')
                ->whereHas('divisi')
                ->get()
                ->groupBy('divisi.nama_divisi')
                ->map->count(),
            'recent_users' => User::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'full_name', 'email', 'created_at']),
        ];
    }

    /**
     * Get users by role
     */
    public static function getUsersByRole($roleName)
    {
        return User::whereHas('role', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })
        ->where('is_active', true)
        ->orderBy('full_name')
        ->get();
    }

    /**
     * Get users by division
     */
    public static function getUsersByDivision($divisiId)
    {
        return User::where('divisi_id', $divisiId)
            ->where('is_active', true)
            ->with(['role', 'divisi'])
            ->orderBy('full_name')
            ->get();
    }

    /**
     * Get user options for dropdown
     */
    public static function getUserOptions($roleId = null, $divisiId = null)
    {
        $query = User::where('is_active', true)
            ->with(['role', 'divisi'])
            ->orderBy('full_name');

        if ($roleId) {
            $query->whereHas('role', function ($q) use ($roleId) {
                $q->where('id', $roleId);
            });
        }

        if ($divisiId) {
            $query->where('divisi_id', $divisiId);
        }

        return $query->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->full_name,
                'email' => $user->email,
                'role' => $user->role->name ?? null,
                'divisi' => $user->divisi->nama_divisi ?? null,
            ];
        });
    }

    /**
     * Get user activity summary
     */
    public static function getUserActivitySummary(User $user, $startDate = null, $endDate = null)
    {
        $queryPengajuan = PengajuanDana::where('created_by', $user->id);
        $queryApproval = Approval::where('approver_id', $user->id);

        if ($startDate) {
            $queryPengajuan->whereDate('created_at', '>=', $startDate);
            $queryApproval->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $queryPengajuan->whereDate('created_at', '<=', $endDate);
            $queryApproval->whereDate('created_at', '<=', $endDate);
        }

        return [
            'pengajuan_count' => $queryPengajuan->count(),
            'pengajuan_total' => $queryPengajuan->sum('total_pengajuan'),
            'approval_count' => $queryApproval->count(),
            'approval_processed' => $queryApproval->whereNotNull('approved_at')->count(),
            'avg_approval_time' => self::calculateAvgApprovalTime($queryApproval->whereNotNull('approved_at')->get()),
            'recent_pengajuan' => $queryPengajuan->orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_approvals' => $queryApproval->orderBy('created_at', 'desc')->limit(5)->get(),
        ];
    }

    /**
     * Calculate average approval time in hours
     */
    private static function calculateAvgApprovalTime($approvals)
    {
        if ($approvals->isEmpty()) {
            return 0;
        }

        $totalHours = $approvals->sum(function ($approval) {
            return $approval->created_at->diffInHours($approval->approved_at);
        });

        return $totalHours / $approvals->count();
    }

    /**
     * Generate username from full name
     */
    private static function generateUsername($fullName)
    {
        // Convert to lowercase and replace spaces with dots
        $username = strtolower(str_replace(' ', '.', $fullName));

        // Remove special characters
        $username = preg_replace('/[^a-z0-9.]/', '', $username);

        // Check if username exists, add number if needed
        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . '.' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Reset user password
     */
    public static function resetPassword(User $user, $newPassword)
    {
        try {
            $user->update([
                'password' => Hash::make($newPassword),
                'updated_at' => now(),
            ]);

            Log::info('Password reset for user', ['user_id' => $user->id]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to reset password', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            return false;
        }
    }

    /**
     * Toggle user active status
     */
    public static function toggleActiveStatus(User $user)
    {
        try {
            $newStatus = !$user->is_active;

            $user->update([
                'is_active' => $newStatus,
                'updated_at' => now(),
            ]);

            Log::info('User status toggled', [
                'user_id' => $user->id,
                'new_status' => $newStatus
            ]);

            return $newStatus;
        } catch (\Exception $e) {
            Log::error('Failed to toggle user status', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            throw $e;
        }
    }

    /**
     * Import users from CSV/Excel
     */
    public static function importUsers($data)
    {
        DB::beginTransaction();
        try {
            $imported = [];
            $errors = [];

            foreach ($data as $index => $row) {
                try {
                    $user = self::createUser([
                        'full_name' => $row['full_name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'] ?? null,
                        'role_id' => $row['role_id'],
                        'divisi_id' => $row['divisi_id'] ?? null,
                        'password' => $row['password'] ?? 'password',
                        'is_active' => $row['is_active'] ?? true,
                    ]);

                    $imported[] = [
                        'row' => $index + 1,
                        'user_id' => $user->id,
                        'full_name' => $user->full_name,
                    ];
                } catch (\Exception $e) {
                    $errors[] = [
                        'row' => $index + 1,
                        'error' => $e->getMessage(),
                        'data' => $row,
                    ];
                }
            }

            DB::commit();

            return [
                'imported' => $imported,
                'errors' => $errors,
                'total' => count($data),
                'success_count' => count($imported),
                'error_count' => count($errors),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to import users', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Export users data
     */
    public static function exportUsers($filters = [])
    {
        $query = User::with(['role', 'divisi']);

        if (isset($filters['role_id'])) {
            $query->where('role_id', $filters['role_id']);
        }

        if (isset($filters['divisi_id'])) {
            $query->where('divisi_id', $filters['divisi_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('full_name')->get();
    }

    /**
     * Get online users (users with recent activity)
     */
    public static function getOnlineUsers($minutes = 5)
    {
        return User::where('is_active', true)
            ->where('last_seen_at', '>=', now()->subMinutes($minutes))
            ->orderBy('last_seen_at', 'desc')
            ->get(['id', 'full_name', 'email', 'last_seen_at']);
    }

    /**
     * Update user last seen timestamp
     */
    public static function updateLastSeen(User $user)
    {
        try {
            $user->update([
                'last_seen_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update last seen', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
        }
    }

    /**
     * Send notification to user
     */
    public static function sendNotification(User $user, $title, $message, $type = 'info', $data = [])
    {
        try {
            return Notifications::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => json_encode($data),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            return false;
        }
    }
}