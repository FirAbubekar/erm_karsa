<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPermission extends Model
{
    use HasFactory;

    protected $table = 'web_permissions';

    protected $fillable = [
        'name',
        'slug',
        'group',
        'deskripsi',
    ];
}
