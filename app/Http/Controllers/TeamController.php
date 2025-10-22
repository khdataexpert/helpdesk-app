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
        if (!auth()->user()->can('view teams')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $teams = Team::with(['lead', 'users'])->latest()->get();
        return [
            'status' => 200,
            'teams' => TeamResource::collection($teams),
            'message' => __('text.teams_list')
        ];
    }

    /**
     * إنشاء فريق جديد
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add teams')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'Specialization' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $team = Team::create($validated);

        return [
            'status' => 201,
            'message' => __('text.team_created_success'),
            'team' => new TeamResource($team->load(['lead', 'users']))
        ];
    }

    /**
     * عرض فريق واحد
     */
    public function show(Team $team)
    {
        if (!auth()->user()->can('view teams')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $team->load(['lead', 'users']);
        return [
            'status' => 200,
            'team' => new TeamResource($team),
            'message' => __('text.team_details')
        ];
    }

    /**
     * تحديث بيانات الفريق
     */
    public function update(Request $request, Team $team)
    {
        if (!auth()->user()->can('edit teams')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:teams,name,' . $team->id,
            'Specialization' => 'nullable|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $team->update($validated);

        return [
            'status' => 200,
            'team' => new TeamResource($team->load(['lead', 'users'])),
            'message' => __('text.team_updated_success')
        ];
    }

    /**
     * حذف فريق
     */
    public function destroy(Team $team)
    {
        if (!auth()->user()->can('delete teams')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $team->delete();

        return response()->json([
            'message' => __('text.team_deleted_success'),
            'status' => 200
        ]);
    }

    public function members(Team $team)
    {
        if (!auth()->user()->can('view teams')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $team->load('users');

        return response()->json([
            'message' => __('text.team_details'),
            'members' => UserResource::collection($team->users),
            'status' => 200,
        ]);
    }


    public function updateMembers(Request $request, Team $team)
    {
        $validated = $request->validate([
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team->users()->sync($validated['members'] ?? []);

        return response()->json([
            'message' => __('text.team_updated_success'),
            'team' => new TeamResource($team->load('users')),
            'status' => 200,
        ]);
    }
}
