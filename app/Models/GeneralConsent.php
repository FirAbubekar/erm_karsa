<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasAuditLogs;

class GeneralConsent extends Model
{
    use HasAuditLogs;

    // Public properties to hold relation data without polluting Eloquent database attributes
    public $old_pelepasan_informasi;
    public $new_pelepasan_informasi;
    public $old_signature;
    public $new_signature;

    protected $table = 'surat_persetujuan_umum';
    protected $primaryKey = 'no_surat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'no_surat',
        'no_rawat',
        'tanggal',
        'pengobatan_kepada',
        'nilai_kepercayaan',
        'nama_pj',
        'umur_pj',
        'no_ktppj',
        'jkpj',
        'bertindak_atas',
        'no_telp',
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
