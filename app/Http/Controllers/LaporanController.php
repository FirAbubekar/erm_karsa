<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralConsent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Laporan General Consent (Grafik & Tabel per Tanggal)
     */
    public function generalConsent(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // Ambil data General Consent berdasarkan filter tanggal
        $query = GeneralConsent::with('regPeriksa.pasien', 'pegawai')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Hitung data tren harian untuk grafik
        $chartRawData = DB::table('surat_persetujuan_umum')
            ->select(DB::raw('tanggal, COUNT(*) as total'))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Susun array tanggal lengkap antara startDate dan endDate agar grafik tidak bolong
        $periodDates = [];
        $current = Carbon::parse($startDate);
        $last = Carbon::parse($endDate);

        while ($current->lte($last)) {
            $periodDates[$current->toDateString()] = 0;
            $current->addDay();
        }

        foreach ($chartRawData as $row) {
            if (isset($periodDates[$row->tanggal])) {
                $periodDates[$row->tanggal] = (int) $row->total;
            }
        }

        $chartLabels = array_map(function ($dateStr) {
            return Carbon::parse($dateStr)->translatedFormat('d M Y');
        }, array_keys($periodDates));
        
        $chartData = array_values($periodDates);

        // Ambil daftar data untuk tabel dengan paginasi
        $consents = $query->orderBy('tanggal', 'desc')
            ->orderBy('no_surat', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Metrik Statistik ringkas
        $totalPeriod = array_sum($chartData);
        $avgDaily = count($chartData) > 0 ? round($totalPeriod / count($chartData), 1) : 0;
        $maxDaily = count($chartData) > 0 ? max($chartData) : 0;

        // 1. Top User / Petugas Pembuat General Consent Terbanyak
        $topUsers = DB::table('surat_persetujuan_umum')
            ->leftJoin('pegawai', 'surat_persetujuan_umum.nip', '=', 'pegawai.nik')
            ->select(
                'surat_persetujuan_umum.nip',
                DB::raw('COALESCE(pegawai.nama, surat_persetujuan_umum.nip, "Tidak Terdata") as nama_petugas'),
                DB::raw('COUNT(*) as total_dibuat')
            )
            ->whereBetween('surat_persetujuan_umum.tanggal', [$startDate, $endDate])
            ->groupBy('surat_persetujuan_umum.nip', 'pegawai.nama')
            ->orderBy('total_dibuat', 'desc')
            ->limit(5)
            ->get();

        // 2. Laporan / Distribusi Pengobatan Kepada (Diri Sendiri, Suami, Istri, Anak, Orang Tua, dll)
        $pengobatanKepadaRaw = DB::table('surat_persetujuan_umum')
            ->select(
                DB::raw('COALESCE(NULLIF(TRIM(pengobatan_kepada), ""), "Lain-lain") as kategori'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('pengobatan_kepada')
            ->orderBy('total', 'desc')
            ->get();

        // Group small categories (< 1% of total) into "Lain-lain" for cleaner chart
        $pengobatanTotal = $pengobatanKepadaRaw->sum('total');
        $threshold = $pengobatanTotal * 0.01; // 1% threshold
        $mainCategories = collect();
        $lainLainTotal = 0;

        foreach ($pengobatanKepadaRaw as $item) {
            if ($item->total >= $threshold && $mainCategories->count() < 10) {
                $mainCategories->push($item);
            } else {
                $lainLainTotal += $item->total;
            }
        }

        if ($lainLainTotal > 0) {
            $lainLain = new \stdClass();
            $lainLain->kategori = 'Lain-lain';
            $lainLain->total = $lainLainTotal;
            $mainCategories->push($lainLain);
        }

        $pengobatanKepadaData = $mainCategories;

        $pengobatanLabels = $pengobatanKepadaData->pluck('kategori')->map(function($kat) {
            return ucfirst($kat);
        })->toArray();
        $pengobatanChartData = $pengobatanKepadaData->pluck('total')->toArray();

        return view('laporan.general_consent', compact(
            'consents',
            'startDate',
            'endDate',
            'chartLabels',
            'chartData',
            'totalPeriod',
            'avgDaily',
            'maxDaily',
            'topUsers',
            'pengobatanKepadaData',
            'pengobatanLabels',
            'pengobatanChartData'
        ));
    }

    /**
     * Laporan Surat Persetujuan Rawat Inap (Grafik & Analitik per Tanggal)
     */
    public function suratPersetujuanRawatInap(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // Hitung data tren harian untuk grafik
        $chartRawData = DB::table('surat_persetujuan_rawat_inap')
            ->select(DB::raw('tanggal, COUNT(*) as total'))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Susun array tanggal lengkap antara startDate dan endDate agar grafik tidak bolong
        $periodDates = [];
        $current = Carbon::parse($startDate);
        $last = Carbon::parse($endDate);

        while ($current->lte($last)) {
            $periodDates[$current->toDateString()] = 0;
            $current->addDay();
        }

        foreach ($chartRawData as $row) {
            if (isset($periodDates[$row->tanggal])) {
                $periodDates[$row->tanggal] = (int) $row->total;
            }
        }

        $chartLabels = array_map(function ($dateStr) {
            return Carbon::parse($dateStr)->translatedFormat('d M Y');
        }, array_keys($periodDates));

        $chartData = array_values($periodDates);

        // Metrik Statistik ringkas
        $totalPeriod = array_sum($chartData);
        $avgDaily = count($chartData) > 0 ? round($totalPeriod / count($chartData), 1) : 0;
        $maxDaily = count($chartData) > 0 ? max($chartData) : 0;

        // 1. Top User / Petugas Pembuat SPRI Terbanyak
        $topUsers = DB::table('surat_persetujuan_rawat_inap')
            ->leftJoin('pegawai', 'surat_persetujuan_rawat_inap.nip', '=', 'pegawai.nik')
            ->select(
                'surat_persetujuan_rawat_inap.nip',
                DB::raw('COALESCE(pegawai.nama, surat_persetujuan_rawat_inap.nip, "Tidak Terdata") as nama_petugas'),
                DB::raw('COUNT(*) as total_dibuat')
            )
            ->whereBetween('surat_persetujuan_rawat_inap.tanggal', [$startDate, $endDate])
            ->groupBy('surat_persetujuan_rawat_inap.nip', 'pegawai.nama')
            ->orderBy('total_dibuat', 'desc')
            ->limit(5)
            ->get();

        // 2. Distribusi Kelas Rawat Inap
        $kelasRaw = DB::table('surat_persetujuan_rawat_inap')
            ->select(
                DB::raw('COALESCE(NULLIF(TRIM(kelas), ""), "Lainnya") as kategori'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('kelas')
            ->orderBy('total', 'desc')
            ->get();

        $kelasLabels = $kelasRaw->pluck('kategori')->toArray();
        $kelasChartData = $kelasRaw->pluck('total')->toArray();

        // 3. Distribusi Hubungan PJ (Anak, Suami, Ayah, Ibu, Istri, dll)
        $hubunganRaw = DB::table('surat_persetujuan_rawat_inap')
            ->select(
                DB::raw('COALESCE(NULLIF(TRIM(hubungan), ""), "Lain-lain") as kategori'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('hubungan')
            ->orderBy('total', 'desc')
            ->get();

        $hubunganTotal = $hubunganRaw->sum('total');
        $thresholdHub = $hubunganTotal * 0.01;
        $hubunganData = collect();
        $lainLainHub = 0;

        foreach ($hubunganRaw as $item) {
            if ($item->total >= $thresholdHub && $hubunganData->count() < 8) {
                $hubunganData->push($item);
            } else {
                $lainLainHub += $item->total;
            }
        }

        if ($lainLainHub > 0) {
            $obj = new \stdClass();
            $obj->kategori = 'Lain-lain';
            $obj->total = $lainLainHub;
            $hubunganData->push($obj);
        }

        $hubunganLabels = $hubunganData->pluck('kategori')->map(function($kat) {
            return ucfirst($kat);
        })->toArray();
        $hubunganChartData = $hubunganData->pluck('total')->toArray();

        // 4. Distribusi Cara Bayar (JKN, Rencana JKN, Bayar Sendiri, dll)
        $bayarRaw = DB::table('surat_persetujuan_rawat_inap')
            ->select(
                DB::raw('COALESCE(NULLIF(TRIM(bayar_secara), ""), "Lain-lain") as kategori'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('bayar_secara')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get();

        $bayarLabels = $bayarRaw->pluck('kategori')->toArray();
        $bayarChartData = $bayarRaw->pluck('total')->toArray();

        return view('laporan.surat_persetujuan_rawat_inap', compact(
            'startDate',
            'endDate',
            'chartLabels',
            'chartData',
            'totalPeriod',
            'avgDaily',
            'maxDaily',
            'topUsers',
            'kelasLabels',
            'kelasChartData',
            'hubunganLabels',
            'hubunganChartData',
            'bayarLabels',
            'bayarChartData'
        ));
    }

    /**
     * Laporan RM 10 Edukasi Pasien (Placeholder)
     */
    public function rm10()
    {
        return view('laporan.rm10');
    }
}
