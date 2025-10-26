<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view Roles')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $roles = Role::with('permissions')->get();
        return [
            'status' => 200,
            'roles' => RoleResource::collection($roles),
            'message' => __('text.roles_list')
        ];
    }

    public function show($id)
    {
        if (!auth()->user()->can('view Roles')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $role = Role::with('permissions')->findOrFail($id);
        return [
            'status' => 200,
            'role' => new RoleResource($role),
            'message' => __('text.role_details')
        ];
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('add Roles')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
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


        return [
            'status' => 201,
            'role' => new RoleResource($role->load('permissions')),
            'message' => __('text.save_role_btn'),
        ];
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit Roles')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return [
            'status' => 200,
            'role' => new RoleResource($role->load('permissions')),
            'message' => __('text.update_role_btn'),
        ];
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete Roles')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $role = Role::findOrFail($id);

        if (! $role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        DB::beginTransaction();
        try {
            // امسح العلاقات من الجداول الوسيطة
            DB::table('model_has_roles')->where('role_id', $id)->delete();
            DB::table('role_has_permissions')->where('role_id', $id)->delete();

            // امسح الرول نفسه
            DB::table('roles')->where('id', $id)->delete();

            DB::commit();

            return response()->json(['message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error deleting role',
                'error' => $e->getMessage()
            ], 500);
        }
        return response()->json([
            'status' => 200,
            'message' => __('text.delete_role_btn'),
        ]);
    }
}
