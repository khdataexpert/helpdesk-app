<?php
namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
   public function index()
    {
        $query = Contract::with(['Client','project','creator'])->latest();

        $user = auth()->user();
        if ($user && $user->hasRole('Client')) {
            $query->where('client_id', $user->id);
        }

        $contracts = $query->paginate(15);
        return view('Dashboard.contracts.index', compact('contracts'));
    }
    public function show(Contract $contract)
    {
        $user = auth()->user();
        if ($user && $user->can('view own contracts') && $user->can('view contracts')) {
            return view('Dashboard.contracts.show', compact('contract'));
        }
        abort(403);
    }

    public function create()
    {
        if (!auth()->user() || auth()->user()->hasRole('client')) {
            abort(403);
        }
        $clients = User::role('Client')->orderBy('name')->get();
        $projects = Project::all();
        return view('Dashboard.contracts.create', compact('clients','projects'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'contract_number' => 'required|string|unique:contracts,contract_number',
            'notes' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,pending,expired,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240', // 10MB
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('contracts','public');
        }

        Contract::create($validated);

        return redirect()->route('contracts.index')->with('success', __('text.contract_created_success'));
    }

    public function edit(Contract $contract)
    {
        if (!auth()->user() || auth()->user()->hasRole('Client')) {
            abort(403);
        }
        $clients = User::role('Client')->orderBy('name')->get();
        $projects = Project::all();
        return view('Dashboard.contracts.edit', compact('contract','clients','projects'));
    }

    public function update(Request $request, Contract $contract)
    {

        $validated = $request->validate([
            'contract_number' => 'required|string|unique:contracts,contract_number,'.$contract->id,
            'notes' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,pending,expired,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            // حذف القديم إذا موجود
            if ($contract->attachment) {
                Storage::disk('public')->delete($contract->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('contracts','public');
        }

        $contract->update($validated);

        return redirect()->route('contracts.index')->with('success', __('text.contract_updated_success'));
    }

    public function destroy(Contract $contract)
    {

        if ($contract->attachment) {
            Storage::disk('public')->delete($contract->attachment);
        }

        $contract->delete();

        return redirect()->route('contracts.index')->with('success', __('text.contract_deleted_success'));
    }


}
