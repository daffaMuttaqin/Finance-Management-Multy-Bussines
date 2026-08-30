<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('Other'); // Coffee Shop, Bakery, Travel, Retail, Services, Other (§8-9)
            $table->string('logo')->nullable();
            $table->string('currency')->default('IDR');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->json('settings')->nullable(); // COGS, Assets, Tax, Receivable, Payable (§8 Step4)
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Pivot business_users for multi-user / future multi-business (§6)
        Schema::create('business_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['OWNER', 'ADMIN'])->default('ADMIN');
            $table->timestamps();
            $table->unique(['business_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_users');
        Schema::dropIfExists('businesses');
    }
};
