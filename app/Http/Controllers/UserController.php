<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Divisi;
use App\Models\JobPosition;
use App\Models\UserJobPosition;
use App\Services\UserService;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermission('user.view')) {
            abort(403);
        }

        $query = User::with(['role', 'divisi']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->orderBy('full_name')
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get filter options
        $roles = Role::orderBy('name')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role_id', 'divisi_id', 'is_active']),
            'filterOptions' => [
                'roles' => $roles,
                'divisis' => $divisis,
            ],
            'permissions' => [
                'create' => $user->hasPermission('user.create'),
                'edit' => $user->hasPermission('user.edit'),
                'delete' => $user->hasPermission('user.delete'),
                'reset_password' => $user->hasPermission('user.reset_password'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->hasPermission('user.create')) {
            abort(403);
        }

        $roles = Role::orderBy('name')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('admin.users.create', [
            'roles' => $roles,
            'divisis' => $divisis,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('user.create')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role_divisi' => 'required|array|min:1',
            'role_divisi.*.role_id' => 'required|exists:roles,id',
            'role_divisi.*.divisi_id' => 'nullable|exists:divisis,id',
            'primary_index' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $userData = $request->only([
                'name', 'full_name', 'username', 'email', 'phone', 'is_active'
            ]);
            $userData['password'] = Hash::make($request->password);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $path = $file->store('avatars', 'public');
                $userData['avatar'] = $path;
            }

            // Set primary role_id and divisi_id for legacy fields
            $primaryCombo = $request->role_divisi[$request->primary_index] ?? null;
            if ($primaryCombo) {
                $userData['role_id'] = $primaryCombo['role_id'];
                $userData['divisi_id'] = $primaryCombo['divisi_id'] ?? null;
            }

            $user = User::create($userData);

            // Add role+divisi combinations
            foreach ($request->role_divisi as $index => $combo) {
                $user->addRoleDivisi(
                    $combo['role_id'],
                    $combo['divisi_id'] ?? null,
                    $index == $request->primary_index
                );
            }

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create user: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat user. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->hasPermission('user.view')) {
            abort(403);
        }

        $user->load(['role', 'divisi', 'pengajuanDana', 'approvals']);

        // Get user activity summary
        $activitySummary = UserService::getUserActivitySummary($user);

        return view('admin.users.show', [
            'user' => $user,
            'activitySummary' => $activitySummary,
            'permissions' => [
                'edit' => $currentUser->hasPermission('user.edit'),
                'delete' => $currentUser->hasPermission('user.delete'),
                'reset_password' => $currentUser->hasPermission('user.reset_password'),
                'toggle_active' => $currentUser->hasPermission('user.toggle_active'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!Auth::user()->hasPermission('user.edit')) {
            abort(403);
        }

        $roles = Role::orderBy('name')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'divisis' => $divisis,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'role_divisi' => 'required|array|min:1',
            'role_divisi.*.role_id' => 'required|exists:roles,id',
            'role_divisi.*.divisi_id' => 'nullable|exists:divisis,id',
            'primary_index' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->name,
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'is_active' => $request->has('is_active') ? true : false,
            ];

            // Set primary role_id and divisi_id for legacy fields
            $primaryCombo = $request->role_divisi[$request->primary_index] ?? null;
            if ($primaryCombo) {
                $userData['role_id'] = $primaryCombo['role_id'];
                $userData['divisi_id'] = $primaryCombo['divisi_id'] ?? null;
            }

            $user->update($userData);

            // Clear existing role+divisi combinations
            DB::table('user_divisi_role')->where('user_id', $user->id)->delete();

            // Add new role+divisi combinations
            foreach ($request->role_divisi as $index => $combo) {
                $user->addRoleDivisi(
                    $combo['role_id'],
                    $combo['divisi_id'] ?? null,
                    $index == $request->primary_index
                );
            }

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update user: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui user. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!Auth::user()->hasPermission('user.delete')) {
            abort(403);
        }

        // Prevent deletion of own account
        if ($user->id === Auth::id()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        try {
            UserService::deleteUser($user);

            return redirect()
                ->route('users.index')
                ->with('success', 'User berhasil dihapus');

        } catch (\Exception $e) {
            \Log::error('Failed to delete user: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus user. ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        if (!Auth::user()->hasPermission('user.toggle_active')) {
            abort(403);
        }

        // Prevent deactivation of own account
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah status akun sendiri'
            ], 403);
        }

        try {
            $newStatus = UserService::toggleActiveStatus($user);

            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => $newStatus ? 'User berhasil diaktifkan' : 'User berhasil dinonaktifkan'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to toggle user status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status user'
            ], 500);
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        if (!Auth::user()->hasPermission('user.reset_password')) {
            abort(403);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            UserService::resetPassword($user, $request->password);

            return redirect()
                ->route('users.index')
                ->with('success', 'Password user berhasil direset');

        } catch (\Exception $e) {
            \Log::error('Failed to reset password: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal reset password. Silakan coba lagi.');
        }
    }

    /**
     * Show user profile.
     */
    public function profile()
    {
        $user = Auth::user();

        $user->load(['role', 'divisi']);

        return view('admin.users.profile', [
            'user' => $user,
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Verify current password if changing password
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()
                        ->back()
                        ->with('error', 'Password saat ini tidak sesuai');
                }

                $user->password = Hash::make($request->password);
            }

            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $file = $request->file('avatar');
                $path = $file->store('avatars', 'public');
                $user->avatar = $path;
            }

            $user->save();

            return redirect()
                ->route('profile')
                ->with('success', 'Profil berhasil diperbarui');

        } catch (\Exception $e) {
            \Log::error('Failed to update profile: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }

    /**
     * Get user statistics.
     */
    public function statistics()
    {
        if (!Auth::user()->hasPermission('user.view')) {
            abort(403);
        }

        $stats = UserService::getUserStatistics();

        return response()->json($stats);
    }

    /**
     * Get user options for dropdown.
     */
    public function options(Request $request)
    {
        $roleId = $request->role_id;
        $divisiId = $request->divisi_id;

        $options = UserService::getUserOptions($roleId, $divisiId);

        return response()->json($options);
    }

    /**
     * Import users from CSV/Excel.
     */
    public function import(Request $request)
    {
        if (!Auth::user()->hasPermission('user.import')) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);

        try {
            $file = $request->file('file');
            $data = [];

            // Parse file based on type
            if ($file->getClientOriginalExtension() === 'csv') {
                $data = $this->parseCsv($file);
            } else {
                // Excel parsing would go here
                return redirect()
                    ->back()
                    ->with('error', 'Excel import coming soon');
            }

            $result = UserService::importUsers($data);

            return redirect()
                ->back()
                ->with('success', "Import selesai. {$result['success_count']} user berhasil diimport, {$result['error_count']} gagal");

        } catch (\Exception $e) {
            \Log::error('Failed to import users: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal import user. ' . $e->getMessage());
        }
    }

    /**
     * Parse CSV file.
     */
    private function parseCsv($file)
    {
        $data = [];
        $headers = [];
        $rowIndex = 0;

        if (($handle = fopen($file->getPathname(), 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if ($rowIndex === 0) {
                    $headers = $row;
                } else {
                    $data[] = array_combine($headers, $row);
                }
                $rowIndex++;
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Export users to Excel.
     */
    public function export(Request $request)
    {
        if (!Auth::user()->hasPermission('user.export')) {
            abort(403);
        }

        try {
            $filters = $request->only(['role_id', 'divisi_id', 'is_active', 'search']);
            $users = UserService::exportUsers($filters);

            $filename = 'users-' . date('Y-m-d') . '.xlsx';

            return Excel::download(new \App\Exports\UsersExport($users), $filename);

        } catch (\Exception $e) {
            \Log::error('Failed to export users: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal export user. ' . $e->getMessage());
        }
    }

    /**
     * Display job positions for the user.
     */
    public function jobPositions(User $user)
    {
        if (!Auth::user()->hasPermission('user.view')) {
            abort(403);
        }

        $userJobPositions = $user->jobPositions()
            ->with('jobPosition.divisi')
            ->orderBy('is_primary', 'desc')
            ->orderBy('assigned_at', 'desc')
            ->get();

        $allJobPositions = JobPosition::active()
            ->with('divisi')
            ->orderBy('divisi_id')
            ->orderBy('level')
            ->get()
            ->groupBy('divisi.nama_divisi');

        return view('admin.users.job-positions', [
            'user' => $user,
            'userJobPositions' => $userJobPositions,
            'allJobPositions' => $allJobPositions,
        ]);
    }

    /**
     * Assign job position to user.
     */
    public function assignJobPosition(Request $request, User $user)
    {
        if (!Auth::user()->hasPermission('user.edit')) {
            abort(403);
        }

        $request->validate([
            'job_position_id' => 'required|exists:job_positions,id',
            'is_primary' => 'nullable|boolean',
            'assigned_at' => 'nullable|date',
            'catatan' => 'nullable|string|max:500',
        ]);

        $jobPosition = JobPosition::findOrFail($request->job_position_id);

        // If setting as primary, unmark existing primary
        if ($request->is_primary) {
            UserJobPosition::where('user_id', $user->id)
                ->where('is_primary', true)
                ->whereNull('ended_at')
                ->update(['is_primary' => false]);
        }

        // Check if user already has this job position active
        $existing = UserJobPosition::where('user_id', $user->id)
            ->where('job_position_id', $request->job_position_id)
            ->whereNull('ended_at')
            ->first();

        if ($existing) {
            return back()->with('error', 'User sudah memiliki jabatan ini.');
        }

        UserJobPosition::create([
            'user_id' => $user->id,
            'job_position_id' => $request->job_position_id,
            'is_primary' => $request->is_primary ?? false,
            'assigned_at' => $request->assigned_at ?? now(),
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route('users.job-positions', $user)
            ->with('success', 'Jabatan berhasil ditambahkan ke user.');
    }

    /**
     * Remove job position from user.
     */
    public function removeJobPosition(User $user, UserJobPosition $userJobPosition)
    {
        if (!Auth::user()->hasPermission('user.edit')) {
            abort(403);
        }

        if ($userJobPosition->user_id !== $user->id) {
            abort(404);
        }

        // Set ended_at instead of deleting
        $userJobPosition->update([
            'ended_at' => now(),
            'is_primary' => false,
        ]);

        return redirect()
            ->route('users.job-positions', $user)
            ->with('success', 'Jabatan berhasil dihapus dari user.');
    }

    /**
     * Set primary job position for user.
     */
    public function setPrimaryJobPosition(User $user, UserJobPosition $userJobPosition)
    {
        if (!Auth::user()->hasPermission('user.edit')) {
            abort(403);
        }

        if ($userJobPosition->user_id !== $user->id) {
            abort(404);
        }

        // Unmark all existing primary
        UserJobPosition::where('user_id', $user->id)
            ->where('is_primary', true)
            ->whereNull('ended_at')
            ->update(['is_primary' => false]);

        // Set new primary
        $userJobPosition->update(['is_primary' => true]);

        return back()->with('success', 'Jabatan utama berhasil diubah.');
    }
}