<?php

namespace App\Services\Finance;

use App\Models\Account;
use App\Models\Business;
use App\Models\Transaction;

/**
 * Centralized Financial Engine — §52
 * Do not duplicate logic across Livewire/Blade. All balance/profit via here.
 * Mirrors frontend app.js: accountBalance(), computeMetrics()
 */
class FinanceService
{
    public function accountBalance(Account $account): int
    {
        $balance = $account->opening_balance;

        $txs = Transaction::where('business_id', $account->business_id)
            ->where('status', 'POSTED')->get();

        foreach ($txs as $t) {
            if ($t->type === 'INCOME' && $t->account_id === $account->id) $balance += $t->amount;
            if ($t->type === 'EXPENSE' && $t->account_id === $account->id) $balance -= $t->amount;
            if ($t->type === 'TRANSFER') {
                if ($t->from_account_id === $account->id) $balance -= $t->amount;
                if ($t->to_account_id === $account->id) $balance += $t->amount;
            }
        }

        // Asset purchases: §21 — reduces cash, not profit
        $assetCash = $account->business->assets()->where('account_id', $account->id)->sum('purchase_price');
        $balance -= $assetCash;

        return $balance;
    }

    public function availableCash(Business $business): int
    {
        return $business->accounts()->where('is_archived', false)->get()
            ->sum(fn(Account $a) => $this->accountBalance($a));
    }

    /**
     * Profit §14-15
     * Gross = Revenue - COGS
     * Net = Gross - Opex (affects_profit=true)
     */
    public function metrics(Business $business, ?callable $filter = null): array
    {
        $txs = $business->transactions()->where('status', 'POSTED')->with('category')->get();
        if ($filter) $txs = $txs->filter($filter);

        $revenue = 0; $cogs = 0; $opex = 0; $cashIn = 0; $cashOut = 0;

        foreach ($txs as $t) {
            if ($t->type === 'INCOME') {
                $revenue += $t->amount; $cashIn += $t->amount;
            }
            if ($t->type === 'EXPENSE') {
                $cat = $t->category;
                $isCogs = $cat && $cat->classification === 'COGS';
                if ($isCogs) $cogs += $t->amount;
                elseif ($cat && $cat->affects_profit) $opex += $t->amount;
                $cashOut += $t->amount;
            }
        }

        $assetCash = $business->assets()->when($filter, fn($q) => $q)->sum('purchase_price');
        // For filtered metrics, assets should be filtered by date in controller

        $gross = $revenue - $cogs;
        $net = $gross - $opex;
        $netCash = $cashIn - $cashOut - $assetCash;

        return compact('revenue','cogs','opex','gross','net','cashIn','cashOut','netCash');
    }

    public function void(Transaction $tx, int $userId): Transaction
    {
        // §40 — no hard delete, reversal via status
        $tx->update(['status' => 'VOIDED', 'updated_by' => $userId]);
        // Audit is handled via observer / AuditService
        return $tx->fresh();
    }
}
