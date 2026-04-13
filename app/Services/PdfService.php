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
        try {
            \Illuminate\Support\Facades\Http::attach(
                'pdf', $pdfOutput, $filenameOri
            )->post('http://192.168.30.24/webapps/persetujuanumum/pages/upload_pdf.php', [
                'no_surat' => $consent->no_surat,
            ]);
        } catch (\Exception $e) {
            // Log error if needed, but continue to return internal path
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
}
