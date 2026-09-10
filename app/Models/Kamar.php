<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $primaryKey = 'kd_kamar';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Usually false in legacy DBs

    protected $fillable = [];
}
