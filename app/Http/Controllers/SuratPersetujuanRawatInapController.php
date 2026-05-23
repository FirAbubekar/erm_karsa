<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratPersetujuanRawatInap;
use App\Models\SignaturePasien;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuratPersetujuanRawatInapController extends Controller
{
    public function index()
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        return view('surat_persetujuan_rawat_inap.index');
    }

    public function store(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        }

        $request->validate([
            'no_surat' => 'required|string',
            'no_rawat' => 'required|string',
            'no_rm' => 'required|string',
            'tanggal' => 'required|date',
            'hak_kelas' => 'required|in:Kelas I,Kelas II,Kelas III',
            'ruang_diinginkan' => 'required|string',
            'kelas' => 'required|in:Utama,Kelas I,Kelas II,Kelas III,VIP,VVIP',
            'pj_biaya' => 'required|string',
            'nama_asuransi' => 'nullable|string',
            'pj_nama' => 'required|string',
            'no_ktp' => 'required|string',
            'pj_umur' => 'required|numeric',
            'pendidikan_pj' => 'required|string',
            'pj_jk' => 'required|in:Laki-Laki,Perempuan',
            'pj_hubungan' => 'required|in:Diri Sendiri,Suami,Istri,Anak,Orang Tua,Wali / Kurator',
            'pj_telp' => 'required|string',
            'pj_alamat' => 'required|string',
            'pj_rt' => 'required|string',
            'pj_rw' => 'required|string',
            'pj_kelurahan' => 'required|string',
            'pj_kecamatan' => 'required|string',
            'pj_kota' => 'required|string',
            'nama_alamat_keluarga_terdekat' => 'required|string',
            'tanda_tangan' => 'required|string', // Base64 signature string
        ]);

        return DB::transaction(function () use ($request) {
            try {
                // 1. Process and Save Signature
                $imageData = $request->tanda_tangan;
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $imageBinary = base64_decode($imageData);

                // Folder structure: signatures/YYYY/MM/DD/
                $year = date('Y');
                $month = date('m');
                $day = date('d');
                $directory = "signatures/{$year}/{$month}/{$day}";
                
                $filename = 'TTD_' . str_replace('/', '', $request->no_rawat) . '_' . time() . '.png';
                $path = "{$directory}/{$filename}";

                // Save to storage (public disk)
                Storage::disk('public')->put($path, $imageBinary);

                // 2. Save Signature to signature_pasien table
                SignaturePasien::create([
                    'id_uuid' => (string) Str::uuid(),
                    'no_rekamedis' => $request->no_rm,
                    'no_rawat' => $request->no_rawat,
                    'signature_path' => $path,
                ]);

                // 3. Concatenate and format Penanggung Jawab Address
                $alamatpj = $request->pj_alamat . " RT " . $request->pj_rt . " RW " . $request->pj_rw . ", Kel. " . $request->pj_kelurahan . ", Kec. " . $request->pj_kecamatan . ", " . $request->pj_kota;
                $alamatpj = substr($alamatpj, 0, 100); // DB limit is varchar(100)

                // 4. Map Kelas and Hak Kelas
                $kelasMap = [
                    'Kelas I' => 'Kelas 1',
                    'Kelas II' => 'Kelas 2',
                    'Kelas III' => 'Kelas 3',
                    'Utama' => 'Kelas Utama',
                    'VIP' => 'Kelas VIP',
                    'VVIP' => 'Kelas VVIP',
                ];
                $kelasDb = $kelasMap[$request->kelas] ?? null;
                $hakKelasDb = $kelasMap[$request->hak_kelas] ?? '-';

                // 5. Map Hubungan Penanggung Jawab
                $hubunganDb = 'Saudara'; // Default fallback
                if ($request->pj_hubungan === 'Diri Sendiri') {
                    $hubunganDb = 'Diri Saya';
                } elseif ($request->pj_hubungan === 'Suami') {
                    $hubunganDb = 'Suami';
                } elseif ($request->pj_hubungan === 'Istri') {
                    $hubunganDb = 'Istri';
                } elseif ($request->pj_hubungan === 'Anak') {
                    $hubunganDb = 'Anak';
                } elseif ($request->pj_hubungan === 'Orang Tua') {
                    $hubunganDb = ($request->pj_jk === 'Laki-Laki') ? 'Ayah' : 'Ibu';
                }

                // 6. Map Penanggung Jawab Biaya (bayar_secara)
                $bayarSecara = $request->pj_biaya;
                if ($bayarSecara === 'Asuransi Lain' && !empty($request->nama_asuransi)) {
                    $bayarSecara = $request->nama_asuransi;
                }
                $bayarSecara = substr($bayarSecara, 0, 30); // DB limit is varchar(30)

                // 7. Save to surat_persetujuan_rawat_inap table
                $spri = SuratPersetujuanRawatInap::updateOrCreate(
                    ['no_surat' => $request->no_surat],
                    [
                        'no_rawat' => $request->no_rawat,
                        'tanggal' => $request->tanggal,
                        'nama_pj' => substr($request->pj_nama, 0, 50),
                        'no_ktppj' => substr($request->no_ktp, 0, 20),
                        'pendidikan_pj' => $request->pendidikan_pj,
                        'alamatpj' => $alamatpj,
                        'no_telppj' => substr($this->formatPhoneNumber($request->pj_telp), 0, 30),
                        'ruang' => substr($request->ruang_diinginkan, 0, 40),
                        'kelas' => $kelasDb,
                        'hubungan' => $hubunganDb,
                        'hak_kelas' => $hakKelasDb,
                        'nama_alamat_keluarga_terdekat' => substr($request->nama_alamat_keluarga_terdekat, 0, 130),
                        'bayar_secara' => $bayarSecara,
                        'nip' => Session::get('user_id') ?? $request->nip ?? '-',
                    ]
                );

                // 8. Generate PDF and save it (Internal storage, Local webapps, Remote server)
                try {
                    $spri->load(['regPeriksa.pasien', 'regPeriksa.signaturePasien', 'pegawai']);

                    $deviceInfo = [
                        'ip' => $request->ip(),
                        'lat' => $request->input('lat') ?? $request->query('lat') ?? '-',
                        'lng' => $request->input('lng') ?? $request->query('lng') ?? '-',
                        'downloaded_at' => now()->format('d/m/Y H:i:s'),
                    ];

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat_persetujuan_rawat_inap.pdf', [
                        'consent' => $spri,
                        'deviceInfo' => $deviceInfo
                    ]);
                    $pdf->setPaper('a4', 'portrait');
                    $pdfOutput = $pdf->output();

                    $year = date('Y', strtotime($spri->tanggal));
                    $month = date('m', strtotime($spri->tanggal));
                    $safeNoSurat = str_replace('/', '_', $spri->no_surat);
                    $pdfFilename = $safeNoSurat . '.pdf';

                    // Save to internal storage (Backup)
                    $internalPdfPath = "private/pdf/spri/{$year}/{$month}/spri_{$safeNoSurat}.pdf";
                    Storage::put($internalPdfPath, $pdfOutput);

                    // Save to local webapps folder
                    $localUploadPath = "D:\\xampp\\htdocs\\webapps\\berkasrawat\\pages\\upload";
                    if (!\Illuminate\Support\Facades\File::isDirectory($localUploadPath)) {
                        \Illuminate\Support\Facades\File::makeDirectory($localUploadPath, 0755, true);
                    }
                    \Illuminate\Support\Facades\File::put($localUploadPath . DIRECTORY_SEPARATOR . $pdfFilename, $pdfOutput);

                    // Send to Remote Server 192.168.30.24 via cURL POST
                    $response = \Illuminate\Support\Facades\Http::attach(
                        'pdf', $pdfOutput, $pdfFilename
                    )->post('http://192.168.30.24/webapps/berkasrawat/pages/upload_pdf.php', [
                        'no_surat' => $spri->no_surat,
                    ]);

                    \Illuminate\Support\Facades\Log::info("Respon cURL SPRI dari server .24: " . $response->body());

                } catch (\Exception $pdfEx) {
                    \Illuminate\Support\Facades\Log::error("Gagal memproses/mengirim PDF SPRI: " . $pdfEx->getMessage());
                }

                // 9. Save to database table: surat_persetujuan_rawat_inap_pembuat_pernyataan
                $safeNoSurat = str_replace('/', '_', $spri->no_surat);
                DB::table('surat_persetujuan_rawat_inap_pembuat_pernyataan')->updateOrInsert(
                    ['no_surat' => $spri->no_surat],
                    ['photo' => 'pages/upload/' . $safeNoSurat . '.pdf']
                );

                // 10. Queue to t_antrean_wa (WhatsApp Queue)
                try {
                    $namaPj = $spri->nama_pj;
                    $nmPasien = $spri->regPeriksa->pasien->nm_pasien ?? '-';
                    $noRm = $spri->regPeriksa->pasien->no_rkm_medis ?? '-';

                    $waMessage = "Yth. Bapak/Ibu *" . $namaPj . "*,\n\n" .
                                "Terima kasih telah mempercayakan pelayanan kesehatan Anda kepada kami di *RSUD Karsa Husada Batu*.\n\n" .
                                "Bersama pesan ini, kami lampirkan dokumen digital *Surat Persetujuan Rawat Inap (RM 02)* untuk Pasien *" . $nmPasien . "* (No. RM: *" . $noRm . "*).\n\n" .
                                "Mohon simpan dokumen digital ini dengan baik sebagai bukti administrasi yang sah. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.";

                    $remoteFileName = str_replace('/', '_', $spri->no_surat) . ".pdf";
                    $remoteUrl = "http://192.168.30.24/webapps/berkasrawat/pages/upload/" . $remoteFileName;

                    DB::table('t_antrean_wa')->insert([
                        'no_surat' => $spri->no_surat,
                        'no_telp' => $spri->no_telppj,
                        'pesan' => $waMessage,
                        'file_path' => $remoteUrl,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $waEx) {
                    \Illuminate\Support\Facades\Log::error("Gagal memasukkan antrean WA otomatis saat simpan SPRI: " . $waEx->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Surat Persetujuan Rawat Inap (RM 02) berhasil disimpan ke database.',
                    'data' => $spri
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    public function history(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $query = SuratPersetujuanRawatInap::with(['regPeriksa.pasien', 'regPeriksa.signaturePasien', 'pegawai']);

        // Default to today if no filters are provided
        $hasFilters = $request->filled('search') || $request->filled('no_rawat') || 
                      $request->filled('person') || $request->filled('start_date') || 
                      $request->filled('end_date');
        
        if (!$hasFilters) {
            $today = now()->toDateString();
            $request->merge(['start_date' => $today, 'end_date' => $today]);
        }

        // Filter Search (Nama Pasien / No. RM / No. Surat)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('surat_persetujuan_rawat_inap.no_surat', 'like', "%$search%")
                  ->orWhereHas('regPeriksa.pasien', function($qp) use ($search) {
                      $qp->where('nm_pasien', 'like', "%$search%")
                         ->orWhere('no_rkm_medis', 'like', "%$search%");
                  });
            });
        }

        // Filter No. Rawat
        if ($request->filled('no_rawat')) {
            $query->where('surat_persetujuan_rawat_inap.no_rawat', 'like', "%" . $request->no_rawat . "%");
        }

        // Filter Nama PJ or Petugas
        if ($request->filled('person')) {
            $person = $request->person;
            $query->where(function($q) use ($person) {
                $q->where('surat_persetujuan_rawat_inap.nama_pj', 'like', "%$person%")
                  ->orWhereHas('pegawai', function($qp) use ($person) {
                      $qp->where('nama', 'like', "%$person%")
                         ->orWhere('nik', 'like', "%$person%");
                  });
            });
        }

        // Filter Date Range
        if ($request->filled('start_date')) {
            $query->where('surat_persetujuan_rawat_inap.tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('surat_persetujuan_rawat_inap.tanggal', '<=', $request->end_date);
        }

        $consents = $query->orderBy('surat_persetujuan_rawat_inap.tanggal', 'desc')
            ->orderBy('surat_persetujuan_rawat_inap.no_surat', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Calculate Stats
        $stats = [
            'total' => SuratPersetujuanRawatInap::count(),
            'today' => SuratPersetujuanRawatInap::where('tanggal', now()->toDateString())->count(),
            'month' => SuratPersetujuanRawatInap::where('tanggal', '>=', now()->startOfMonth()->toDateString())->count(),
        ];

        $isDefaultToday = !$hasFilters;
        $activeFilters = ($request->filled('search') ? 1 : 0) + 
                          ($request->filled('no_rawat') ? 1 : 0) + 
                          ($request->filled('person') ? 1 : 0) + 
                          (($request->filled('start_date') && $request->input('start_date') !== now()->toDateString()) ? 1 : 0) +
                          (($request->filled('end_date') && $request->input('end_date') !== now()->toDateString()) ? 1 : 0);

        return view('surat_persetujuan_rawat_inap.history', compact('consents', 'stats', 'isDefaultToday', 'activeFilters'));
    }

    public function downloadPDF(Request $request, $no_surat)
    {
        $consent = SuratPersetujuanRawatInap::where('surat_persetujuan_rawat_inap.no_surat', $no_surat)
            ->firstOrFail();

        $safeNoSurat = str_replace('/', '_', $consent->no_surat);
        $namaFile = $safeNoSurat . '.pdf';
        $remoteUrl = 'http://192.168.30.24/webapps/berkasrawat/pages/upload/' . $namaFile;

        try {
            // Fetch PDF from remote server .24
            $response = \Illuminate\Support\Facades\Http::get($remoteUrl);
            
            if ($response->successful()) {
                $fileContent = $response->body();
                return response($fileContent, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="Surat_Persetujuan_Rawat_Inap_' . $namaFile . '"',
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal mendownload PDF dari remote server .24: " . $e->getMessage());
        }

        // Fallback: If remote fetch fails or file not found, compile it locally as a backup
        $consent->load(['regPeriksa.pasien', 'regPeriksa.signaturePasien', 'pegawai']);

        $deviceInfo = [
            'ip' => $request->ip(),
            'lat' => $request->input('lat') ?? $request->query('lat') ?? '-',
            'lng' => $request->input('lng') ?? $request->query('lng') ?? '-',
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat_persetujuan_rawat_inap.pdf', compact('consent', 'deviceInfo'));
        
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Surat_Persetujuan_Rawat_Inap_' . $namaFile);
    }

    public function getWaTemplate($no_surat)
    {
        $consent = SuratPersetujuanRawatInap::with(['regPeriksa.pasien'])->where('no_surat', $no_surat)->first();
        if (!$consent) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $namaPj = $consent->nama_pj;
        $nmPasien = $consent->regPeriksa->pasien->nm_pasien ?? '-';
        $noRm = $consent->regPeriksa->pasien->no_rkm_medis ?? '-';

        $template = "Yth. Bapak/Ibu *" . $namaPj . "*,\n\n" .
                    "Terima kasih telah mempercayakan pelayanan kesehatan Anda kepada kami di *RSUD Karsa Husada Batu*.\n\n" .
                    "Bersama pesan ini, kami lampirkan dokumen digital *Surat Persetujuan Rawat Inap (RM 02)* untuk Pasien *" . $nmPasien . "* (No. RM: *" . $noRm . "*).\n\n" .
                    "Mohon simpan dokumen digital ini dengan baik sebagai bukti administrasi yang sah. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.";

        return response()->json([
            'template' => $template
        ]);
    }

    public function sendWhatsappManual(Request $request)
    {
        $request->validate([
            'no_surat' => 'required|string',
            'message' => 'required|string',
        ]);

        $no_surat = $request->input('no_surat');
        $message = $request->input('message');

        $consent = SuratPersetujuanRawatInap::with(['regPeriksa.pasien', 'regPeriksa.signaturePasien', 'pegawai'])
            ->where('surat_persetujuan_rawat_inap.no_surat', $no_surat)
            ->first();

        if (!$consent) {
            return response()->json([
                'success' => false,
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        // Generate PDF and save to storage
        $deviceInfo = [
            'ip' => $request->ip(),
            'lat' => $request->input('lat') ?? $request->query('lat') ?? '-',
            'lng' => $request->input('lng') ?? $request->query('lng') ?? '-',
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat_persetujuan_rawat_inap.pdf', compact('consent', 'deviceInfo'));
        $pdf->setPaper('a4', 'portrait');
        $pdfOutput = $pdf->output();

        $year = date('Y', strtotime($consent->tanggal));
        $month = date('m', strtotime($consent->tanggal));
        $safeId = str_replace('/', '_', $consent->no_surat);
        $pdfPath = "private/pdf/spri/{$year}/{$month}/spri_{$safeId}.pdf";

        // Save PDF to storage
        Storage::put($pdfPath, $pdfOutput);

        // Queue to t_antrean_wa
        $remoteFileName = str_replace('/', '_', $consent->no_surat) . ".pdf";
        $remoteUrl = "http://192.168.30.24/webapps/berkasrawat/pages/upload/" . $remoteFileName;

        DB::table('t_antrean_wa')->insert([
            'no_surat' => $consent->no_surat,
            'no_telp' => $consent->no_telppj,
            'pesan' => $message,
            'file_path' => $remoteUrl,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan WhatsApp berhasil dimasukkan ke antrean pengiriman.'
        ]);
    }

    private function formatPhoneNumber($number)
    {
        if (empty($number) || $number === '-') return $number;
        
        // Remove non-digits
        $digits = preg_replace('/\D/', '', $number);
        
        // If it starts with 0, remove it
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }
        
        // If it already starts with 62, return it
        if (str_starts_with($digits, '62')) {
            return $digits;
        }
        
        // Prepend 62
        return '62' . $digits;
    }
}
