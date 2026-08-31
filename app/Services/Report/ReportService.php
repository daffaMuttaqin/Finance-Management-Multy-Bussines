<?php

namespace App\Services\Report;

use App\Models\Business;
use App\Services\Finance\FinanceService;
use Illuminate\Http\Request;

class ReportService
{
    public function __construct(protected FinanceService $finance) {}

    /**
     * Financial Summary filtered (§32-33)
     */
    public function summary(Business $business, Request $request): array
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $type = $request->input('type'); // INCOME/EXPENSE/TRANSFER
        $categoryId = $request->input('category_id');
        $accountId = $request->input('account_id');

        $filter = function ($q) use ($from, $to, $type, $categoryId, $accountId) {
            if ($from && $q->transaction_date < $from) return false;
            if ($to && $q->transaction_date > $to) return false;
            if ($type && $q->type !== $type) return false;
            if ($categoryId && $q->category_id != $categoryId) return false;
            if ($accountId) {
                if ($q->type === 'TRANSFER') {
                    return $q->from_account_id == $accountId || $q->to_account_id == $accountId;
                }
                return $q->account_id == $accountId;
            }
            return true;
        };

        $metrics = $this->finance->metrics($business, $filter);

        // Asset filtered
        $assets = $business->assets;
        if ($from || $to) {
            $assets = $assets->filter(fn($a) => (!$from || $a->purchase_date >= $from) && (!$to || $a->purchase_date <= $to));
        }

        return array_merge($metrics, [
            'assets_total' => $assets->sum('purchase_price'),
            'assets_count' => $assets->count(),
            'transactions' => $business->transactions()->where('status','POSTED')->get()->filter($filter)->values(),
        ]);
    }

    public function transactions(Business $business, Request $request)
    {
        $q = $business->transactions()->with(['category','account','fromAccount','toAccount'])->where('status','POSTED');

        if ($request->filled('type')) $q->where('type', $request->input('type'));
        if ($request->filled('category_id')) $q->where('category_id', $request->input('category_id'));
        if ($request->filled('account_id')) {
            $aid = $request->input('account_id');
            $q->where(function($w) use ($aid){
                $w->where('account_id', $aid)->orWhere('from_account_id', $aid)->orWhere('to_account_id', $aid);
            });
        }
        if ($request->filled('from')) $q->where('transaction_date','>=',$request->input('from'));
        if ($request->filled('to')) $q->where('transaction_date','<=',$request->input('to'));
        if ($request->filled('search')) {
            $s = $request->input('search');
            $q->where(function($w) use ($s){
                $w->where('description','like',"%$s%")->orWhere('reference_number','like',"%$s%");
            });
        }

        return $q->orderByDesc('transaction_date')->orderByDesc('id')->paginate($request->input('per_page', 20));
    }
}
