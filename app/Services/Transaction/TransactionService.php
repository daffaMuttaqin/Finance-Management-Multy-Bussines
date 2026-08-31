<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    /**
     * Create transaction with financial validation §52
     * @throws ValidationException
     */
    public static function create(array $data, ?int $userId = null): Transaction
    {
        return DB::transaction(function () use ($data, $userId) {
            $type = $data['type'];

            if ($type === 'TRANSFER') {
                if (($data['from_account_id'] ?? null) === ($data['to_account_id'] ?? null)) {
                    throw ValidationException::withMessages(['to_account_id' => 'Akun asal dan tujuan tidak boleh sama.']);
                }
                // ensure accounts belong to same business
                if (($data['business_id'] ?? null) && $data['from_account_id'] && $data['to_account_id']) {
                    $from = \App\Models\Account::find($data['from_account_id']);
                    $to = \App\Models\Account::find($data['to_account_id']);
                    if ($from?->business_id !== $data['business_id'] || $to?->business_id !== $data['business_id']) {
                        throw ValidationException::withMessages(['business_id' => 'Akun tidak termasuk business ini.']);
                    }
                }
            } else {
                // INCOME / EXPENSE must have category & account belonging to business
                if (empty($data['category_id']) || empty($data['account_id'])) {
                    throw ValidationException::withMessages(['category_id' => 'Kategori dan akun wajib.']);
                }
            }

            if (($data['amount'] ?? 0) <= 0) {
                throw ValidationException::withMessages(['amount' => 'Jumlah harus lebih dari 0.']);
            }

            $tx = Transaction::create([
                'business_id' => $data['business_id'],
                'type' => $type,
                'status' => 'POSTED',
                'category_id' => $data['category_id'] ?? null,
                'account_id' => $data['account_id'] ?? null,
                'from_account_id' => $data['from_account_id'] ?? null,
                'to_account_id' => $data['to_account_id'] ?? null,
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'] ?? now()->toDateString(),
                'description' => $data['description'] ?? null,
                'reference_number' => $data['reference_number'] ?? null,
                'party' => $data['party'] ?? null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            AuditService::log($tx->business_id, $userId, 'CREATE_TRANSACTION', 'transaction', (string)$tx->id, null, $tx->toArray());

            return $tx;
        });
    }

    public static function update(Transaction $tx, array $data, ?int $userId = null): Transaction
    {
        if ($tx->status === 'VOIDED') {
            throw ValidationException::withMessages(['status' => 'Transaksi VOIDED tidak bisa di-edit.']);
        }

        return DB::transaction(function () use ($tx, $data, $userId) {
            $old = $tx->toArray();

            if (($tx->type === 'TRANSFER' || ($data['type'] ?? null) === 'TRANSFER')) {
                // transfer update
                $from = $data['from_account_id'] ?? $tx->from_account_id;
                $to = $data['to_account_id'] ?? $tx->to_account_id;
                if ($from === $to) {
                    throw ValidationException::withMessages(['to_account_id' => 'Akun asal dan tujuan tidak boleh sama.']);
                }
            }

            $tx->update([
                'category_id' => $data['category_id'] ?? $tx->category_id,
                'account_id' => $data['account_id'] ?? $tx->account_id,
                'from_account_id' => $data['from_account_id'] ?? $tx->from_account_id,
                'to_account_id' => $data['to_account_id'] ?? $tx->to_account_id,
                'amount' => $data['amount'] ?? $tx->amount,
                'transaction_date' => $data['transaction_date'] ?? $tx->transaction_date,
                'description' => $data['description'] ?? $tx->description,
                'reference_number' => $data['reference_number'] ?? $tx->reference_number,
                'party' => $data['party'] ?? $tx->party,
                'updated_by' => $userId,
            ]);

            AuditService::log($tx->business_id, $userId, 'UPDATE_TRANSACTION', 'transaction', (string)$tx->id, $old, $tx->fresh()->toArray());

            return $tx->fresh();
        });
    }

    public static function void(Transaction $tx, ?int $userId = null): Transaction
    {
        if ($tx->status === 'VOIDED') {
            throw ValidationException::withMessages(['status' => 'Sudah VOIDED.']);
        }

        return DB::transaction(function () use ($tx, $userId) {
            $old = $tx->toArray();
            $tx->update(['status' => 'VOIDED', 'updated_by' => $userId]);
            AuditService::log($tx->business_id, $userId, 'VOID_TRANSACTION', 'transaction', (string)$tx->id, $old, $tx->fresh()->toArray());
            return $tx->fresh();
        });
    }
}
