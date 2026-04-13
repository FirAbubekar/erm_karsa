<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CaraBayar extends Model
{
    use HasUuids;

    protected $fillable = [
        'kode',
        'nama',
        'status',
    ];
}
