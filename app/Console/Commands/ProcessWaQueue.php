<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsappService;

class ProcessWaQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:process-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proses antrean WhatsApp 1 file per eksekusi (biasanya tiap menit)';

    /**
     * Execute the console command.
     */
    public function handle(WhatsappService $waService)
    {
        // 1. Ambil maksimal 2 antrean yang pending ATAU failed (yang belum mencapai batas retry)
        $antreans = DB::table('t_antrean_wa')
            ->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere(function($q) {
                          $q->where('status', 'failed')
                            ->where(function($sq) {
                                $sq->whereNull('error_message')
                                   ->orWhere('error_message', 'not like', '%[Max RetriesReached]%');
                            });
                      });
            })
            ->orderBy('id', 'asc')
            ->limit(2)
            ->get();

        // 2. Jika tidak ada antrean, berhenti
        if ($antreans->isEmpty()) {
            $this->info('Tidak ada antrean WhatsApp.');
            return;
        }

        $this->info("Menemukan {$antreans->count()} antrean. Mulai memproses...");

        foreach ($antreans as $antrean) {
            // 3. Ubah status jadi processing agar tidak diambil oleh proses lain
            DB::table('t_antrean_wa')
                ->where('id', $antrean->id)
                ->update(['status' => 'processing', 'updated_at' => now()]);

            $this->info("Memproses antrean ID: {$antrean->id} untuk No. Surat: {$antrean->no_surat}");

            try {
                // 4. Kirim WA
                $success = false;
                
                if (!empty($antrean->file_path)) {
                    $success = $waService->sendFile($antrean->no_telp, $antrean->file_path, $antrean->pesan);
                } else {
                    $success = $waService->sendMessage($antrean->no_telp, $antrean->pesan);
                }

                // 5. Update status berdasar hasil
                if ($success) {
                    DB::table('t_antrean_wa')
                        ->where('id', $antrean->id)
                        ->update([
                            'status' => 'sent',
                            'sent_at' => now(),
                            'updated_at' => now()
                        ]);
                    $this->info("Berhasil mengirim ke {$antrean->no_telp}");
                } else {
                    // Cari tahu alasan gagal
                    $errorMsg = 'Gagal dikirim (API merespon gagal)';
                    
                    if (!empty($antrean->file_path)) {
                        // Check if it's a URL or local
                        if (str_starts_with($antrean->file_path, 'http')) {
                            // URL check is done inside WhatsappService
                        } else if (!\Illuminate\Support\Facades\Storage::exists($antrean->file_path)) {
                            $errorMsg = "Gagal: File PDF tidak ditemukan di storage ({$antrean->file_path})";
                        }
                    }

                    // Hitung jumlah retry berdasarkan isi error_message lama
                    $currentErrorMessage = $antrean->error_message ?? '';
                    $newRetryCount = 1;
                    if (preg_match('/Retry (\d+)/', $currentErrorMessage, $matches)) {
                        $newRetryCount = intval($matches[1]) + 1;
                    }

                    if ($newRetryCount >= 3) {
                        $errorMsg .= " | Retry {$newRetryCount} [Max RetriesReached]";
                    } else {
                        $errorMsg .= " | Retry {$newRetryCount}";
                    }
 
                    DB::table('t_antrean_wa')
                        ->where('id', $antrean->id)
                        ->update([
                            'status' => 'failed',
                            'error_message' => $errorMsg,
                            'updated_at' => now()
                        ]);
                    $this->error($errorMsg);
                }

            } catch (\Exception $e) {
                // 6. Jika terjadi error sistem/jaringan
                $currentErrorMessage = $antrean->error_message ?? '';
                $newRetryCount = 1;
                if (preg_match('/Retry (\d+)/', $currentErrorMessage, $matches)) {
                    $newRetryCount = intval($matches[1]) + 1;
                }

                $errorMsg = "Error system: " . $e->getMessage();
                if ($newRetryCount >= 3) {
                    $errorMsg .= " | Retry {$newRetryCount} [Max RetriesReached]";
                } else {
                    $errorMsg .= " | Retry {$newRetryCount}";
                }

                DB::table('t_antrean_wa')
                    ->where('id', $antrean->id)
                    ->update([
                        'status' => 'failed',
                        'error_message' => $errorMsg,
                        'updated_at' => now()
                    ]);
                $this->error("Error system: " . $e->getMessage());
                Log::error("Error WA Queue ID {$antrean->id}: " . $e->getMessage());
            }

            // Beri jeda 2 detik antar pengiriman agar tidak spam
            sleep(2);
        }
    }
}
