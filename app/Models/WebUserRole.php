<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebUserRole extends Model
{
    protected $table = 'web_user_roles';
    public $timestamps = false;

    protected $fillable = [
        'nip', 'role_id'
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'role_id', 'kd_jbtn');
    }

    // We can't define a straightforward relation for 'user' since it could be Pegawai or Dokter
    // But we can get the name manually in the controller/view or via accessor if needed
}
