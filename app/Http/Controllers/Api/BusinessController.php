<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\Business\BusinessService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        // V1: single business, ambil terbaru (wizard bisa buat baru)
        $business = Business::with(['accounts','categories'])->latest('id')->first();
        if (!$business) return response()->json(['message' => 'Belum ada business — jalankan wizard.'], 404);
        return response()->json($business);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:100',
            'business_type' => 'required|string',
            'logo' => 'nullable|string',
            'currency' => 'required|string',
            'timezone' => 'required|string',
            'settings' => 'nullable|array',
            'accounts' => 'nullable|array',
            'income_categories' => 'nullable|array',
            'expense_categories' => 'nullable|array',
        ]);
        $business = BusinessService::createWithTemplate($data, $request->user());
        return response()->json($business->load(['accounts','categories']), 201);
    }

    public function show(Business $business)
    {
        return $business->load(['accounts','categories']);
    }

    public function update(Request $request, Business $business)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:100',
            'type' => 'sometimes|string',
            'logo' => 'nullable|string',
            'currency' => 'sometimes|string',
            'timezone' => 'sometimes|string',
            'settings' => 'sometimes|array',
        ]);
        $old = $business->toArray();
        $business->update($data);
        \App\Services\Audit\AuditService::log($business->id, $request->user()?->id, 'UPDATE_BUSINESS', 'business', (string)$business->id, $old, $business->fresh()->toArray());
        return response()->json($business);
    }
}
