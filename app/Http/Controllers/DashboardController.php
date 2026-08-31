<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\Finance\FinanceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected FinanceService $finance) {}

    /**
     * Dashboard SPA — PRD §11-13, §49
     * Jika ada business di DB, hydrate untuk Blade @json; else fallback localStorage (§8 wizard)
     */
    public function index(Request $request)
    {
        $business = Business::with(['accounts','categories','transactions.category','transactions.account','assets'])->first();
        $accounts = [];
        $categories = [];
        $transactions = [];
        $assets = [];
        $metrics = null;
        $available = 0;
        $recent = [];

        if ($business) {
            $accounts = $business->accounts->map(fn($a) => array_merge($a->toArray(), ['current_balance' => $this->finance->accountBalance($a)]));
            $categories = $business->categories;
            $transactions = $business->transactions()->with(['category','account','fromAccount','toAccount'])->where('status','POSTED')->orderByDesc('transaction_date')->get();
            $assets = $business->assets;
            $metrics = $this->finance->metrics($business);
            $available = $this->finance->availableCash($business);
            $recent = $transactions->take(8);
        }

        return view('dashboard', compact('business', 'accounts', 'categories', 'transactions', 'assets', 'metrics', 'available', 'recent'));
    }
}
