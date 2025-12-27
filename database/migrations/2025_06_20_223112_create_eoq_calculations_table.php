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
        Schema::create(
            'eoq_calculations',
            function (Blueprint $table) {
                $table->id();
                $table->timestamp('calculation_date')->useCurrent();
                $table->decimal('annual_demand', 10, 2);
                $table->decimal('ordering_cost', 10, 2);
                $table->decimal('holding_cost', 10, 2);
                $table->decimal('purchase_price', 10, 2)->nullable();
                $table->decimal('eoq_value', 10, 2);
                $table->decimal('total_cost', 10, 2);
                $table->decimal('optimal_frequency', 10, 2)->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eoq_calculations');
    }
};


