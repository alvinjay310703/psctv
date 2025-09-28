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
        Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
    $table->string('invoice_no')->unique();
    $table->date('period_start')->nullable();
    $table->date('period_end')->nullable();
    $table->decimal('amount_due', 12, 2);
    $table->decimal('amount_paid', 12, 2)->default(0);
    $table->date('due_date');
    $table->enum('status', ['unpaid','paid','overdue','cancelled'])->default('unpaid');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
