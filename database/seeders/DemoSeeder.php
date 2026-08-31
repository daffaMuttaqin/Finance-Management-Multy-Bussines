<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Business\BusinessService;
use App\Services\Transaction\TransactionService;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'owner@keukita.id'],
            ['name' => 'Owner', 'password' => bcrypt('password'), 'email_verified_at' => now()]
        );
        // ensure verified
        if (!$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        // Create business via template (Coffee Shop)
        $business = BusinessService::createWithTemplate([
            'business_name' => 'Kopi Sore',
            'business_type' => 'Coffee Shop',
            'logo' => 'https://api.dicebear.com/7.x/shapes/svg?seed=kopi',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
        ], $user);

        $accounts = $business->accounts()->get()->keyBy('name');
        $cats = $business->categories()->get()->keyBy('name');

        $cash = $accounts['Cash'] ?? $accounts->first();
        $bca = $accounts['Bank BCA'] ?? $accounts->first();
        $qris = $accounts['QRIS'] ?? $cash;

        $sales = $cats['Coffee Sales'] ?? $cats->where('type','INCOME')->first();
        $raw = $cats['Raw Material'] ?? $cats->where('classification','COGS')->first();
        $rent = $cats['Rent'] ?? $cats->where('type','EXPENSE')->first();

        // Transactions demo §61.2-61.4
        TransactionService::create([
            'business_id' => $business->id,
            'type' => 'INCOME',
            'category_id' => $sales->id,
            'account_id' => $bca->id,
            'amount' => 4500000,
            'transaction_date' => now()->subDays(2)->toDateString(),
            'description' => 'Penjualan harian',
        ], $user->id);

        TransactionService::create([
            'business_id' => $business->id,
            'type' => 'INCOME',
            'category_id' => $sales->id,
            'account_id' => $qris->id,
            'amount' => 1200000,
            'transaction_date' => now()->subDay()->toDateString(),
            'description' => 'QRIS sore',
        ], $user->id);

        TransactionService::create([
            'business_id' => $business->id,
            'type' => 'EXPENSE',
            'category_id' => $raw->id,
            'account_id' => $bca->id,
            'amount' => 1800000,
            'transaction_date' => now()->subDay()->toDateString(),
            'description' => 'Biji kopi & susu',
        ], $user->id);

        TransactionService::create([
            'business_id' => $business->id,
            'type' => 'EXPENSE',
            'category_id' => $rent->id,
            'account_id' => $bca->id,
            'amount' => 2500000,
            'transaction_date' => now()->subDays(5)->toDateString(),
            'description' => 'Sewa tempat bulan ini',
        ], $user->id);

        TransactionService::create([
            'business_id' => $business->id,
            'type' => 'TRANSFER',
            'from_account_id' => $bca->id,
            'to_account_id' => $cash->id,
            'amount' => 2000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Setoran BCA ke Cash',
        ], $user->id);

        // Asset §21 — purchase reduces cash, not profit
        $business->assets()->create([
            'name' => 'Mesin Espresso',
            'category' => 'Machine',
            'purchase_date' => now()->subDays(30)->toDateString(),
            'purchase_price' => 5000000,
            'account_id' => $bca->id,
            'description' => 'La Marzocco — tidak kurangi Net Profit',
            'status' => 'ACTIVE',
        ]);

        $this->command->info("Demo business seeded: {$business->name} ({$business->type}) id={$business->id}");
    }
}
