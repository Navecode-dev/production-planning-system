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
        Schema::table('criterias', function (Blueprint $table) {
            // Menambahkan kolom 'category' setelah kolom 'type'
            // Anda bisa memberikan nilai default jika diperlukan untuk data yang sudah ada
            $table->string('category')->after('type')->default('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
