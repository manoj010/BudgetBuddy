<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->defaultInfos();
            $table->decimal('amount', 10, 2);
            $table->foreignId('category_id')->constrained('expense_categories')->cascadeOnUpdate();
            $table->date('date_spent');
            $table->longText('notes')->nullable();
            $table->boolean('recurring')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
