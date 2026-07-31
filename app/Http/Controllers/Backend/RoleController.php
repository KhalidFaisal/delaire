<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('backend.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Role::PERMISSION_GROUPS;
        return view('backend.pages.roles.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $slug = Str::slug($request->name, '_');

        Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'permissions' => $request->permissions ?? [],
        ]);

        // Log action
        \App\Models\AdminLog::log('Role Created', "Admin '" . auth('admin')->user()->name . "' created role '{$request->name}'.");

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent editing Super Admin to avoid lockouts
        if ($role->slug === 'super_admin') {
            return redirect()->route('admin.roles.index')->with('error', 'The Super Admin role permissions cannot be modified.');
        }

        $groups = Role::PERMISSION_GROUPS;
        return view('backend.pages.roles.edit', compact('role', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->slug === 'super_admin') {
            return redirect()->route('admin.roles.index')->with('error', 'The Super Admin role permissions cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        $role->name = $request->name;
        // Keep slug unchanged to avoid breaking existing users with this role
        $role->permissions = $request->permissions ?? [];
        $role->save();

        // Log action
        \App\Models\AdminLog::log('Role Updated', "Admin '" . auth('admin')->user()->name . "' updated permissions for role '{$role->name}'.");

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->slug, ['super_admin', 'admin', 'manager', 'editor'])) {
            return redirect()->route('admin.roles.index')->with('error', 'Default system roles cannot be deleted.');
        }

        // Check if any admin has this role assigned
        $adminsWithRole = \App\Models\Admin::where('role', $role->slug)->count();
        if ($adminsWithRole > 0) {
            return redirect()->route('admin.roles.index')->with('error', "Cannot delete role because it is currently assigned to {$adminsWithRole} admin(s).");
        }

        $role->delete();

        // Log action
        \App\Models\AdminLog::log('Role Deleted', "Admin '" . auth('admin')->user()->name . "' deleted role '{$role->name}'.");

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
