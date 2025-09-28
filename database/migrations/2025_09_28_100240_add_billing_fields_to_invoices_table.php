<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->date('billing_date')->nullable();
        $table->boolean('is_recurring')->default(true);
    });
}

public function down()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->dropColumn(['billing_date', 'is_recurring']);
    });
}

};
