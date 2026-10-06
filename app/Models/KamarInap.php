<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KamarInap extends Model
{
    protected $table = 'kamar_inap';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Usually false in legacy DBs

    protected $fillable = [];
}
