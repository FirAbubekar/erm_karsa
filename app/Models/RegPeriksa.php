<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegPeriksa extends Model
{
    protected $table = 'reg_periksa';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    public function generalConsent()
    {
        return $this->hasOne(GeneralConsent::class, 'no_rawat', 'no_rawat');
    }

    public function signaturePasien()
    {
        return $this->hasOne(SignaturePasien::class, 'no_rawat', 'no_rawat');
    }
}
