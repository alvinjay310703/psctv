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
       Schema::create('job_photos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('job_id')->constrained('technician_jobs')->onDelete('cascade');
    $table->foreignId('uploaded_by')->nullable()->constrained('users');
    $table->string('url');
    $table->string('thumb_url')->nullable();
    $table->json('meta')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_photos');
    }
};
