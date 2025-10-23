<?php

namespace App\Http\Controllers;

use App\Models\CompanyStyle;
use Illuminate\Http\Request;

class CompanyStyleController extends Controller
{
      public function show($companyId)
    {
        $style = CompanyStyle::where('company_id', $companyId)->first();

        if (!$style) {
            return response()->json(['message' => 'Style not found'], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $style,
            'message' => __('text.style_fetched_successfully'),
        ]);
    }

    public function storeOrUpdate(Request $request, $companyId)
    {
        $validated = $request->validate([
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
        ]);

        $style = CompanyStyle::updateOrCreate(
            ['company_id' => $companyId],
            $validated
        );

        return response()->json([
            'message' => __('text.style_saved_successfully'),
            'data' => $style
        ]);
    }
}
