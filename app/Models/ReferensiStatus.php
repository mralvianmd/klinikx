<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferensiStatus extends Model
{
    protected $table = 'referensi_statuses';
    protected $fillable = [
        'kode',
        'deskripsi',
        'created_at',
        'updated_at'
    ];
}
