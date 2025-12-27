<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'sales_area',
        'contact',
        'person_in_charge',
    ];

    public function scores()
    {
        return $this->hasMany(StoreScore::class);
    }
}