<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
     /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            $invoices = Invoice::with(['client', 'project', 'creator', 'company'])
                ->orderByDesc('created_at')
                ->paginate(10);
        } elseif ($user->hasRole('Client')) {
            $invoices = Invoice::with(['client', 'project', 'creator', 'company'])
                ->where('client_id', $user->id)
                ->orderByDesc('created_at')
                ->paginate(10);
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return InvoiceResource::collection($invoices);
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $user = auth()->user();

        if (
            $user->hasRole('Super Admin') ||
            ($user->hasRole('Client') && $invoice->client_id === $user->id)
        ) {
            $invoice->load(['client', 'project', 'creator', 'company']);
            return new InvoiceResource($invoice);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'invoice_date'   => 'required|date',
            'total'          => 'required|numeric|min:0',
            'status'         => 'required|string|in:paid,unpaid,pending',
            'client_id'      => 'required|exists:users,id',
            'project_id'     => 'nullable|exists:projects,id',
            'company_id'     => 'nullable|exists:companies,id',
            'attachment'     => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments/invoices', 'public');
        }

        $validated['created_by'] = auth()->id();

        $invoice = Invoice::create($validated);

        return new InvoiceResource($invoice->load(['client', 'project', 'creator', 'company']));
    }

    /**
     * Update the specified invoice.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date'   => 'required|date',
            'total'          => 'required|numeric|min:0',
            'status'         => 'required|string|in:paid,unpaid,pending',
            'client_id'      => 'required|exists:users,id',
            'project_id'     => 'nullable|exists:projects,id',
            'company_id'     => 'nullable|exists:companies,id',
            'attachment'     => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            if ($invoice->attachment) {
                Storage::disk('public')->delete($invoice->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('attachments/invoices', 'public');
        }

        $invoice->update($validated);

        return new InvoiceResource($invoice->load(['client', 'project', 'creator', 'company']));
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($invoice->attachment) {
            Storage::disk('public')->delete($invoice->attachment);
        }

        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}
