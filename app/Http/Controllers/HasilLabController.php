<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class HasilLabController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $search     = $request->input('search');       
        $bangsal    = $request->input('bangsal');       
        $tglMulai   = $request->input('tgl_mulai');
        $tglAkhir   = $request->input('tgl_akhir');

        // Ambil daftar bangsal dari tabel master bangsal
        $bangsalList = DB::table('bangsal')
            ->select('kd_bangsal', 'nm_bangsal')
            ->orderBy('nm_bangsal')
            ->get();

        $results = collect();
        $searched = $request->hasAny(['search', 'bangsal', 'tgl_mulai', 'tgl_akhir']);

        if ($searched) {
            $query = DB::table('lis_order_detail as lod')
                ->join('reshd as rh', 'lod.ONO', '=', 'rh.ONO')
                ->select(
                    'lod.PID',
                    'lod.PNAME',
                    'lod.ADDRESS',
                    'lod.KELURAHAN',
                    'lod.KECAMATAN',
                    'lod.KABUPATEN',
                    'lod.ONO',
                    'lod.ROOM_NO as ROOM',
                    'lod.COMMENT',
                    'lod.VISITNO',
                    'rh.CLINICIAN_NM',
                    'rh.REQUEST_DT',
                    'rh.VALIDATE_ON',
                    'rh.ORDER_TESNM as nm_perawatan',
                    'lod.SEX as jk',
                    'lod.BIRTH_DT'
                )
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($q2) use ($search) {
                        $q2->where('lod.PNAME', 'LIKE', "%{$search}%")
                           ->orWhere('lod.PID', 'LIKE', "%{$search}%")
                           ->orWhere('lod.ONO', 'LIKE', "%{$search}%");
                    });
                })
                ->when($bangsal, function ($q) use ($bangsal) {
                    $q->whereRaw("SUBSTRING_INDEX(lod.SOURCE, '^', 1) = ?", [$bangsal]);
                })
                ->when($tglMulai, function ($q) use ($tglMulai) {
                    $q->whereDate('rh.REQUEST_DT', '>=', $tglMulai);
                })
                ->when($tglAkhir, function ($q) use ($tglAkhir) {
                    $q->whereDate('rh.REQUEST_DT', '<=', $tglAkhir);
                })
                ->orderByDesc('rh.REQUEST_DT')
                ->paginate(20)
                ->withQueryString();

            $results = $query;
        }

        return view('hasil_lab.index', compact('results', 'bangsalList', 'searched', 'search', 'bangsal', 'tglMulai', 'tglAkhir'));
    }

    public function detail($ono)
    {
        // Existing detail view (still useful if they want a direct link)
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $periksaLab = DB::table('reshd as rh')
            ->join('lis_order_detail as lod', 'rh.ONO', '=', 'lod.ONO')
            ->select('rh.*', 'lod.ADDRESS', 'lod.KELURAHAN', 'lod.KECAMATAN', 'lod.KABUPATEN', 'lod.ROOM_NO as ROOM', 'rh.ORDER_TESNM as nm_perawatan')
            ->where('rh.ONO', $ono)
            ->first();

        if (!$periksaLab) abort(404);

        $detailLab = DB::table('resdt')
            ->select('TEST_NM as Pemeriksaan', 'UNIT as satuan', 'REF_RANGE as nilai_rujukan', 'RESULT_VALUE as nilai', 'FLAG as keterangan', 'DISP_SEQ as urut')
            ->where('ONO', $ono)
            ->orderBy('DISP_SEQ')
            ->get();

        $saranKesan = (object)['kesan' => $periksaLab->COMMENT, 'saran' => ''];
        return view('hasil_lab.detail', compact('periksaLab', 'detailLab', 'saranKesan'));
    }

    public function getDetailJson($ono)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $header = DB::table('reshd as rh')
            ->join('lis_order_detail as lod', 'rh.ONO', '=', 'lod.ONO')
            ->leftJoin('pasien as p', 'rh.PID', '=', 'p.no_rkm_medis')
            ->leftJoin('bangsal as b', 'b.kd_bangsal', '=', DB::raw("SUBSTRING_INDEX(lod.SOURCE, '^', 1)"))
            ->select('rh.PNAME', 'rh.PID', 'rh.ONO', 'rh.ORDER_TESNM', 'rh.REQUEST_DT', 'rh.CLINICIAN_NM', 'b.nm_bangsal as ROOM', 'rh.SEX', 'lod.BIRTH_DT', 'rh.VALIDATE_ON', 'lod.VISITNO', 'p.no_tlp')
            ->where('rh.ONO', $ono)
            ->first();

        if (!$header) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $details = DB::table('resdt')
            ->select('TEST_NM', 'RESULT_VALUE', 'UNIT', 'REF_RANGE', 'FLAG', 'TEST_GROUP')
            ->where('ONO', $ono)
            ->orderBy('DISP_SEQ')
            ->get();

        return response()->json([
            'header' => $header,
            'details' => $details
        ]);
    }

    public function downloadPDF(Request $request, $ono)
    {
        if (!Session::get('is_logged_in')) return redirect('/');

        $pdfData = $this->preparePdf($ono);
        if (!$pdfData) abort(404);

        $pdf = Pdf::loadView('hasil_lab.pdf', $pdfData);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = "Hasil_Lab_{$ono}.pdf";
        if ($request->has('download')) {
            return $pdf->download($filename);
        }
        return $pdf->stream($filename);
    }

    public function sendWhatsApp(Request $request)
    {
        $ono = $request->ono;
        $no_telp = $request->no_telp;

        if (!$ono || !$no_telp) {
            return response()->json(['message' => 'Data tidak lengkap'], 400);
        }

        $pdfData = $this->preparePdf($ono);
        if (!$pdfData) return response()->json(['message' => 'Data tidak ditemukan'], 404);

        $pdf = Pdf::loadView('hasil_lab.pdf', $pdfData);
        $pdf->setPaper('A4', 'portrait');

        $filename = "Hasil_Lab_{$ono}.pdf";
        $path = "temp_lab/{$filename}";
        
        // Ensure directory exists
        if (!Storage::exists('public/temp_lab')) {
            Storage::makeDirectory('public/temp_lab');
        }

        // Save to storage
        Storage::put("public/{$path}", $pdf->output());

        // Queue to t_antrean_wa
        $patientName = $pdfData['header']->PNAME;
        $message = "Yth. Bapak/Ibu *{$patientName}*,\n\n" .
                   "Terima kasih telah mempercayakan pemeriksaan kesehatan Anda kepada kami di *RSUD Karsa Husada Batu*.\n\n" .
                   "Bersama pesan ini, kami lampirkan dokumen digital hasil pemeriksaan laboratorium Anda dengan Nomor Laboratorium: *{$ono}*.\n\n" .
                   "Semoga sehat selalu.";
        
        $success = DB::table('t_antrean_wa')->insert([
            'no_surat' => $ono,
            'no_telp' => $no_telp,
            'pesan' => $message,
            'file_path' => "public/{$path}",
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($success) {
            return response()->json(['message' => 'Hasil Lab berhasil dimasukkan ke antrean WhatsApp']);
        } else {
            return response()->json(['message' => 'Gagal memasukkan WhatsApp ke antrean.'], 500);
        }
    }

    private function preparePdf($ono)
    {
        $header = DB::table('reshd as rh')
            ->join('lis_order_detail as lod', 'rh.ONO', '=', 'lod.ONO')
            ->leftJoin('bangsal as b', 'b.kd_bangsal', '=', DB::raw("SUBSTRING_INDEX(lod.SOURCE, '^', 1)"))
            ->select('rh.*', 'lod.ADDRESS', 'lod.KELURAHAN', 'lod.KECAMATAN', 'lod.KABUPATEN', 'b.nm_bangsal as ROOM', 'lod.BIRTH_DT', 'rh.ORDER_TESNM as nm_perawatan')
            ->where('rh.ONO', $ono)
            ->first();

        if (!$header) return null;

        $details_raw = DB::table('resdt')
            ->where('ONO', $ono)
            ->orderBy('DISP_SEQ')
            ->get();

        $validate_on = $details_raw->first()->VALIDATE_ON ?? null;
        $v_by_raw = $details_raw->first()->VALIDATE_BY ?? null;
        $validate_by = $v_by_raw;
        if (strpos($v_by_raw, '^') !== false) {
            $parts = explode('^', $v_by_raw);
            $validate_by = end($parts);
        }

        $details = $details_raw->groupBy('TEST_GROUP');

        // Fetch signature as base64 to avoid dompdf remote issues
        $sigUrl = "http://192.168.30.24/webapps/penggajian/temp/DRKEMALA.PNG";
        $sigBase64 = null;
        try {
            $imageContent = @file_get_contents($sigUrl);
            if ($imageContent) {
                $sigBase64 = 'data:image/png;base64,' . base64_encode($imageContent);
            }
        } catch (\Exception $e) {
            \Log::error("Lab PDF Sig Error: " . $e->getMessage());
        }

        return compact('header', 'details', 'sigBase64', 'validate_on', 'validate_by');
    }
}
