<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringAsuhanGizi extends Model
{
    protected $table = 'monitoring_asuhan_gizi';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'tanggal'];

    protected $fillable = [
        'no_rawat',
        'tanggal',
        'monitoring',
        'evaluasi',
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
