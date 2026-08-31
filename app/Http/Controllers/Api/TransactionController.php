<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Services\Transaction\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $businessId = $request->input('business_id', 1);
        $q = Transaction::with(['category','account','fromAccount','toAccount'])
            ->where('business_id', $businessId)
            ->when($request->input('type'), fn($w,$v)=> $w->where('type',$v))
            ->when($request->input('status'), fn($w,$v)=> $w->where('status',$v))
            ->when($request->input('category_id'), fn($w,$v)=> $w->where('category_id',$v))
            ->when($request->input('account_id'), fn($w,$v)=> $w->where(function($qq) use ($v){
                $qq->where('account_id',$v)->orWhere('from_account_id',$v)->orWhere('to_account_id',$v);
            }))
            ->when($request->input('search'), fn($w,$v)=> $w->where('description','like',"%$v%"))
            ->when($request->input('from'), fn($w,$v)=> $w->where('transaction_date','>=',$v))
            ->when($request->input('to'), fn($w,$v)=> $w->where('transaction_date','<=',$v))
            ->orderByDesc('transaction_date')->orderByDesc('id');

        if ($request->boolean('paginate', true)) {
            return $q->paginate($request->input('per_page', 20));
        }
        return $q->get();
    }

    public function store(StoreTransactionRequest $request)
    {
        $tx = TransactionService::create($request->validated(), $request->user()?->id);
        return response()->json($tx->load(['category','account']), 201);
    }

    public function show(Transaction $transaction, Request $request)
    {
        $this->authorizeBusiness($transaction, $request);
        return $transaction->load(['category','account','fromAccount','toAccount']);
    }

    public function update(StoreTransactionRequest $request, Transaction $transaction)
    {
        $this->authorizeBusiness($transaction, $request);
        $tx = TransactionService::update($transaction, $request->validated(), $request->user()?->id);
        return response()->json($tx);
    }

    public function void(Request $request, Transaction $transaction)
    {
        $this->authorizeBusiness($transaction, $request);
        $tx = TransactionService::void($transaction, $request->user()?->id);
        return response()->json($tx);
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        // §40 no hard delete — use void
        return response()->json(['message' => 'Gunakan void, hard delete tidak diizinkan.'], 422);
    }

    protected function authorizeBusiness(Transaction $tx, Request $request): void
    {
        $bid = $request->input('business_id') ?? $request->header('X-Business-Id') ?? $tx->business_id;
        if ((int)$tx->business_id !== (int)$bid && $request->has('business_id')) {
            abort(403, 'Business isolation — tidak boleh akses resource bisnis lain.');
        }
    }
}
