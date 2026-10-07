<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\RegPeriksa;
use App\Models\Bangsal;
use App\Models\SkriningNutrisiAnak;
use App\Models\SkriningNutrisiDewasa;
use App\Models\SkriningNutrisiLansia;
use App\Models\AsuhanGizi;
use App\Models\CatatanAdimeGizi;
use App\Models\MonitoringAsuhanGizi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class InstalasiGiziController extends Controller
{
    /* ===================== SKRINING GIZI ===================== */

    public function skriningIndex(Request $request)
    {
        $bangsalList = Bangsal::where('status', '1')
            ->orderBy('nm_bangsal', 'asc')
            ->select('kd_bangsal', 'nm_bangsal')
            ->get();

        $petugasList = Pegawai::where('stts_aktif', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->select('nik', 'nama')
            ->get();

        $bangsal = $request->bangsal;
        $search = $request->search;

        $hasRoomFilter = $request->filled('bangsal');
        $pasienList = collect();

        if ($hasRoomFilter) {
            $pasienList = DB::table('kamar_inap as ki')
                ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
                ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
                ->join('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
                ->join('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
                ->leftJoin('kamar_inap as check_latest', function($join) {
                    $join->on('check_latest.no_rawat', '=', 'ki.no_rawat')
                         ->on('check_latest.tgl_masuk', '>', 'ki.tgl_masuk');
                })
                ->whereNull('check_latest.no_rawat') // Only get their latest room assignment
                ->where(function ($q) {
                    $q->whereNull('ki.tgl_keluar')
                      ->orWhere('ki.tgl_keluar', '0000-00-00');
                })
                ->where('k.kd_bangsal', $bangsal)
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($q2) use ($search) {
                        $q2->where('p.nm_pasien', 'like', '%' . $search . '%')
                           ->orWhere('rp.no_rkm_medis', 'like', '%' . $search . '%')
                           ->orWhere('ki.no_rawat', 'like', '%' . $search . '%');
                    });
                })
                ->select(
                    'ki.no_rawat',
                    'rp.no_rkm_medis',
                    'p.nm_pasien',
                    'p.jk',
                    'p.tgl_lahir',
                    'ki.kd_kamar',
                    'k.kd_bangsal',
                    'b.nm_bangsal',
                    'ki.diagnosa_awal',
                    DB::raw("(SELECT MAX(tanggal) FROM asuhan_gizi WHERE no_rawat = ki.no_rawat) as tgl_asuhan_terakhir")
                )
                ->get();
        }

        return view('instalasi_gizi.skrining', compact('bangsalList', 'petugasList', 'pasienList', 'hasRoomFilter'));
    }

    public function skriningStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_rawat' => 'required|string|max:17',
            'tanggal'  => 'required|date',
            'nip'      => 'required|string',
        ], [
            'no_rawat.required' => 'No. Rawat wajib diisi.',
            'tanggal.required'  => 'Tanggal wajib diisi.',
            'nip.required'      => 'Petugas wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal. Silakan periksa kembali input Anda.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $jam = '00:00:00';
            if ($request->filled('jam_h') && $request->filled('jam_m') && $request->filled('jam_s')) {
                $jam = $request->jam_h . ':' . $request->jam_m . ':' . $request->jam_s;
            } else {
                $jam = Carbon::now()->format('H:i:s');
            }

            $datetime = Carbon::parse($request->tanggal)->format('Y-m-d') . ' ' . $jam;

            // Delete existing records on the same day for this rawat if needed, or update. SIMRS Khanza uses composite primary key.
            AsuhanGizi::updateOrCreate(
                ['no_rawat' => $request->no_rawat, 'tanggal' => $datetime],
                [
                    'antropometri_bb'       => $request->antropometri_bb ?? '-',
                    'antropometri_tb'       => $request->antropometri_tb ?? '-',
                    'antropometri_imt'      => $request->antropometri_imt ?? '-',
                    'antropometri_statusgizi'=> $request->antropometri_statusgizi ?? '-',
                    'antropometri_lila'     => $request->antropometri_lila ?? '-',
                    'antropometri_tl'       => $request->antropometri_tl ?? '-',
                    'antropometri_ulna'     => $request->antropometri_ulna ?? '-',
                    'antropometri_bbideal'  => $request->antropometri_bbideal ?? '-',
                    'antropometri_bbperu'   => $request->antropometri_bbperu ?? '-',
                    'antropometri_tbperu'   => $request->antropometri_tbperu ?? '-',
                    'antropometri_bbpertb'  => $request->antropometri_bbpertb ?? '-',
                    'antropometri_llaperu'  => $request->antropometri_llaperu ?? '-',
                    'biokimia'              => $request->biokimia ?? '-',
                    'fisik_klinis'          => $request->fisik_klinis ?? '-',
                    'alergi_telur'          => $request->alergi_telur ?? 'Tidak',
                    'alergi_susu_sapi'      => $request->alergi_susu_sapi ?? 'Tidak',
                    'alergi_kacang'         => $request->alergi_kacang ?? 'Tidak',
                    'alergi_gluten'         => $request->alergi_gluten ?? 'Tidak',
                    'alergi_udang'          => $request->alergi_udang ?? 'Keep', // Fallback
                    'alergi_udang'          => $request->alergi_udang ?? 'Tidak',
                    'alergi_ikan'           => $request->alergi_ikan ?? 'Tidak',
                    'alergi_hazelnut'       => $request->alergi_hazelnut ?? 'Tidak',
                    'pola_makan'            => $request->pola_makan ?? '-',
                    'riwayat_personal'      => $request->riwayat_personal ?? '-',
                    'diagnosis'             => $request->diagnosis ?? '-',
                    'intervensi_gizi'       => $request->intervensi_gizi ?? '-',
                    'monitoring_evaluasi'   => $request->monitoring_evaluasi ?? '-',
                    'nip'                   => $request->nip,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asuhan Gizi berhasil disimpan.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error menyimpan Asuhan Gizi:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function skriningRiwayat(Request $request)
    {
        $dateFrom = $request->filled('start_date') ? $request->start_date : Carbon::today()->toDateString();
        $dateTo   = $request->filled('end_date') ? $request->end_date : Carbon::today()->toDateString();

        $asuhan = DB::table('asuhan_gizi as ag')
            ->join('reg_periksa as r', 'r.no_rawat', '=', 'ag.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'r.no_rkm_medis')
            ->leftJoin('pegawai as peg', 'peg.nik', '=', 'ag.nip')
            ->select(
                'ag.no_rawat',
                'ag.tanggal',
                'ag.antropometri_bb',
                'ag.antropometri_tb',
                'ag.antropometri_imt',
                'ag.antropometri_statusgizi',
                'ag.antropometri_lila',
                'ag.antropometri_tl',
                'ag.antropometri_ulna',
                'ag.antropometri_bbideal',
                'ag.antropometri_bbperu',
                'ag.antropometri_tbperu',
                'ag.antropometri_bbpertb',
                'ag.antropometri_llaperu',
                'ag.biokimia',
                'ag.fisik_klinis',
                'ag.alergi_telur',
                'ag.alergi_susu_sapi',
                'ag.alergi_kacang',
                'ag.alergi_gluten',
                'ag.alergi_udang',
                'ag.alergi_ikan',
                'ag.alergi_hazelnut',
                'ag.pola_makan',
                'ag.riwayat_personal',
                'ag.diagnosis',
                'ag.intervensi_gizi',
                'ag.monitoring_evaluasi',
                'ag.nip',
                'peg.nama as nama_petugas',
                'r.no_rkm_medis',
                'p.nm_pasien',
                'p.jk',
                'p.tgl_lahir',
                DB::raw("(SELECT b.nm_bangsal 
                          FROM kamar_inap ki 
                          JOIN kamar k ON k.kd_kamar = ki.kd_kamar 
                          JOIN bangsal b ON b.kd_bangsal = k.kd_bangsal 
                          WHERE ki.no_rawat = ag.no_rawat 
                          ORDER BY ki.tgl_masuk DESC, ki.jam_masuk DESC 
                          LIMIT 1) as ruangan")
            )
            ->whereDate('ag.tanggal', '>=', $dateFrom)
            ->whereDate('ag.tanggal', '<=', $dateTo)
            ->when($request->filled('no_rawat'), function ($q) use ($request) {
                $q->where('ag.no_rawat', 'like', '%' . $request->no_rawat . '%');
            })
            ->when($request->filled('nama_pasien'), function ($q) use ($request) {
                $q->where('p.nm_pasien', 'like', '%' . $request->nama_pasien . '%');
            })
            ->when($request->filled('petugas'), function ($q) use ($request) {
                $q->where(function($q2) use ($request) {
                    $q2->where('peg.nama', 'like', '%' . $request->petugas . '%')
                       ->orWhere('ag.nip', 'like', '%' . $request->petugas . '%');
                });
            })
            ->orderBy('ag.tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        $todayCount = DB::table('asuhan_gizi')
            ->whereDate('tanggal', Carbon::today())
            ->count();

        return view('instalasi_gizi.skrining_riwayat', compact('asuhan', 'dateFrom', 'dateTo', 'todayCount'));
    }

    /* ===================== ASUHAN GIZI (ADIME) ===================== */

    public function asuhanIndex(Request $request)
    {
        $bangsalList = Bangsal::where('status', '1')
            ->orderBy('nm_bangsal', 'asc')
            ->select('kd_bangsal', 'nm_bangsal')
            ->get();

        $petugasList = Pegawai::where('stts_aktif', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->select('nik', 'nama')
            ->get();

        $hasRoomFilter = $request->filled('bangsal');
        $pasienList = collect();

        if ($hasRoomFilter) {
            $pasienList = DB::table('kamar_inap as ki')
                ->join('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
                ->join('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
                ->join('reg_periksa as r', 'r.no_rawat', '=', 'ki.no_rawat')
                ->join('pasien as p', 'p.no_rkm_medis', '=', 'r.no_rkm_medis')
                ->select(
                    'ki.no_rawat',
                    'ki.kd_kamar',
                    'k.kd_bangsal',
                    'k.kelas',
                    'b.nm_bangsal',
                    'r.no_rkm_medis',
                    'p.nm_pasien',
                    'p.tgl_lahir',
                    'p.jk',
                    DB::raw("(SELECT MAX(tanggal) FROM catatan_adime_gizi WHERE no_rawat = ki.no_rawat) as tgl_adime_terakhir")
                )
                ->where('k.kd_bangsal', $request->bangsal)
                ->where(function ($q) {
                    $q->whereNull('ki.tgl_keluar')
                      ->orWhere('ki.tgl_keluar', '0000-00-00');
                })
                ->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($q2) use ($search) {
                        $q2->where('p.nm_pasien', 'like', '%' . $search . '%')
                           ->orWhere('r.no_rkm_medis', 'like', '%' . $search . '%')
                           ->orWhere('ki.no_rawat', 'like', '%' . $search . '%');
                    });
                })
                ->orderBy('ki.kd_kamar')
                ->get();
        }

        return view('instalasi_gizi.asuhan', compact('bangsalList', 'petugasList', 'pasienList', 'hasRoomFilter'));
    }

    public function asuhanStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_rawat'  => 'required|string|max:17',
            'tanggal'   => 'required|date_format:Y-m-d',
            'jam'       => 'required|string',
            'nip'       => 'required|string|max:20',
            'asesmen'   => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'intervensi'=> 'nullable|string',
            'monitoring'=> 'nullable|string',
            'evaluasi'  => 'nullable|string',
            'instruksi' => 'nullable|string',
        ], [
            'no_rawat.required' => 'No. Rawat wajib diisi.',
            'tanggal.required'  => 'Tanggal wajib diisi.',
            'nip.required'      => 'Petugas wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Silakan periksa kembali input Anda.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $jam = trim($request->jam);
            if (strlen($jam) === 5) {
                $jam .= ':00';
            }
            $datetime = $request->tanggal . ' ' . $jam;

            // Check duplicate
            if ($request->filled('old_tanggal') && $request->old_tanggal !== $datetime) {
                $exists = CatatanAdimeGizi::where('no_rawat', $request->no_rawat)
                    ->where('tanggal', $datetime)
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Catatan ADIME Gizi untuk waktu baru ini sudah ada.',
                    ], 400);
                }
            } else if (!$request->filled('old_tanggal')) {
                $exists = CatatanAdimeGizi::where('no_rawat', $request->no_rawat)
                    ->where('tanggal', $datetime)
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Catatan ADIME Gizi untuk waktu ini sudah ada.',
                    ], 400);
                }
            }

            $currentUserId = Session::get('user_id');
            if (empty($currentUserId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi login Anda tidak valid atau telah berakhir.',
                ], 401);
            }

            if ($request->filled('old_tanggal')) {
                // Find existing record to verify ownership
                $existingRecord = CatatanAdimeGizi::where('no_rawat', $request->no_rawat)
                    ->where(function($q) use ($request) {
                        $q->where('tanggal', $request->old_tanggal)
                          ->orWhere('tanggal', date('Y-m-d H:i:s', strtotime($request->old_tanggal)));
                    })
                    ->first();

                if (!$existingRecord) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Catatan ADIME yang akan diedit tidak ditemukan.',
                    ], 404);
                }

                // Strictly verify that only the creator can edit this record
                if ($existingRecord->nip !== $currentUserId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya berhak mengubah catatan ADIME yang Anda buat sendiri. Data dari akun lain hanya dapat disalin sebagai acuan/inputan baru.',
                    ], 403);
                }

                $rawOldTanggal = $existingRecord->getRawOriginal('tanggal') ?? $existingRecord->tanggal;

                CatatanAdimeGizi::where('no_rawat', $request->no_rawat)
                    ->where('tanggal', $rawOldTanggal)
                    ->update([
                        'tanggal'    => $datetime,
                        'asesmen'    => $request->asesmen,
                        'diagnosis'  => $request->diagnosis,
                        'intervensi' => $request->intervensi,
                        'monitoring' => $request->monitoring,
                        'evaluasi'   => $request->evaluasi,
                        'instruksi'  => $request->instruksi,
                        'nip'        => $currentUserId,
                    ]);
            } else {
                CatatanAdimeGizi::create([
                    'no_rawat'   => $request->no_rawat,
                    'tanggal'    => $datetime,
                    'asesmen'    => $request->asesmen,
                    'diagnosis'  => $request->diagnosis,
                    'intervensi' => $request->intervensi,
                    'monitoring' => $request->monitoring,
                    'evaluasi'   => $request->evaluasi,
                    'instruksi'  => $request->instruksi,
                    'nip'        => $currentUserId,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Catatan ADIME Gizi berhasil disimpan.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error menyimpan Catatan ADIME Gizi:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function asuhanDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_rawat' => 'required|string',
            'tanggal'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter tidak lengkap untuk menghapus data.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $currentUserId = Session::get('user_id');
            if (empty($currentUserId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi login Anda tidak valid atau telah berakhir.'
                ], 401);
            }

            $record = CatatanAdimeGizi::where('no_rawat', $request->no_rawat)
                ->where(function($q) use ($request) {
                    $q->where('tanggal', $request->tanggal)
                      ->orWhere('tanggal', date('Y-m-d H:i:s', strtotime($request->tanggal)));
                })
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catatan ADIME tidak ditemukan.'
                ], 404);
            }

            // Strictly check if current logged-in user is the creator (nip)
            if ($record->nip !== $currentUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya berhak menghapus catatan ADIME yang Anda buat sendiri.'
                ], 403);
            }

            $rawTanggal = $record->getRawOriginal('tanggal') ?? $record->tanggal;

            CatatanAdimeGizi::where('no_rawat', $record->no_rawat)
                ->where('tanggal', $rawTanggal)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catatan ADIME Gizi berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error menghapus Catatan ADIME Gizi:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /* ===================== PERMINTAAN GIZI (MONITORING RANAP) ===================== */

    public function permintaanIndex(Request $request)
    {
        $tanggal = $request->filled('tanggal') ? $request->tanggal : Carbon::today()->toDateString();

        $bangsalList = Bangsal::where('status', '1')
            ->orderBy('nm_bangsal', 'asc')
            ->select('kd_bangsal', 'nm_bangsal')
            ->get();

        $kelasList = ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas Utama', 'Kelas VIP', 'Kelas VVIP'];

        // Opsi form buat permintaan gizi (DB sim_gizi)
        $bentukMakanOptions  = DB::connection('gizi')->table('t_bentukmakan')->where('status', '1')->orderBy('id')->get();
        $dietOptions         = DB::connection('gizi')->table('t_diet')->where('status', '1')->orderBy('id')->get();
        $alergiOptions       = DB::connection('gizi')->table('t_alergi')->where('status', '1')->orderBy('id')->get();
        $diagnosaGiziOptions = DB::connection('gizi')->table('t_diagnosagizi')->where('status', '1')->orderBy('id')->get();

        $grouped               = collect();
        $waktuColumns          = [];
        $permintaanPerPasien   = [];
        $pasienInfo            = [];
        $hasRoomFilter         = $request->filled('bangsal');

        // Hanya jalankan query pasien ketika ruangan sudah dipilih,
        // agar halaman pertama tidak memuat seluruh pasien rawat inap.
        if ($hasRoomFilter) {
            $query = DB::table('kamar_inap as ki')
                ->join('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
                ->join('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
                ->join('reg_periksa as r', 'r.no_rawat', '=', 'ki.no_rawat')
                ->join('pasien as p', 'p.no_rkm_medis', '=', 'r.no_rkm_medis')
                ->select(
                    'ki.no_rawat',
                    'ki.kd_kamar',
                    'ki.diagnosa_awal',
                    'ki.tgl_masuk',
                    'ki.tgl_keluar',
                    'k.kd_bangsal',
                    'k.kelas',
                    'b.nm_bangsal',
                    'r.no_rkm_medis',
                    'p.nm_pasien',
                    'p.tgl_lahir',
                    'p.jk'
                )
                ->where('k.kd_bangsal', $request->bangsal)
                ->whereDate('ki.tgl_masuk', '<=', $tanggal)
                ->whereRaw("(ki.tgl_keluar IS NULL OR ki.tgl_keluar = '0000-00-00' OR ki.tgl_keluar >= ?)", [$tanggal]);

            if ($request->filled('kelas')) {
                $query->where('k.kelas', $request->kelas);
            }
            if ($request->filled('nama_pasien')) {
                $query->where('p.nm_pasien', 'like', '%' . $request->nama_pasien . '%');
            }
            if ($request->filled('no_rm')) {
                $query->where('r.no_rkm_medis', 'like', '%' . $request->no_rm . '%');
            }
            if ($request->filled('no_rawat')) {
                $query->where('ki.no_rawat', 'like', '%' . $request->no_rawat . '%');
            }

            $pasienList = $query->orderBy('b.nm_bangsal')->orderBy('ki.no_rawat')->get();

            foreach ($pasienList as $p) {
                $pasienInfo[$p->no_rawat] = [
                    'noRawat'      => $p->no_rawat,
                    'no_rekamedis' => $p->no_rkm_medis,
                    'nama_pasien'  => $p->nm_pasien,
                    'kdRanap'      => $p->kd_bangsal,
                    'nmRanap'      => $p->nm_bangsal,
                    'kdKelas'      => $p->kelas,
                    'kdBed'        => $p->kd_kamar,
                ];
            }

            if ($pasienList->isNotEmpty()) {
                // === Permintaan gizi dari DB sim_gizi (t_permintaan) ===
                $bentukMakanRows = DB::connection('gizi')->table('t_bentukmakan')->get();
                $bentukMakanMap  = [];
                foreach ($bentukMakanRows as $b) {
                    $bentukMakanMap[trim($b->kd_bentukmakan)] = $b->nama_bentukmakan;
                }

                $permintaans = DB::connection('gizi')
                    ->table('t_permintaan as tp')
                    ->leftJoin('t_detail_permintaan_diet as td', 'td.kdPermintaan', '=', 'tp.kdPermintaan')
                    ->select(
                        'tp.tglPermintaan',
                        'tp.noRawat',
                        'tp.no_rekamedis',
                        'tp.nama_pasien',
                        'tp.kdRanap',
                        'tp.nmRanap',
                        'tp.kdKelas',
                        'tp.kdBed',
                        'tp.kdAlergi',
                        'tp.alergi',
                        'tp.diagnosaGizi',
                        'tp.kdAhliGizi',
                        'tp.waktu',
                        'tp.kdBentukMakan',
                        'tp.extraDiet',
                        'tp.keteranganSnack',
                        'tp.keteranganDiet',
                        'tp.statusUpdate',
                        'tp.kdPermintaan',
                        'td.kdDiet',
                        'td.namaDiet',
                        'td.dateCreated',
                        'td.status'
                    )
                    ->where('tp.statusUpdate', '1')
                    ->where('tp.kdRanap', $request->bangsal)
                    ->whereIn('tp.noRawat', $pasienList->pluck('no_rawat'))
                    ->orderBy('tp.waktu')
                    ->get();

                foreach ($permintaans as $p) {
                    $waktuColumns[$p->waktu] = true;

                    if (!isset($permintaanPerPasien[$p->noRawat]['info'])) {
                        $permintaanPerPasien[$p->noRawat]['info'] = [
                            'tglPermintaan' => $p->tglPermintaan,
                            'noRawat'       => $p->noRawat,
                            'no_rekamedis'  => $p->no_rekamedis,
                            'nama_pasien'   => $p->nama_pasien,
                            'kdRanap'       => $p->kdRanap,
                            'nmRanap'       => $p->nmRanap,
                            'kdKelas'       => $p->kdKelas,
                            'kdBed'         => $p->kdBed,
                            'kdAlergi'      => $p->kdAlergi,
                            'alergi'        => $p->alergi,
                            'diagnosaGizi'  => $p->diagnosaGizi,
                            'kdAhliGizi'    => $p->kdAhliGizi,
                            'statusUpdate'  => $p->statusUpdate,
                        ];
                    }

                    $w = $p->waktu;
                    $permintaanPerPasien[$p->noRawat]['waktu'][$w]['kdPermintaan']    = $p->kdPermintaan;
                    $permintaanPerPasien[$p->noRawat]['waktu'][$w]['bentuk']          = $bentukMakanMap[trim($p->kdBentukMakan)] ?? trim($p->kdBentukMakan);
                    $permintaanPerPasien[$p->noRawat]['waktu'][$w]['extraDiet']       = $p->extraDiet;
                    $permintaanPerPasien[$p->noRawat]['waktu'][$w]['keteranganSnack'] = $p->keteranganSnack;
                    $permintaanPerPasien[$p->noRawat]['waktu'][$w]['keteranganDiet']  = $p->keteranganDiet;

                    if (!empty($p->namaDiet)) {
                        $permintaanPerPasien[$p->noRawat]['waktu'][$w]['diets'][] = [
                            'kdPermintaan' => $p->kdPermintaan,
                            'kdDiet'       => $p->kdDiet,
                            'namaDiet'     => $p->namaDiet,
                            'dateCreated'  => $p->dateCreated,
                            'status'       => $p->status,
                        ];
                    }
                }
            }

            $filterStatus = $request->input('filter_status');
            if ($filterStatus && $pasienList->isNotEmpty()) {
                $pasienList = $pasienList->filter(function ($p) use ($filterStatus, $permintaanPerPasien) {
                    $existingWaktu = [];
                    if (isset($permintaanPerPasien[$p->no_rawat]['waktu'])) {
                        foreach (array_keys($permintaanPerPasien[$p->no_rawat]['waktu']) as $wKey) {
                            $existingWaktu[strtoupper(trim($wKey))] = true;
                        }
                    }

                    $hasPagi  = isset($existingWaktu['PAGI'])  || isset($existingWaktu['PAGI2'])  || isset($existingWaktu['PAGI3']);
                    $hasSiang = isset($existingWaktu['SIANG']) || isset($existingWaktu['SIANG2']) || isset($existingWaktu['SIANG3']);
                    $hasSore  = isset($existingWaktu['SORE'])  || isset($existingWaktu['SORE2'])  || isset($existingWaktu['SORE3']);
                    $hasAny   = !empty($existingWaktu);
                    $hasAll   = $hasPagi && $hasSiang && $hasSore;

                    if ($filterStatus === 'belum_pagi') {
                        return !$hasPagi;
                    } elseif ($filterStatus === 'belum_siang') {
                        return !$hasSiang;
                    } elseif ($filterStatus === 'belum_sore') {
                        return !$hasSore;
                    } elseif ($filterStatus === 'belum_semua') {
                        return !$hasAny;
                    } elseif ($filterStatus === 'sudah_pagi') {
                        return $hasPagi;
                    } elseif ($filterStatus === 'sudah_siang') {
                        return $hasSiang;
                    } elseif ($filterStatus === 'sudah_sore') {
                        return $hasSore;
                    } elseif ($filterStatus === 'sudah_semua') {
                        return $hasAll;
                    } elseif ($filterStatus === 'sudah_ada') {
                        return $hasAny;
                    }

                    return true;
                });
            }

            $grouped = $pasienList->groupBy('nm_bangsal')->sortKeys();
        }

        // Urutkan kolom waktu makan sesuai urutan standar
        $waktuColumns = array_keys($waktuColumns);
        $waktuOrder   = ['PAGI', 'PAGI2', 'PAGI3', 'SIANG', 'SIANG2', 'SIANG3', 'SORE', 'SORE2', 'SORE3', 'MALAM', 'MALAM2', 'MALAM3'];
        usort($waktuColumns, function ($a, $b) use ($waktuOrder) {
            $ia = array_search(strtoupper($a), $waktuOrder);
            $ib = array_search(strtoupper($b), $waktuOrder);
            $ia = $ia === false ? 99 : $ia;
            $ib = $ib === false ? 99 : $ib;
            return $ia <=> $ib;
        });

        $stats = [
            'total_pasien'   => $grouped->flatten()->count(),
            'total_ruangan'  => $grouped->count(),
            'total_beri_diet'=> count($permintaanPerPasien),
        ];

        // === Hitung Rekapitulasi Dapur per Ruangan ===
        $ranapQuery = DB::table('kamar_inap as ki')
            ->join('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->join('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
            ->join('reg_periksa as r', 'r.no_rawat', '=', 'ki.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'r.no_rkm_medis')
            ->select('ki.no_rawat', 'k.kd_bangsal', 'b.nm_bangsal')
            ->whereDate('ki.tgl_masuk', '<=', $tanggal)
            ->whereRaw("(ki.tgl_keluar IS NULL OR ki.tgl_keluar = '0000-00-00' OR ki.tgl_keluar >= ?)", [$tanggal]);

        if ($request->filled('bangsal')) {
            $ranapQuery->where('k.kd_bangsal', $request->bangsal);
        }
        if ($request->filled('kelas')) {
            $ranapQuery->where('k.kelas', $request->kelas);
        }

        $ranapPasienIds = $ranapQuery->pluck('no_rawat');

        $rekapData = [];
        if ($ranapPasienIds->isNotEmpty()) {
            $rekapPermintaans = DB::connection('gizi')
                ->table('t_permintaan as tp')
                ->leftJoin('t_detail_permintaan_diet as td', 'td.kdPermintaan', '=', 'tp.kdPermintaan')
                ->select(
                    'tp.kdPermintaan',
                    'tp.nmRanap',
                    'tp.waktu',
                    'tp.kdBentukMakan',
                    'td.namaDiet'
                )
                ->where('tp.statusUpdate', '1')
                ->whereIn('tp.noRawat', $ranapPasienIds)
                ->get();

            $bentukMakanRows = DB::connection('gizi')->table('t_bentukmakan')->get();
            $bentukMakanMap  = [];
            foreach ($bentukMakanRows as $b) {
                $bentukMakanMap[trim($b->kd_bentukmakan)] = $b->nama_bentukmakan;
            }

            $processedPermintaan = [];
            foreach ($rekapPermintaans as $rp) {
                $room = $rp->nmRanap ?: 'Tanpa Ruangan';
                $waktu = strtoupper($rp->waktu);
                $kd = $rp->kdPermintaan;

                if (!isset($rekapData[$room])) {
                    $rekapData[$room] = [
                        'nama_ruangan' => $room,
                        'waktu' => []
                    ];
                }
                if (!isset($rekapData[$room]['waktu'][$waktu])) {
                    $rekapData[$room]['waktu'][$waktu] = [
                        'bentuk' => [],
                        'diet' => [],
                        'total' => 0
                    ];
                }

                if (!isset($processedPermintaan[$kd])) {
                    $processedPermintaan[$kd] = true;
                    $bentuk = $bentukMakanMap[trim($rp->kdBentukMakan)] ?? trim($rp->kdBentukMakan) ?: 'Tanpa Bentuk';
                    
                    if (!isset($rekapData[$room]['waktu'][$waktu]['bentuk'][$bentuk])) {
                        $rekapData[$room]['waktu'][$waktu]['bentuk'][$bentuk] = 0;
                    }
                    $rekapData[$room]['waktu'][$waktu]['bentuk'][$bentuk]++;
                    $rekapData[$room]['waktu'][$waktu]['total']++;
                }

                if (!empty($rp->namaDiet)) {
                    $diet = $rp->namaDiet;
                    if (!isset($rekapData[$room]['waktu'][$waktu]['diet'][$diet])) {
                        $rekapData[$room]['waktu'][$waktu]['diet'][$diet] = 0;
                    }
                    $rekapData[$room]['waktu'][$waktu]['diet'][$diet]++;
                }
            }
            ksort($rekapData);
        }

        return view('instalasi_gizi.permintaan', compact(
            'bangsalList', 'kelasList', 'tanggal', 'grouped',
            'stats', 'hasRoomFilter', 'waktuColumns', 'permintaanPerPasien',
            'pasienInfo', 'bentukMakanOptions', 'dietOptions', 'alergiOptions', 'diagnosaGiziOptions',
            'rekapData'
        ));
    }

    /* ===================== BUAT PERMINTAAN GIZI ===================== */

    public function permintaanStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noRawat'        => 'required|string|max:100',
            'no_rekamedis'   => 'nullable|string|max:15',
            'nama_pasien'    => 'required|string|max:150',
            'kdRanap'        => 'required|string|max:50',
            'nmRanap'        => 'nullable|string|max:100',
            'kdKelas'        => 'nullable|string|max:50',
            'kdBed'          => 'nullable|string|max:50',
            'waktu'          => 'required|array|min:1',
            'waktu.*'        => 'string|max:20',
            'kdBentukMakan'  => 'required|string|max:10',
            'kdDiet'         => 'nullable|array',
            'kdDiet.*'       => 'string|max:255',
            'kdAlergi'       => 'nullable|array',
            'kdAlergi.*'     => 'string|max:10',
            'alergi'         => 'nullable|string|max:255',
            'diagnosaGizi'   => 'nullable|string',
            'kdAhliGizi'     => 'nullable|string|max:10',
            'extraDiet'      => 'nullable|string',
            'keteranganSnack'=> 'nullable|string',
            'keteranganDiet' => 'nullable|string',
        ], [
            'noRawat.required' => 'No. Rawat wajib diisi.',
            'nama_pasien.required' => 'Nama pasien wajib diisi.',
            'kdRanap.required' => 'Ruangan wajib diisi.',
            'waktu.required'   => 'Pilih minimal satu waktu makan.',
            'waktu.min'        => 'Pilih minimal satu waktu makan.',
            'kdBentukMakan.required' => 'Bentuk makanan wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Pastikan ruangan, waktu makan, dan bentuk makanan sudah diisi.',
                'errors'  => $validator->errors(),
            ], 422);
        }

// Batas waktu dihapus agar semua sesi bisa diinput sekaligus kapan saja

        try {
            DB::connection('gizi')->beginTransaction();

            // Nonaktifkan permintaan aktif lama untuk pasien ini
            DB::connection('gizi')->table('t_permintaan')
                ->where('noRawat', $request->noRawat)
                ->where('statusUpdate', '1')
                ->update(['statusUpdate' => '0', 'dateUpdate' => now()]);

            $now       = now();
            $prefix    = 'PGZ' . $now->format('dmyHis');
            $seq       = 1;
            $dietMap   = DB::connection('gizi')->table('t_diet')->pluck('diet', 'kd_diet');

            $kdAlergiVal = is_array($request->kdAlergi) ? implode(',', $request->kdAlergi) : $request->kdAlergi;

            foreach ($request->waktu as $waktu) {
                $kdPermintaan = $this->generateKdPermintaan($prefix, $seq);

                DB::connection('gizi')->table('t_permintaan')->insert([
                    'kdPermintaan'    => $kdPermintaan,
                    'tglPermintaan'   => $now->format('Y-m-d H:i:s'),
                    'noRawat'         => $request->noRawat,
                    'no_rekamedis'    => $request->no_rekamedis,
                    'nama_pasien'     => $request->nama_pasien,
                    'kdRanap'         => $request->kdRanap,
                    'nmRanap'         => $request->nmRanap,
                    'kdKelas'         => $request->kdKelas,
                    'kdBed'           => $request->kdBed,
                    'kdAlergi'        => $kdAlergiVal,
                    'alergi'          => $request->alergi,
                    'diagnosaGizi'    => $request->diagnosaGizi,
                    'kdAhliGizi'      => $request->kdAhliGizi ?? Session::get('user_id'),
                    'waktu'           => $waktu,
                    'kdBentukMakan'   => $request->kdBentukMakan,
                    'extraDiet'       => $request->extraDiet,
                    'keteranganSnack' => $request->keteranganSnack,
                    'keteranganDiet'  => $request->keteranganDiet,
                    'statusUpdate'    => '1',
                    'dateUpdate'      => $now->format('Y-m-d H:i:s'),
                ]);

                if (!empty($request->kdDiet)) {
                    foreach ($request->kdDiet as $kd) {
                        DB::connection('gizi')->table('t_detail_permintaan_diet')->insert([
                            'kdPermintaan' => $kdPermintaan,
                            'kdDiet'       => $kd,
                            'namaDiet'     => $dietMap[$kd] ?? $kd,
                            'dateCreated'  => $now->format('Y-m-d H:i:s'),
                            'status'       => '1',
                        ]);
                    }
                }

                $seq++;
            }

            DB::connection('gizi')->commit();

            Log::info('Permintaan gizi dibuat:', [
                'noRawat' => $request->noRawat,
                'waktu'   => $request->waktu,
                'nip'     => Session::get('user_id'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan gizi berhasil dibuat.',
            ], 200);
        } catch (\Exception $e) {
            DB::connection('gizi')->rollBack();

            Log::error('Error membuat permintaan gizi:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan permintaan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function generateKdPermintaan(string $prefix, int $seq): string
    {
        $base = $prefix . '-' . $seq;

        if (!DB::connection('gizi')->table('t_permintaan')->where('kdPermintaan', $base)->exists()) {
            return $base;
        }

        $suffix = 'A';
        while (DB::connection('gizi')->table('t_permintaan')->where('kdPermintaan', $base . $suffix)->exists()) {
            $suffix++;
        }

        return $base . $suffix;
    }

    /* ===================== HELPER ===================== */

    private function resolveKelompokUmur($reg)
    {
        $tglLahir = $reg?->pasien?->tgl_lahir;
        if (!$tglLahir || $tglLahir === '0000-00-00') {
            return 'dewasa';
        }

        try {
            $age = Carbon::parse($tglLahir)->age;
        } catch (\Throwable $e) {
            return 'dewasa';
        }

        if ($age < 18) {
            return 'anak';
        }
        if ($age >= 60) {
            return 'lansia';
        }
        return 'dewasa';
    }

    private function skorAnak(int $total): string
    {
        if ($total >= 4) {
            return 'Risikio Berat';
        }
        if ($total >= 1) {
            return 'Risiko Sedang';
        }
        return 'Risiko Rendah';
    }

    private function skorLansia(int $total): string
    {
        if ($total >= 12) {
            return 'Status Gizi Normal';
        }
        if ($total >= 8) {
            return 'Beresiko Malnutrisi';
        }
        return 'Malnutrisi';
    }

    private function parseAlergi(Request $request): array
    {
        $list = ['telur', 'susu_sapi', 'kacang', 'gluten', 'udang', 'ikan', 'hazelnut'];
        $result = [];
        foreach ($list as $key) {
            $result['alergi_' . $key] = $request->input('alergi_' . $key) === 'Ya' ? 'Ya' : 'Tidak';
        }
        return $result;
    }

    public function getKamarByBangsal(string $kd_bangsal)
    {
        $kamar = DB::table('kamar')
            ->where('kd_bangsal', $kd_bangsal)
            ->where('statusdata', '1')
            ->orderBy('kd_kamar', 'asc')
            ->pluck('kd_kamar');

        return response()->json([
            'success' => true,
            'data'    => $kamar,
        ]);
    }

    public function permintaanUpdate(Request $request, $kdPermintaan)
    {
        $validator = Validator::make($request->all(), [
            'kdBentukMakan'  => 'required|string|max:10',
            'kdDiet'         => 'nullable|array',
            'kdDiet.*'       => 'string|max:255',
            'kdAlergi'       => 'nullable|array',
            'kdAlergi.*'     => 'string|max:10',
            'alergi'         => 'nullable|string|max:255',
            'diagnosaGizi'   => 'nullable|string',
            'kdAhliGizi'     => 'nullable|string|max:10',
            'extraDiet'      => 'nullable|string',
            'keteranganSnack'=> 'nullable|string',
            'keteranganDiet' => 'nullable|string',
            'kdKelas'        => 'nullable|string|max:50',
            'kdBed'          => 'nullable|string|max:50',
        ], [
            'kdBentukMakan.required' => 'Bentuk makanan wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Pastikan bentuk makanan sudah diisi.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::connection('gizi')->beginTransaction();

            $now = now();

            // 1. Ambil data permintaan gizi lama
            $old = DB::connection('gizi')->table('t_permintaan')->where('kdPermintaan', $kdPermintaan)->first();
            if (!$old) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data permintaan lama tidak ditemukan.',
                ], 404);
            }

// Batas waktu dihapus agar sesi dapat diedit kapan saja

            // 2. Nonaktifkan permintaan lama dengan statusUpdate = '0'
            DB::connection('gizi')->table('t_permintaan')
                ->where('kdPermintaan', $kdPermintaan)
                ->update([
                    'statusUpdate' => '0',
                    'dateUpdate'   => $now->format('Y-m-d H:i:s'),
                ]);

            // 3. Generate kdPermintaan baru
            $prefix = 'PGZ' . $now->format('dmyHis');
            $newKdPermintaan = $this->generateKdPermintaan($prefix, 1);

            $kdAlergiVal = is_array($request->kdAlergi) ? implode(',', $request->kdAlergi) : $request->kdAlergi;

            // 4. Insert data baru sebagai permintaan aktif (statusUpdate = '1')
            DB::connection('gizi')->table('t_permintaan')->insert([
                'kdPermintaan'    => $newKdPermintaan,
                'tglPermintaan'   => $now->format('Y-m-d H:i:s'),
                'noRawat'         => $old->noRawat,
                'no_rekamedis'    => $old->no_rekamedis,
                'nama_pasien'     => $old->nama_pasien,
                'kdRanap'         => $old->kdRanap,
                'nmRanap'         => $old->nmRanap,
                'kdKelas'         => $request->kdKelas ?? $old->kdKelas,
                'kdBed'           => $request->kdBed ?? $old->kdBed,
                'kdAlergi'        => $kdAlergiVal,
                'alergi'          => $request->alergi,
                'diagnosaGizi'    => $request->diagnosaGizi,
                'kdAhliGizi'      => $request->kdAhliGizi ?? Session::get('user_id'),
                'waktu'           => $old->waktu,
                'kdBentukMakan'   => $request->kdBentukMakan,
                'extraDiet'       => $request->extraDiet,
                'keteranganSnack' => $request->keteranganSnack,
                'keteranganDiet'  => $request->keteranganDiet,
                'statusUpdate'    => '1',
                'dateUpdate'      => $now->format('Y-m-d H:i:s'),
            ]);

            // 5. Simpan detail diet baru untuk kdPermintaan baru
            if (!empty($request->kdDiet)) {
                $dietMap = DB::connection('gizi')->table('t_diet')->pluck('diet', 'kd_diet');
                foreach ($request->kdDiet as $kd) {
                    DB::connection('gizi')->table('t_detail_permintaan_diet')->insert([
                        'kdPermintaan' => $newKdPermintaan,
                        'kdDiet'       => $kd,
                        'namaDiet'     => $dietMap[$kd] ?? $kd,
                        'dateCreated'  => $now->format('Y-m-d H:i:s'),
                        'status'       => '1',
                    ]);
                }
            }

            DB::connection('gizi')->commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan gizi berhasil diubah (riwayat tersimpan).',
            ], 200);

        } catch (\Exception $e) {
            DB::connection('gizi')->rollBack();

            Log::error('Error mengubah permintaan gizi:', [
                'kdPermintaan' => $kdPermintaan,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah permintaan gizi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function permintaanRiwayatPasien($noRawat)
    {
        try {
            $bentukMakanRows = DB::connection('gizi')->table('t_bentukmakan')->get();
            $bentukMakanMap  = [];
            foreach ($bentukMakanRows as $b) {
                $bentukMakanMap[trim($b->kd_bentukmakan)] = $b->nama_bentukmakan;
            }

            $rows = DB::connection('gizi')
                ->table('t_permintaan as tp')
                ->leftJoin('t_detail_permintaan_diet as td', 'td.kdPermintaan', '=', 'tp.kdPermintaan')
                ->select(
                    'tp.kdPermintaan',
                    'tp.tglPermintaan',
                    'tp.noRawat',
                    'tp.no_rekamedis',
                    'tp.nama_pasien',
                    'tp.nmRanap',
                    'tp.kdBed',
                    'tp.kdKelas',
                    'tp.waktu',
                    'tp.kdBentukMakan',
                    'tp.alergi',
                    'tp.diagnosaGizi',
                    'tp.kdAhliGizi',
                    'tp.extraDiet',
                    'tp.keteranganSnack',
                    'tp.keteranganDiet',
                    'tp.statusUpdate',
                    'tp.dateUpdate',
                    'td.namaDiet'
                )
                ->where('tp.noRawat', $noRawat)
                ->orderBy('tp.tglPermintaan', 'desc')
                ->orderBy('tp.waktu', 'asc')
                ->get();

            // Group by kdPermintaan to combine diet names
            $grouped = [];
            foreach ($rows as $r) {
                $kd = $r->kdPermintaan;
                if (!isset($grouped[$kd])) {
                    $grouped[$kd] = [
                        'kdPermintaan'    => $r->kdPermintaan,
                        'tglPermintaan'   => $r->tglPermintaan,
                        'noRawat'         => $r->noRawat,
                        'no_rekamedis'    => $r->no_rekamedis,
                        'nama_pasien'     => $r->nama_pasien,
                        'nmRanap'         => $r->nmRanap,
                        'kdBed'           => $r->kdBed,
                        'kdKelas'         => $r->kdKelas,
                        'waktu'           => $r->waktu,
                        'bentuk'          => $bentukMakanMap[trim($r->kdBentukMakan)] ?? trim($r->kdBentukMakan) ?: '—',
                        'alergi'          => $r->alergi ?: '—',
                        'diagnosaGizi'    => $r->diagnosaGizi ?: '—',
                        'kdAhliGizi'      => $r->kdAhliGizi ?: '—',
                        'extraDiet'       => $r->extraDiet ?: '—',
                        'keteranganSnack' => $r->keteranganSnack ?: '—',
                        'keteranganDiet'  => $r->keteranganDiet ?: '—',
                        'statusUpdate'    => $r->statusUpdate,
                        'dateUpdate'      => $r->dateUpdate,
                        'diets'           => []
                    ];
                }
                if (!empty($r->namaDiet)) {
                    $grouped[$kd]['diets'][] = $r->namaDiet;
                }
            }

            return response()->json([
                'success' => true,
                'data'    => array_values($grouped)
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching patient diet history:', [
                'noRawat' => $noRawat,
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat riwayat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function asuhanRiwayat(Request $request)
    {
        $dateFrom = $request->filled('start_date') ? $request->start_date : Carbon::today()->toDateString();
        $dateTo   = $request->filled('end_date') ? $request->end_date : Carbon::today()->toDateString();

        $asuhan = DB::table('catatan_adime_gizi as ca')
            ->join('reg_periksa as r', 'r.no_rawat', '=', 'ca.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'r.no_rkm_medis')
            ->leftJoin('pegawai as peg', 'peg.nik', '=', 'ca.nip')
            ->select(
                'ca.no_rawat',
                'ca.tanggal',
                'ca.asesmen',
                'ca.diagnosis',
                'ca.intervensi',
                'ca.monitoring',
                'ca.evaluasi',
                'ca.instruksi',
                'ca.nip',
                'peg.nama as nama_petugas',
                'r.no_rkm_medis',
                'p.nm_pasien',
                'p.jk',
                'p.tgl_lahir',
                DB::raw("(SELECT b.nm_bangsal 
                          FROM kamar_inap ki 
                          JOIN kamar k ON k.kd_kamar = ki.kd_kamar 
                          JOIN bangsal b ON b.kd_bangsal = k.kd_bangsal 
                          WHERE ki.no_rawat = ca.no_rawat 
                          ORDER BY ki.tgl_masuk DESC, ki.jam_masuk DESC 
                          LIMIT 1) as ruangan")
            )
            ->whereDate('ca.tanggal', '>=', $dateFrom)
            ->whereDate('ca.tanggal', '<=', $dateTo)
            ->when($request->filled('no_rawat'), function ($q) use ($request) {
                $q->where('ca.no_rawat', 'like', '%' . $request->no_rawat . '%');
            })
            ->when($request->filled('nama_pasien'), function ($q) use ($request) {
                $q->where('p.nm_pasien', 'like', '%' . $request->nama_pasien . '%');
            })
            ->when($request->filled('petugas'), function ($q) use ($request) {
                $q->where(function($q2) use ($request) {
                    $q2->where('peg.nama', 'like', '%' . $request->petugas . '%')
                       ->orWhere('ca.nip', 'like', '%' . $request->petugas . '%');
                });
            })
            ->orderBy('ca.tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        $todayCount = DB::table('catatan_adime_gizi')
            ->whereDate('tanggal', Carbon::today())
            ->count();

        return view('instalasi_gizi.asuhan_riwayat', compact('asuhan', 'dateFrom', 'dateTo', 'todayCount'));
    }

    public function adimeRiwayatPasien($noRawat)
    {
        try {
            $rows = DB::table('catatan_adime_gizi as ca')
                ->leftJoin('pegawai as peg', 'peg.nik', '=', 'ca.nip')
                ->select(
                    'ca.no_rawat',
                    'ca.tanggal',
                    'ca.asesmen',
                    'ca.diagnosis',
                    'ca.intervensi',
                    'ca.monitoring',
                    'ca.evaluasi',
                    'ca.instruksi',
                    'ca.nip',
                    'peg.nama as nama_petugas'
                )
                ->where('ca.no_rawat', $noRawat)
                ->orderBy('ca.tanggal', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $rows
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching patient ADIME history:', [
                'noRawat' => $noRawat,
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat riwayat ADIME: ' . $e->getMessage()
            ], 500);
        }
    }

    public function permintaanRiwayat(Request $request)
    {
        $dateFrom = $request->filled('start_date') ? $request->start_date : Carbon::today()->toDateString();
        $dateTo   = $request->filled('end_date') ? $request->end_date : Carbon::today()->toDateString();

        $bangsalList = Bangsal::where('status', '1')
            ->orderBy('nm_bangsal', 'asc')
            ->select('kd_bangsal', 'nm_bangsal')
            ->get();

        $bentukMakanRows = DB::connection('gizi')->table('t_bentukmakan')->get();
        $bentukMakanMap  = [];
        foreach ($bentukMakanRows as $b) {
            $bentukMakanMap[trim($b->kd_bentukmakan)] = $b->nama_bentukmakan;
        }

        // Query the permintaan gizi records
        $query = DB::connection('gizi')->table('t_permintaan as tp')
            ->leftJoin('t_detail_permintaan_diet as td', 'td.kdPermintaan', '=', 'tp.kdPermintaan')
            ->select(
                'tp.kdPermintaan',
                'tp.tglPermintaan',
                'tp.noRawat',
                'tp.no_rekamedis',
                'tp.nama_pasien',
                'tp.kdRanap',
                'tp.nmRanap',
                'tp.kdKelas',
                'tp.kdBed',
                'tp.kdAlergi',
                'tp.alergi',
                'tp.diagnosaGizi',
                'tp.kdAhliGizi',
                'tp.waktu',
                'tp.kdBentukMakan',
                'tp.extraDiet',
                'tp.keteranganSnack',
                'tp.keteranganDiet',
                'tp.statusUpdate',
                'td.kdDiet',
                'td.namaDiet'
            )
            ->whereBetween(DB::raw('DATE(tp.tglPermintaan)'), [$dateFrom, $dateTo]);

        // Advanced Filters
        if ($request->filled('bangsal')) {
            $query->where('tp.kdRanap', $request->bangsal);
        }

        if ($request->filled('waktu')) {
            $query->where('tp.waktu', $request->waktu);
        }

        if ($request->filled('bentuk_makan')) {
            $query->where('tp.kdBentukMakan', $request->bentuk_makan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $nikListMatched = DB::table('pegawai')
                ->where('nama', 'like', '%' . $search . '%')
                ->pluck('nik');

            $query->where(function ($q) use ($search, $nikListMatched) {
                $q->where('tp.nama_pasien', 'like', '%' . $search . '%')
                  ->orWhere('tp.no_rekamedis', 'like', '%' . $search . '%')
                  ->orWhere('tp.noRawat', 'like', '%' . $search . '%')
                  ->when($nikListMatched->isNotEmpty(), function ($q2) use ($nikListMatched) {
                      $q2->orWhereIn('tp.kdAhliGizi', $nikListMatched);
                  });
            });
        }

        $rows = $query->orderBy('tp.tglPermintaan', 'desc')
            ->orderBy('tp.kdPermintaan', 'desc')
            ->get();

        // Fetch pegawai names from the default connection
        $nikList = $rows->pluck('kdAhliGizi')->filter()->unique();
        $pegawaiMap = [];
        if ($nikList->isNotEmpty()) {
            $pegawaiMap = DB::table('pegawai')
                ->whereIn('nik', $nikList)
                ->pluck('nama', 'nik')
                ->toArray();
        }

        // Group rows by kdPermintaan to combine diet names
        $grouped = [];
        foreach ($rows as $r) {
            $kd = $r->kdPermintaan;
            if (!isset($grouped[$kd])) {
                $grouped[$kd] = [
                    'kdPermintaan'    => $r->kdPermintaan,
                    'tglPermintaan'   => $r->tglPermintaan,
                    'noRawat'         => $r->noRawat,
                    'no_rekamedis'    => $r->no_rekamedis,
                    'nama_pasien'     => $r->nama_pasien,
                    'kdRanap'         => $r->kdRanap,
                    'nmRanap'         => $r->nmRanap ?: '—',
                    'kdKelas'         => $r->kdKelas ?: '—',
                    'kdBed'           => $r->kdBed ?: '—',
                    'alergi'          => $r->alergi ?: 'Tidak Ada',
                    'diagnosaGizi'    => $r->diagnosaGizi ?: '—',
                    'nama_petugas'    => $pegawaiMap[$r->kdAhliGizi] ?? ($r->kdAhliGizi ?: '—'),
                    'waktu'           => strtoupper($r->waktu),
                    'bentuk'          => $bentukMakanMap[trim($r->kdBentukMakan)] ?? trim($r->kdBentukMakan) ?: '—',
                    'extraDiet'       => $r->extraDiet ?: '—',
                    'keteranganSnack' => $r->keteranganSnack ?: '—',
                    'keteranganDiet'  => $r->keteranganDiet ?: '—',
                    'statusUpdate'    => $r->statusUpdate,
                    'diets'           => []
                ];
            }
            if (!empty($r->namaDiet)) {
                $grouped[$kd]['diets'][] = $r->namaDiet;
            }
        }

        $permintaanList = collect(array_values($grouped));

        $bentukMakanOptions = DB::connection('gizi')->table('t_bentukmakan')->orderBy('nama_bentukmakan')->get();

        return view('instalasi_gizi.permintaan_riwayat', compact(
            'permintaanList', 'dateFrom', 'dateTo', 'bangsalList', 'bentukMakanOptions'
        ));
    }
}
