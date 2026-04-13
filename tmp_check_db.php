<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PelepasanInformasi;
use App\Models\GeneralConsent;

$pelepasan = PelepasanInformasi::orderBy('id', 'desc')->limit(10)->get();
echo "--- LATEST PELEPASAN INFORMASI ---\n";
foreach ($pelepasan as $p) {
    echo "ID: {$p->id}, No Surat: " . ($p->no_surat ?? 'NULL') . ", RM: {$p->no_rekamedis}, Status: {$p->status}, Created: {$p->created_date}\n";
}

$consents = GeneralConsent::orderBy('id', 'desc')->limit(5)->get();
echo "\n--- LATEST CONSENTS ---\n";
foreach ($consents as $c) {
    echo "ID: {$c->id}, No Surat: {$c->no_surat}, Created: {$c->tanggal}\n";
}
