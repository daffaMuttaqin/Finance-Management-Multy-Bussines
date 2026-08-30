<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Business Setup Wizard §8 — 5 steps, <5 menit, template §9
     * GET  /setup → Blade wizard (standalone, tidak pakai layouts.app)
     * POST /setup → persist ke DB (future) + redirect dashboard
     */
    public function index()
    {
        return view('setup');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:100',
            'business_type' => 'required|string',
            'currency' => 'required|string',
            'timezone' => 'required|string',
            'accounts' => 'nullable|array',
            'categories' => 'nullable|array',
        ]);

        // TODO: persist to businesses, accounts, categories, business_settings
        // Business::create([...]); Account::insert(...); etc.
        // AuditTrail::log('BUSINESS_CREATED');

        return redirect()->route('dashboard')->with('success', 'Bisnis berhasil dibuat — redirect ke dashboard.');
    }
}
