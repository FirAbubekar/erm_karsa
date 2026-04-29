<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GeneralConsent;
use App\Models\SignaturePasien;
use App\Models\PelepasanInformasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PdfService;
use App\Services\WhatsappService;
use Carbon\Carbon;

class GeneralConsentController extends Controller
{
    protected $pdfService;
    protected $whatsappService;

    public function __construct(PdfService $pdfService, WhatsappService $whatsappService)
    {
        $this->pdfService = $pdfService;
        $this->whatsappService = $whatsappService;
    }

    public function index(Request $request)
    {
        $query = GeneralConsent::with('regPeriksa.pasien')
            ->leftJoin(DB::raw('(SELECT no_surat, MAX(created_date) as latest_created FROM pelepasan_informasi GROUP BY no_surat) as pi'), 
                       'surat_persetujuan_umum.no_surat', '=', 'pi.no_surat')
            ->select('surat_persetujuan_umum.*');

        // Filter Search (Nama Pasien / No. RM / No. Surat)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('surat_persetujuan_umum.no_surat', 'like', "%$search%")
                  ->orWhereHas('regPeriksa.pasien', function($qp) use ($search) {
                      $qp->where('nm_pasien', 'like', "%$search%")
                         ->orWhere('no_rkm_medis', 'like', "%$search%");
                  });
            });
        }

        // Filter No. Rawat
        if ($request->filled('no_rawat')) {
            $query->where('surat_persetujuan_umum.no_rawat', 'like', "%" . $request->no_rawat . "%");
        }

        // Filter Nama PJ or Petugas
        if ($request->filled('person')) {
            $person = $request->person;
            $query->where(function($q) use ($person) {
                $q->where('surat_persetujuan_umum.nama_pj', 'like', "%$person%")
                  ->orWhereHas('pegawai', function($qp) use ($person) {
                      $qp->where('nama', 'like', "%$person%")
                         ->orWhere('nik', 'like', "%$person%");
                  });
            });
        }

        // Filter Date Range
        if ($request->filled('start_date')) {
            $query->where('surat_persetujuan_umum.tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('surat_persetujuan_umum.tanggal', '<=', $request->end_date);
        }

        $consents = $query->orderBy('surat_persetujuan_umum.tanggal', 'desc')
            ->orderBy('surat_persetujuan_umum.no_surat', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('general_consent.index', compact('consents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required|string',
            'no_rawat' => 'required|string',
            'no_rm' => 'required|string', // Added no_rm validation
            'tanggal' => 'required|date',
            'pengobatan_kepada' => 'required|string',
            'nilai_kepercayaan' => 'nullable|string',
            'nama_pj' => 'required|string',
            'umur_pj' => 'required|string',
            'no_ktppj' => 'required|string',
            'jkpj' => 'required|in:L,P',
            'bertindak_atas' => 'required|string',
            'no_telp' => 'required|string',
            'signature' => 'required|string', // Base64 string
            'auth_name_1' => 'nullable|string',
            'auth_telp_1' => 'nullable|string',
            'auth_name_2' => 'nullable|string',
            'auth_telp_2' => 'nullable|string',
            'auth_name_3' => 'nullable|string',
            'auth_telp_3' => 'nullable|string',
            'auth_name_4' => 'nullable|string',
            'auth_telp_4' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            try {
                // Ensure auth_name_1 is synced with Penanggung Jawab data as requested
                // Format: Nama / Hubungan
                $pjNameWithHubungan = $request->nama_pj . ' / ' . $request->bertindak_atas;
                $request->merge([
                    'auth_name_1' => $pjNameWithHubungan,
                    'auth_telp_1' => $request->no_telp
                ]);

                // Process Signature
                $imageData = $request->signature;
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

                // 1. Save to surat_persetujuan_umum
                $consent = GeneralConsent::updateOrCreate(
                    ['no_surat' => $request->no_surat],
                    [
                        'no_rawat' => $request->no_rawat,
                        'tanggal' => $request->tanggal,
                        'pengobatan_kepada' => $request->pengobatan_kepada,
                        'nilai_kepercayaan' => $request->nilai_kepercayaan,
                        'nama_pj' => $request->nama_pj,
                        'umur_pj' => $request->umur_pj,
                        'no_ktppj' => $request->no_ktppj,
                        'jkpj' => $request->jkpj,
                        'bertindak_atas' => $request->bertindak_atas,
                        'no_telp' => $this->formatPhoneNumber($request->no_telp),
                        'nip' => Session::get('user_id'),
                    ]
                );

                // 2. Save to signature_pasien
                SignaturePasien::create([
                    'id_uuid' => (string) Str::uuid(),
                    'no_rekamedis' => $request->no_rm,
                    'no_rawat' => $request->no_rawat,
                    'signature_path' => $path,
                ]);

                // 3. Save to pelepasan_informasi
                for ($i = 1; $i <= 4; $i++) {
                    $name = $request->{"auth_name_$i"};
                    $telp = $request->{"auth_telp_$i"};

                    if (!empty($name)) {
                        PelepasanInformasi::create([
                            'no_surat' => $request->no_surat,
                            'no_rekamedis' => $request->no_rm,
                            'nama' => $name,
                            'no_telp' => $this->formatPhoneNumber($telp) ?? '-',
                            'status' => 'aktif',
                            'created_date' => now()
                        ]);
                    }
                }

                // 4. Save to berkas_digital_perawatan
                $safeNoSurat = str_replace('/', '_', $request->no_surat);
                DB::table('berkas_digital_perawatan')->updateOrInsert(
                    ['no_rawat' => $request->no_rawat, 'kode' => '28'],
                    ['lokasi_file' => 'pages/upload/' . $safeNoSurat . '.pdf']
                );

                // 5. Generate PDF and Save to Storage
                $pdfPath = $this->pdfService->generateAndSave($consent);
                
                // 6. Send WhatsApp Notification (File directly)
                $this->whatsappService->sendFile($consent->no_telp, $pdfPath);

                return response()->json([
                    'success' => true,
                    'message' => 'Pernyataan General Consent berhasil disimpan dan PDF telah dikirim ke WhatsApp.',
                    'data' => $consent
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        });
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

    public function downloadPDF(Request $request, $no_surat)
    {
        $consent = GeneralConsent::with(['regPeriksa.pasien', 'regPeriksa.signaturePasien', 'pegawai'])
            ->where('no_surat', $no_surat)
            ->firstOrFail();

        // Fetch pelepasan_informasi by no_rekamedis
        $pelepasanInformasi = PelepasanInformasi::where('no_rekamedis', $consent->regPeriksa->pasien->no_rkm_medis)
            ->where('status', 'aktif')
            ->get();

        // Device info for footer
        $deviceInfo = [
            'ip' => $request->ip(),
            'lat' => $request->query('lat', '-'),
            'lng' => $request->query('lng', '-'),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = Pdf::loadView('general_consent.pdf', compact('consent', 'pelepasanInformasi', 'deviceInfo'));
        
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('General_Consent_' . str_replace('/', '_', $consent->no_surat) . '.pdf');
    }

    public function getPelepasanInformasi($no_surat)
    {
        // Try fetching by no_surat (new data)
        $data = PelepasanInformasi::where('no_surat', $no_surat)
            ->get(['nama', 'no_telp', 'created_date']);

        // Fallback for historical data (where no_surat is NULL)
        if ($data->isEmpty()) {
            $consent = GeneralConsent::with('regPeriksa')->where('no_surat', $no_surat)->first();
            
            if ($consent && $consent->regPeriksa) {
                // Find by medical record and current active status
                $data = PelepasanInformasi::where('no_rekamedis', $consent->regPeriksa->no_rkm_medis)
                    ->where('status', 'aktif')
                    ->get(['nama', 'no_telp', 'created_date']);
            }
        }

        return response()->json($data);
    }

    public function downloadSignedPdf(Request $request, $no_surat)
    {
        $consent = GeneralConsent::where('no_surat', $no_surat)->firstOrFail();
        $path = PdfService::getInternalPath($consent);

        if (!Storage::exists($path)) {
            abort(404, 'Dokumen PDF tidak ditemukan.');
        }

        return Storage::download($path, "General_Consent_" . str_replace('/', '_', $no_surat) . ".pdf");
    }

    public function getWaTemplate($no_surat, WhatsappService $whatsappService)
    {
        $consent = GeneralConsent::where('no_surat', $no_surat)->first();
        if (!$consent) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        return response()->json([
            'template' => $whatsappService->getDefaultFileMessage()
        ]);
    }

    public function sendWhatsappManual(Request $request, $no_surat, WhatsappService $whatsappService)
    {
        $consent = GeneralConsent::where('no_surat', $no_surat)->first();
        
        if (!$consent) {
            return response()->json([
                'success' => false,
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        $message = $request->input('message');

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'error' => 'Pesan tidak boleh kosong'
            ], 400);
        }        

        // Get the storage path for the PDF
        $pdfPath = PdfService::getInternalPath($consent);

        // Send the file via WhatsApp
        $success = $whatsappService->sendFile($consent->no_telp, $pdfPath, $message);
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'WhatsApp berhasil dikirim ke ' . $consent->no_telp
            ]);
        }       

        return response()->json([
            'success' => false,
            'error' => 'Gagal mengirim WhatsApp melalui gateway'.$pdfPath
        ], 500);
    }
}
