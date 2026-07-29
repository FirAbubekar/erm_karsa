<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bangsal extends Model
{
    protected $table = 'bangsal';
    protected $primaryKey = 'kd_bangsal';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Usually false in legacy DBs

    protected $fillable = [];
}
