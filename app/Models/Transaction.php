<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    public $timestamps = false; // Karena tabel pakai 'tanggal' bukan created_at/updated_at

    protected $fillable = [
        'user_id',
        'whatsapp',
        'email',
        'game',
        'item',
        'nominal',
        'kode_unik',
        'metode',
        'status',
        'username'
    ];
}