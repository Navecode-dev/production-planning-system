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
        Schema::table('eoq_calculations', function (Blueprint $table) {
            // Increase precision for cost-related columns to prevent out of range errors
            $table->decimal('ordering_cost', 15, 2)->change();
            $table->decimal('holding_cost', 15, 2)->change();
            $table->decimal('total_cost', 20, 2)->change(); // Increased to handle very large values
            $table->decimal('optimal_frequency', 15, 5)->nullable()->change(); // Increased for more precision
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eoq_calculations', function (Blueprint $table) {
            // Revert to original precision
            $table->decimal('ordering_cost', 10, 2)->change();
            $table->decimal('holding_cost', 10, 2)->change();
            $table->decimal('total_cost', 10, 2)->change();
            $table->decimal('optimal_frequency', 10, 2)->nullable()->change();
        });
    }
};