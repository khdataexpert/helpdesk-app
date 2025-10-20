<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين (READ)
     */
    public function index()
    {

    if (auth()->user()->can('view users')||auth()->user()->can('add users')) {
        $users = User::with('roles')->paginate(10);
        return view('Dashboard.Users.index', compact('users'));
    }
    abort(403, 'Unauthorized action.');
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد (CREATE)
     */
    public function create()
    {
        if(auth()->user()->can('add users')){
            $roles = Role::pluck('name', 'name');
            return view('Dashboard.Users.create', compact('roles'));
        }
        abort(403, 'Unauthorized action.');
    }
    public function show(User $user)
    {
        if (auth()->user()->can('view users')) {
            $user->load('roles.permissions');
           return view('Dashboard.Users.show', compact('user'));
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * تخزين مستخدم جديد (CREATE - Store)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // تعيين الدور
        $user->assignRole($validated['role']);

        // 💡 استخدام مفتاح ترجمة
        return redirect()->route('users.index')->with('success', __('text.user_created_success'));
    }


    /**
     * عرض نموذج تعديل المستخدم (EDIT)
     */
    public function edit(User $user)
    {
        if (auth()->user()->can('edit users')) {
            $roles = Role::pluck('name', 'name');
            return view('Dashboard.Users.edit', compact('user', 'roles'));
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * تحديث بيانات المستخدم (UPDATE)
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // مزامنة الدور (يزيل القديم ويعين الجديد)
        $user->syncRoles($validated['role']);

        // 💡 استخدام مفتاح ترجمة
        return redirect()->route('users.index')->with('success', __('text.user_updated_success'));
    }
    public function permissions($id)
    {
        if(auth()->user()->can('manage permissions')){
            $user = User::findOrFail($id);
            $permissions = Permission::all();
    
            return view('Dashboard.users.permissions', compact('user', 'permissions'));
        }
        abort(403, 'Unauthorized action.');
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('users.index')->with('success', __('text.permissions_updated_successfully'));
    }
    /**
     * حذف المستخدم (DELETE)
     */
    public function destroy(User $user)
    {
        // 💡 تصحيح الطريقة: يجب استخدام auth()->user()->id
        if (auth()->user()->id === $user->id) {
            // 💡 استخدام مفتاح ترجمة
            return redirect()->route('users.index')->with('error', __('text.cannot_delete_self'));
        }

        $user->delete();
        // 💡 استخدام مفتاح ترجمة
        return redirect()->route('users.index')->with('success', __('text.user_deleted_success'));
    }
}
