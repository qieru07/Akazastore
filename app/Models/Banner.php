<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    // Tambahkan baris ini agar kolom bisa diisi
    protected $fillable = ['title', 'image', 'status', 'type', 'video_url'];
}