<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'no_rkm_medis';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Usually false in legacy DBs

    protected $fillable = []; // Keeping empty for now as we only read
}
