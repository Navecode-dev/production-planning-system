<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionScheduleStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_schedule_id',
        'store_id',
        'deadline_days',
        'total_qty',
        'product_variety',
        'rank_score',
        'rank_position',
    ];

    /**
     * Relasi ke jadwal produksi utama
     */
    public function productionSchedule()
    {
        return $this->belongsTo(ProductionSchedule::class, 'production_schedule_id');
    }

    /**
     * Relasi ke master toko
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Relasi ke produk-produk yang dipesan dalam jadwal ini
     */
    public function products()
    {
        return $this->hasMany(ProductionScheduleProduct::class, 'production_schedule_store_id');
    }

    /**
     * Relasi ke nilai kriteria untuk ELECTRE
     */
    public function scores()
    {
        return $this->hasMany(ProductionScheduleScore::class, 'production_schedule_store_id');
    }
}