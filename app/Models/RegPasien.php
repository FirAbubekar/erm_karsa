<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegPasien extends Model
{
    protected $table = 'reg_pasien';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }
}
