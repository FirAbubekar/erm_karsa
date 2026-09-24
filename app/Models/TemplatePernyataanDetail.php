<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplatePernyataanDetail extends Model
{
    use HasFactory;

    protected $table = 'template_pernyataan_detail';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_dokumen',
        'urutan',
        'jenis_informasi',
        'isi_informasi',
        'is_editable',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_editable' => 'boolean',
    ];

    public function master()
    {
        return $this->belongsTo(MasterTemplatePernyataan::class, 'kode_dokumen', 'kode_dokumen');
    }
}
