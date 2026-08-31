<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\Finance\FinanceService;
use App\Services\Report\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(protected FinanceService $finance, protected ReportService $report) {}

    public function summary(Request $request)
    {
        $request->validate(['business_id' => 'required|exists:businesses,id']);
        $business = Business::findOrFail($request->input('business_id'));
        $data = $this->report->summary($business, $request);
        return response()->json($data);
    }

    public function dashboard(Request $request)
    {
        $request->validate(['business_id' => 'required|exists:businesses,id']);
        $business = Business::with(['accounts','categories'])->findOrFail($request->input('business_id'));
        $metrics = $this->finance->metrics($business);
        $available = $this->finance->availableCash($business);
        $accounts = $business->accounts->map(fn($a)=> array_merge($a->toArray(), ['current_balance'=>$this->finance->accountBalance($a)]));
        return response()->json([
            'business' => $business,
            'available_cash' => $available,
            'metrics' => $metrics,
            'accounts' => $accounts,
            'recent' => $business->transactions()->with(['category','account'])->where('status','POSTED')->orderByDesc('transaction_date')->limit(8)->get(),
        ]);
    }

    public function export(Request $request)
    {
        // §34 — mock, dalam real pakai Maatwebsite\Excel / DomPDF
        return response()->json(['message' => 'Export mengikuti filter aktif — mock. Integrasi Excel/PDF tinggal pasang package.']);
    }
}
