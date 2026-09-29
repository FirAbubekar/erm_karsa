<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersetujuanPenolakanTindakan;
use App\Models\DetailPersetujuanPenolakanTindakan;
use App\Models\MasterTemplatePernyataan;
use App\Models\Pegawai;
use App\Models\Dokter;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PersetujuanPenolakanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('edit')) {
            if (!hasPermission('rm007.edit')) {
                abort(403, 'Anda tidak memiliki akses (rm007.edit) untuk mengubah form ini.');
            }
        } else {
            if (!hasPermission('rm007.create')) {
                abort(403, 'Anda tidak memiliki akses (rm007.create) untuk membuat form ini.');
            }
        }

        // Index is only for the creation form. The queries below have been moved to history method.
        $templates = MasterTemplatePernyataan::where('is_active', true)->get();
        
        $stats = [
            'total' => PersetujuanPenolakanTindakan::count(),
            'today' => PersetujuanPenolakanTindakan::whereDate('waktu_persetujuan', now()->toDateString())->count(),
            'month' => PersetujuanPenolakanTindakan::whereMonth('waktu_persetujuan', now()->month)->count(),
        ];
        $loggedInUser = Pegawai::where('nik', session('user_id'))->first();

        $editData = null;
        if ($request->has('edit') && $request->has('no_pernyataan')) {
            $editData = PersetujuanPenolakanTindakan::with(['details', 'dokter', 'pemberiInformasi'])->where('no_pernyataan', $request->no_pernyataan)->first();
            if ($editData) {
                if ($editData->path_ttd_penerima_informasi && \Illuminate\Support\Facades\Storage::disk('local')->exists($editData->path_ttd_penerima_informasi)) {
                    $editData->base64_ttd_penerima = 'data:image/png;base64,' . base64_encode(\Illuminate\Support\Facades\Storage::disk('local')->get($editData->path_ttd_penerima_informasi));
                }
                if ($editData->path_ttd_yang_menyatakan && \Illuminate\Support\Facades\Storage::disk('local')->exists($editData->path_ttd_yang_menyatakan)) {
                    $editData->base64_ttd_menyatakan = 'data:image/png;base64,' . base64_encode(\Illuminate\Support\Facades\Storage::disk('local')->get($editData->path_ttd_yang_menyatakan));
                }
                if ($editData->path_ttd_saksi_keluarga && \Illuminate\Support\Facades\Storage::disk('local')->exists($editData->path_ttd_saksi_keluarga)) {
                    $editData->base64_ttd_saksi = 'data:image/png;base64,' . base64_encode(\Illuminate\Support\Facades\Storage::disk('local')->get($editData->path_ttd_saksi_keluarga));
                }
            }
        }

        return view('persetujuan_penolakan', compact('templates', 'stats', 'loggedInUser', 'editData'));
    }

    public function history(Request $request)
    {
        if (!hasPermission('rm007.view')) {
            abort(403, 'Anda tidak memiliki akses (rm007.view) untuk melihat riwayat form ini.');
        }

        $query = PersetujuanPenolakanTindakan::with(['regPeriksa.pasien', 'template']);

        $hasFilters = $request->filled('search') || $request->filled('start_date') || $request->filled('end_date');
        
        if (!$hasFilters) {
            $today = now()->toDateString();
            $request->merge(['start_date' => $today, 'end_date' => $today]);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('no_pernyataan', 'like', "%$search%")
                  ->orWhereHas('regPeriksa.pasien', function($qp) use ($search) {
                      $qp->where('nm_pasien', 'like', "%$search%")
                         ->orWhere('no_rkm_medis', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('waktu_persetujuan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('waktu_persetujuan', '<=', $request->end_date);
        }

        $pernyataans = $query->orderBy('waktu_persetujuan', 'desc')->paginate(20)->withQueryString();

        $pernyataans->getCollection()->transform(function($p) {
            $p->base64_ttd = null;
            if ($p->path_ttd_yang_menyatakan && \Illuminate\Support\Facades\Storage::disk('local')->exists($p->path_ttd_yang_menyatakan)) {
                $p->base64_ttd = 'data:image/png;base64,' . base64_encode(\Illuminate\Support\Facades\Storage::disk('local')->get($p->path_ttd_yang_menyatakan));
            }
            return $p;
        });

        $stats = [
            'total' => PersetujuanPenolakanTindakan::count(),
            'today' => PersetujuanPenolakanTindakan::whereDate('waktu_persetujuan', now()->toDateString())->count(),
            'month' => PersetujuanPenolakanTindakan::whereMonth('waktu_persetujuan', now()->month)->count(),
        ];

        $isDefaultToday = !$hasFilters;
        $activeFilters = ($request->filled('search') ? 1 : 0) + ($request->filled('start_date') ? 1 : 0) + ($request->filled('end_date') ? 1 : 0);

        return view('persetujuan_penolakan.history', compact('pernyataans', 'stats', 'isDefaultToday', 'activeFilters'));
    }

    public function getTemplateDetails($kode_dokumen)
    {
        $template = MasterTemplatePernyataan::with('details')->where('kode_dokumen', $kode_dokumen)->firstOrFail();
        return response()->json($template);
    }
    
    public function searchPegawai(Request $request)
    {
        $query = $request->get('q');
        $pegawai = Pegawai::where('nama', 'like', "%$query%")->orWhere('nik', 'like', "%$query%")->limit(10)->get();
        return response()->json($pegawai);
    }

    public function searchDokter(Request $request)
    {
        $query = $request->get('q');
        $dokter = Dokter::where('nm_dokter', 'like', "%$query%")->orWhere('kd_dokter', 'like', "%$query%")->limit(10)->get();
        return response()->json($dokter);
    }

    public function store(Request $request)
    {
        $isUpdate = $request->has('no_pernyataan') && !empty($request->no_pernyataan);

        if ($isUpdate && !hasPermission('rm007.edit')) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses (rm007.edit) untuk mengubah form ini.'], 403);
        } else if (!$isUpdate && !hasPermission('rm007.create')) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses (rm007.create) untuk membuat form ini.'], 403);
        }

        $request->validate([
            'no_rawat' => 'required',
            'template_id' => 'required',
            'kd_dokter' => 'required',
            'keputusan' => 'required|in:Setuju,Tolak',
            'pemberi_informasi_id' => 'required',
            'nama_penerima_informasi' => 'required',
            'hubungan_penerima' => 'required',
            'nama_yang_menyatakan' => 'required',
            'hubungan_yang_menyatakan' => 'required',
            'waktu_persetujuan' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $isUpdate = $request->has('no_pernyataan') && !empty($request->no_pernyataan);

            if ($isUpdate) {
                $noPernyataan = $request->no_pernyataan;
                $pernyataan = PersetujuanPenolakanTindakan::where('no_pernyataan', $noPernyataan)->firstOrFail();
            } else {
                // Generate ID PM + YYYYMMDD + 0001
                $datePrefix = 'PM' . Carbon::now()->format('Ymd');
                $lastData = PersetujuanPenolakanTindakan::where('no_pernyataan', 'like', $datePrefix . '%')
                            ->orderBy('no_pernyataan', 'desc')
                            ->first();
                
                $nextNumber = 1;
                if ($lastData) {
                    $lastNumber = (int) substr($lastData->no_pernyataan, -4);
                    $nextNumber = $lastNumber + 1;
                }
                $noPernyataan = $datePrefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }

            $directory = "persetujuan_penolakan/{$noPernyataan}";
            
            $pathTtdYangMenyatakan = $isUpdate ? $pernyataan->path_ttd_yang_menyatakan : null;
            if ($request->path_ttd_yang_menyatakan && str_starts_with($request->path_ttd_yang_menyatakan, 'data:image')) {
                if ($isUpdate && $pernyataan->path_ttd_yang_menyatakan && Storage::disk('local')->exists($pernyataan->path_ttd_yang_menyatakan)) {
                    Storage::disk('local')->delete($pernyataan->path_ttd_yang_menyatakan);
                }
                $imgData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->path_ttd_yang_menyatakan);
                $filename = 'TTD_MENYATAKAN_' . time() . '.png';
                $pathTtdYangMenyatakan = "{$directory}/{$filename}";
                Storage::disk('local')->put($pathTtdYangMenyatakan, base64_decode($imgData));
            } else if ($request->path_ttd_yang_menyatakan === 'DELETE') {
                if ($isUpdate && $pernyataan->path_ttd_yang_menyatakan && Storage::disk('local')->exists($pernyataan->path_ttd_yang_menyatakan)) {
                    Storage::disk('local')->delete($pernyataan->path_ttd_yang_menyatakan);
                }
                $pathTtdYangMenyatakan = null;
            }

            $pathTtdSaksiKeluarga = $isUpdate ? $pernyataan->path_ttd_saksi_keluarga : null;
            if ($request->path_ttd_saksi_keluarga && str_starts_with($request->path_ttd_saksi_keluarga, 'data:image')) {
                if ($isUpdate && $pernyataan->path_ttd_saksi_keluarga && Storage::disk('local')->exists($pernyataan->path_ttd_saksi_keluarga)) {
                    Storage::disk('local')->delete($pernyataan->path_ttd_saksi_keluarga);
                }
                $imgData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->path_ttd_saksi_keluarga);
                $filename = 'TTD_SAKSI_KELUARGA_' . time() . '.png';
                $pathTtdSaksiKeluarga = "{$directory}/{$filename}";
                Storage::disk('local')->put($pathTtdSaksiKeluarga, base64_decode($imgData));
            } else if ($request->path_ttd_saksi_keluarga === 'DELETE') {
                if ($isUpdate && $pernyataan->path_ttd_saksi_keluarga && Storage::disk('local')->exists($pernyataan->path_ttd_saksi_keluarga)) {
                    Storage::disk('local')->delete($pernyataan->path_ttd_saksi_keluarga);
                }
                $pathTtdSaksiKeluarga = null;
            }

            $pathTtdPenerima = $isUpdate ? $pernyataan->path_ttd_penerima_informasi : null;
            if ($request->path_ttd_penerima_informasi && str_starts_with($request->path_ttd_penerima_informasi, 'data:image')) {
                if ($isUpdate && $pernyataan->path_ttd_penerima_informasi && Storage::disk('local')->exists($pernyataan->path_ttd_penerima_informasi)) {
                    Storage::disk('local')->delete($pernyataan->path_ttd_penerima_informasi);
                }
                $imgData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->path_ttd_penerima_informasi);
                $filename = 'TTD_PENERIMA_' . time() . '.png';
                $pathTtdPenerima = "{$directory}/{$filename}";
                Storage::disk('local')->put($pathTtdPenerima, base64_decode($imgData));
            } else if ($request->path_ttd_penerima_informasi === 'DELETE') {
                if ($isUpdate && $pernyataan->path_ttd_penerima_informasi && Storage::disk('local')->exists($pernyataan->path_ttd_penerima_informasi)) {
                    Storage::disk('local')->delete($pernyataan->path_ttd_penerima_informasi);
                }
                $pathTtdPenerima = null;
            }

            $dataToSave = [
                'no_rawat' => $request->no_rawat,
                'template_id' => $request->template_id,
                'kd_dokter' => $request->kd_dokter,
                'pemberi_informasi_id' => $request->pemberi_informasi_id,
                'path_ttd_pemberi_informasi' => session('user_id'),
                'nama_penerima_informasi' => $request->nama_penerima_informasi,
                'hubungan_penerima' => $request->hubungan_penerima,
                'path_ttd_penerima_informasi' => $pathTtdPenerima,
                'nama_yang_menyatakan' => $request->nama_yang_menyatakan,
                'hubungan_yang_menyatakan' => $request->hubungan_yang_menyatakan,
                'path_ttd_yang_menyatakan' => $pathTtdYangMenyatakan,
                'keputusan' => $request->keputusan,
                'alasan_penolakan' => $request->alasan_penolakan,
                'waktu_persetujuan' => $request->waktu_persetujuan,
                'nama_saksi_keluarga' => $request->nama_saksi_keluarga,
                'path_ttd_saksi_keluarga' => $pathTtdSaksiKeluarga,
                'saksi_rs' => $request->saksi_rs,
            ];

            if ($isUpdate) {
                $pernyataan->update($dataToSave);
                DetailPersetujuanPenolakanTindakan::where('no_pernyataan', $noPernyataan)->delete();
            } else {
                $dataToSave['no_pernyataan'] = $noPernyataan;
                $pernyataan = PersetujuanPenolakanTindakan::create($dataToSave);
            }

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    DetailPersetujuanPenolakanTindakan::create([
                        'no_pernyataan' => $noPernyataan,
                        'jenis_informasi' => $detail['jenis_informasi'] ?? '-',
                        'isi_informasi' => $detail['isi_informasi'] ?? '-',
                        'is_checked' => isset($detail['is_checked']) ? 1 : 0
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan', 'data' => $pernyataan]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
    public function download(Request $request, $no_pernyataan)
    {
        $pernyataan = PersetujuanPenolakanTindakan::with(['regPeriksa.pasien', 'details', 'dokter', 'pemberiInformasi'])->where('no_pernyataan', $no_pernyataan)->firstOrFail();

        $ip = $request->header('X-Real-IP') ?? $request->header('X-Forwarded-For') ?? $request->ip();
        if (strpos($ip, ',') !== false) {
            $ip = explode(',', $ip)[0]; // get the first IP in the list
        }

        $deviceInfo = [
            'ip' => trim($ip),
            'lat' => $request->query('lat', '-'),
            'lng' => $request->query('lng', '-'),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOption('isPhpEnabled', true)->loadView('persetujuan_penolakan.pdf', [
            'pernyataan' => $pernyataan,
            'deviceInfo' => $deviceInfo
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream();
    }
}
