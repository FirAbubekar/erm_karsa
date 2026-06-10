<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PatientEducationImplementation extends Model
{
    use HasUuids;

    protected $table = 'patient_education_implementations';
    protected $primaryKey = 'id_uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'assessment_id',
        'poli_unit',
        'kode_topik',
        'nama_topik',
        'no_urut',
        'is_custom',
        'verifikasi',
        'ttd_pasien',
        'ttd_edukator',
        'ip_address_submit',
        'tgl_reedukasi',
        'created_at',
        'updated_at'

    ];

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
