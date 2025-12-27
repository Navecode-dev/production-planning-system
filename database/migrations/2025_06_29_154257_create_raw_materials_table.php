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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_per_pallet', 15, 2)->comment('Harga per Palet (Rp)');
            $table->decimal('storage_cost', 15, 2)->comment('Biaya Simpan');
            $table->decimal('price_per_sheet', 15, 2)->comment('Harga per Lembar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};