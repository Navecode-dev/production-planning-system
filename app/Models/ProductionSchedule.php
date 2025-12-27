<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_name',
        'description',
        'status',
        'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    /**
     * Relasi ke daftar toko yang dipilih dalam jadwal
     */
    public function scheduleStores()
    {
        return $this->hasMany(ProductionScheduleStore::class, 'production_schedule_id');
    }
}