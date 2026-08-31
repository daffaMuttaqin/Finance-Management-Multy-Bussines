<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'business_id' => 'required|exists:businesses,id',
            'type' => 'required|in:INCOME,EXPENSE,TRANSFER',
            'category_id' => 'required_unless:type,TRANSFER|nullable|exists:categories,id',
            'account_id' => 'required_unless:type,TRANSFER|nullable|exists:accounts,id',
            'from_account_id' => 'required_if:type,TRANSFER|nullable|exists:accounts,id|different:to_account_id',
            'to_account_id' => 'required_if:type,TRANSFER|nullable|exists:accounts,id|different:from_account_id',
            'amount' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'reference_number' => 'nullable|string|max:100',
            'party' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Jumlah harus lebih dari 0.',
            'from_account_id.different' => 'Akun asal dan tujuan tidak boleh sama.',
        ];
    }
}
