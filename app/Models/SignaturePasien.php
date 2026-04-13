<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignaturePasien extends Model
{
    protected $table = 'signature_pasien';
    protected $primaryKey = 'id_uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_uuid',
        'no_rekamedis',
        'no_rawat',
        'signature_path'
    ];
}
