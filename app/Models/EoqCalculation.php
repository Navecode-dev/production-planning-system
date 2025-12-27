<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EoqCalculation extends Model
{
    use HasFactory;

    protected $table = 'eoq_calculations';

    protected $fillable = [
        'raw_material_id',
        'annual_demand',
        'ordering_cost',
        'holding_cost',
        'purchase_price',
        'eoq_value',
        'total_cost',
        'optimal_frequency',
        'lead_time',
        'working_days',
        'max_daily_demand',
        'daily_demand',
        'safety_stock',
        'rop_value',
    ];

    protected $casts = [
        'calculation_date' => 'datetime',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}