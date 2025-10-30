<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
  /**
     * عرض قائمة المشاريع
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->can('view projects')) {
            $projects = Project::with(['client', 'team', 'company','assignedUser'])->latest()->get();
        } elseif ($user->can('view own projects') || $user->can('add projects')) {
            if ($user->hasRole('Client')) {
                $projects = Project::with(['client', 'team', 'company','assignedUser'])
                    ->where('client_id', $user->id)
                    ->latest()
                    ->get();
            } elseif ($user->hasRole('Agent')) {
                $teamIds = $user->teams()->pluck('teams.id')->toArray();
                $projects = Project::with(['client', 'team', 'company','assignedUser'])
                    ->where(function ($query) use ($teamIds, $user) {
                        $query->whereIn('team_id', $teamIds)
                            ->orWhere('assigned_to', $user->id);
                    })
                    ->latest()
                    ->get();
            } else {
                $projects = collect();
            }
        } else {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        return [
            'status' => 200,
            'message' => __('text.projects_list_retrieved_successfully'),
            'projects' => ProjectResource::collection($projects)
        ];
    }

    /**
     * إنشاء مشروع جديد
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $user->can('add projects')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'company_id' => 'nullable|exists:companies,id',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $project = Project::create($validated);

        return [
            'status' => 201,
            'message' => __('text.project_created_success'),
            'project' => new ProjectResource($project->load(['client', 'team', 'company']))
        ];
    }

    /**
     * عرض مشروع محدد
     */
    public function show(Project $project)
    {
        $user = auth()->user();

        $canView = $user->can('view projects') ||
            ($user->can('view own projects') &&
                ($user->id == $project->client_id ||
                 $user->id == $project->assigned_to ||
                 $user->teams->contains('id', $project->team_id))
            );

        if (! $canView) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $project->load(['client', 'team', 'company', 'tickets.assignedUser','assignedUser']);
        return [
            'status' => 200,
            'message' => __('text.project_details_retrieved_successfully'),
            'project' => new ProjectResource($project)
        ];
    }

    /**
     * تحديث مشروع
     */
    public function update(Request $request, Project $project)
    {
        $user = auth()->user();

        if (! $user->can('edit projects')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'company_id' => 'nullable|exists:companies,id',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $project->update($validated);

        return [
            'status' => 200,
            'message' => __('text.project_updated_success'),
            'project' => new ProjectResource($project)
        ];
    }

    /**
     * تعيين المشروع للمستخدم الحالي (Agent)
     */
    public function assignToMe(Project $project)
    {
        $user = auth()->user();

        if (! $user->hasRole('Agent')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $project->assigned_to = $user->id;
        $project->save();

        return response()->json([
            'status' => 200,
            'message' => __('text.agent_assigned_success'),
            'project' => new ProjectResource($project->load(['client', 'team', 'company']))
        ]);
    }

    /**
     * حذف مشروع
     */
    public function destroy(Project $project)
    {
        $user = auth()->user();

        if (! $user->can('delete projects')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $project->delete();

        return response()->json([
            'status' => 200,
            'message' => __('text.project_deleted_success')
        ]);
    }
}
