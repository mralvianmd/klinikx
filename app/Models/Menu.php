<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillable = [
        'menu_id',
        'deskripsi',
        'route',
        'created_at',
        'updated_at'
    ];
}
