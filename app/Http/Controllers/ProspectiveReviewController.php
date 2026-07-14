<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ProspectiveReviu;
use Illuminate\Support\Str;
use App\Http\Requests\StoreProspectiveReviuRequest;

class ProspectiveReviewController extends Controller
{
    /**
     * Tampilkan halaman form Prospective Reviu.
     */
    public function index(Request $request)
    {
        $id_uuid = $request->get('id_uuid');
        $no_rawat = $request->get('no_rawat');
        $no_rm = '';
        $selected_review = null;

        if ($id_uuid) {
            $selected_review = \App\Models\ProspectiveReviu::find($id_uuid);
            if ($selected_review) {
                $no_rawat = $selected_review->no_rawat;

                $dokter = \App\Models\Dokter::where('kd_dokter', $selected_review->ttd_dpjp)->first();
                $selected_review->nama_dpjp = $dokter ? $dokter->nm_dokter : $selected_review->ttd_dpjp;

                $perawat = \App\Models\Pegawai::where('nik', $selected_review->ttd_perawat)->first();
                $selected_review->nama_perawat = $perawat ? $perawat->nama : $selected_review->ttd_perawat;
            }
        }

        if ($no_rawat) {
            $pasienData = \Illuminate\Support\Facades\DB::table('reg_periksa')
                ->where('no_rawat', $no_rawat)
                ->select('no_rkm_medis')
                ->first();
            if ($pasienData) {
                $no_rm = $pasienData->no_rkm_medis;
            }
        }
        return view('prospective_reviu', compact('no_rm', 'no_rawat', 'selected_review', 'id_uuid'));
    }

    /**
     * Simpan data prospective reviu.
     */
    public function store(StoreProspectiveReviuRequest $request)
    {
        $data = $request->all();

        // Handle TTD mapping according to business rules
        $data['ttd_apoteker_klinis'] = Session::get('user_id', 'Unknown');
        $data['ttd_kpra'] = 'DRRESTI';

        $message = 'Data prospective reviu berhasil disimpan!';
        $review = null;
        if (!empty($data['id_uuid'])) {
            $review = ProspectiveReviu::find($data['id_uuid']);
            if ($review) {
                $review->update($data);
                $message = 'Data prospective reviu berhasil diperbarui!';
            } else {
                $review = ProspectiveReviu::create($data);
            }
        } else {
            $review = ProspectiveReviu::create($data);
        }

        try {
            if ($review) {
                try {
                    $pdfService = app(\App\Services\PdfService::class);
                    $pdfService->generateAndSaveProspectiveReviu($review);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Gagal generate PDF prospective reviu saat store: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal menyimpan data prospective reviu: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function history(Request $request)
    {
        $reviews = ProspectiveReviu::where('no_rawat', $request->no_rawat)
            ->orderBy('created_at', 'desc')
            ->get();

        // Map names for the UI
        foreach ($reviews as $rev) {
            $dokter = \App\Models\Dokter::where('kd_dokter', $rev->ttd_dpjp)->first();
            $rev->nama_dpjp = $dokter ? $dokter->nm_dokter : $rev->ttd_dpjp;

            $perawat = \App\Models\Pegawai::where('nik', $rev->ttd_perawat)->first();
            $rev->nama_perawat = $perawat ? $perawat->nama : $rev->ttd_perawat;
        }

        return response()->json($reviews);
    }

    public function historyPage(Request $request)
    {
        $query = ProspectiveReviu::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('no_rawat', 'like', "%{$search}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_reviu', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_reviu', '<=', $request->end_date);
        }

        $reviews = $query->paginate(20)->withQueryString();

        foreach ($reviews as $rev) {
            $dokter = \App\Models\Dokter::where('kd_dokter', $rev->ttd_dpjp)->first();
            $rev->nama_dpjp = $dokter ? $dokter->nm_dokter : $rev->ttd_dpjp;

            $perawat = \App\Models\Pegawai::where('nik', $rev->ttd_perawat)->first();
            $rev->nama_perawat = $perawat ? $perawat->nama : $rev->ttd_perawat;

            $pasienData = \Illuminate\Support\Facades\DB::table('reg_periksa')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->where('reg_periksa.no_rawat', $rev->no_rawat)
                ->select('pasien.nm_pasien', 'pasien.no_rkm_medis')
                ->first();
            $rev->nama_pasien = $pasienData ? $pasienData->nm_pasien : '-';
            $rev->no_rm = $pasienData ? $pasienData->no_rkm_medis : '-';
        }

        $stats = [
            'total' => ProspectiveReviu::count(),
            'today' => ProspectiveReviu::whereDate('tanggal_reviu', now()->toDateString())->count(),
            'month' => ProspectiveReviu::whereMonth('tanggal_reviu', now()->month)
                ->whereYear('tanggal_reviu', now()->year)->count(),
        ];

        return view('prospective_reviu.history', compact('reviews', 'stats'));
    }

    public function searchDokter(Request $request)
    {
        $term = $request->get('q');
        if (!$term || strlen(trim($term)) < 3) {
            return response()->json([]);
        }

        $query = \App\Models\Dokter::select('kd_dokter as id', 'nm_dokter as text')
            ->where('nm_dokter', 'like', "%{$term}%");

        $results = $query->limit(50)->get();
        return response()->json($results);
    }

    public function searchPegawai(Request $request)
    {
        $term = $request->get('q');
        if (!$term || strlen(trim($term)) < 3) {
            return response()->json([]);
        }

        $query = \App\Models\Pegawai::select('nik as id', 'nama as text')
            ->where('nama', 'like', "%{$term}%");

        $results = $query->limit(50)->get();
        return response()->json($results);
    }

    public function downloadPdf($id_uuid)
    {
        $review = ProspectiveReviu::findOrFail($id_uuid);

        // Map names
        $dokter = \App\Models\Dokter::where('kd_dokter', $review->ttd_dpjp)->first();
        $review->nama_dpjp = $dokter ? $dokter->nm_dokter : $review->ttd_dpjp;

        $perawat = \App\Models\Pegawai::where('nik', $review->ttd_perawat)->first();
        $review->nama_perawat = $perawat ? $perawat->nama : $review->ttd_perawat;

        $pasienData = \Illuminate\Support\Facades\DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->where('reg_periksa.no_rawat', $review->no_rawat)
            ->select('pasien.nm_pasien', 'pasien.no_rkm_medis')
            ->first();
        $review->nama_pasien = $pasienData ? $pasienData->nm_pasien : '-';
        $review->no_rm = $pasienData ? $pasienData->no_rkm_medis : '-';

        $deviceInfo = [
            'ip' => request()->ip(),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        // Signatures (barcode/QR) from Pegawai Model (which hits 192.168.30.24)
        $apotekerPegawai = Pegawai::where('nik', $review->ttd_apoteker_klinis)->first();
        $dpjpPegawai = Pegawai::where('nik', $review->ttd_dpjp)->first();
        $kpraPegawai = Pegawai::where('nik', $review->ttd_kpra)->first();

        $qrApoteker = $apotekerPegawai ? $apotekerPegawai->signature_base64 : null;
        $qrPerawat = $perawat ? $perawat->signature_base64 : null;
        $qrDpjp = $dpjpPegawai ? $dpjpPegawai->signature_base64 : null;
        $qrKpra = $kpraPegawai ? $kpraPegawai->signature_base64 : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('prospective_reviu.pdf', compact('review', 'deviceInfo', 'qrApoteker', 'qrPerawat', 'qrDpjp', 'qrKpra'));

        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Prospective_Reviu_' . str_replace('/', '_', $review->no_rawat) . '.pdf');
    }
}
