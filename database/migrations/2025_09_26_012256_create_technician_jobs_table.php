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
        Schema::create('technician_jobs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
    $table->foreignId('technician_id')->constrained('users'); // technicians in users table
    $table->timestamp('assigned_at')->nullable();
    $table->enum('status',['assigned','enroute','arrived','in_progress','completed','verified'])->default('assigned');
    $table->timestamp('start_time')->nullable();
    $table->timestamp('end_time')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_jobs');
    }
};
