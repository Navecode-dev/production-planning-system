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
        // Tabel utama production_schedules
        Schema::create('production_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft'); // draft, calculated, completed
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });

        // Tabel relasi many-to-many: production_schedule dengan store
        Schema::create('production_schedule_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->integer('deadline_days'); // Input deadline dalam hari
            $table->decimal('total_qty', 10, 2)->default(0); // Auto calculated
            $table->integer('product_variety')->default(0); // Auto calculated
            $table->decimal('rank_score', 10, 4)->nullable(); // Hasil ELECTRE
            $table->integer('rank_position')->nullable(); // Posisi ranking
            $table->timestamps();
        });

        // Tabel produk per toko dalam jadwal
        Schema::create('production_schedule_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_schedule_store_id')->constrained('production_schedule_stores')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->timestamps();
        });

        // Tabel nilai kriteria untuk perhitungan ELECTRE (terpisah dari master)
        Schema::create('production_schedule_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_schedule_store_id')->constrained('production_schedule_stores')->onDelete('cascade');
            $table->string('criteria_name'); // 'Total Qty Orderan', 'Variasi Produk', 'Deadline Kiriman'
            $table->string('criteria_type'); // 'benefit' atau 'cost'
            $table->decimal('raw_value', 10, 2); // Nilai asli (qty, jumlah produk, hari)
            $table->integer('normalized_value'); // Nilai konversi 1-5
            $table->decimal('weight', 5, 2)->default(0.33); // Bobot kriteria (bisa diatur)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_schedule_scores');
        Schema::dropIfExists('production_schedule_products');
        Schema::dropIfExists('production_schedule_stores');
        Schema::dropIfExists('production_schedules');
    }
};