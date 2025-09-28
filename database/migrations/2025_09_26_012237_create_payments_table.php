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
      Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
    $table->decimal('amount', 12, 2);
    $table->enum('method', ['cash','gcash','paymaya','bank_transfer','card','other'])->default('other');
    $table->string('reference_no')->nullable();
    $table->enum('status', ['pending','confirmed','failed','reversed'])->default('pending');
    $table->timestamp('paid_at')->nullable();
    $table->json('payload')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
