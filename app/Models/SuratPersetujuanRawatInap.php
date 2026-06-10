<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPersetujuanRawatInap extends Model
{
    protected $table = 'surat_persetujuan_rawat_inap';
    protected $primaryKey = 'no_surat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'no_surat',
        'no_rawat',
        'tanggal',
        'nama_pj',
        'no_ktppj',
        'pendidikan_pj',
        'alamatpj',
        'no_telppj',
        'ruang',
        'kelas',
        'hubungan',
        'hak_kelas',
        'nama_alamat_keluarga_terdekat',
        'bayar_secara',
        'nip',
    ];

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }
}
