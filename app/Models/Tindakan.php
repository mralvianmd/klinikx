<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    protected $table = 'tindakans';
    protected $fillable = [
        'tindakan',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
