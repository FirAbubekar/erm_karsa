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

        // Check if custom WA gateway is configured
        $waUrl = env('WA_URL');
        $deviceId = env('WA_DEVICE_ID');
        // dd($waUrl);
        try {
            if ($waUrl && $deviceId) {
                // Use custom gateway with multiple common headers for compatibility
                $response = Http::timeout(10)
                    ->withBasicAuth('admin', 'Y0ndaktaukoktanyasay4@1113!') // 🔥 INI YANG KURANG
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
            Log::error('WhatsappService Error: ' . $e->getMessage());
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
        $message = $customMessage ?: "Berikut kami lampirkan dokumen General Consent Anda.";

        // Check if custom WA gateway is configured
        $waUrl = env('WA_URL');
        $deviceId = env('WA_DEVICE_ID');

        try {
            // Ensure file exists
            if (!Storage::exists($relativeStoragePath)) {
                Log::error("WhatsappService: File not found at {$relativeStoragePath}");
                return false;
            }

            $absolutePath = Storage::path($relativeStoragePath);
            $fileContent = Storage::get($relativeStoragePath);
            $fileName = basename($absolutePath);

            if ($waUrl && $deviceId) {
                // Use custom gateway - Assuming /send/file endpoint
                $response = Http::timeout(20)
                    ->withBasicAuth('admin', 'Y0ndaktaukoktanyasay4@1113!')
                    ->withHeaders([
                        'X-Device-Id' => $deviceId,
                    ])
                    ->attach('file', $fileContent, $fileName)
                    ->post($waUrl . '/send/file', [
                        'phone'   => $target,
                        'message' => $message,
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

    public function getDefaultMessage(string $signedUrl): string
    {
        return "Berikut kami sampaikan tautan untuk mengunduh dokumen yang General Consent (berlaku selama 1 jam):\n\n" .
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
