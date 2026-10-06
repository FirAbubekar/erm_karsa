@include('errors.layout', [
    'code' => '405',
    'title' => 'Metode Tidak Diizinkan',
    'desc' => 'Metode permintaan HTTP yang digunakan tidak diizinkan untuk rute ini.',
])
