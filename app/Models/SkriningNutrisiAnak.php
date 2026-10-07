<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkriningNutrisiAnak extends Model
{
    protected $table = 'skrining_nutrisi_anak';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'tanggal'];

    protected $fillable = [
        'no_rawat',
        'tanggal',
        'td',
        'hr',
        'rr',
        'suhu',
        'bb',
        'tbpb',
        'spo2',
        'alergi',
        'sg1',
        'nilai1',
        'sg2',
        'nilai2',
        'sg3',
        'nilai3',
        'sg4',
        'nilai4',
        'total_hasil',
        'skor_nutrisi',
        'diketahui_dietisien',
        'keterangan_diketahui_dietisien',
        'nip',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }
}
