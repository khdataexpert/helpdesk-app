<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * عرض كل الفواتير
     */
    public function index()
    {
        $user = auth()->user();

        // لو المستخدم عميل، يشوف فواتيره فقط
        if ($user->hasRole('Client')) {
            $invoices = Invoice::with(['client', 'project', 'creator', 'company'])
                ->where('client_id', $user->id)
                ->latest()
                ->paginate(15);
        }
        // لو عنده صلاحية عرض كل الفواتير
        elseif ($user->can('view invoices')) {
            $invoices = Invoice::with(['client', 'project', 'creator', 'company'])
                ->latest()
                ->paginate(15);
        }
        // لو عنده صلاحية عرض فواتيره الخاصة
        elseif ($user->can('view own invoices')) {
            $invoices = Invoice::with(['client', 'project', 'creator', 'company'])
                ->where('created_by', $user->id)
                ->latest()
                ->paginate(15);
        } else {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        return [
            'status' => 200,
            'message' => __('text.fetch_successful'),
            'data' => InvoiceResource::collection($invoices),
        ];
    }

    /**
     * عرض فاتورة محددة
     */
    public function show(Invoice $invoice)
    {
        $user = auth()->user();

        // لو المستخدم عميل، يشوف فقط الفواتير الخاصة به
        if ($user->hasRole('Client')) {
            if ($invoice->client_id !== $user->id) {
                return response()->json(['message' => __('text.permission_denied')], 403);
            }
        }
        // لو عنده صلاحية view own invoices
        elseif ($user->can('view own invoices')) {
            if ($invoice->created_by !== $user->id) {
                return response()->json(['message' => __('text.permission_denied')], 403);
            }
        }
        // لو ماعندوش صلاحية عامة
        elseif (!$user->can('view invoices')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        return [
            'status' => 200,
            'message' => __('text.fetch_successful'),
            'data' => new InvoiceResource($invoice->load(['client', 'project', 'creator', 'company'])),
        ];
    }

    /**
     * إنشاء فاتورة جديدة
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add invoices')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'invoice_date'   => 'required|date',
            'total'          => 'required|numeric|min:0',
            'status'         => ['required', Rule::in(['paid', 'unpaid', 'pending'])],
            'client_id'      => 'required|exists:users,id',
            'project_id'     => 'nullable|exists:projects,id',
            'company_id'     => 'nullable|exists:companies,id',
            'attachment'     => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('invoices', 'public');
        }

        $invoice = Invoice::create($validated);

        return [
            'status' => 201,
            'message' => __('text.create_successful'),
            'data' => new InvoiceResource($invoice->load(['client', 'project', 'creator', 'company'])),
        ];
    }

    /**
     * تحديث فاتورة
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (!auth()->user()->can('edit invoices')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date'   => 'required|date',
            'total'          => 'required|numeric|min:0',
            'status'         => ['required', Rule::in(['paid', 'unpaid', 'pending'])],
            'client_id'      => 'required|exists:users,id',
            'project_id'     => 'nullable|exists:projects,id',
            'company_id'     => 'nullable|exists:companies,id',
            'attachment'     => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
                Storage::disk('public')->delete($invoice->attachment);
            }

            $validated['attachment'] = $request->file('attachment')->store('invoices', 'public');
        }

        $invoice->update($validated);

        return [
            'status' => 200,
            'message' => __('text.update_successful'),
            'data' => new InvoiceResource($invoice->load(['client', 'project', 'creator', 'company'])),
        ];
    }

    /**
     * حذف فاتورة
     */
    public function destroy(Invoice $invoice)
    {
        if (!auth()->user()->can('delete invoices')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }

        if ($invoice->attachment && Storage::disk('public')->exists($invoice->attachment)) {
            Storage::disk('public')->delete($invoice->attachment);
        }

        $invoice->delete();

        return response()->json([
            'status' => 200,
            'message' => __('text.delete_successful'),
        ]);
    }
}
