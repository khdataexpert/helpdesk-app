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

        $query = Contract::with(['client', 'project', 'creator', 'company'])->latest();

        // لو المستخدم عميل، يعرض عقوده فقط
        if ($user && $user->hasRole('Client')) {
            $query->where('client_id', $user->id);
        }

        $contracts = $query->paginate(15);

        return ContractResource::collection($contracts);
    }

    /**
     * إنشاء عقد جديد
     */
    public function store(Request $request)
    {
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

        return new ContractResource($contract->load(['client', 'project', 'creator', 'company']));
    }

    /**
     * عرض عقد محدد
     */
    public function show(Contract $contract)
    {
        $user = auth()->user();

        // لو المستخدم عميل، ما يشوفش غير عقوده فقط
        if ($user && $user->hasRole('Client') && $contract->client_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new ContractResource($contract->load(['client', 'project', 'creator', 'company']));
    }

    /**
     * تحديث عقد
     */
    public function update(Request $request, Contract $contract)
    {
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

        return new ContractResource($contract->load(['client', 'project', 'creator', 'company']));
    }

    /**
     * حذف عقد
     */
    public function destroy(Contract $contract)
    {
        if ($contract->attachment && Storage::disk('public')->exists($contract->attachment)) {
            Storage::disk('public')->delete($contract->attachment);
        }

        $contract->delete();

        return response()->json(['message' => 'Contract deleted successfully']);
    }

}
