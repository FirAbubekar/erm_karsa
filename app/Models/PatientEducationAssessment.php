<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\HasAuditLogs;

class PatientEducationAssessment extends Model
{
    use HasUuids, HasAuditLogs;

    protected $table = 'patient_education_assessments';
    protected $primaryKey = 'id_uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_rawat',
        'nama_penerima_info',
        'hubungan_dgn_pasien',
        'bahasa',
        'bahasa_lainnya',
        'perlu_penerjemah',
        'baca_dan_tulis',
        'pendidikan',
        'pendidikan_lainnya',
        'nilai_budaya',
        'gaya_pembelajaran',
        'literasi_kesehatan',
        'hambatan_edukasi',
        'hambatan_lainnya',
        'kesediaan_menerima',
        'rencana_kebutuhan',
        'rencana_lainnya',
        'tanggal_edukasi',
        'nama_pasien_wali_ttd',
        'ttd_pasien_wali',
        'ip_address_ttd',
        'created_at',
        'created_by'
    ];

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function implementations()
    {
        return $this->hasOne(PatientEducationImplementation::class, 'assessment_id', 'id_uuid');
    }
}
