<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebRolePermission extends Model
{
    protected $table = 'web_role_permissions';
    public $timestamps = false;

    protected $fillable = [
        'role_id', 'permission_id'
    ];
}
