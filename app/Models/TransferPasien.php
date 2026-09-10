<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferPasien extends Model
{
    use HasFactory;

    protected $table = 'transfer_pasien_antar_ruang';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['no_rawat', 'tanggal_masuk'];

    protected $fillable = [
        'no_rawat',
        'tanggal_masuk',
        'tanggal_pindah',
        'asal_ruang',
        'ruang_selanjutnya',
        'diagnosa_utama',
        'diagnosa_sekunder',
        'indikasi_pindah_ruang',
        'keterangan_indikasi_pindah_ruang',
        'prosedur_yang_sudah_dilakukan',
        'obat_yang_telah_diberikan',
        'metode_pemindahan_pasien',
        'peralatan_yang_menyertai',
        'keterangan_peralatan_yang_menyertai',
        'pemeriksaan_penunjang_yang_dilakukan',
        'pasien_keluarga_menyetujui',
        'nama_menyetujui',
        'hubungan_menyetujui',
        'keluhan_utama_sebelum_transfer',
        'keadaan_umum_sebelum_transfer',
        'td_sebelum_transfer',
        'nadi_sebelum_transfer',
        'rr_sebelum_transfer',
        'suhu_sebelum_transfer',
        'keluhan_utama_sesudah_transfer',
        'keadaan_umum_sesudah_transfer',
        'td_sesudah_transfer',
        'nadi_sesudah_transfer',
        'rr_sesudah_transfer',
        'suhu_sesudah_transfer',
        'nip_menyerahkan',
        'nip_menerima',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_pindah' => 'datetime',
    ];

    public function asalBangsal()
    {
        return $this->belongsTo(Bangsal::class, 'asal_ruang', 'kd_bangsal');
    }

    public function tujuanBangsal()
    {
        return $this->belongsTo(Bangsal::class, 'ruang_selanjutnya', 'kd_bangsal');
    }

    public function perawatMenyerahkan()
    {
        return $this->belongsTo(Pegawai::class, 'nip_menyerahkan', 'nik');
    }

    public function perawatMenerima()
    {
        return $this->belongsTo(Pegawai::class, 'nip_menerima', 'nik');
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function scopeDateBetween($query, $start, $end)
    {
        return $query->whereBetween('tanggal_pindah', [$start, $end]);
    }

    public function getTglPindahFormattedAttribute()
    {
        return $this->tanggal_pindah ? $this->tanggal_pindah->format('d/m/Y H:i') : null;
    }

    public function getStatusPersetujuanAttribute()
    {
        return $this->pasien_keluarga_menyetujui === 'Ya' ? 'Disetujui' : 'Tidak Disetujui';
    }
}
