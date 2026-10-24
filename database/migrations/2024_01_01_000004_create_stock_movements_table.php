<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 30)->unique();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->unsignedInteger('stock_before')->default(0);
            $table->unsignedInteger('stock_after')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['product_id']);
            $table->index(['user_id']);
            $table->index(['type']);
            $table->index(['occurred_at']);
            $table->index(['reference_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
