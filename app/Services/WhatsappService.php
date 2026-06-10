<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhatsappService
{
    /**
     * Send signed download link via Fonnte API.
     *
     * @param string $noTelp
     * @param string $signedUrl
     * @return bool
     */
    public function sendLink(string $noTelp, string $signedUrl, string $customMessage = null): bool
    {
        $target = $this->formatNumber($noTelp);
        $message = $customMessage ?: $this->getDefaultMessage($signedUrl);
        return $this->sendMessage($target, $message);
    }

    /**
     * Send plain text message via WhatsApp gateway.
     *
     * @param string $noTelp
     * @param string $message
     * @return bool
     */
    public function sendMessage(string $noTelp, string $message): bool
    {
        $target = $this->formatNumber($noTelp);

        // Check if custom WA gateway is configured
        $waUrl = env('WA_URL');
        $deviceId = env('WA_DEVICE_ID');

        try {
            if ($waUrl && $deviceId) {
                // Use custom gateway
                $response = Http::timeout(10)
                    ->withBasicAuth('admin', 'Y0ndaktaukoktanyasay4@1113!')
                    ->withHeaders([
                        'X-Device-Id' => $deviceId,
                    ])
                    ->post($waUrl . '/send/message', [
                        'phone'   => $target,
                        'message' => $message,
                ]);
            } else {    
                // Fallback to Fonnte
                $token = config('services.fonnte.token');
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);
            }

            if ($response->successful()) {
                return true;
            }

            Log::error('WA Gateway Error details:', [
                'url' => $waUrl,
                'status' => $response->status(),
                'body' => $response->body(),
                'device_id' => $deviceId
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsappService sendMessage Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send file via WhatsApp gateway.
     *
     * @param string $noTelp
     * @param string $relativeStoragePath
     * @param string|null $customMessage
     * @return bool
     */
    public function sendFile(string $noTelp, string $relativeStoragePath, string $customMessage = null): bool
    {
        $target = $this->formatNumber($noTelp);
        $message = $customMessage ?: $this->getDefaultFileMessage();

        // Check if custom WA gateway is configured
        $waUrl = env('WA_URL');
        $deviceId = env('WA_DEVICE_ID');

        try {
            $fileContent = null;
            $fileName = '';

            // Check if it's a URL or a local path
            if (str_starts_with($relativeStoragePath, 'http')) {
                $fileName = basename(parse_url($relativeStoragePath, PHP_URL_PATH));
                
                // It's a URL, download content with a shorter timeout
                try {
                    $response = Http::timeout(10)->get($relativeStoragePath);
                    if ($response->successful()) {
                        $fileContent = $response->body();
                    } else {
                        Log::warning("WhatsappService: HTTP GET failed with status {$response->status()} for URL: {$relativeStoragePath}");
                    }
                } catch (\Exception $urlEx) {
                    Log::warning("WhatsappService: Failed to download from URL due to exception: " . $urlEx->getMessage());
                }

                // Fallback: If URL download failed, try local file backup
                if (empty($fileContent)) {
                    $localPath = "D:\\xampp\\htdocs\\webapps\\berkasrawat\\pages\\upload\\" . $fileName;
                    if (file_exists($localPath)) {
                        $fileContent = file_get_contents($localPath);
                        Log::info("WhatsappService: Successfully fell back to local file path: {$localPath}");
                    } else {
                        Log::error("WhatsappService: Failed to download file from URL and local fallback not found at {$localPath}");
                        return false;
                    }
                }
            } else {
                // It's a local storage path
                if (!Storage::exists($relativeStoragePath)) {
                    Log::error("WhatsappService: File not found at {$relativeStoragePath}");
                    return false;
                }
                $absolutePath = Storage::path($relativeStoragePath);
                $fileContent = Storage::get($relativeStoragePath);
                $fileName = basename($absolutePath);
            }

            if ($waUrl && $deviceId) {
                // Use custom gateway - Assuming /send/file endpoint
                $response = Http::timeout(30)
                    ->withBasicAuth('admin', 'Y0ndaktaukoktanyasay4@1113!')
                    ->withHeaders([
                        'X-Device-Id' => $deviceId,
                    ])
                    ->attach('file', $fileContent, $fileName)
                    ->post($waUrl . '/send/file', [
                        'phone'   => $target,
                        'message' => $message,
                        'caption' => $message,
                    ]);
            } else {
                // Fallback to Fonnte
                $token = config('services.fonnte.token');
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])
                ->attach('file', $fileContent, $fileName)
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'caption' => $message, // Added for Fonnte compatibility if needed
                    'countryCode' => '62',
                ]);
            }

            if ($response->successful()) {
                return true;
            }

            Log::error('WA Gateway File Error details:', [
                'url' => $waUrl,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsappService sendFile Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getDefaultFileMessage(): string
    {
        return "Yth. Bapak/Ibu,\n\n" .
               "Terima kasih telah mempercayakan pelayanan kesehatan Anda kepada kami di *RSUD Karsa Husada Batu*.\n\n" .
               "Bersama pesan ini, kami lampirkan dokumen digital *General Consent (Persetujuan Umum)* Anda sebagai bukti administrasi yang sah.\n\n" .
               "Mohon simpan dokumen ini dengan baik. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.";
    }

    public function getDefaultMessage(string $signedUrl): string
    {
        return "Yth. Bapak/Ibu Pasien,\n\n" .
               "Berikut kami sampaikan tautan untuk mengunduh dokumen General Consent (berlaku selama 1 jam):\n\n" .
               $signedUrl . "\n\n" .
               "Mohon untuk segera mengunduh dokumen tersebut sebelum masa berlaku tautan berakhir.\n\n" .
               "Atas perhatian dan kerja samanya, kami ucapkan terima kasih.";
    }

    /**
     * Format phone number to Indonesia format (62...).
     *
     * @param string $number
     * @return string
     */
    private function formatNumber(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }
}
