<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Bangsal;
use App\Models\TransferPasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransferPasienController extends Controller
{
    public function index()
    {
        $petugasList = Pegawai::where('stts_aktif', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->select('nik', 'nama')
            ->get();

        $bangsalList = Bangsal::where('status', '1')
            ->orderBy('nm_bangsal', 'asc')
            ->select('kd_bangsal', 'nm_bangsal')
            ->get();

        return view('transfer_pasien.index', compact('petugasList', 'bangsalList'));
    }

    public function riwayat(Request $request)
    {
        $query = TransferPasien::with([
            'asalBangsal', 'tujuanBangsal',
            'perawatMenyerahkan', 'perawatMenerima',
            'regPeriksa.pasien'
        ])
        ->orderBy('tanggal_pindah', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end   = $request->end_date . ' 23:59:59';
            $query->where(function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_masuk', [$start, $end])
                  ->orWhereBetween('tanggal_pindah', [$start, $end]);
            });
        } else {
            $today = today();
            $query->where(function ($q) use ($today) {
                $q->whereDate('tanggal_masuk', $today)
                  ->orWhereDate('tanggal_pindah', $today);
            });
        }
        if ($request->filled('no_rawat')) {
            $keyword = $request->no_rawat;
            $query->where(function ($q) use ($keyword) {
                $q->where('no_rawat', 'like', '%' . $keyword . '%')
                  ->orWhereHas('regPeriksa.pasien', function ($q2) use ($keyword) {
                      $q2->where('no_rkm_medis', 'like', '%' . $keyword . '%');
                  });
            });
        }
        if ($request->filled('nama_pasien')) {
            $query->whereHas('regPeriksa.pasien', function ($q) use ($request) {
                $q->where('nm_pasien', 'like', '%' . $request->nama_pasien . '%');
            });
        }
        if ($request->filled('asal_ruang')) {
            $query->where('asal_ruang', $request->asal_ruang);
        }
        if ($request->filled('tujuan_ruang')) {
            $query->where('ruang_selanjutnya', $request->tujuan_ruang);
        }
        if ($request->filled('status_persetujuan')) {
            $query->where('pasien_keluarga_menyetujui', $request->status_persetujuan);
        }

        $transfers = $query->paginate(20)->withQueryString();

        // Stats
        $totalTransfers = TransferPasien::count();
        $todayTransfers = TransferPasien::whereDate('tanggal_pindah', today())->count();
        $totalActive = TransferPasien::whereExists(function ($q) {
            $q->select(DB::raw(1))->from('kamar_inap')
              ->whereRaw('kamar_inap.no_rawat = transfer_pasien_antar_ruang.no_rawat')
              ->where(function ($q2) { $q2->whereNull('tgl_keluar')->orWhere('tgl_keluar', '0000-00-00'); });
        })->count();

        // Filter dropdown data
        $bangsalList = Bangsal::where('status', '1')->orderBy('nm_bangsal')->select('nm_bangsal')->get()->pluck('nm_bangsal');
        $petugasList = Pegawai::where('stts_aktif', 'AKTIF')->orderBy('nama')->select('nik', 'nama')->get();

        return view('transfer_pasien.riwayat', compact(
            'transfers', 'totalActive', 'todayTransfers', 'totalTransfers',
            'bangsalList'
        ));
    }

     public function store(Request $request)
    {
        Log::info('Data Transfer Pasien masuk:', $request->all());

        $validator = Validator::make($request->all(), [
            'no_rawat' => 'required|string|max:20',

            'tgl_masuk' => 'required|date',
            'tgl_pindah' => 'required|date|after_or_equal:tgl_masuk',
            'indikasi_pindah' => 'required|string|max:100',
            'ket_indikasi' => 'nullable|string|max:255',

            'kd_bangsal' => 'required|string|exists:bangsal,kd_bangsal',
            'tujuan_bangsal' => 'required|string|exists:bangsal,kd_bangsal|different:kd_bangsal',
            'metode_pemindahan' => 'required|string|max:50',

            'diagnosa_utama' => 'required|string|max:255',
            'diagnosa_sekunder' => 'nullable|string',
            'prosedur' => 'nullable|string',
            'obat_diberikan' => 'nullable|string',
            'pemeriksaan_penunjang' => 'nullable|string',

            'peralatan_menyertai' => 'nullable|string|max:100',
            'ket_peralatan' => 'nullable|string|max:255',
            'persetujuan_pindah' => 'required|in:Ya,Tidak',
            'nama_pj' => 'nullable|string|max:100',
            'hubungan_pj' => 'nullable|string|max:50',

            'keadaan_umum_sebelum' => 'nullable|string|max:50',
            'td_sebelum' => 'nullable|string|max:20',
            'nadi_sebelum' => 'nullable|string|max:10',
            'rr_sebelum' => 'nullable|string|max:10',
            'suhu_sebelum' => 'nullable|string|max:10',
            'keluhan_sebelum' => 'nullable|string',

            'keadaan_umum_setelah' => 'nullable|string|max:50',
            'td_setelah' => 'nullable|string|max:20',
            'nadi_setelah' => 'nullable|string|max:10',
            'rr_setelah' => 'nullable|string|max:10',
            'suhu_setelah' => 'nullable|string|max:10',
            'keluhan_setelah' => 'nullable|string',

            'perawat_menyerahkan' => 'required|string|exists:pegawai,nik',
            'perawat_menerima' => 'nullable|string|exists:pegawai,nik|different:perawat_menyerahkan',
        ], [
            'tgl_pindah.after_or_equal' => 'Tanggal pindah harus setelah atau sama dengan tanggal masuk.',
            'tujuan_bangsal.different' => 'Asal ruangan dan tujuan ruangan tidak boleh sama.',
            'perawat_menerima.different' => 'Perawat menyerahkan dan menerima tidak boleh sama.',
            'kd_bangsal.exists' => 'Asal ruangan yang dipilih tidak valid.',
            'tujuan_bangsal.exists' => 'Tujuan ruangan yang dipilih tidak valid.',
            'perawat_menyerahkan.exists' => 'Perawat menyerahkan yang dipilih tidak valid.',
            'perawat_menerima.exists' => 'Perawat menerima yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi Transfer Pasien gagal:', $validator->errors()->toArray());

            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal. Silakan periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $transfer = TransferPasien::create([
                'no_rawat' => $request->no_rawat,
                'tanggal_masuk' => $request->tgl_masuk,
                'tanggal_pindah' => $request->tgl_pindah,

                'asal_ruang' => Bangsal::where('kd_bangsal', $request->kd_bangsal)->value('nm_bangsal'),
                'ruang_selanjutnya' => Bangsal::where('kd_bangsal', $request->tujuan_bangsal)->value('nm_bangsal'),

                'diagnosa_utama' => $request->diagnosa_utama,
                'diagnosa_sekunder' => $request->diagnosa_sekunder,

                'indikasi_pindah_ruang' => $request->indikasi_pindah,
                'keterangan_indikasi_pindah_ruang' => $request->ket_indikasi,

                'prosedur_yang_sudah_dilakukan' => $request->prosedur,
                'obat_yang_telah_diberikan' => $request->obat_diberikan,

                'metode_pemindahan_pasien' => $request->metode_pemindahan,
                'peralatan_yang_menyertai' => $request->peralatan_menyertai,
                'keterangan_peralatan_yang_menyertai' => $request->ket_peralatan,

                'pemeriksaan_penunjang_yang_dilakukan' => $request->pemeriksaan_penunjang,

                'pasien_keluarga_menyetujui' => $request->persetujuan_pindah,
                'nama_menyetujui' => $request->nama_pj,
                'hubungan_menyetujui' => $request->hubungan_pj,

                'keluhan_utama_sebelum_transfer' => $request->keluhan_sebelum,
                'keadaan_umum_sebelum_transfer' => $request->keadaan_umum_sebelum,
                'td_sebelum_transfer' => $request->td_sebelum,
                'nadi_sebelum_transfer' => $request->nadi_sebelum,
                'rr_sebelum_transfer' => $request->rr_sebelum,
                'suhu_sebelum_transfer' => $request->suhu_sebelum,

                'keluhan_utama_sesudah_transfer' => $request->keluhan_setelah,
                'keadaan_umum_sesudah_transfer' => $request->keadaan_umum_setelah,
                'td_sesudah_transfer' => $request->td_setelah,
                'nadi_sesudah_transfer' => $request->nadi_setelah,
                'rr_sesudah_transfer' => $request->rr_setelah,
                'suhu_sesudah_transfer' => $request->suhu_setelah,

                'nip_menyerahkan' => $request->perawat_menyerahkan,
                'nip_menerima' => $request->perawat_menerima,
            ]);

            DB::commit();

            Log::info('Transfer Pasien berhasil disimpan:', [
                'no_rawat' => $transfer->no_rawat,
                'tanggal_masuk' => $transfer->tanggal_masuk,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Formulir Transfer Pasien berhasil disimpan!',
                'data' => $transfer,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error menyimpan Transfer Pasien:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_rawat' => 'required|string',
            'tanggal_masuk' => 'required',
            'keadaan_umum_setelah' => 'nullable|string|max:50',
            'td_setelah' => 'nullable|string|max:20',
            'nadi_setelah' => 'nullable|string|max:10',
            'rr_setelah' => 'nullable|string|max:10',
            'suhu_setelah' => 'nullable|string|max:10',
            'keluhan_setelah' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transfer = TransferPasien::where('no_rawat', $request->no_rawat)
                ->where('tanggal_masuk', $request->tanggal_masuk)
                ->first();

            if (!$transfer) {
                // Try matching by date only (without time)
                $dateOnly = explode(' ', $request->tanggal_masuk)[0];
                $transfer = TransferPasien::where('no_rawat', $request->no_rawat)
                    ->whereDate('tanggal_masuk', $dateOnly)
                    ->first();
            }

            if (!$transfer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data transfer tidak ditemukan untuk no_rawat ' . $request->no_rawat
                ], 404);
            }

            $transfer->update([
                'keadaan_umum_sesudah_transfer' => $request->keadaan_umum_setelah,
                'td_sesudah_transfer' => $request->td_setelah,
                'nadi_sesudah_transfer' => $request->nadi_setelah,
                'rr_sesudah_transfer' => $request->rr_setelah,
                'suhu_sesudah_transfer' => $request->suhu_setelah,
                'keluhan_utama_sesudah_transfer' => $request->keluhan_setelah,
            ]);

            Log::info('Keadaan setelah transfer diupdate:', [
                'no_rawat' => $request->no_rawat,
                'tanggal_masuk' => $request->tanggal_masuk,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Keadaan setelah transfer berhasil diperbarui.',
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal update transfer:', ['msg' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }
}
