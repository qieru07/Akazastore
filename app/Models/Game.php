<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games'; 

    public $timestamps = false; 

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'video',
        'status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}