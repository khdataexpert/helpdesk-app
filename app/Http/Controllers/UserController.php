<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين (GET /api/users)
     */
    public function index()
    {
        if (!auth()->user()->can('view users')) {
            return response()->json([
                'status' => 403,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $users = User::with(['roles', 'permissions', 'company'])->paginate(10);

        return response()->json([
            'status' => 200,
            'message' => __('text.users_management'),
            'data' => UserResource::collection($users),
        ]);
    }

    /**
     * عرض بيانات مستخدم واحد (GET /api/users/{id})
     */
    public function show(User $user)
    {
        if (!auth()->user()->can('view users')) {
            return response()->json([
                'status' => 403,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $user->load(['roles', 'permissions', 'company']);

        return response()->json([
            'status' => 200,
            'data' => new UserResource($user),
            'message' => __('text.user_details'),
        ]);
    }

    /**
     * إنشاء مستخدم جديد (POST /api/users)
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add users')) {
            return response()->json([
                'status' => 403,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'company_id' => $validated['company_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $role = Role::findByName($validated['role'], 'api');
        $user->assignRole($role);

        return response()->json([
            'status' => 200,
            'message' => __('text.user_created_success'),
            'data' => new UserResource($user),
        ]);
    }

    /**
     * تحديث بيانات المستخدم (PUT /api/users/{id})
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->can('edit users')) {
            return response()->json([
                'status' => 403,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->update([
            'company_id' => $validated['company_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $role = Role::findByName($validated['role'], 'api');
        $user->syncRoles($role);

        return response()->json([
            'status' => 200,
            'message' => __('text.user_updated_success'),
            'data' => new UserResource($user),
        ]);
    }

    /**
     * حذف المستخدم (DELETE /api/users/{id})
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->can('delete users')) {
            return response()->json([
                'status' => 403,
                'message' => __('text.permission_denied'),
            ], 403);
        }
        if (auth()->id() === $user->id) {
            return response()->json([
                'status' => false,
                'message' => __('text.cannot_delete_self'),
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => __('text.user_deleted_success'),
        ]);
    }

    /**
     * تحديث صلاحيات المستخدم (PUT /api/users/{id}/permissions)
     */
    public function updatePermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncPermissions($request->input('permissions', []));

        return response()->json([
            'status' => 200,
            'message' => __('text.permissions_updated_successfully'),
            'data' => new UserResource($user),
        ]);
    }
}
