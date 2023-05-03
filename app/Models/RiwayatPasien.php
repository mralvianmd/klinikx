<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPasien extends Model
{
    protected $table = 'riwayat_pasiens';
    protected $fillable = [
        'tindakan_id',
        'obat_id',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
