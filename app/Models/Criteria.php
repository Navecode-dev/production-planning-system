<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;
    protected $table = 'criterias';

    protected $fillable = [
        'name',
        'weight',
        'type',
        'category',
    ];

    public function productScores() {
        return $this->hasMany(ProductScore::class);
    }
    public function storeScores() {
        return $this->hasMany(StoreScore::class);
    }
}
