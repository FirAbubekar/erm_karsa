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

        $waUrl = env('WA_URL');
        $deviceId = env('WA_DEVICE_ID');

        try {
            if (!$waUrl || !$deviceId) {
                Log::error('WhatsappService: WA_URL or WA_DEVICE_ID not configured');
                return false;
            }

            $response = Http::timeout(10)
                ->withBasicAuth('rskh-wa-gw', 'Y0ndaktaukoktanyasay4@1113!')
                ->withHeaders([
                    'X-Device-Id' => $deviceId,
                ])
                ->post($waUrl . '/send/message', [
                    'phone'   => $target,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WA Gateway Error:', [
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

        $waUrl = env('WA_URL');
        $deviceId = env('WA_DEVICE_ID');

        try {
            if (!$waUrl || !$deviceId) {
                Log::error('WhatsappService: WA_URL or WA_DEVICE_ID not configured');
                return false;
            }

            $fileContent = null;
            $fileName = '';

            if (str_starts_with($relativeStoragePath, 'http')) {
                $response = Http::timeout(30)
                    ->withBasicAuth('rskh-wa-gw', 'Y0ndaktaukoktanyasay4@1113!')
                    ->withHeaders([
                        'X-Device-Id' => $deviceId,
                    ])
                    ->post($waUrl . '/send/file', [
                        'phone'   => $target,
                        'file'    => $relativeStoragePath,
                        'caption' => $message,
                        'message' => $message,
                    ]);

                if ($response->successful()) {
                    return true;
                }

                Log::warning('WA Gateway JSON File URL failed, trying multipart upload. Error details:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            if (str_starts_with($relativeStoragePath, 'http')) {
                $fileName = basename(parse_url($relativeStoragePath, PHP_URL_PATH));
                try {
                    $dlResponse = Http::timeout(10)->get($relativeStoragePath);
                    if ($dlResponse->successful()) {
                        $fileContent = $dlResponse->body();
                    }
                } catch (\Exception $urlEx) {
                    Log::warning("WhatsappService: Failed to download from URL: " . $urlEx->getMessage());
                }

                if (empty($fileContent)) {
                    $localPath = "D:\\xampp\\htdocs\\webapps\\berkasrawat\\pages\\upload\\" . $fileName;
                    if (file_exists($localPath)) {
                        $fileContent = file_get_contents($localPath);
                    }
                }
            } else {
                if (Storage::exists($relativeStoragePath)) {
                    $absolutePath = Storage::path($relativeStoragePath);
                    $fileContent = Storage::get($relativeStoragePath);
                    $fileName = basename($absolutePath);
                }
            }

            if (empty($fileContent)) {
                Log::error("WhatsappService: Could not load file content. Path: {$relativeStoragePath}");
                return false;
            }

            $response = Http::timeout(30)
                ->withBasicAuth('rskh-wa-gw', 'Y0ndaktaukoktanyasay4@1113!')
                ->withHeaders([
                    'X-Device-Id' => $deviceId,
                ])
                ->attach('file', $fileContent, $fileName)
                ->attach('phone', $target)
                ->attach('message', $message)
                ->attach('caption', $message)
                ->post($waUrl . '/send/file');

            if ($response->successful()) {
                return true;
            }

            Log::error('WA Gateway File Error:', [
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

