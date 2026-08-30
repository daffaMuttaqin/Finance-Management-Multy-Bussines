<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Oven, Laptop, etc. §21
            $table->string('category')->default('Asset');
            $table->date('purchase_date');
            $table->bigInteger('purchase_price');
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete(); // account used
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['ACTIVE', 'ARCHIVED'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
