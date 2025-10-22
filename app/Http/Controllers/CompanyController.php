<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;


class CompanyController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view companies')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        if (!auth()->user()->can('view own companies')) {
            $companies = Company::where('created_by', auth()->id())->orderBy('id', 'desc')->paginate(15);
        } else {    
            $companies = Company::orderBy('id', 'desc')->paginate(15);
        }
        return [
            'status' => 200,
            'message' => __('text.companies_list_retrieved_successfully'),
            'companies' => CompanyResource::collection($companies),
        ];
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('add companies')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('companies', 'public');
        }

        $company = Company::create($data);

        return [
            'status' => 201,
            'message' => __('text.company_created_success'),
            'company' => new CompanyResource($company),
        ];
    }

    public function show(Company $company)
    {
        if (!auth()->user()->can('view companies')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        return [
            'status' => 200,
            'message' => __('text.company_details_retrieved_successfully'),
            'company' => new CompanyResource($company),
        ];
    }

    public function update(Request $request, Company $company)
    {
        if (!auth()->user()->can('edit companies')) {
            return response()->json(['message' => __('text.permission_denied')], 403);
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($company->image) {
                Storage::disk('public')->delete($company->image);
            }
            $data['image'] = $request->file('image')->store('companies', 'public');
        }

        $company->update($data);

        return [
            'status' => 200,
            'message' => __('text.company_updated_success'),
            'company' => new CompanyResource($company),
        ];
    }

    public function destroy(Company $company)
    {
        if ($company->image) {
            Storage::disk('public')->delete($company->image);
        }
        $company->delete();

        return response()->json([
            'status' => 200,
            'message' => __('text.company_deleted_success'),
        ]);
    }

}
