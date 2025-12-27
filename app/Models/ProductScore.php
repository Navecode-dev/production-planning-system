<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'criteria_id',
        'value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}