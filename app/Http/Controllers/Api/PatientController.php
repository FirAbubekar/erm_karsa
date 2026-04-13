<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\RegPasien;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function search(Request $request)
    {
        $noRm = $request->get('no_rm');

        if (!$noRm) {
            return response()->json(['error' => 'No. RM harus diisi'], 400);
        }

        // Get Patient Details
        $pasien = Pasien::where('no_rkm_medis', $noRm)->first();

        if (!$pasien) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        }

        // Get Registration History (latest first)
        $history = RegPasien::where('no_rkm_medis', $noRm)
            ->orderBy('tgl_registrasi', 'desc')
            ->orderBy('jam_reg', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'pasien' => $pasien,
            'history' => $history
        ]);
    }
}
