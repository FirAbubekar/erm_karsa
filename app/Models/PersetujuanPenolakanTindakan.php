<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersetujuanPenolakanTindakan extends Model
{
    use HasFactory;

    protected $table = 'form_persetujuan_penolakan_tindakan';
    protected $primaryKey = 'no_pernyataan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_pernyataan',
        'no_rawat',
        'template_id',
        'kd_dokter',
        'pemberi_informasi_id',
        'path_ttd_pemberi_informasi',
        'nama_penerima_informasi',
        'hubungan_penerima',
        'path_ttd_penerima_informasi',
        'nama_yang_menyatakan',
        'hubungan_yang_menyatakan',
        'path_ttd_yang_menyatakan',
        'keputusan',
        'alasan_penolakan',
        'waktu_persetujuan',
        'nama_saksi_keluarga',
        'path_ttd_saksi_keluarga',
        'saksi_rs'
    ];

    public function template()
    {
        return $this->belongsTo(MasterTemplatePernyataan::class, 'template_id', 'kode_dokumen');
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }


    public function details()
    {
        return $this->hasMany(DetailPersetujuanPenolakanTindakan::class, 'no_pernyataan', 'no_pernyataan');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function pemberiInformasi()
    {
        return $this->belongsTo(Pegawai::class, 'pemberi_informasi_id', 'nik');
    }
}
