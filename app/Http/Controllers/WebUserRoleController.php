<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\WebUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WebUserRoleController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $query = WebUserRole::with('jabatan');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $matchingPegawaiNiks = DB::table('pegawai')->where('nama', 'like', "%{$search}%")->pluck('nik')->toArray();
            $matchingDokterKds = DB::table('dokter')->where('nm_dokter', 'like', "%{$search}%")->pluck('kd_dokter')->toArray();
            
            $allMatchingNips = array_merge($matchingPegawaiNiks, $matchingDokterKds);
            $query->whereIn('nip', $allMatchingNips);
        }

        // Get paginated roles
        $userRoles = $query->paginate(10);
        $userRoles->appends(['search' => $request->search]);

        // Map names manually since it's from 2 different tables
        // To be efficient, we can fetch all needed IDs from pegawai and dokter
        $nips = $userRoles->pluck('nip')->toArray();
        
        $pegawais = DB::table('pegawai')->whereIn('nik', $nips)->pluck('nama', 'nik');
        $dokters = DB::table('dokter')->whereIn('kd_dokter', $nips)->pluck('nm_dokter', 'kd_dokter');

        $userRoles->getCollection()->transform(function ($role) use ($pegawais, $dokters) {
            // Priority to Pegawai name, fallback to Dokter
            $role->user_name = $pegawais[$role->nip] ?? $dokters[$role->nip] ?? 'Unknown';
            return $role;
        });

        $jabatans = Jabatan::orderBy('nm_jbtn')->get();

        return view('web-user-roles.index', compact('userRoles', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'role_id' => 'required|string'
        ]);

        // Check if role already assigned to this user
        $exists = WebUserRole::where('nip', $request->nip)
            ->where('role_id', $request->role_id)
            ->exists();

        if ($exists) {
            return redirect()->route('web-user-roles.index')->withErrors(['User sudah memiliki role ini.']);
        }

        WebUserRole::create([
            'nip' => $request->nip,
            'role_id' => $request->role_id
        ]);

        return redirect()->route('web-user-roles.index')->with('success', 'Role berhasil ditambahkan ke user.');
    }

    public function destroy($id)
    {
        $userRole = WebUserRole::findOrFail($id);
        $userRole->delete();

        return redirect()->route('web-user-roles.index')->with('success', 'Role berhasil dihapus dari user.');
    }

    public function searchUser(Request $request)
    {
        $search = $request->q;

        $pegawai = DB::table('pegawai')
            ->select('nik as id', 'nama as text')
            ->where('nama', 'like', "%{$search}%")
            ->orWhere('nik', 'like', "%{$search}%");

        $dokter = DB::table('dokter')
            ->select('kd_dokter as id', 'nm_dokter as text')
            ->where('nm_dokter', 'like', "%{$search}%")
            ->orWhere('kd_dokter', 'like', "%{$search}%");

        // Limit results to prevent heavy query
        // Using union
        $query = $pegawai->union($dokter)->limit(50);
        $users = $query->get();

        // Ensure unique IDs
        $uniqueUsers = $users->unique('id')->values();

        return response()->json($uniqueUsers);
    }
}
