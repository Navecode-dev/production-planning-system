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
        Schema::table('production_schedule_scores', function (Blueprint $table) {
            // Tambah kolom criteria_id untuk link ke tabel criterias
            $table->foreignId('criteria_id')->nullable()->after('production_schedule_store_id')->constrained('criterias')->onDelete('cascade');
            
            // Tambah kolom source untuk tahu nilai dari mana (master/order)
            $table->enum('source', ['master', 'order'])->default('order')->after('criteria_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_schedule_scores', function (Blueprint $table) {
            $table->dropForeign(['criteria_id']);
            $table->dropColumn(['criteria_id', 'source']);
        });
    }
};