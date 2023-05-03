<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $fillable = [
        'kode_transaksi',
        'riwayat_id',
        'total_harga',
        'user_id',
        'status_bayar',
        'created_at',
        'updated_at'
    ];
}
