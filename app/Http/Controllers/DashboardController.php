<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard SPA — PRD §11-13, §49
     * Data saat ini via localStorage (frontend mock), siap di-hydrate dari DB
     * ketika migrasi §53-57 selesai (business_id isolation).
     */
    public function index(Request $request)
    {
        // TODO: hydrate from DB when auth & business middleware ready
        // $business = $request->user()?->business ?? Business::first();
        $business = null; // Blade handles null via ?? fallback (localStorage)
        $accounts = [];
        $categories = [];

        return view('dashboard', compact('business', 'accounts', 'categories'));
    }
}
