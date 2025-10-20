<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->can('view projects')) {
            $projects = Project::with(['Client', 'team'])->get();
            return view('Dashboard.projects.index', compact('projects'));
        }

        if ($user->can('view own projects') || $user->can('add projects')) {

            if ($user->hasRole('Client')) {
                $projects = Project::with(['Client', 'team'])
                    ->where('client_id', $user->id)
                    ->get();
            } elseif ($user->hasRole('Agent')) {
                $teamIds = $user->teams()->pluck('teams.id')->toArray();

                $projects = Project::with(['Client', 'team'])
                    ->where(function ($query) use ($teamIds, $user) {
                        $query->whereIn('team_id', $teamIds)
                            ->orWhere('assigned_to', $user->id);
                    })
                    ->get();
            } else {
                $projects = collect();
            }

            return view('Dashboard.projects.index', compact('projects'));
        }

        abort(403, __('text.permission_denied'));
    }


    public function create()
    {
        if (auth()->user()->can('add projects')) {
            $clients = User::role('Client')->get();
            $teams = Team::all();
            $agents = User::role('Agent')->get(); 
    
            return view('Dashboard.projects.create', compact('clients', 'teams', 'agents'));
        }
        abort(403, __('text.permission_denied'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id', // 👈 نتحقق منه لو اتبعت
        ]);



            Project::create($validated);


        return redirect()->route('projects.index')->with('success', __('text.project_created_success'));
    }

    public function edit(Project $project)
    {
        if (auth()->user()->can('edit projects')) {
            $clients = User::role('Client')->get();
            $teams = Team::all();
            $agents = User::role('Agent')->get(); // 👈 المستخدمين اللي ينفع يتعيّنوا
    
            return view('Dashboard.projects.edit', compact('project', 'clients', 'teams', 'agents'));
        }
        abort(403, __('text.permission_denied'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);


        $project->update($validated);

        return redirect()->route('projects.index')->with('success', __('text.project_updated_success'));
    }
    public function show(Project $project)
    {
        if (auth()->user()->can('view projects') || (auth()->user()->can('view own projects') && (auth()->user()->id == $project->client_id || auth()->user()->id == $project->assigned_to || auth()->user()->teams->contains('id', $project->team_id)))) {
            $project->load(['client', 'team', 'tickets.assignedUser']);
            return view('Dashboard.projects.show', compact('project'));
        }
        abort(403, __('text.permission_denied'));
    }
    public function assignToMe(Project $project)
    {
        $user = auth()->user();

        $project->assigned_to = $user->id;
        $project->save();

        return redirect()->route('projects.show', $project)->with('success', __('text.agent_assigned_success'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', __('text.project_deleted_success'));
    }
}
