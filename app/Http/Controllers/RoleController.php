<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        $roles = Role::orderBy('name')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
        ]);

        // Convert permissions from comma-separated string to array if needed
        if (is_string($request->permissions)) {
            $validated['permissions'] = array_map('trim', explode(',', $request->permissions));
        }

        Role::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dibuat.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role): View
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
        ]);

        // Convert permissions from comma-separated string to array if needed
        if (is_string($request->permissions)) {
            $validated['permissions'] = array_map('trim', explode(',', $request->permissions));
        }

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'superadmin') {
            return back()->with('error', 'Role superadmin tidak dapat dihapus.');
        }

        // Check if role is being used by users
        $userCount = \App\Models\User::where('role_id', $role->id)->count();
        if ($userCount > 0) {
            return back()->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
