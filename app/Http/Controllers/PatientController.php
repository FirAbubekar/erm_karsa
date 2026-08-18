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

        $searchTerms = [$noRm];
        if (is_numeric($noRm)) {
            $pad8 = str_pad($noRm, 8, '0', STR_PAD_LEFT);
            if ($pad8 !== $noRm) $searchTerms[] = $pad8;
            
            $pad6 = str_pad($noRm, 6, '0', STR_PAD_LEFT);
            if ($pad6 !== $noRm && strlen($noRm) < 6) $searchTerms[] = $pad6;
        }

        // Get Patient Details - Prioritaskan exact match, lalu pad 8, lalu pad 6
        $pasien = null;
        foreach ($searchTerms as $term) {
            $pasien = Pasien::where('pasien.no_rkm_medis', $term)
                ->leftJoin('kelurahan', 'pasien.kd_kel', '=', 'kelurahan.kd_kel')
                ->leftJoin('kecamatan', 'pasien.kd_kec', '=', 'kecamatan.kd_kec')
                ->leftJoin('kabupaten', 'pasien.kd_kab', '=', 'kabupaten.kd_kab')
                ->select('pasien.*', 'kelurahan.nm_kel', 'kecamatan.nm_kec', 'kabupaten.nm_kab')
                ->first();
                
            if ($pasien) {
                break;
            }
        }

        if (!$pasien) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        }

        // Get Latest Pelepasan Informasi from the latest General Consent
        $latestAuthParties = [];
        $latestConsent = GeneralConsent::whereHas('regPeriksa', function ($q) use ($pasien) {
            $q->where('no_rkm_medis', $pasien->no_rkm_medis);
        })->orderBy('tanggal', 'desc')->first();

        if ($latestConsent) {
            $latestAuthParties = PelepasanInformasi::where('no_surat', $latestConsent->no_surat)
                ->get(['nama', 'no_telp']);
        }

        // Get History of Registrations for this patient, including their General Consent and SPRI status
        $history = RegPeriksa::with(['generalConsent', 'signaturePasien', 'suratPersetujuanRawatInap'])
            ->where('no_rkm_medis', $pasien->no_rkm_medis)
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
