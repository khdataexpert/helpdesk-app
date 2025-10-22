<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class dashboardcontroller extends Controller
{
 /**
     * Get dashboard statistics based on user permissions.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $stats = [];
        $teamIds = $user->teams()->pluck('teams.id')->toArray();

        // ✅ عدد المستخدمين
        if ($user->can('view users')) {
            $stats['users'] = User::count();
        }

        // ✅ عدد المشاريع
        if ($user->can('view projects')) {
            $stats['projects'] = Project::count();
        } elseif ($user->can('view own projects')) {
            if ($user->hasRole('Client')) {
                $stats['projects'] = Project::where('client_id', $user->id)->count();
            } elseif ($user->hasRole('Agent')) {
                $stats['projects'] = Project::whereIn('team_id', $teamIds)->count();
            }
        }

        // ✅ عدد التذاكر
        if ($user->can('view tickets')) {
            $stats['tickets'] = Ticket::count();
        } elseif ($user->can('view own tickets')) {
            if ($user->hasRole('Client')) {
                $stats['tickets'] = Ticket::where('client_id', $user->id)->count();
            } elseif ($user->hasRole('Agent')) {
                $stats['tickets'] = Ticket::whereIn('team_id', $teamIds)->count();
            }
        }

        // ✅ عدد العقود
        if ($user->can('view contracts')) {
            $stats['contracts'] = Contract::count();
        } elseif ($user->can('view own contracts')) {
            $stats['contracts'] = Contract::where('client_id', $user->id)->count();
        }

        // ✅ عدد الفواتير
        if ($user->can('view invoices')) {
            $stats['invoices'] = Invoice::count();
        } elseif ($user->can('view own invoices')) {
            $stats['invoices'] = Invoice::where('client_id', $user->id)->count();
        }

        // ✅ عدد الفرق
        if ($user->can('view teams')) {
            $stats['teams'] = Team::count();
        }

        return response()->json([
            'status' => 200,
            'message' => __('text.dashboard_title'),
            'data' => [
                'user' => new UserResource($user),
                'stats' => $stats,
            ]
        ]);
    }
}
