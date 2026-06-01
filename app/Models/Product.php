<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'game_id',
        'name',
        'category',
        'price',
        'provider_code'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}