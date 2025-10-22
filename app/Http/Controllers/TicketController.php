<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    /**
     * عرض كل التيكتات (Tickets)
     */
    public function index()
    {
        $user = auth()->user();

        // صلاحية عرض جميع التذاكر
        if ($user->can('view tickets')) {
            $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator', 'company'])->latest()->get();
        }
        // صلاحيات عرض التذاكر الخاصة
        elseif ($user->can('view own tickets') || $user->can('add tickets')) {
            if ($user->hasRole('Client')) {
                $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator', 'company'])
                    ->where('created_by', $user->id)
                    ->latest()
                    ->get();
            } elseif ($user->hasRole('Agent')) {
                $teamIds = $user->teams()->pluck('teams.id')->toArray();

                $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator', 'company'])
                    ->where(function ($query) use ($teamIds, $user) {
                        $query->whereIn('team_id', $teamIds)
                            ->orWhere('assigned_to', $user->id);
                    })
                    ->latest()
                    ->get();
            } elseif ($user->hasRole('Super Admin')) {
                $tickets = Ticket::with(['project', 'team', 'assignedUser', 'creator', 'company'])->latest()->get();
            } else {
                $tickets = collect();
            }
        } else {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        return [
            "status" => 200,
            "message" => __('text.tickets_list'),
            "tickets" => TicketResource::collection($tickets),
        ];
    }

    /**
     * إنشاء تيكت جديدة
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $user->can('add tickets')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'team_id' => 'nullable|exists:teams,id',
            'assigned_to' => 'nullable|exists:users,id',
            'type' => 'required|in:task,bug,feature,improvement',
            'priority' => 'required|in:low,medium,high,urgent',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $validated['created_by'] = $user->id;
        $validated['status'] = 'open';

        $ticket = Ticket::create($validated);

        return [
            "status" => 201,
            "message" => __('text.ticket_created_success'),
            "ticket" => new TicketResource($ticket->load(['project', 'team', 'assignedUser', 'creator', 'company']))
        ];
    }

    /**
     * عرض تيكت معينة
     */
    public function show(Ticket $ticket)
    {
        $user = auth()->user();

        $canView = $user->can('view tickets') ||
            ($user->can('view own tickets') &&
                ($ticket->created_by === $user->id ||
                    $ticket->assigned_to === $user->id ||
                    $user->teams->contains('id', $ticket->team_id))
            );

        if (! $canView) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $ticket->load(['project', 'team', 'assignedUser', 'creator', 'company']);
        return [
            'ticket' => new TicketResource($ticket),
            'status' => 200,
            'message' => __('text.ticket_details'),
        ];
    }

    /**
     * تحديث تيكت
     */
    public function update(Request $request, Ticket $ticket)
    {
        $user = auth()->user();

        if (! $user->can('edit tickets')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'team_id' => 'nullable|exists:teams,id',
            'assigned_to' => 'nullable|exists:users,id',
            'type' => 'required|in:task,bug,feature,improvement',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $ticket->update($validated);

        return [
            'status' => 200,
            'message' => __('text.ticket_details'),
            'ticket' => new TicketResource($ticket->load(['project', 'team', 'assignedUser', 'creator', 'company']))
        ];
    }

    /**
     * تعيين التيكت للمستخدم الحالي (Agent)
     */
    public function assignToMe(Ticket $ticket)
    {
        $user = auth()->user();

        if (! $user->hasRole('Agent')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $ticket->assigned_to = $user->id;
        $ticket->save();

        return response()->json([
            'status' => 200,
            'message' => __('text.ticket_assigned_success'),
            'ticket' => new TicketResource($ticket->load(['project', 'team', 'assignedUser', 'creator', 'company']))
        ]);
    }

    /**
     * حذف تيكت
     */
    public function destroy(Ticket $ticket)
    {
        $user = auth()->user();

        if (! $user->can('delete tickets')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $ticket->delete();

        return response()->json([
            'status' => 200,
            'message' => __('text.ticket_deleted_success')
        ]);
    }
}
