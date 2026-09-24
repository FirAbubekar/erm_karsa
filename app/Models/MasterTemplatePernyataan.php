<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTemplatePernyataan extends Model
{
    use HasFactory;

    protected $table = 'master_template_pernyataan';
    protected $primaryKey = 'kode_dokumen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_dokumen',
        'judul_formulir',
        'jenis_formulir',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function details()
    {
        return $this->hasMany(TemplatePernyataanDetail::class, 'kode_dokumen', 'kode_dokumen')->orderBy('urutan', 'asc');
    }
}
