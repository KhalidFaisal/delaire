<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('backend.pages.admin.index', compact('admins'));
    }

    public function create()
    {
        return view('backend.pages.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => 1, // Default active
        ]);

        return redirect()->route('admin.manage.index')->with('success', 'Admin created successfully.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('backend.pages.admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $id],
            'role' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->role = $request->role;
        $admin->phone = $request->phone;

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.manage.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->id === auth()->guard('admin')->id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }
        $admin->delete();

        return redirect()->route('admin.manage.index')->with('success', 'Admin deleted successfully.');
    }
}
