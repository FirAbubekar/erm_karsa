<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Bahasa extends Model
{
    use HasUuids;

    protected $fillable = [
        'kode',
        'nama',
        'status',
    ];
}
