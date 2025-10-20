<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('view Roles')) { {
                $roles = Role::with('permissions')->get();
                return view('Dashboard.Roles.index', compact('roles'));
            }
            abort(403);
        }
    }

    public function create()
    {
        if (!auth()->user()->can('add Roles')) abort(403);
        $permissions = Permission::all();
        return view('Dashboard.Roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array|required'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');
        return view('Dashboard.Roles.edit', compact('role', 'permissions'));
    }
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array|required'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
