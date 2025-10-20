<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * عرض كل التيكتس
     */
    public function index()
    {
        $user = auth()->user();

        // لو المستخدم عنده صلاحية يشوف كل التذاكر
        if ($user->can('view tickets')) {
            $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator'])->get();
            return view('Dashboard.tickets.index', compact('tickets'));
        }

        // لو المستخدم يقدر يشوف تذاكر خاصة أو ينشئ تذاكر
        if ($user->can('view own tickets') || $user->can('add tickets')) {

            if ($user->hasRole('Client')) {
                // العميل يشوف التذاكر اللي هو أنشأها
                $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator'])
                    ->where('created_by', $user->id)
                    ->get();
            } elseif ($user->hasRole('Agent')) {
                // الوكيل يشوف تذاكر فريقه أو اللي اتكلف بيها
                $teamIds = $user->teams()->pluck('teams.id')->toArray();

                $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator'])
                    ->where(function ($query) use ($teamIds, $user) {
                        $query->whereIn('team_id', $teamIds)
                            ->orWhere('assigned_to', $user->id);
                    })
                    ->get();
            } elseif ($user->hasRole('Super Admin')) {
                // السوبر أدمن يشوف كل التذاكر
                $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator'])->get();
            } else {
                $tickets = collect(); // فاضي لو مش في أي رول معروف
            }

            return view('Dashboard.tickets.index', compact('tickets'));
        }

        // لو معندوش صلاحية نهائي
        abort(403, __('text.permission_denied'));
    }



    /**
     * عرض صفحة إنشاء تيكت جديدة
     */
    public function create()
    {
        if (auth()->user()->can('add tickets')) {
            $projects = Project::all();
            $teams = Team::all();
            $users = User::role('Agent')->get();

            return view('Dashboard.tickets.create', compact('projects', 'teams', 'users'));
        }
        abort(403, __('text.permission_denied'));
    }

    /**
     * حفظ التيكت الجديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'team_id' => 'nullable|exists:teams,id',
            'assigned_to' => 'nullable|exists:users,id',
            'type' => 'required|in:task,bug,feature,improvement',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'open';

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', __('text.ticket_created_success'));
    }

    /**
     * عرض تيكت معينة
     */
    public function show(Ticket $ticket)
    {
        if (auth()->user()->can('view tickets') || (auth()->user()->can('view own tickets') && $ticket->created_by === auth()->id())) {

            return view('Dashboard.tickets.show', compact('ticket'));
        }
        abort(403, __('text.permission_denied'));
    }

    /**
     * تعديل تيكت
     */
    public function edit(Ticket $ticket)
    {
        if (auth()->user()->can('edit tickets')) {

            $projects = Project::all();
            $teams = Team::all();
            $users = User::all();

            return view('Dashboard.tickets.edit', compact('ticket', 'projects', 'teams', 'users'));
        }
        abort(403, __('text.permission_denied'));
    }

    /**
     * تحديث التيكت
     */
    public function update(Request $request, Ticket $ticket)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'team_id' => 'nullable|exists:teams,id',
            'assigned_to' => 'nullable|exists:users,id',
            'type' => 'required|in:task,bug,feature,improvement',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', __('text.ticket_updated_success'));
    }

    public function assignToMe(Ticket $ticket)
    {
        if (auth()->user()->hasRole('Agent')) {
            $ticket->assigned_to = auth()->id();
            $ticket->save();

            return redirect()->route('tickets.index')->with('success', __('text.ticket_assigned_success'));
        }
        abort(403, __('text.permission_denied'));
    }
    /**
     * حذف تيكت
     */
    public function destroy(Ticket $ticket)
    {

        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', __('text.ticket_deleted_success'));
    }
}
