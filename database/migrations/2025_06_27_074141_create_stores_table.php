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
        Schema::create('stores', function (Blueprint $table) {
            $table->id(); // ID Toko
            $table->string('name'); // Nama Toko
            $table->text('address'); // Alamat Toko
            $table->string('sales_area'); // Area Penjualan
            $table->string('contact'); // Kontak Toko
            $table->string('person_in_charge'); // Penanggung Jawab
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};