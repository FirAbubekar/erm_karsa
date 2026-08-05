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
        $input = $request->get('no_rm') ?? $request->get('no_rawat');
        $input = trim($input);

        if (!$input) {
            return response()->json(['error' => 'No. RM harus diisi'], 400);
        }

        // Determine the no_rkm_medis. Prefer direct patient match, otherwise
        // fall back to resolving via RegPeriksa by no_rawat.
        $pasien = Pasien::where('pasien.no_rkm_medis', $input)
            ->leftJoin('kelurahan', 'pasien.kd_kel', '=', 'kelurahan.kd_kel')
            ->leftJoin('kecamatan', 'pasien.kd_kec', '=', 'kecamatan.kd_kec')
            ->leftJoin('kabupaten', 'pasien.kd_kab', '=', 'kabupaten.kd_kab')
            ->select('pasien.*', 'kelurahan.nm_kel', 'kecamatan.nm_kec', 'kabupaten.nm_kab')
            ->first();

        if (!$pasien) {
            // Try resolving through registration number (no_rawat)
            $reg = RegPeriksa::where('no_rawat', $input)->first();

            if ($reg && $reg->no_rkm_medis) {
                $pasien = Pasien::where('pasien.no_rkm_medis', $reg->no_rkm_medis)
                    ->leftJoin('kelurahan', 'pasien.kd_kel', '=', 'kelurahan.kd_kel')
                    ->leftJoin('kecamatan', 'pasien.kd_kec', '=', 'kecamatan.kd_kec')
                    ->leftJoin('kabupaten', 'pasien.kd_kab', '=', 'kabupaten.kd_kab')
                    ->select('pasien.*', 'kelurahan.nm_kel', 'kecamatan.nm_kec', 'kabupaten.nm_kab')
                    ->first();
            }
        }

        if (!$pasien) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        }

        $noRm = $pasien->no_rkm_medis;

        $matchedNoRawat = null;
        if ($request->get('no_rawat')) {
            $matchedNoRawat = $request->get('no_rawat');
        } elseif ($reg ?? null) {
            $matchedNoRawat = $reg->no_rawat;
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

        // Get History of Registrations for this patient, including their General Consent and SPRI status
        $history = RegPeriksa::with(['generalConsent', 'signaturePasien', 'suratPersetujuanRawatInap'])
            ->where('no_rkm_medis', $noRm)
            ->whereDate('tgl_registrasi', '<=', now()->toDateString())
            ->orderBy('tgl_registrasi', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'pasien' => $pasien,
            'history' => $history,
            'latest_auth_parties' => $latestAuthParties,
            'matched_no_rawat' => $matchedNoRawat
        ]);
    }
}
