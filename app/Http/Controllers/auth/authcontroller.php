<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class authcontroller extends Controller
{
     /**
     * 🔹 تسجيل الدخول (Login)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => [__('text.login_failed')],
            ]);
        }

        $user = Auth::user();

        // إنشاء Token جديد (باستخدام Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => __('text.welcome_title') . ', ' . $user->name,
            'token' => $token,
            'user' => new UserResource($user->load('company', 'roles')),
        ]);
    }

    /**
     * 🔹 تسجيل الخروج (Logout)
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // حذف الـ token الحالي فقط
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => 200,
            'message' => __('text.logout_success'),
        ]);
    }

    /**
     * 🔹 بيانات المستخدم الحالي (Me)
     */
    public function me()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 200,
            'user' => new UserResource($user->load('company', 'roles')),
            'message' => __('text.welcome_title') . ', ' . $user->name,
        ]);
    }
}
