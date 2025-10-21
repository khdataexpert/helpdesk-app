<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(15);

        return PermissionResource::collection($permissions)
            ->additional(['status' => 200]);
    }

    /**
     * عرض صلاحية واحدة
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return (new PermissionResource($permission))
            ->additional(['status' => 200]);
    }

    /**
     * إنشاء صلاحية جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'nullable|string|default:api',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'api',
        ]);

        return (new PermissionResource($permission))
            ->additional([
                'status' => true,
                'message' => __('text.permission_created_success'),
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * تحديث صلاحية موجودة
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'guard_name' => 'nullable|string',
        ]);

        $permission->update([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? $permission->guard_name,
        ]);

        return (new PermissionResource($permission))
            ->additional([
                'status' => true,
                'message' => __('text.permission_updated_success'),
            ]);
    }

    /**
     * حذف صلاحية
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'status' => true,
            'message' => __('text.permission_deleted_success'),
        ]);
    }
}
