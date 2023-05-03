<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasiens';
    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'tgl_lahir',
        'telepon',
        'created_at',
        'updated_at'
    ];
}
