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

                if ($response->successful()) {
                    return true;
                }

                Log::warning('WA Gateway Error details, falling back to Fonnte:', [
                    'url' => $waUrl,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'device_id' => $deviceId
                ]);
            }

            // Fallback to Fonnte
            $token = config('services.fonnte.token');
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Fonnte Gateway Error details:', [
                'status' => $response->status(),
                'body' => $response->body(),
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

            if ($waUrl && $deviceId) {
                // Use custom gateway - First try JSON if URL
                $customGatewaySuccess = false;
                
                if (str_starts_with($relativeStoragePath, 'http')) {
                    $response = Http::timeout(30)
                        ->withBasicAuth('admin', 'Y0ndaktaukoktanyasay4@1113!')
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
                    
                    Log::warning('WA Gateway JSON File URL failed, falling back to download and attach method. Error details:', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }

                // Download content for multipart (Custom gateway fallback or Fonnte)
                if (empty($fileContent)) {
                    if (str_starts_with($relativeStoragePath, 'http')) {
                        $fileName = basename(parse_url($relativeStoragePath, PHP_URL_PATH));
                        try {
                            $response = Http::timeout(10)->get($relativeStoragePath);
                            if ($response->successful()) {
                                $fileContent = $response->body();
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
                }

                // If file content is successfully loaded, try custom gateway multipart
                if (!empty($fileContent)) {
                    $response = Http::timeout(30)
                        ->withBasicAuth('admin', 'Y0ndaktaukoktanyasay4@1113!')
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

                    Log::warning('WA Gateway File Error details, falling back to Fonnte:', [
                        'url' => $waUrl,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }
            }

            // Fallback to Fonnte
            if (empty($fileContent)) {
                if (str_starts_with($relativeStoragePath, 'http')) {
                    $fileName = basename(parse_url($relativeStoragePath, PHP_URL_PATH));
                    try {
                        $response = Http::timeout(10)->get($relativeStoragePath);
                        if ($response->successful()) {
                            $fileContent = $response->body();
                        }
                    } catch (\Exception $e) {}
                    
                    if (empty($fileContent)) {
                        $localPath = "D:\\xampp\\htdocs\\webapps\\berkasrawat\\pages\\upload\\" . $fileName;
                        if (file_exists($localPath)) {
                            $fileContent = file_get_contents($localPath);
                        }
                    }
                } else {
                    if (Storage::exists($relativeStoragePath)) {
                        $fileContent = Storage::get($relativeStoragePath);
                        $fileName = basename(Storage::path($relativeStoragePath));
                    }
                }
            }
            
            if (empty($fileContent)) {
                Log::error("WhatsappService: Could not load file content for Fonnte fallback. Path: {$relativeStoragePath}");
                return false;
            }
            
            $token = config('services.fonnte.token');
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])
            ->attach('file', $fileContent, $fileName)
            ->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'caption' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Fonnte Gateway File Error details:', [
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

