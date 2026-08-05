<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bangsal;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BankDarahController extends Controller
{
    public function rekomendasi()
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        $dokterList = DB::table('dokter')
            ->select('kd_dokter', 'nm_dokter')
            ->whereNotNull('nm_dokter')
            ->where('nm_dokter', '!=', '')
            ->orderBy('nm_dokter')
            ->get();

        $poliList = DB::table('poliklinik')
            ->where('status', '1')
            ->orderBy('nm_poli')
            ->pluck('nm_poli');

        $bangsalList = DB::table('bangsal')
            ->where('status', '1')
            ->orderBy('nm_bangsal')
            ->pluck('nm_bangsal');

        $ruangList = $poliList->concat($bangsalList)->sort()->values();

        return view('bank_darah.rekomendasi', compact('dokterList', 'ruangList'));
    }

    public function cariPasien(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $keyword = $request->get('keyword', '');

        if (strlen(trim($keyword)) < 2) {
            return response()->json(['success' => false, 'message' => 'Masukkan minimal 2 karakter'], 400);
        }

        $results = DB::select("
            SELECT
                p.no_rkm_medis,
                p.nm_pasien,
                p.tgl_lahir,
                p.umur,
                p.alamat,
                p.gol_darah,
                p.jk,
                r.no_rawat,
                r.tgl_registrasi,
                r.kd_dokter,
                d.nm_dokter,
                r.kd_poli,
                po.nm_poli,
                r.status_lanjut,
                ki.kd_kamar,
                ki.diagnosa_awal,
                km.kd_bangsal,
                b.nm_bangsal
            FROM pasien p
            LEFT JOIN reg_periksa r ON p.no_rkm_medis = r.no_rkm_medis
            LEFT JOIN dokter d ON r.kd_dokter = d.kd_dokter
            LEFT JOIN poliklinik po ON r.kd_poli = po.kd_poli
            LEFT JOIN kamar_inap ki ON r.no_rawat = ki.no_rawat
            LEFT JOIN kamar km ON ki.kd_kamar = km.kd_kamar
            LEFT JOIN bangsal b ON km.kd_bangsal = b.kd_bangsal
            WHERE p.no_rkm_medis LIKE ? OR p.nm_pasien LIKE ? OR r.no_rawat LIKE ?
            ORDER BY r.tgl_registrasi DESC
            LIMIT 30
        ", ["%$keyword%", "%$keyword%", "%$keyword%"]);

        // ambil data vital terbaru dari pemeriksaan_ralan / pemeriksaan_ranap per no_rawat
        $noRawatList = collect($results)->pluck('no_rawat')->filter()->unique()->values()->all();

        $latestRalan = collect();
        $latestRanap = collect();
        if (!empty($noRawatList)) {
            $latestRalan = DB::table('pemeriksaan_ralan')
                ->select('no_rawat', 'tgl_perawatan', 'jam_rawat', 'suhu_tubuh', 'tensi', 'nadi')
                ->whereIn('no_rawat', $noRawatList)
                ->orderBy('tgl_perawatan', 'desc')
                ->orderBy('jam_rawat', 'desc')
                ->get()
                ->keyBy('no_rawat');

            $latestRanap = DB::table('pemeriksaan_ranap')
                ->select('no_rawat', 'tgl_perawatan', 'jam_rawat', 'suhu_tubuh', 'tensi', 'nadi')
                ->whereIn('no_rawat', $noRawatList)
                ->orderBy('tgl_perawatan', 'desc')
                ->orderBy('jam_rawat', 'desc')
                ->get()
                ->keyBy('no_rawat');
        }

        $formatted = [];
        foreach ($results as $row) {
            $ruang = '';
            if ($row->nm_bangsal) {
                $ruang = $row->nm_bangsal . ($row->kd_kamar ? ' - ' . $row->kd_kamar : '');
            } elseif ($row->nm_poli) {
                $ruang = $row->nm_poli;
            }

            // ambil vital sesuai status_lanjut
            $vital = null;
            if ($row->status_lanjut === 'Ranap') {
                $vital = $latestRanap->get($row->no_rawat);
            } elseif ($row->status_lanjut === 'Ralan') {
                $vital = $latestRalan->get($row->no_rawat);
            }

            $formatted[] = [
                'no_rkm_medis'  => $row->no_rkm_medis,
                'nm_pasien'     => $row->nm_pasien,
                'tgl_lahir'     => $row->tgl_lahir,
                'umur'          => $row->umur,
                'alamat'        => $row->alamat,
                'gol_darah'     => $row->gol_darah,
                'jk'            => $row->jk,
                'diagnosa_awal' => $row->diagnosa_awal,
                'no_rawat'      => $row->no_rawat,
                'kd_dokter'     => $row->kd_dokter,
                'nm_dokter'     => $row->nm_dokter,
                'nm_poli'       => $row->nm_poli,
                'status_lanjut' => $row->status_lanjut,
                'ruang'         => $ruang,
                'suhu_tubuh'    => $vital->suhu_tubuh ?? '',
                'tensi'         => $vital->tensi ?? '',
                'nadi'          => $vital->nadi ?? '',
            ];
        }

        // juga cari berdasarkan barcode di t_permintaan_kantong
        $bagResults = DB::connection('simbdrs')
            ->table('t_permintaan_kantong')
            ->where('barcode', 'LIKE', "%$keyword%")
            ->whereNotNull('barcode')
            ->where('barcode', '!=', '')
            ->orderBy('tanggal_permintaan', 'desc')
            ->limit(10)
            ->get();

        foreach ($bagResults as $row) {
            $noRm = $row->no_rekamedis ?? '';
            $noRawat = $row->no_rawat ?? '';
            $pasienData = null;
            if ($noRm) {
                $pasienData = DB::table('pasien')->where('no_rkm_medis', $noRm)->first();
            }

            // cari vital dari reg_periksa + pemeriksaan berdasarkan no_rawat
            $vital = null;
            if ($noRawat) {
                $reg = DB::table('reg_periksa')->where('no_rawat', $noRawat)->first();
                if ($reg) {
                    if ($reg->status_lanjut === 'Ranap') {
                        $vital = DB::table('pemeriksaan_ranap')
                            ->where('no_rawat', $noRawat)
                            ->orderBy('tgl_perawatan', 'desc')
                            ->orderBy('jam_rawat', 'desc')
                            ->first(['suhu_tubuh', 'tensi', 'nadi']);
                    } elseif ($reg->status_lanjut === 'Ralan') {
                        $vital = DB::table('pemeriksaan_ralan')
                            ->where('no_rawat', $noRawat)
                            ->orderBy('tgl_perawatan', 'desc')
                            ->orderBy('jam_rawat', 'desc')
                            ->first(['suhu_tubuh', 'tensi', 'nadi']);
                    }
                }
            }

            $formatted[] = [
                'no_rkm_medis'  => $noRm,
                'nm_pasien'     => $row->nama_pasien ?: ($pasienData->nm_pasien ?? ''),
                'tgl_lahir'     => $pasienData->tgl_lahir ?? '',
                'umur'          => $pasienData->umur ?? '',
                'alamat'        => $pasienData->alamat ?? '',
                'gol_darah'     => $row->gol_darah ?: ($pasienData->gol_darah ?? ''),
                'jk'            => $pasienData->jk ?? '',
                'diagnosa_awal' => '',
                'no_rawat'      => $noRawat,
                'kd_dokter'     => '',
                'nm_dokter'     => '',
                'nm_poli'       => '',
                'status_lanjut' => $reg->status_lanjut ?? '',
                'ruang'         => $row->ruangan ?? '',
                'suhu_tubuh'    => $vital->suhu_tubuh ?? '',
                'tensi'         => $vital->tensi ?? '',
                'nadi'          => $vital->nadi ?? '',
                '_barcode'      => $row->barcode ?? '',
                '_id_permintaan' => $row->id_permintaan ?? '',
                '_volume'       => $row->volume ?? '',
                '_gol_rhesus'   => $row->gol_rhesus ?? '',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $formatted,
        ]);
    }

    public function cariBarcode(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $barcode = $request->get('barcode', '');

        if (strlen(trim($barcode)) < 2) {
            return response()->json(['success' => false, 'message' => 'Masukkan minimal 2 karakter'], 400);
        }

        $results = DB::connection('simbdrs')
            ->table('t_permintaan_kantong')
            ->where('barcode', 'LIKE', "%$barcode%")
            ->whereNotNull('barcode')
            ->where('barcode', '!=', '')
            ->orderBy('tanggal_permintaan', 'desc')
            ->limit(20)
            ->get();

        $formatted = [];
        foreach ($results as $row) {
            $formatted[] = [
                'id_permintaan'      => $row->id_permintaan ?? '',
                'no_rekamedis'       => $row->no_rekamedis ?? '',
                'nama_pasien'        => $row->nama_pasien ?? '',
                'ruangan'            => $row->ruangan ?? '',
                'gol_darah'          => $row->gol_darah ?? '',
                'gol_rhesus'         => $row->gol_rhesus ?? '',
                'volume'             => $row->volume ?? '',
                'barcode'            => $row->barcode ?? '',
                'tanggal_permintaan' => $row->tanggal_permintaan ?? '',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $formatted,
        ]);
    }

    public function store(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'tgl_uji' => 'required|date',
            'bentuk_darah' => 'required|string',
            'gol_darah_permintaan' => 'required|string',
            'nama_pasien' => 'required|string',
            'umur_pasien' => 'nullable|string',
            'alamat_pasien' => 'nullable|string',
            'rumah_sakit' => 'nullable|string',
            'ruang_bagian' => 'nullable|string',
            'no_reg_rm' => 'nullable|string',
            'no_rkm_medis' => 'nullable|string',
            'no_rawat' => 'nullable|string',
            'gol_darah_pasien' => 'nullable|string',
            'nama_dokter' => 'required|string',
            'kd_dokter' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        try {
            $noRekomendasi = 'REK-' . date('YmdHis');

            DB::connection('simbdrs')->table('rekomendasi_dokter')->insert([
                'no_rekomendasi' => $noRekomendasi,
                'no_rkm_medis' => $request->no_rkm_medis,
                'no_rawat' => $request->no_rawat,
                'tgl_uji' => $request->tgl_uji,
                'tahun' => date('Y'),
                'bentuk_darah' => $request->bentuk_darah,
                'gol_darah_permintaan' => $request->gol_darah_permintaan,
                'nama_pasien' => $request->nama_pasien,
                'umur_pasien' => $request->umur_pasien,
                'alamat_pasien' => $request->alamat_pasien,
                'rumah_sakit' => $request->rumah_sakit,
                'ruang_bagian' => $request->ruang_bagian,
                'no_reg_rm' => $request->no_reg_rm,
                'gol_darah_pasien' => $request->gol_darah_pasien,
                'nama_dokter' => $request->nama_dokter,
                'kd_dokter' => $request->kd_dokter,
                'tanggal' => $request->tanggal,
                'nip' => Session::get('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $id = DB::getPdo()->lastInsertId();

            return response()->json([
                'success' => true,
                'message' => 'Rekomendasi Dokter berhasil disimpan!',
                'id' => $id,
                'no_rekomendasi' => $noRekomendasi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function validasi()
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $rooms = DB::table('bangsal')
            ->select('nm_bangsal')
            ->whereNotNull('nm_bangsal')
            ->where('nm_bangsal', '!=', '')
            ->orderBy('nm_bangsal')
            ->pluck('nm_bangsal');

        $bdrs = DB::connection('simbdrs')->table('t_permintaan_kantong');

        $statusList = (clone $bdrs)
            ->select('status')
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        $produkList = (clone $bdrs)
            ->select('gol_rhesus')
            ->whereNotNull('gol_rhesus')
            ->where('gol_rhesus', '!=', '')
            ->distinct()
            ->orderBy('gol_rhesus')
            ->pluck('gol_rhesus');

        $hasilList = (clone $bdrs)
            ->select('hasil')
            ->whereNotNull('hasil')
            ->where('hasil', '!=', '')
            ->distinct()
            ->orderBy('hasil')
            ->pluck('hasil');

        return view('bank_darah.validasi_darah', compact('rooms', 'statusList', 'produkList', 'hasilList'));
    }

    public function getPasienByRuangan(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $ruangan = $request->get('ruangan', '');
        $tglAwal = $request->get('tgl_awal', '');
        $tglAkhir = $request->get('tgl_akhir', '');
        $statusPermintaan = $request->get('status_permintaan', '');
        $produk = $request->get('produk', '');
        $hasilCross = $request->get('hasil_cross', '');
        $noRekamedis = $request->get('no_rekamedis', '');
        $sortBy = $request->get('sort_by', 'tanggal_permintaan');
        $sortOrder = strtolower($request->get('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 20);

        $allowedSort = ['tanggal_permintaan', 'nama_pasien', 'no_rekamedis', 'gol_darah', 'gol_rhesus', 'status', 'hasil', 'ruangan'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'tanggal_permintaan';
        }

        if (!$ruangan && !$noRekamedis) {
            return response()->json(['success' => false, 'message' => 'Pilih ruangan atau masukkan No. RM terlebih dahulu'], 400);
        }

        $baseQuery = DB::connection('simbdrs')->table('t_permintaan_kantong');

        if ($ruangan) {
            $baseQuery->where('ruangan', $ruangan);
        }

        if ($tglAwal) {
            $baseQuery->where('tanggal_permintaan', '>=', $tglAwal);
        }
        if ($tglAkhir) {
            $baseQuery->where('tanggal_permintaan', '<=', $tglAkhir);
        }
        if ($statusPermintaan) {
            $baseQuery->where('status', $statusPermintaan);
        }
        if ($produk) {
            $baseQuery->where('gol_rhesus', $produk);
        }
        if ($hasilCross) {
            $baseQuery->where('hasil', $hasilCross);
        }
        if ($noRekamedis) {
            $baseQuery->where('no_rekamedis', 'LIKE', "%$noRekamedis%");
        }

        $total = (clone $baseQuery)->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        if ($page > $lastPage) $page = $lastPage;

        $diterima  = (clone $baseQuery)->where('status penerimaan', 'sudah')->count();
        $transfusi = (clone $baseQuery)->where('status_pemberian', 'transfusi')->count();

        $summary = [
            'total'     => $total,
            'diterima'  => $diterima,
            'transfusi' => $transfusi,
            'menunggu'  => max(0, $total - $diterima),
        ];

        $baseQuery->orderBy($sortBy, $sortOrder);
        $permintaan = $baseQuery->skip(($page - 1) * $perPage)->take($perPage)->get();

        $formatted = [];
        foreach ($permintaan as $row) {
            $noRkmMedis = trim($row->no_rekamedis ?? '');
            $statusRanap = null;
            $pasienInfo = null;

            if ($noRkmMedis) {
                $pasienInfo = DB::selectOne("
                    SELECT
                        ki.no_rawat,
                        ki.tgl_masuk,
                        ki.kd_kamar,
                        km.kd_bangsal,
                        b.nm_bangsal,
                        rp.no_rkm_medis,
                        p.nm_pasien AS nm_pasien_sik,
                        p.umur,
                        p.gol_darah,
                        rd.id AS rekomendasi_id,
                        rd.no_rekomendasi
                    FROM pasien p
                    LEFT JOIN reg_periksa rp ON p.no_rkm_medis = rp.no_rkm_medis
                    LEFT JOIN kamar_inap ki ON rp.no_rawat = ki.no_rawat AND (ki.tgl_keluar IS NULL OR ki.tgl_keluar = '0000-00-00')
                    LEFT JOIN kamar km ON ki.kd_kamar = km.kd_kamar
                    LEFT JOIN bangsal b ON km.kd_bangsal = b.kd_bangsal
                    LEFT JOIN rekomendasi_dokter rd ON rd.no_rkm_medis = p.no_rkm_medis
                    WHERE p.no_rkm_medis = ?
                    ORDER BY rp.tgl_registrasi DESC
                    LIMIT 1
                ", [$noRkmMedis]);

                if ($pasienInfo && $pasienInfo->no_rawat) {
                    $statusRanap = 'MASIH DIRAWAT';
                }
            }

            $formatted[] = [
                'id_permintaan'      => $row->id_permintaan ?? '',
                'no_rekamedis'       => $noRkmMedis,
                'nama_pasien'        => $row->nama_pasien ?? '',
                'ruangan'            => $row->ruangan ?? '',
                'rumah_sakit'        => $row->rumah_sakit ?? '',
                'gol_darah'          => $row->gol_darah ?? '',
                'gol_rhesus'         => $row->gol_rhesus ?? '',
                'volume'             => $row->volume ?? '',
                'barcode'            => $row->barcode ?? '',
                'hasil'              => $row->hasil ?? '',
                'tanggal_permintaan' => $row->tanggal_permintaan ?? '',
                'status'             => $row->status ?? '',
                'status_pemberian'   => $row->status_pemberian ?? '',
                'status_penerimaan'  => $row->{'status penerimaan'} ?? '',
                'user_penerimaan'    => $row->user_penerimaan ?? '',
                'waktu_penerimaan'   => $row->waktu_penerimaan ?? '',
                'user_validasi'      => $row->user_validasi ?? '',
                'waktu_pemberian'    => $row->waktu_pemberian ?? '',
                'no_rawat'           => $pasienInfo->no_rawat ?? '',
                'nm_bangsal'         => $pasienInfo->nm_bangsal ?? '',
                'kd_kamar'           => $pasienInfo->kd_kamar ?? '',
                'tgl_masuk'          => $pasienInfo->tgl_masuk ?? '',
                'no_rkm_medis_sik'   => $pasienInfo->no_rkm_medis ?? '',
                'nm_pasien_sik'      => $pasienInfo->nm_pasien_sik ?? '',
                'umur'               => $pasienInfo->umur ?? '',
                'gol_darah_sik'      => $pasienInfo->gol_darah ?? '',
                'rekomendasi_id'     => $pasienInfo->rekomendasi_id ?? '',
                'no_rekomendasi'     => $pasienInfo->no_rekomendasi ?? '',
                'status_ranap'       => $statusRanap,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $formatted,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $lastPage,
            'from' => (($page - 1) * $perPage) + 1,
            'to' => min($page * $perPage, $total),
            'summary' => $summary,
        ]);
    }

    public function validasiTransfusi(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $idPermintaan = $request->get('id_permintaan', '');

        if (!$idPermintaan) {
            return response()->json(['success' => false, 'message' => 'ID permintaan tidak valid'], 400);
        }

        try {
            DB::connection('simbdrs')
                ->table('t_permintaan_kantong')
                ->where('id_permintaan', $idPermintaan)
                ->update([
                    'status_pemberian' => 'transfusi',
                    'user_validasi' => Session::get('user_id'),
                    'waktu_pemberian' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Transfusi berhasil divalidasi',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memvalidasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function validasiPenerimaan(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $idPermintaan = $request->get('id_permintaan', '');

        if (!$idPermintaan) {
            return response()->json(['success' => false, 'message' => 'ID permintaan tidak valid'], 400);
        }

        try {
            DB::connection('simbdrs')
                ->statement("UPDATE t_permintaan_kantong SET `status penerimaan` = ?, user_penerimaan = ?, waktu_penerimaan = NOW() WHERE id_permintaan = ?", [
                    'sudah',
                    Session::get('user_id'),
                    $idPermintaan
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Penerimaan berhasil divalidasi',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memvalidasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reaksiTransfusi()
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $poliList = DB::table('poliklinik')
            ->where('status', '1')
            ->orderBy('nm_poli')
            ->pluck('nm_poli');

        $bangsalList = DB::table('bangsal')
            ->where('status', '1')
            ->orderBy('nm_bangsal')
            ->pluck('nm_bangsal');

        $ruangList = $poliList->concat($bangsalList)->sort()->values();

        // $bangsalList = Bangsal::where('status', '1')->orderBy('nm_bangsal')->select('kd_bangsal', 'nm_bangsal')->get();
        $pegawaiList = Pegawai::where('stts_aktif', 'AKTIF')->orderBy('nama')->select('nik', 'nama')->get();
        $kesimpulanList = [
            'Reaksi Febril Non Hemolitik (Febrile Non-Hemolytic Transfusion Reaction)',
            'Reaksi Alergi Ringan (Simple Allergic Reaction)',
            'Reaksi Alergi Berat / Anafilaksis (Anaphylactic Reaction)',
            'Reaksi Hemolitik Akut (Acute Hemolytic Reaction)',
            'Reaksi Hemolitik Tertunda (Delayed Hemolytic Reaction)',
            'Bakterial Contamination / Sepsis',
            'Transfusion Related Acute Lung Injury (TRALI)',
            'Transfusion Associated Circulatory Overload (TACO)',
            'Purpura Pasca Transfusi (Post Transfusion Purpura)',
            'Bukan reaksi transfusi / Tidak terbukti reaksi transfusi',
        ];

        return view('bank_darah.reaksi_transfusi', compact('pegawaiList', 'kesimpulanList', 'ruangList'));
    }

    public function history(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $query = DB::connection('simbdrs')->table('rekomendasi_dokter');

        if ($request->filled('tgl_awal')) {
            $query->where('tgl_uji', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->where('tgl_uji', '<=', $request->tgl_akhir);
        }
        if ($request->filled('bentuk_darah')) {
            $query->where('bentuk_darah', $request->bentuk_darah);
        }
        if ($request->filled('gol_darah')) {
            $query->where('gol_darah_permintaan', $request->gol_darah);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_pasien', 'LIKE', "%$s%")
                  ->orWhere('no_reg_rm', 'LIKE', "%$s%")
                  ->orWhere('no_rkm_medis', 'LIKE', "%$s%")
                  ->orWhere('no_rekomendasi', 'LIKE', "%$s%");
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());

        $bentukDarahList = DB::connection('simbdrs')->table('rekomendasi_dokter')
            ->select('bentuk_darah')->distinct()->whereNotNull('bentuk_darah')->orderBy('bentuk_darah')->pluck('bentuk_darah');

        $golDarahList = DB::connection('simbdrs')->table('rekomendasi_dokter')
            ->select('gol_darah_permintaan')->distinct()->whereNotNull('gol_darah_permintaan')->orderBy('gol_darah_permintaan')->pluck('gol_darah_permintaan');

        $totalData = DB::connection('simbdrs')->table('rekomendasi_dokter')->count();
        $totalBulanIni = DB::connection('simbdrs')->table('rekomendasi_dokter')
            ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();

        return view('bank_darah.history', compact('data', 'bentukDarahList', 'golDarahList', 'totalData', 'totalBulanIni'));
    }

    public function downloadPDF($id)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $data = DB::connection('simbdrs')->table('rekomendasi_dokter')->where('id', $id)->first();
        if (!$data) {
            return abort(404);
        }

        $deviceInfo = [
            'ip' => request()->ip(),
            'lat' => request()->input('lat', '-'),
            'lng' => request()->input('lng', '-'),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        $sigBase64 = null;
        if (!empty($data->kd_dokter)) {
            $baseUrl = env('STAFF_SIGNATURE_PATH', 'http://192.168.30.24/webapps/penggajian/temp');
            $extensions = ['png', 'jpg', 'jpeg'];
            foreach ($extensions as $ext) {
                $url = $baseUrl . '/' . trim($data->kd_dokter) . '.' . $ext;
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
                    if ($response->successful()) {
                        $sigBase64 = 'data:image/' . $ext . ';base64,' . base64_encode($response->body());
                        break;
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Rekomendasi PDF Sig Error: " . $e->getMessage());
                }
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bank_darah.pdf', [
            'data' => $data,
            'deviceInfo' => $deviceInfo,
            'sigBase64' => $sigBase64,
        ]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Rekomendasi_Dokter_' . $data->no_rekomendasi . '.pdf');
    }

    public function storeReaksiTransfusi(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'nama_pasien' => 'required|string',
            'no_rkm_medis' => 'nullable|string',
        ]);

        try {
            $noReaksi = 'RT-' . date('YmdHis');

            $db = DB::connection('simbdrs');

            $id = $db->table('reaksi_transfusi')->insertGetId([
                'no_reaksi' => $noReaksi,
                'rumah_sakit' => $request->input('rumah_sakit', 'RSUD Karsa Husada Batu'),
                'ruangan' => $request->input('ruangan'),
                'no_rekam_medis' => $request->input('no_rkm_medis'),
                'no_rawat' => $request->input('no_rawat'),
                'nama_pasien' => $request->input('nama_pasien'),
                'jenis_kelamin' => $request->input('jk'),
                'tanggal_lahir' => $request->input('tgl_lahir_umur'),
                'diagnosa' => $request->input('diagnosa'),
                'dokter_pj' => $request->input('dpjp'),
                'no_permintaan' => $request->input('no_permintaan'),
                'barcode' => $request->input('barcode'),
                'jenis_komponen' => $request->input('jenis_komponen'),
                'jenis_komponen_lain' => $request->input('jenis_komponen_lain'),
                'gol_darah' => $request->input('gol_darah'),
                'gol_darah_donor' => $request->input('gol_darah_donor'),
                'volume_transfusi' => $request->input('volume_transfusi'),
                'tanggal_transfusi' => $request->input('tanggal_transfusi') ?: date('Y-m-d'),
                'tanggal_reaksi' => $request->input('tanggal_reaksi') ?: date('Y-m-d'),
                'jam_mulai' => $request->input('jam_mulai'),
                'jam_reaksi' => $request->input('jam_reaksi'),
                'jam_selesai' => $request->input('jam_selesai'),
                'petugas_transfusi' => $request->input('petugas_transfusi'),
                'jenis_reaksi' => $request->input('jenis_reaksi'),
                'gejala' => $request->input('gejala'),
                'td_sebelum' => $request->input('td_sebelum'),
                'td_reaksi' => $request->input('td_reaksi'),
                'td_setelah' => $request->input('td_setelah'),
                'nadi_sebelum' => $request->input('nadi_sebelum'),
                'nadi_reaksi' => $request->input('nadi_reaksi'),
                'nadi_setelah' => $request->input('nadi_setelah'),
                'suhu_sebelum' => $request->input('suhu_sebelum'),
                'suhu_reaksi' => $request->input('suhu_reaksi'),
                'suhu_setelah' => $request->input('suhu_setelah'),
                'kesimpulan_reaksi' => $request->input('kesimpulan_reaksi'),
                'keterangan' => $request->input('keterangan'),
                'tindak_lanjut' => $request->input('tindak_lanjut'),
                'nama_petugas_ruangan' => $request->input('nama_petugas_ruangan'),
                'nama_petugas_bdrs' => $request->input('nama_petugas_bdrs'),
                'petugas_pelapor' => Session::get('user_id'),
                'user_input' => Session::get('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $now = now();

            // Simpan ke tabel child: Gejala
            $gejalaChecked = $request->input('gejala_check', []);
            $gejalaNilai = $request->input('gejala_val', []);
            $gejalaInserts = [];
            foreach ($gejalaChecked as $item) {
                $gejalaInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => $item,
                    'nilai' => $gejalaNilai[$item] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($request->filled('gejala_lain')) {
                $gejalaInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => 'Lain-lain',
                    'nilai' => $request->input('gejala_lain'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($gejalaInserts) {
                $db->table('reaksi_transfusi_gejala')->insert($gejalaInserts);
            }

            // Simpan ke tabel child: Tindakan
            $tindakanChecked = $request->input('tindakan_check', []);
            $tindakanNilai = $request->input('tindakan_val', []);
            $tindakanInserts = [];
            foreach ($tindakanChecked as $item) {
                $tindakanInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => $item,
                    'nilai' => $tindakanNilai[$item] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($request->filled('tindakan_obat')) {
                $tindakanInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => 'Pemberian obat',
                    'nilai' => $request->input('tindakan_obat'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($request->filled('tindakan_lain')) {
                $tindakanInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => 'Lain-lain',
                    'nilai' => $request->input('tindakan_lain'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($tindakanInserts) {
                $db->table('reaksi_transfusi_tindakan')->insert($tindakanInserts);
            }

            // Simpan ke tabel child: Spesimen
            $spesimenChecked = $request->input('spesimen_check', []);
            $spesimenNilai = $request->input('spesimen_val', []);
            $spesimenInserts = [];
            foreach ($spesimenChecked as $item) {
                $spesimenInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => $item,
                    'nilai' => $spesimenNilai[$item] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($request->filled('spesimen_lain')) {
                $spesimenInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => 'Lain-lain',
                    'nilai' => $request->input('spesimen_lain'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($spesimenInserts) {
                $db->table('reaksi_transfusi_spesimen')->insert($spesimenInserts);
            }

            // Simpan ke tabel child: Investigasi
            $investigasiChecked = $request->input('investigasi_check', []);
            $investigasiNilai = $request->input('investigasi_val', []);
            $investigasiInserts = [];
            foreach ($investigasiChecked as $item) {
                $investigasiInserts[] = [
                    'reaksi_transfusi_id' => $id,
                    'item' => $item,
                    'nilai' => $investigasiNilai[$item] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($investigasiInserts) {
                $db->table('reaksi_transfusi_investigasi')->insert($investigasiInserts);
            }

            return response()->json([
                'success' => true,
                'message' => 'Formulir Penelusuran Reaksi Transfusi berhasil disimpan!',
                'id' => $id,
                'no_reaksi' => $noReaksi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function historyValidasi(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $query = DB::connection('simbdrs')->table('t_permintaan_kantong');

        $today = date('Y-m-d');
        $tglAwal = $request->filled('tgl_awal') ? $request->tgl_awal : $today;
        $tglAkhir = $request->filled('tgl_akhir') ? $request->tgl_akhir : $today;

        $query->where('waktu_pemberian', '>=', $tglAwal)
              ->where('waktu_pemberian', '<=', $tglAkhir . ' 23:59:59');
        if ($request->filled('ruangan')) {
            $query->where('ruangan', $request->ruangan);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_pasien', 'LIKE', "%$s%")
                  ->orWhere('no_rekamedis', 'LIKE', "%$s%")
                  ->orWhere('barcode', 'LIKE', "%$s%");
            });
        }

        $data = $query->orderBy('tanggal_permintaan', 'desc')->paginate(20)->appends($request->all());

        $ruanganList = DB::connection('simbdrs')->table('t_permintaan_kantong')
            ->select('ruangan')->distinct()->whereNotNull('ruangan')->orderBy('ruangan')->pluck('ruangan');
        $statusList = DB::connection('simbdrs')->table('t_permintaan_kantong')
            ->select('status')->distinct()->whereNotNull('status')->orderBy('status')->pluck('status');

        $totalData = DB::connection('simbdrs')->table('t_permintaan_kantong')->count();
        $totalValidated = DB::connection('simbdrs')->table('t_permintaan_kantong')
            ->where('status_pemberian', 'transfusi')->count();

        return view('bank_darah.validasi_history', compact('data', 'ruanganList', 'statusList', 'totalData', 'totalValidated'));
    }

    public function downloadReaksiTransfusiPDF($id)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $db = DB::connection('simbdrs');
        $data = $db->table('reaksi_transfusi')->where('id', $id)->first();
        if (!$data) {
            return abort(404);
        }

        $data->gejala_items = $db->table('reaksi_transfusi_gejala')
            ->where('reaksi_transfusi_id', $id)->get()->keyBy('item');
        $data->tindakan_items = $db->table('reaksi_transfusi_tindakan')
            ->where('reaksi_transfusi_id', $id)->get()->keyBy('item');
        $data->spesimen_items = $db->table('reaksi_transfusi_spesimen')
            ->where('reaksi_transfusi_id', $id)->get()->keyBy('item');
        $data->investigasi_items = $db->table('reaksi_transfusi_investigasi')
            ->where('reaksi_transfusi_id', $id)->get()->keyBy('item');

        $deviceInfo = [
            'ip' => request()->ip(),
            'lat' => request()->input('lat', '-'),
            'lng' => request()->input('lng', '-'),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bank_darah.pdf_reaksi_transfusi', [
            'data' => $data,
            'deviceInfo' => $deviceInfo,
        ]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Formulir_Reaksi_Transfusi_' . $data->no_reaksi . '.pdf');
    }

    public function historyReaksiTransfusi(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }

        $query = DB::connection('simbdrs')->table('reaksi_transfusi');

        $today = date('Y-m-d');
        $tglAwal = $request->filled('tgl_awal') ? $request->tgl_awal : $today;
        $tglAkhir = $request->filled('tgl_akhir') ? $request->tgl_akhir : $today;

        $query->where('tanggal_transfusi', '>=', $tglAwal)
              ->where('tanggal_transfusi', '<=', $tglAkhir);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_pasien', 'LIKE', "%$s%")
                  ->orWhere('no_rawat', 'LIKE', "%$s%")
                  ->orWhere('no_rekam_medis', 'LIKE', "%$s%")
                  ->orWhere('no_reaksi', 'LIKE', "%$s%");
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());

        $totalData = DB::connection('simbdrs')->table('reaksi_transfusi')->count();
        $totalBulanIni = DB::connection('simbdrs')->table('reaksi_transfusi')
            ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();

        return view('bank_darah.history_reaksi_transfusi', compact('data', 'totalData', 'totalBulanIni'));
    }
}
