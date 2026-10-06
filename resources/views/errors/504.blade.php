@include('errors.layout', [
    'code' => '504',
    'title' => 'Gateway Timeout',
    'desc' => 'Waktu tunggu server telah habis saat menghubungi upstream. Silakan coba kembali beberapa saat lagi.',
])
