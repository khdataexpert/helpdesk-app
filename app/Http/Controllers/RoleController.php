<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return RoleResource::collection($roles);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return new RoleResource($role);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'api',
        ]);
        $role->syncPermissions($validated['permissions']);
        

        return (new RoleResource($role->load('permissions')))
            ->additional([
                'status' => true,
                'message' => __('text.save_role_btn'),
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return (new RoleResource($role->load('permissions')))
            ->additional([
                'status' => true,
                'message' => __('text.update_role_btn'),
            ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'status' => true,
            'message' => __('text.permission_delete Roles'),
        ]);
    }
}
