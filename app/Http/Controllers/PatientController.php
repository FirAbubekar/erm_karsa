<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RegPeriksa;
use App\Models\GeneralConsent;
use App\Models\PelepasanInformasi;
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

        // Get Latest Pelepasan Informasi from the latest General Consent
        $latestAuthParties = [];
        $latestConsent = GeneralConsent::whereHas('regPeriksa', function ($q) use ($noRm) {
            $q->where('no_rkm_medis', $noRm);
        })->orderBy('tanggal', 'desc')->first();

        if ($latestConsent) {
            $latestAuthParties = PelepasanInformasi::where('no_surat', $latestConsent->no_surat)
                ->get(['nama', 'no_telp']);
        }

        // Get History of Registrations for this patient, including their General Consent status
        $history = RegPeriksa::with('generalConsent')
            ->where('no_rkm_medis', $noRm)
            ->orderBy('tgl_registrasi', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'pasien' => $pasien,
            'history' => $history,
            'latest_auth_parties' => $latestAuthParties
        ]);
    }
}
