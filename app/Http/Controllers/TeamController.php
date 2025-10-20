<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * عرض قائمة بجميع الفرق (Teams).
     */
    public function index()
    {
        if (auth()->user()->can('view teams')) {
            $teams = Team::withCount('users')->get();
            return view('Dashboard.teams.index', compact('teams'));
        }
        abort(403, 'Unauthorized action.');
    }

    public function show(Team $team)
    {
        if (auth()->user()->can('view teams')) {
            $team->load('users');
            return view('Dashboard.teams.show', compact('team'));
        }
        abort(403, 'Unauthorized action.');
    }
    /**
     * عرض نموذج إنشاء فريق جديد.
     */
    public function create()
    {
        if (auth()->user()->can('add teams')) {
            $users = User::role('Agent')->orderBy('name')->get();
            return view('Dashboard.teams.create', compact('users')); 
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * حفظ بيانات الفريق الجديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'Specialization' => 'nullable|string|max:255',
            // 💡 إضافة validation لقائد الفريق
            'user_id' => 'required|exists:users,id', // 'required' لأنه حقل أساسي الآن
        ]);

        Team::create($validated);

        // استخدام مفتاح ترجمة لرسالة النجاح
        return redirect()->route('teams.index')
            ->with('success', __('text.created_success'));
    }

    /**
     * عرض واجهة تعديل أعضاء فريق معين.
     */
    public function editMembers(Team $team)
    {
        if (auth()->user()->can('edit teams')) {
            $users = User::role('Agent')->orderBy('name')->get();
    
            // توحيد مسار الـ View
            return view('Dashboard.teams.edit_members', [
                'team' => $team,
                'users' => $users,
                'currentMembers' => $team->users->pluck('id')->toArray(),
            ]);
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * حفظ التغييرات على أعضاء الفريق.
     */
    public function updateMembers(Request $request, Team $team)
    {
        $validated = $request->validate([
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team->users()->sync($validated['members'] ?? []);

        // استخدام مفتاح ترجمة لرسالة النجاح
        return redirect()->route('teams.index')
            ->with('success', __('text.members_updated_success'));
    }
}
