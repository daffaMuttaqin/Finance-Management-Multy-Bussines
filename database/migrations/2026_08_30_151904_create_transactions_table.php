<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['INCOME', 'EXPENSE', 'TRANSFER']); // §56 MVP
            $table->enum('status', ['POSTED', 'VOIDED'])->default('POSTED'); // §57, §40
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete(); // for INCOME/EXPENSE
            $table->foreignId('from_account_id')->nullable()->constrained('accounts')->nullOnDelete(); // for TRANSFER
            $table->foreignId('to_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->bigInteger('amount'); // in cents / rupiah integer
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('party')->nullable(); // customer/vendor
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['business_id', 'type', 'status', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
