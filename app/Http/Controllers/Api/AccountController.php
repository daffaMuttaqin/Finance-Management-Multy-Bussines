<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\Audit\AuditService;
use App\Services\Finance\FinanceService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(protected FinanceService $finance) {}

    public function index(Request $request)
    {
        $bid = $request->input('business_id', 1);
        $accounts = Account::where('business_id', $bid)->orderBy('name')->get()
            ->map(fn(Account $a) => array_merge($a->toArray(), ['current_balance' => $this->finance->accountBalance($a)]));
        return response()->json($accounts);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:100',
            'type' => 'required|in:Cash,Bank,E-Wallet,Other',
            'opening_balance' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        $acc = Account::create([
            'business_id' => $data['business_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'opening_balance' => $data['opening_balance'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);
        AuditService::log($acc->business_id, $request->user()?->id, 'CREATE_ACCOUNT', 'account', (string)$acc->id, null, $acc->toArray());
        return response()->json(array_merge($acc->toArray(), ['current_balance' => $this->finance->accountBalance($acc)]), 201);
    }

    public function update(Request $request, Account $account)
    {
        $this->authorizeBusiness($account, $request);
        $old = $account->toArray();
        $data = $request->validate([
            'name' => 'sometimes|string|max:100',
            'type' => 'sometimes|in:Cash,Bank,E-Wallet,Other',
            'opening_balance' => 'sometimes|integer|min:0',
            'notes' => 'nullable|string',
            'is_archived' => 'sometimes|boolean',
        ]);
        $account->update($data);
        AuditService::log($account->business_id, $request->user()?->id, 'UPDATE_ACCOUNT', 'account', (string)$account->id, $old, $account->fresh()->toArray());
        return response()->json($account);
    }

    public function archive(Request $request, Account $account)
    {
        $this->authorizeBusiness($account, $request);
        $old = $account->toArray();
        // prevent archiving last active
        if (!$account->is_archived && Account::where('business_id',$account->business_id)->where('is_archived',false)->count() <= 1) {
            return response()->json(['message' => 'Minimal 1 akun aktif harus tersedia.'], 422);
        }
        $account->update(['is_archived' => !$account->is_archived]);
        AuditService::log($account->business_id, $request->user()?->id, $account->is_archived ? 'ARCHIVE_ACCOUNT' : 'ACTIVATE_ACCOUNT', 'account', (string)$account->id, $old, $account->fresh()->toArray());
        return response()->json($account);
    }

    protected function authorizeBusiness(Account $a, Request $r): void
    {
        if ($r->has('business_id') && (int)$a->business_id !== (int)$r->input('business_id')) abort(403);
    }
}
