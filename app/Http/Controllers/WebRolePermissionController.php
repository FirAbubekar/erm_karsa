<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\WebPermission;
use App\Models\WebRolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WebRolePermissionController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $jabatans = Jabatan::orderBy('nm_jbtn')->get();
        $selectedRole = $request->role_id;
        
        $permissionsByGroup = WebPermission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        $activePermissions = [];

        if ($selectedRole) {
            $activePermissions = WebRolePermission::where('role_id', $selectedRole)
                ->pluck('permission_id')
                ->toArray();
        }

        return view('web-role-permissions.index', compact(
            'jabatans', 
            'selectedRole', 
            'permissionsByGroup', 
            'activePermissions'
        ));
    }

    public function sync(Request $request)
    {
        $request->validate([
            'role_id' => 'required|string',
            'permissions' => 'nullable|array' // can be null if unchecking everything
        ]);

        $roleId = $request->role_id;
        $permissions = $request->permissions ?? [];

        // Delete all old permissions for this role
        WebRolePermission::where('role_id', $roleId)->delete();

        // Insert new ones
        $inserts = [];
        foreach ($permissions as $permId) {
            $inserts[] = [
                'role_id' => $roleId,
                'permission_id' => $permId
            ];
        }

        if (count($inserts) > 0) {
            WebRolePermission::insert($inserts);
        }

        return redirect()->route('web-role-permissions.index', ['role_id' => $roleId])
            ->with('success', 'Permission untuk role ini berhasil diperbarui.');
    }
}
