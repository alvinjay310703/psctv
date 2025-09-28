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
       Schema::create('service_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
    $table->foreignId('subscription_id')->nullable()->constrained('subscriptions');
    $table->enum('type',['installation','repair','upgrade','downgrade','cancellation','other'])->default('other');
    $table->text('description')->nullable();
    $table->enum('status',['open','assigned','in_progress','completed','cancelled'])->default('open');
    $table->timestamp('requested_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
