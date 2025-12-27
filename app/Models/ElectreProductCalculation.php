<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectreProductCalculation extends Model
{
    use HasFactory;

    protected $fillable = ['ranking'];

    protected $casts = [
        'ranking' => 'array',
    ];
}
