<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionScheduleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_schedule_store_id',
        'product_id',
        'quantity',
    ];

    /**
     * Relasi ke schedule store
     */
    public function scheduleStore()
    {
        return $this->belongsTo(ProductionScheduleStore::class, 'production_schedule_store_id');
    }

    /**
     * Relasi ke master produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}