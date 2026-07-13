<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HasilLabController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/dashboard', function () {
    if (!Session::get('is_logged_in')) {
        return redirect('/');
    }
    return view('dashboard');
})->name('dashboard');

Route::get('/general-consent', function () {
    if (!Session::get('is_logged_in')) {
        return redirect('/');
    }
    return view('general_consent');
})->name('general-consent');

Route::get('/pasien/search', [App\Http\Controllers\PatientController::class, 'search'])->name('pasien.search');
Route::get('/general-consent/list', [App\Http\Controllers\GeneralConsentController::class, 'index'])->name('general-consent.index');
Route::post('/general-consent/save', [App\Http\Controllers\GeneralConsentController::class, 'store'])->name('general-consent.store');
Route::get('/general-consent/download/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'downloadPDF'])->name('general-consent.download');
Route::get('/general-consent/download-signed/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'downloadSignedPdf'])->name('pdf.download.signed')->middleware('signed');
Route::get('/pelepasan-informasi/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'getPelepasanInformasi'])->name('pelepasan-informasi.get');
Route::post('/general-consent/send-wa/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'sendWhatsappManual'])->name('general-consent.send-wa');
Route::get('/general-consent/wa-template/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'getWaTemplate'])->name('general-consent.wa-template');

// Hasil Lab
Route::get('/hasil-lab', [HasilLabController::class, 'index'])->name('hasil-lab.index');
Route::get('/hasil-lab/detail/{ono}', [HasilLabController::class, 'detail'])->name('hasil-lab.detail');
Route::get('/hasil-lab/json/{ono}', [HasilLabController::class, 'getDetailJson'])->name('hasil-lab.json');
Route::get('/hasil-lab/pdf/{ono}', [HasilLabController::class, 'downloadPDF'])->name('hasil-lab.pdf');
Route::post('/hasil-lab/send-wa', [HasilLabController::class, 'sendWhatsApp'])->name('hasil-lab.send-wa');

use App\Http\Controllers\SuratPersetujuanRawatInapController;

Route::get('/surat-persetujuan-rawat-inap', [SuratPersetujuanRawatInapController::class, 'index'])->name('surat-persetujuan-rawat-inap.index');
Route::post('/surat-persetujuan-rawat-inap/store', [SuratPersetujuanRawatInapController::class, 'store'])->name('surat-persetujuan-rawat-inap.store');
Route::get('/surat-persetujuan-rawat-inap/history', [SuratPersetujuanRawatInapController::class, 'history'])->name('surat-persetujuan-rawat-inap.history');
Route::get('/surat-persetujuan-rawat-inap/download/{no_surat}', [SuratPersetujuanRawatInapController::class, 'downloadPDF'])->name('surat-persetujuan-rawat-inap.download');
Route::get('/surat-persetujuan-rawat-inap/wa-template/{no_surat}', [SuratPersetujuanRawatInapController::class, 'getWaTemplate'])->name('surat-persetujuan-rawat-inap.wa-template');
Route::post('/surat-persetujuan-rawat-inap/send-wa', [SuratPersetujuanRawatInapController::class, 'sendWhatsappManual'])->name('surat-persetujuan-rawat-inap.send-wa');

// Transfer Pasien Antar Ruangan
use App\Http\Controllers\TransferPasienController;
Route::get('/transfer-pasien', [TransferPasienController::class, 'index'])->name('transfer-pasien.index');
Route::post('/transfer-pasien/store', [TransferPasienController::class, 'store'])->name('transfer-pasien.store');

// WA Gateway Check
Route::post('/wa-gateway/test-send', function () {
    if (!Session::get('is_logged_in')) {
        return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
    }

    $request = request();
    $phone = $request->input('phone');
    $message = $request->input('message');

    if (!$phone) {
        return response()->json(['success' => false, 'message' => 'Nomor telepon wajib diisi.']);
    }
    if (!$message) {
        return response()->json(['success' => false, 'message' => 'Pesan wajib diisi.']);
    }

    try {
        $waService = app(\App\Services\WhatsappService::class);
        $result = $waService->sendMessage($phone, $message);

        if ($result) {
            return response()->json(['success' => true, 'message' => "Pesan berhasil dikirim ke {$phone}."]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengirim pesan. Periksa log untuk detail.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
})->name('wa-gateway.test-send');

Route::get('/wa-gateway/check', function () {
    if (!Session::get('is_logged_in')) {
        return redirect('/');
    }

    $waUrl = env('WA_URL');
    $deviceId = env('WA_DEVICE_ID');
    $result = [
        'url' => $waUrl,
        'device_id' => $deviceId,
        'status' => 'unknown',
        'message' => '',
        'response_time' => null,
    ];

    if (!$waUrl) {
        $result['status'] = 'error';
        $result['message'] = 'WA_URL tidak dikonfigurasi di .env';
    } elseif (!$deviceId) {
        $result['status'] = 'error';
        $result['message'] = 'WA_DEVICE_ID tidak dikonfigurasi di .env';
    } else {
        try {
            $start = microtime(true);
            $response = Illuminate\Support\Facades\Http::timeout(10)
                ->withBasicAuth('rskh-wa-gw', 'Y0ndaktaukoktanyasay4@1113!')
                ->withHeaders(['X-Device-Id' => $deviceId])
                ->get($waUrl . '/');
            $elapsed = round((microtime(true) - $start) * 1000, 0);

            $result['response_time'] = $elapsed;

            if ($response->successful()) {
                $result['status'] = 'connected';
                $result['message'] = "Gateway merespons dengan status {$response->status()} dalam {$elapsed}ms.";
            } else {
                $result['status'] = 'error';
                $result['message'] = "Gateway merespons dengan status {$response->status()} dalam {$elapsed}ms.";
                $result['body'] = $response->body();
            }
        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['message'] = 'Tidak dapat terhubung: ' . $e->getMessage();
        }
    }

    return view('wa_gateway_check', compact('result'));
})->name('wa-gateway.check');

Route::get('/wa-gateway/history', function () {
    if (!Session::get('is_logged_in')) {
        return redirect('/');
    }

    $stats = [
        'total'   => DB::table('t_antrean_wa')->count(),
        'pending' => DB::table('t_antrean_wa')->where('status', 'pending')->count(),
        'sent'    => DB::table('t_antrean_wa')->where('status', 'sent')->count(),
        'failed'  => DB::table('t_antrean_wa')->where('status', 'failed')->count(),
        'processing' => DB::table('t_antrean_wa')->where('status', 'processing')->count(),
    ];

    return view('wa_gateway_history', compact('stats'));
})->name('wa-gateway.history');

Route::post('/wa-gateway/history-data', function () {
    if (!Session::get('is_logged_in')) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $draw     = request('draw', 1);
    $start    = request('start', 0);
    $length   = request('length', 20);
    $search   = request('search.value', '');
    $orderCol = request('order.0.column', 0);
    $orderDir = request('order.0.dir', 'desc');

    $columns = ['id', 'no_surat', 'no_telp', 'pesan', 'file_path', 'status', 'error_message', 'sent_at', 'created_at'];
    $sortCol = $columns[$orderCol] ?? 'id';

    $query = DB::table('t_antrean_wa');

    $recordsTotal = $query->count();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('no_surat', 'like', "%{$search}%")
              ->orWhere('no_telp', 'like', "%{$search}%")
              ->orWhere('pesan', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhere('error_message', 'like', "%{$search}%");
        });
    }

    $recordsFiltered = $query->count();

    $data = $query->orderBy($sortCol, $orderDir)
        ->skip($start)
        ->take($length)
        ->get()
        ->map(function ($row) {
            $statusBadge = '<span class="status-badge ' . $row->status . '"><span class="status-dot ' . $row->status . '"></span> ' . ucfirst($row->status) . '</span>';

            $pesan = e($row->pesan ?? '-');
            $pesanPreview = '<div class="msg-preview">' . $pesan . '</div>';

            $errorHtml = $row->error_message
                ? '<div class="error-msg" title="' . e($row->error_message) . '">' . e($row->error_message) . '</div>'
                : '<span class="text-muted">-</span>';

            $sentAt = $row->sent_at
                ? '<span class="text-muted">' . \Carbon\Carbon::parse($row->sent_at)->format('d/m/Y H:i') . '</span>'
                : '<span class="text-muted">-</span>';

            $createdAt = '<span class="text-muted">' . \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') . '</span>';

            return [
                'id'             => '<span class="text-mono">' . $row->id . '</span>',
                'no_surat'       => '<span class="text-mono">' . e($row->no_surat ?? '-') . '</span>',
                'no_telp'        => '<span class="text-mono">' . e($row->no_telp) . '</span>',
                'pesan'          => $pesanPreview,
                'status'         => $statusBadge,
                'error_message'  => $errorHtml,
                'sent_at'        => $sentAt,
                'created_at'     => $createdAt,
            ];
        });

    return response()->json([
        'draw'            => (int) $draw,
        'recordsTotal'    => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data'            => $data,
    ]);
})->name('wa-gateway.history-data');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


