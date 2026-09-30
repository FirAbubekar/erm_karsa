<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPersetujuanPenolakanTindakan extends Model
{
    use HasFactory;

    protected $table = 'detail_persetujuan_penolakan_tindakan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'no_pernyataan',
        'jenis_informasi',
        'isi_informasi',
        'is_checked'
    ];

    protected $casts = [];

    public function form()
    {
        return $this->belongsTo(PersetujuanPenolakanTindakan::class, 'no_pernyataan', 'no_pernyataan');
    }
}
