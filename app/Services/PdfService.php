<?php

namespace App\Services;

use App\Models\GeneralConsent;
use App\Models\PelepasanInformasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PdfService
{
    /**
     * Generate PDF and save to internal and external locations.
     *
     * @param GeneralConsent $consent
     * @return string The relative path to the saved PDF in internal storage
     */
    public function generateAndSave(GeneralConsent $consent): string
    {
        // Fetch required data for the view
        $consent->load(['regPeriksa.pasien', 'regPeriksa.signaturePasien', 'pegawai']);
        
        $pelepasanInformasi = PelepasanInformasi::where('no_rekamedis', $consent->regPeriksa->pasien->no_rkm_medis)
            ->where('status', 'aktif')
            ->get();

        $deviceInfo = [
            'ip' => request()->ip(),
            'lat' => request()->query('lat', '-'),
            'lng' => request()->query('lng', '-'),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('general_consent.pdf', compact('consent', 'pelepasanInformasi', 'deviceInfo'));
        $pdf->setPaper('a4', 'portrait');
        $pdfOutput = $pdf->output();

        // Get Path dynamically
        $internalPath = self::getInternalPath($consent);
        $filename = basename($internalPath);

        // 1. Save to Internal Storage (Backup)
        Storage::put($internalPath, $pdfOutput);

        // 2. Save to External Path 1: D:\xampp\htdocs\file_erm\rm01
        $extPath1 = "D:\\xampp\\htdocs\\file_erm\\rm01";
        if (!File::isDirectory($extPath1)) {
            File::makeDirectory($extPath1, 0755, true);
        }
        File::put($extPath1 . DIRECTORY_SEPARATOR . $filename, $pdfOutput);

        // 3. Save to External Path 2: D:\xampp\htdocs\webapps\pernyataanumum\pages\upload
        $extPath2 = "D:\\xampp\\htdocs\\webapps\\pernyataanumum\\pages\\upload";
        if (!File::isDirectory($extPath2)) {
            File::makeDirectory($extPath2, 0755, true);
        }
        File::put($extPath2 . DIRECTORY_SEPARATOR . $filename, $pdfOutput);

        // 4. Send to Remote Server 192.168.30.24 using cURL
        $filenameOri = str_replace('/', '_', $consent->no_surat) . ".pdf";
        try {
            $response = \Illuminate\Support\Facades\Http::attach(
                'pdf', $pdfOutput, $filenameOri
            )->post('http://192.168.30.24/webapps/berkasrawat/pages/upload_pdf.php', [
                'no_surat' => $consent->no_surat,
            ]);

            // Catat balasan dari server .24 ke log untuk debug
            \Illuminate\Support\Facades\Log::info("Respon dari server .24: " . $response->body());
            
        } catch (\Exception $e) {
            // Log error jika koneksi gagal
            \Illuminate\Support\Facades\Log::error("Gagal mengirim PDF ke server .24: " . $e->getMessage());
        }

        return $internalPath;
    }

    /**
     * Reconstruct the internal PDF path dynamically.
     *
     * @param GeneralConsent $consent
     * @return string
     */
    public static function getInternalPath(GeneralConsent $consent): string
    {
        $year = date('Y', strtotime($consent->tanggal));
        $month = date('m', strtotime($consent->tanggal));
        $safeId = str_replace('/', '_', $consent->no_surat);
        return "private/pdf/{$year}/{$month}/dokumen_{$safeId}.pdf";
    }

    public function generateAndSaveEdukasiPasien(\App\Models\PatientEducationAssessment $assessment): string
    {
        $assessment->load(['regPeriksa.pasien']);

        $implementations = \App\Models\PatientEducationImplementation::where('assessment_id', $assessment->id_uuid)
            ->orderBy('no_urut')
            ->get();

        $topikEdukasi = [
            [ 'kode' => 'TE_HAK_PARTISIPASI', 'poli_unit' => 'Pendaftaran', 'topik' => "Hak untuk Berpartisipasi Pada Proses Pelayanan", 'is_custom' => false ],
            [ 'kode' => 'TE_KONDISI', 'poli_unit' => 'Dokter', 'topik' => "1. Diagnosis\n2. Tanda dan Gejala Penyakit\n3. Penatalaksanaan/ Terapi\n4. Komplikasi yang mungkin terjadi\n5. Prognosa\n(sebutkan)", 'is_custom' => false ],
            [ 'kode' => 'TE_PROSEDUR_PENUNJANG', 'poli_unit' => 'Rawat Inap', 'topik' => 'Prosedur Pemeriksaan Penunjang (sebutkan)', 'is_custom' => false ],
            [ 'kode' => 'TE_INFORMED_CONSENT', 'poli_unit' => 'Pendaftaran', 'topik' => 'Proses Pemberian Informed Consent (sebutkan)', 'is_custom' => false ],
            [ 'kode' => 'TE_DIET_NUTRISI', 'poli_unit' => 'Gizi', 'topik' => 'Diet dan Nutrisi (sebutkan)', 'is_custom' => false ],
            [ 'kode' => 'TE_OBAT', 'poli_unit' => 'Farmasi', 'topik' => "1. Manfaat Obat yang Diberikan\n2. Efek Samping Obat-obatan yang Diberi\n3. Interaksi Obat dan Makanan", 'is_custom' => false ],
            [ 'kode' => 'TE_ALAT_MEDIS', 'poli_unit' => 'Rawat Inap', 'topik' => 'Penggunaan Alat Medis yang Aman (Sebutkan)', 'is_custom' => false ],
            [ 'kode' => 'TE_MANAJEMEN_NYERI', 'poli_unit' => 'Rawat Inap', 'topik' => 'Manajemen Nyeri', 'is_custom' => false ],
            [ 'kode' => 'TE_REHABILITASI', 'poli_unit' => 'Rawat Inap', 'topik' => 'Teknik Rehabilitasi', 'is_custom' => false ],
            [ 'kode' => 'TE_CUCI_TANGAN', 'poli_unit' => 'Rawat Inap', 'topik' => 'Cuci Tangan yang Benar', 'is_custom' => false ],
            [ 'kode' => 'TE_BAHAYA_ROKOK', 'poli_unit' => 'Rawat Inap', 'topik' => 'Bahaya Merokok', 'is_custom' => false ],
            [ 'kode' => 'TE_EDUKASI_PULANG', 'poli_unit' => 'Rawat Inap', 'topik' => 'Edukasi Pasien Pulang', 'is_custom' => false ],
            [ 'kode' => 'TE_RUJUKAN', 'poli_unit' => 'Rawat Inap', 'topik' => 'Rujukan Internal', 'is_custom' => false ],
            [ 'kode' => 'TE_EDUKASI_UMUM', 'poli_unit' => 'Pendaftaran', 'topik' => "Edukasi Pasien Umum\nBersedia membayar seluruh biaya perawatan pada kelas perawatan dari awal sampai selesai perawatan.\nTelah diberikan edukasi oleh petugas pendaftaran.\nMenandatangani tanpa paksaan dengan kesadaran penuh.", 'is_custom' => false ],
            [ 'kode' => 'TE_SESUAI_KEBUTUHAN', 'poli_unit' => '', 'topik' => 'Edukasi Sesuai Kebutuhan Pasien, sesuai PPA yang merawat', 'is_custom' => false ],
        ];

        $mergedImplementations = [];

        foreach ($topikEdukasi as $default) {
            $found = $implementations->firstWhere('kode_topik', $default['kode']);
            if ($found) {
                $mergedImplementations[] = $found;
            } else {
                $dummy = new \stdClass();
                $dummy->poli_unit = $default['poli_unit'];
                $dummy->nama_topik = $default['topik'];
                $dummy->created_at = null;
                $dummy->verifikasi = null;
                $dummy->ttd_pasien = null;
                $dummy->ttd_edukator = null;
                $dummy->tgl_reedukasi = null;
                $dummy->tgl_akhir_edukasi = null;
                $mergedImplementations[] = $dummy;
            }
        }

        $defaultKodes = array_column($topikEdukasi, 'kode');
        $customs = $implementations->whereNotIn('kode_topik', $defaultKodes);
        foreach ($customs as $c) {
            $mergedImplementations[] = $c;
        }

        $deviceInfo = [
            'ip' => request()->ip() ?? '127.0.0.1',
            'lat' => request()->query('lat', '-'),
            'lng' => request()->query('lng', '-'),
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];
        
        $pdf = Pdf::loadView('edukasi_pasien.pdf', [
            'assessment' => $assessment, 
            'implementations' => $mergedImplementations, 
            'deviceInfo' => $deviceInfo
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        $pdfOutput = $pdf->output();

        $safeNoRawat = str_replace('/', '', $assessment->no_rawat);
        $filenameOri = "EduPasien" . $safeNoRawat . ".pdf";

        try {
            $response = \Illuminate\Support\Facades\Http::attach(
                'pdf', $pdfOutput, $filenameOri
            )->post('http://192.168.30.24/webapps/berkasrawat/pages/upload_pdf.php', [
                'no_surat' => "EduPasien" . $safeNoRawat,
            ]);

            \Illuminate\Support\Facades\Log::info("Respon dari server .24 (Edukasi Pasien): " . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal mengirim Edukasi Pasien PDF ke server .24: " . $e->getMessage());
        }

        \Illuminate\Support\Facades\DB::table('berkas_digital_perawatan')->updateOrInsert(
            ['no_rawat' => $assessment->no_rawat, 'kode' => '29'],
            ['lokasi_file' => 'pages/upload/' . $filenameOri]
        );

        return $filenameOri;
    }

    public function generateAndSaveProspectiveReviu(\App\Models\ProspectiveReviu $review): string
    {
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
            'ip' => request()->ip() ?? '127.0.0.1',
            'downloaded_at' => now()->format('d/m/Y H:i:s'),
        ];

        // Signatures (barcode/QR) from Pegawai Model
        $apotekerPegawai = \App\Models\Pegawai::where('nik', $review->ttd_apoteker_klinis)->first();
        $dpjpPegawai = \App\Models\Pegawai::where('nik', $review->ttd_dpjp)->first();
        $kpraPegawai = \App\Models\Pegawai::where('nik', $review->ttd_kpra)->first();

        $qrApoteker = $apotekerPegawai ? $apotekerPegawai->signature_base64 : null;
        $qrPerawat = $perawat ? $perawat->signature_base64 : null;
        $qrDpjp = $dpjpPegawai ? $dpjpPegawai->signature_base64 : null;
        $qrKpra = $kpraPegawai ? $kpraPegawai->signature_base64 : null;

        $pdf = Pdf::loadView('prospective_reviu.pdf', compact('review', 'deviceInfo', 'qrApoteker', 'qrPerawat', 'qrDpjp', 'qrKpra'));
        $pdf->setPaper('a4', 'portrait');
        $pdfOutput = $pdf->output();

        $safeNoRawat = str_replace('/', '', $review->no_rawat);
        $shortUuid = substr(str_replace('-', '', $review->id_uuid), 0, 8);
        $filenameOri = "ProspReviu_" . $safeNoRawat . "_" . $shortUuid . ".pdf";

        try {
            $response = \Illuminate\Support\Facades\Http::attach(
                'pdf', $pdfOutput, $filenameOri
            )->post('http://192.168.30.24/webapps/berkasrawat/pages/upload_pdf.php', [
                'no_surat' => "ProspReviu_" . $safeNoRawat . "_" . $shortUuid,
            ]);

            \Illuminate\Support\Facades\Log::info("Respon dari server .24 (Prospective Reviu): " . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal mengirim Prospective Reviu PDF ke server .24: " . $e->getMessage());
        }

        // Simpan ke database SIMRS
        // Kondisi updateOrInsert menggunakan lokasi_file agar satu no_rawat bisa punya banyak reviu tanpa saling timpa
        \Illuminate\Support\Facades\DB::table('berkas_digital_perawatan')->updateOrInsert(
            [
                'no_rawat' => $review->no_rawat,
                'kode' => '30',
                'lokasi_file' => 'pages/upload/' . $filenameOri
            ]
        );

        return $filenameOri;
    }
}
