<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'unit',
        'brand',
        'barcode',
        'stock',
    ];

    public function scores()
    {
        return $this->hasMany(ProductScore::class);
    }
}