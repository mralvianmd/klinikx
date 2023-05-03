<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hakakses extends Model
{
    protected $table = 'hakakses';
    protected $fillable = [
        'akses_id',
        'akses',
        'deskripsi',
        'menu_id',
        'created_at',
        'updated_at'
    ];
}
