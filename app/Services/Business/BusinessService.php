<?php

namespace App\Services\Business;

use App\Models\Business;
use App\Models\User;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\DB;

class BusinessService
{
    public const TEMPLATES = [
        'Coffee Shop' => [
            'income' => ['Coffee Sales', 'Food Sales', 'Other Sales'],
            'expense' => [
                ['name' => 'Raw Material', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Salary', 'classification' => 'Salary', 'affects_profit' => true],
                ['name' => 'Rent', 'classification' => 'Rent', 'affects_profit' => true],
                ['name' => 'Electricity', 'classification' => 'Utilities', 'affects_profit' => true],
                ['name' => 'Marketing', 'classification' => 'Marketing', 'affects_profit' => true],
                ['name' => 'Other Expense', 'classification' => 'Other', 'affects_profit' => true],
            ],
            'accounts' => [
                ['name' => 'Cash', 'type' => 'Cash', 'opening_balance' => 2000000],
                ['name' => 'Bank BCA', 'type' => 'Bank', 'opening_balance' => 8000000],
                ['name' => 'QRIS', 'type' => 'E-Wallet', 'opening_balance' => 1500000],
            ],
        ],
        'Bakery / Patisserie' => [
            'income' => ['Cake Sales', 'Dessert Sales', 'Catering', 'Other Sales'],
            'expense' => [
                ['name' => 'Ingredients', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Packaging', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Salary', 'classification' => 'Salary', 'affects_profit' => true],
                ['name' => 'Rent', 'classification' => 'Rent', 'affects_profit' => true],
                ['name' => 'Marketing', 'classification' => 'Marketing', 'affects_profit' => true],
                ['name' => 'Other Expense', 'classification' => 'Other', 'affects_profit' => true],
            ],
            'accounts' => [
                ['name' => 'Cash', 'type' => 'Cash', 'opening_balance' => 1500000],
                ['name' => 'Bank BCA', 'type' => 'Bank', 'opening_balance' => 10000000],
                ['name' => 'QRIS', 'type' => 'E-Wallet', 'opening_balance' => 1200000],
            ],
        ],
        'Travel' => [
            'income' => ['Tour', 'Ticket', 'Transportation', 'Other Income'],
            'expense' => [
                ['name' => 'Ticket Cost', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Hotel', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Transportation', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Commission', 'classification' => 'Operational', 'affects_profit' => true],
                ['name' => 'Marketing', 'classification' => 'Marketing', 'affects_profit' => true],
                ['name' => 'Other Expense', 'classification' => 'Other', 'affects_profit' => true],
            ],
            'accounts' => [
                ['name' => 'Cash', 'type' => 'Cash', 'opening_balance' => 3000000],
                ['name' => 'Bank BCA', 'type' => 'Bank', 'opening_balance' => 12000000],
                ['name' => 'E-Wallet', 'type' => 'E-Wallet', 'opening_balance' => 2000000],
            ],
        ],
        'Retail' => [
            'income' => ['Product Sales', 'Other Sales'],
            'expense' => [
                ['name' => 'Product Cost', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Salary', 'classification' => 'Salary', 'affects_profit' => true],
                ['name' => 'Rent', 'classification' => 'Rent', 'affects_profit' => true],
                ['name' => 'Utilities', 'classification' => 'Utilities', 'affects_profit' => true],
                ['name' => 'Marketing', 'classification' => 'Marketing', 'affects_profit' => true],
                ['name' => 'Other Expense', 'classification' => 'Other', 'affects_profit' => true],
            ],
            'accounts' => [
                ['name' => 'Cash', 'type' => 'Cash', 'opening_balance' => 2500000],
                ['name' => 'Bank', 'type' => 'Bank', 'opening_balance' => 9000000],
                ['name' => 'QRIS', 'type' => 'E-Wallet', 'opening_balance' => 1000000],
            ],
        ],
        'Services' => [
            'income' => ['Service Revenue', 'Other Income'],
            'expense' => [
                ['name' => 'Operational', 'classification' => 'Operational', 'affects_profit' => true],
                ['name' => 'Salary', 'classification' => 'Salary', 'affects_profit' => true],
                ['name' => 'Software', 'classification' => 'Operational', 'affects_profit' => true],
                ['name' => 'Marketing', 'classification' => 'Marketing', 'affects_profit' => true],
                ['name' => 'Rent', 'classification' => 'Rent', 'affects_profit' => true],
                ['name' => 'Other Expense', 'classification' => 'Other', 'affects_profit' => true],
            ],
            'accounts' => [
                ['name' => 'Cash', 'type' => 'Cash', 'opening_balance' => 2000000],
                ['name' => 'Bank', 'type' => 'Bank', 'opening_balance' => 7000000],
                ['name' => 'E-Wallet', 'type' => 'E-Wallet', 'opening_balance' => 800000],
            ],
        ],
        'Other' => [
            'income' => ['Sales', 'Other Income'],
            'expense' => [
                ['name' => 'COGS', 'classification' => 'COGS', 'affects_profit' => true],
                ['name' => 'Operational', 'classification' => 'Operational', 'affects_profit' => true],
                ['name' => 'Marketing', 'classification' => 'Marketing', 'affects_profit' => true],
                ['name' => 'Salary', 'classification' => 'Salary', 'affects_profit' => true],
                ['name' => 'Rent', 'classification' => 'Rent', 'affects_profit' => true],
                ['name' => 'Other Expense', 'classification' => 'Other', 'affects_profit' => true],
            ],
            'accounts' => [
                ['name' => 'Cash', 'type' => 'Cash', 'opening_balance' => 2000000],
                ['name' => 'Bank', 'type' => 'Bank', 'opening_balance' => 5000000],
            ],
        ],
    ];

    public static function createWithTemplate(array $data, ?User $owner = null): Business
    {
        return DB::transaction(function () use ($data, $owner) {
            $type = $data['business_type'] ?? $data['type'] ?? 'Other';
            $template = self::TEMPLATES[$type] ?? self::TEMPLATES['Other'];

            $business = Business::create([
                'name' => $data['business_name'] ?? $data['name'],
                'type' => $type,
                'logo' => $data['logo'] ?? 'https://api.dicebear.com/7.x/shapes/svg?seed=kopi',
                'currency' => $data['currency'] ?? 'IDR',
                'timezone' => $data['timezone'] ?? 'Asia/Jakarta',
                'settings' => $data['settings'] ?? ['cogs' => true, 'assets' => true, 'tax' => false, 'receivable' => false, 'payable' => false],
                'owner_id' => $owner?->id,
            ]);

            // Accounts
            $accounts = $data['accounts'] ?? $template['accounts'];
            foreach ($accounts as $a) {
                $business->accounts()->create([
                    'name' => $a['name'],
                    'type' => $a['type'] ?? 'Cash',
                    'opening_balance' => $a['opening_balance'] ?? $a['opening'] ?? 0,
                ]);
            }

            // Income categories
            $incomes = $data['income_categories'] ?? $template['income'];
            foreach ($incomes as $name) {
                $n = is_array($name) ? $name['name'] : $name;
                $c = is_array($name) ? ($name['classification'] ?? 'Sales') : 'Sales';
                $business->categories()->create([
                    'name' => $n,
                    'type' => 'INCOME',
                    'classification' => $c,
                    'affects_profit' => true,
                ]);
            }

            // Expense categories
            $expenses = $data['expense_categories'] ?? $template['expense'];
            foreach ($expenses as $e) {
                $business->categories()->create([
                    'name' => $e['name'],
                    'type' => 'EXPENSE',
                    'classification' => $e['classification'],
                    'affects_profit' => $e['affects_profit'] ?? true,
                ]);
            }

            // Non-profit defaults §15
            foreach ([
                ['name' => 'Asset Purchase', 'classification' => 'Asset', 'affects_profit' => false],
                ['name' => 'Owner Withdrawal', 'classification' => 'Other', 'affects_profit' => false],
            ] as $e) {
                if (!$business->categories()->where('name', $e['name'])->exists()) {
                    $business->categories()->create([
                        'name' => $e['name'],
                        'type' => 'EXPENSE',
                        'classification' => $e['classification'],
                        'affects_profit' => $e['affects_profit'],
                    ]);
                }
            }

            if ($owner) {
                $business->users()->attach($owner->id, ['role' => 'OWNER']);
            }

            AuditService::log($business->id, $owner?->id, 'BUSINESS_CREATED', 'business', (string)$business->id, null, $business->toArray());

            return $business;
        });
    }
}
