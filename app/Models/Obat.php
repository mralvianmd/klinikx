<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obats';
    protected $fillable = [
        'obat',
        'qty',
        'harga',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
