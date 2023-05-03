<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatObat extends Model
{
    protected $table = 'riwayat_obats';
    protected $fillable = [
        'obat_id',
        'qty_in',
        'qty_out',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
