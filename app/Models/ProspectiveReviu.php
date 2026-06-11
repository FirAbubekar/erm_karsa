<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProspectiveReviu extends Model
{
    protected $table = 'prospective_reviews';
    protected $primaryKey = 'id_uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_uuid',
        'no_rawat',
        'tanggal_reviu',
        'diagnosis',
        'tipe_antibiotik',
        'hari_ke',
        'klinis_td',
        'klinis_suhu',
        'klinis_rr',
        'klinis_spo2',
        'klinis_gcs',
        'is_demam',
        'lab_leukosit',
        'lab_neutrofil_persen',
        'lab_kreatinin',
        'lab_ureum',
        'kultur_status',
        'kultur_hasil_positif',
        'kultur_rekomendasi_antibiotik',
        'is_indikasi_tepat',
        'is_jenis_tepat',
        'is_dosis_tepat',
        'is_durasi_sesuai',
        'rekomendasi_pga',
        'rekomendasi_pga_lainnya',
        'respon_dpjp',
        'respon_catatan',
        'ttd_apoteker_klinis',
        'ttd_perawat',
        'ttd_dpjp',
        'ttd_kpra',
    ];

    protected $casts = [
        'rekomendasi_pga' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_uuid)) {
                $model->id_uuid = (string) Str::uuid();
            }
        });
    }
}
