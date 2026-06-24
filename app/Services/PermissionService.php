<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PermissionService
{
         public static function getPermissions($nik)
     {
         $rolePermissions = DB::table('web_user_roles')
             ->join(
                 'web_role_permissions',
                 'web_user_roles.role_id',
                 '=',
                 'web_role_permissions.role_id'
             )
             ->join(
                 'web_permissions',
                 'web_permissions.id',
                 '=',
                 'web_role_permissions.permission_id'
             )
             ->where('nip', $nik)
             ->pluck('slug')
             ->toArray();
 
         return collect($rolePermissions)
                 ->unique()
                 ->toArray();
     }

}