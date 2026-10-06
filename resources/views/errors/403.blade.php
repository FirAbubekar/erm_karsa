@include('errors.layout', [
    'code' => '403',
    'title' => 'Akses Ditolak',
    'desc' => $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman atau fitur ini. Silakan hubungi administrator.',
])
