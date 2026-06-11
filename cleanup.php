<?php
$file = "resources/views/edukasi_pasien/history.blade.php";
$content = file_get_contents($file);

// Remove Pelepasan Informasi section from HTML
$content = preg_replace("/<!-- Pelepasan Informasi -->.*?<!-- Signature -->/s", "<!-- Signature -->", $content);

// Remove JS fetch for pelepasan informasi
$content = preg_replace("/\/\/ ─── Fetch Pelepasan Informasi ───.*?document\.getElementById\('detailModal'\)/s", "document.getElementById('detailModal')", $content);

file_put_contents($file, $content);
