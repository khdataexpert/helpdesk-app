<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $user = auth()->user();

        // لو أدمن يشوف الكل، لو عميل يشوف فواتيره بس
        if ($user->hasRole('Super Admin')) {
            $invoices = Invoice::with(['Client', 'project', 'creator'])
                ->orderByDesc('created_at')
                ->paginate(10);
        } else if ($user->hasRole('Client')) {
            $invoices = Invoice::with(['Client', 'project', 'creator'])
                ->where('client_id', $user->id)
                ->orderByDesc('created_at')
                ->paginate(10);
        } else {
            abort(403);
        }

        return view('Dashboard.invoices.index', compact('invoices'));
    }
    public function show(Invoice $invoice)
    {
        $user = auth()->user();
        if ($user && $user->can('view own invoices') && $user->can('view invoices')) {
            return view('Dashboard.invoices.show', compact('invoice'));
        }
        abort(403);
    }
    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        if (!auth()->user()->hasRole('Super Admin')) abort(403);

        $clients = User::role('Client')->get();
        $projects = Project::all();

        return view('Dashboard.invoices.create', compact('clients', 'projects'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) abort(403);

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'invoice_date'   => 'required|date',
            'total'          => 'required|numeric|min:0',
            'status'         => 'required|string|in:paid,unpaid,pending',
            'client_id'      => 'required|exists:users,id',
            'project_id'     => 'nullable|exists:projects,id',
            'attachment'     => 'nullable|max:2048',
        ]);

        // ✅ لو في مرفق يتم حفظه
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments/invoices', 'public');
        }

        $validated['created_by'] = auth()->id();

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', __('text.invoice_created_success'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        if (!auth()->user()->hasRole('Super Admin')) abort(403);

        $clients = User::role('Client')->get();
        $projects = Project::all();

        return view('Dashboard.invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (!auth()->user()->hasRole('Super Admin')) abort(403);

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date'   => 'required|date',
            'total'          => 'required|numeric|min:0',
            'status'         => 'required|string|in:paid,unpaid,pending',
            'client_id'      => 'required|exists:users,id',
            'project_id'     => 'nullable|exists:projects,id',
            'attachment'     => 'nullable|max:2048',
        ]);

        // ✅ تحديث المرفق إن وجد
        if ($request->hasFile('attachment')) {
            if ($invoice->attachment) {
                Storage::disk('public')->delete($invoice->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('attachments/invoices', 'public');
        }

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', __('text.invoice_updated_success'));
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if (!auth()->user()->hasRole('Super Admin')) abort(403);

        if ($invoice->attachment) {
            Storage::disk('public')->delete($invoice->attachment);
        }

        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', __('text.invoice_deleted_success'));
    }
}
