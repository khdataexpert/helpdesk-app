<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;

class TeamController extends Controller
{
    /**
     * عرض كل الفرق
     */
    public function index()
    {
        $teams = Team::with(['lead', 'users'])->latest()->get();
        return TeamResource::collection($teams);
    }

    /**
     * إنشاء فريق جديد
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'Specialization' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $team = Team::create($validated);

        return new TeamResource($team->load(['lead', 'users']));
    }

    /**
     * عرض فريق واحد
     */
    public function show(Team $team)
    {

        $team->load(['lead', 'users']);
        return new TeamResource($team);
    }

    /**
     * تحديث بيانات الفريق
     */
    public function update(Request $request, Team $team)
    {

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:teams,name,' . $team->id,
            'Specialization' => 'nullable|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $team->update($validated);

        return new TeamResource($team->load(['lead', 'users']));
    }

    /**
     * حذف فريق
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }

    /**
     * عرض أعضاء الفريق
     */
    public function members(Team $team)
    {
        $members = $team->users()->orderBy('name')->get();
        return UserResource::collection($members);
    }

    /**
     * تحديث أعضاء الفريق
     */
    public function updateMembers(Request $request, Team $team)
    {
        $validated = $request->validate([
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team->users()->sync($validated['members'] ?? []);

        return response()->json([
            'message' => 'Team members updated successfully',
            'team' => new TeamResource($team->load('users'))
        ]);
    }
}
