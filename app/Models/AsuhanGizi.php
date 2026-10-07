<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsuhanGizi extends Model
{
    protected $table = 'asuhan_gizi';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'tanggal'];

    protected $fillable = [
        'no_rawat',
        'tanggal',
        'antropometri_bb',
        'antropometri_tb',
        'antropometri_imt',
        'antropometri_lla',
        'antropometri_tl',
        'antropometri_ulna',
        'antropometri_bbideal',
        'antropometri_bbperu',
        'antropometri_tbperu',
        'antropometri_bbpertb',
        'antropometri_llaperu',
        'biokimia',
        'fisik_klinis',
        'alergi_telur',
        'alergi_susu_sapi',
        'alergi_kacang',
        'alergi_gluten',
        'alergi_udang',
        'alergi_ikan',
        'alergi_hazelnut',
        'pola_makan',
        'riwayat_personal',
        'diagnosis',
        'intervensi_gizi',
        'monitoring_evaluasi',
        'nip',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }

    public function catatanAdime()
    {
        return $this->hasMany(CatatanAdimeGizi::class, 'no_rawat', 'no_rawat');
    }
}
