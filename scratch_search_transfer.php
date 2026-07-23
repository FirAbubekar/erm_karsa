<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== FIRST 5 TRANSFER RECORDS ===\n";
    $records = DB::table('transfer_pasien_antar_ruang')->limit(5)->get();
    foreach ($records as $r) {
         echo "Rawat: " . $r->no_rawat . " | Asal: " . $r->asal_ruang . " | Selanjutnya: " . $r->ruang_selanjutnya . " | Masuk: " . $r->tanggal_masuk . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
