<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'criteria_id',
        'value',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}