<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('manage permissions')) {
            return response()->json([
                'status' => false,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $permissions = Permission::where('guard_name', 'api')->get();

        return [
            'permissions' => PermissionResource::collection($permissions),
            'status' => 200,
            'message' => __('text.permissions_list_retrieved_successfully'),
        ];
    }

    /**
     * عرض صلاحية واحدة
     */
    public function show($id)
    {
        if (!auth()->user()->can('manage permissions')) {
            return response()->json([
                'status' => false,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $permission = Permission::findOrFail($id);
        return [
            'permission' => new PermissionResource($permission),
            'status' => 200,
            'message' => __('text.permission_details_retrieved_successfully'),
        ];
    }

    /**
     * إنشاء صلاحية جديدة
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('manage permissions')) {
            return response()->json([
                'status' => false,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'nullable|string',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'api',
        ]);

        return[
            'permission' => new PermissionResource($permission),
            'status' => 201,
            'message' => __('text.permission_created_success'),
        ];
    }

    /**
     * تحديث صلاحية موجودة
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('manage permissions')) {
            return response()->json([
                'status' => false,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'guard_name' => 'nullable|string',
        ]);

        $permission->update([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? $permission->guard_name,
        ]);

        return [
            'permission' => new PermissionResource($permission),
            'status' => 200,
            'message' => __('text.permission_updated_success'),
        ];
    }

}
