<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete(); // §41 business isolation
            $table->string('name'); // Cash, BCA, QRIS, GoPay (§19)
            $table->enum('type', ['Cash', 'Bank', 'E-Wallet', 'Other'])->default('Cash');
            $table->bigInteger('opening_balance')->default(0); // §20 Opening Balance — not revenue
            $table->boolean('is_archived')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'is_archived']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
