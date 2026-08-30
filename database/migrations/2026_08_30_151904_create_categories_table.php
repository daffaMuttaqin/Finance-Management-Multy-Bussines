<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['INCOME', 'EXPENSE']);
            $table->string('classification')->default('Other'); // COGS, Operational, Marketing, Salary, Rent, Asset, Other (§30)
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->boolean('affects_profit')->default(true); // §15
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
            $table->index(['business_id', 'type', 'is_archived']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
