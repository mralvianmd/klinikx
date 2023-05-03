<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table = 'antrians';
    protected $fillable = [
        'no_urut',
        'pasien_id',
        'keluhan',
        'status_antrian',
        'created_at',
        'updated_at'
    ];
}
