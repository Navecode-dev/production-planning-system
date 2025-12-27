<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionScheduleScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_schedule_store_id',
        'criteria_id',
        'criteria_name',
        'criteria_type',
        'raw_value',
        'normalized_value',
        'weight',
        'source',
    ];

    /**
     * Relasi ke schedule store
     */
    public function scheduleStore()
    {
        return $this->belongsTo(ProductionScheduleStore::class, 'production_schedule_store_id');
    }

    /**
     * Relasi ke tabel master kriteria
     */
    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }
}