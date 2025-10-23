<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ContractResource;

class ContractController extends Controller
{
    /**
     * عرض كل العقود
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Client')) {
            $contracts = Contract::with(['client', 'project', 'creator', 'company'])
                ->where('client_id', $user->id)
                ->latest()
                ->paginate(15);
        } elseif ($user->can('view contracts')) {
            $contracts = Contract::with(['client', 'project', 'creator', 'company'])
                ->latest()
                ->paginate(15);
        } else {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        return [
            'status' => 200,
            'message' => __('text.fetch_successful'),
            'data' => ContractResource::collection($contracts),
        ];
    }


    /**
     * إنشاء عقد جديد
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add contracts')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $validated = $request->validate([
            'contract_number' => 'required|string|unique:contracts,contract_number',
            'notes' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'company_id' => 'nullable|exists:companies,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,pending,expired,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240', // 10MB
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('contracts', 'public');
        }

        $contract = Contract::create($validated);

        return [
            'status' => 201,
            'message' => __('text.create_successful'),
            'data' => new ContractResource($contract->load(['client', 'project', 'creator', 'company'])),
        ];
    }

    /**
     * عرض عقد محدد
     */
    public function show(Contract $contract)
    {
        $user = auth()->user();

        // لو المستخدم عنده صلاحية "عرض عقوده الخاصة" ومش عميل
        if ($user && $user->can('view own contracts') && !$user->hasRole('Client')) {
            if ($contract->created_by !== $user->id) {
                return response()->json(['message' => __('text.permission_denied')], 403);
            }
        }
        // لو المستخدم عميل
        elseif ($user && $user->hasRole('Client')) {
            if ($contract->client_id !== $user->id) {
                return response()->json(['message' => __('text.permission_denied')], 403);
            }
        }
        // لو المستخدم ماعندوش صلاحية عرض كل العقود
        elseif (!$user->can('view contracts')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        return [
            'status' => 200,
            'message' => __('text.fetch_successful'),
            'data' => new ContractResource(
                $contract->load(['client', 'project', 'creator', 'company'])
            ),
        ];
    }


    /**
     * تحديث عقد
     */
    public function update(Request $request, Contract $contract)
    {
        if (!auth()->user()->can('edit contracts')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $validated = $request->validate([
            'contract_number' => 'required|string|unique:contracts,contract_number,' . $contract->id,
            'notes' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'company_id' => 'nullable|exists:companies,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,pending,expired,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            // حذف القديم إذا موجود
            if ($contract->attachment && Storage::disk('public')->exists($contract->attachment)) {
                Storage::disk('public')->delete($contract->attachment);
            }

            $validated['attachment'] = $request->file('attachment')->store('contracts', 'public');
        }

        $contract->update($validated);

        return [
            'status' => 200,
            'message' => __('text.update_successful'),
            'data' => new ContractResource($contract->load(['client', 'project', 'creator', 'company'])),
        ];
    }

    /**
     * حذف عقد
     */
    public function destroy(Contract $contract)
    {
        if (!auth()->user()->can('delete contracts')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        if ($contract->attachment && Storage::disk('public')->exists($contract->attachment)) {
            Storage::disk('public')->delete($contract->attachment);
        }

        $contract->delete();

        return response()->json([
            'status' => 200,
            'message' => __('text.delete_successful'),
        ]);
    }
}
