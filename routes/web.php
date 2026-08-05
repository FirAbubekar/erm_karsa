<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HasilLabController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SuratPersetujuanRawatInapController;
use App\Http\Controllers\BankDarahController;

Route::get('/', function () {
    if (Session::get('is_logged_in')) {
        return redirect()->route('dashboard');
    }
    if (Session::get('is_logged_in')) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login', function () {
    return redirect('/');
});

Route::middleware([\App\Http\Middleware\CheckLoginSession::class])->group(function () {
Route::get('/login', function () {
    return redirect('/');
});

Route::middleware([\App\Http\Middleware\CheckLoginSession::class])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/general-consent', function () {
        return view('general_consent');
    })->name('general-consent');
    Route::get('/general-consent', function () {
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
    Route::get('/general-consent/check-wa', [GeneralConsentController::class, 'checkWa'])->name('general-consent.check-wa');

    Route::get('/edukasi-pasien', function () {
        return app(App\Http\Controllers\PatientEducationController::class)->index();
    })->name('edukasi-pasien');
    Route::get('/edukasi-pasien', function () {
        return app(App\Http\Controllers\PatientEducationController::class)->index();
    })->name('edukasi-pasien');

    Route::post('/edukasi-pasien/save-assessment', [App\Http\Controllers\PatientEducationController::class, 'storeAssessment'])->name('edukasi-pasien.store-assessment');
    Route::get('/edukasi-pasien/get-assessment', [App\Http\Controllers\PatientEducationController::class, 'getAssessment'])->name('edukasi-pasien.get-assessment');
    Route::post('/edukasi-pasien/save-implementation', [App\Http\Controllers\PatientEducationController::class, 'storeImplementation'])->name('edukasi-pasien.store-implementation');
    Route::get('/riwayat-edukasi-pasien', [App\Http\Controllers\PatientEducationController::class, 'history'])->name('edukasi-pasien.history');
    Route::get('/edukasi-pasien/{id_uuid}/pdf', [App\Http\Controllers\PatientEducationController::class, 'downloadPDF'])->name('edukasi-pasien.download-pdf');
    Route::get('/edukasi-pasien/signature/{filename}', [App\Http\Controllers\PatientEducationController::class, 'showSignature'])->name('edukasi-pasien.signature')->where('filename', '.*');

    // Prospective Reviu (Farmasi)
    Route::get('/prospective-reviu', function (\Illuminate\Http\Request $request) {
        return app(App\Http\Controllers\ProspectiveReviewController::class)->index($request);
    })->name('prospective-reviu');

    Route::post('/prospective-reviu/save', [App\Http\Controllers\ProspectiveReviewController::class, 'store'])->name('prospective-reviu.store');
    Route::get('/prospective-reviu/search-dokter', [App\Http\Controllers\ProspectiveReviewController::class, 'searchDokter'])->name('prospective-reviu.search-dokter');
    Route::get('/prospective-reviu/search-pegawai', [App\Http\Controllers\ProspectiveReviewController::class, 'searchPegawai'])->name('prospective-reviu.search-pegawai');
    Route::get('/prospective-reviu/history', [App\Http\Controllers\ProspectiveReviewController::class, 'history'])->name('prospective-reviu.history');
    Route::get('/prospective-reviu/pdf/{id_uuid}', [App\Http\Controllers\ProspectiveReviewController::class, 'downloadPdf'])->name('prospective-reviu.pdf');
    Route::get('/riwayat-prospective-reviu', [App\Http\Controllers\ProspectiveReviewController::class, 'historyPage'])->name('prospective-reviu.history-page');
    Route::get('/prospective-reviu/export-excel', [App\Http\Controllers\ProspectiveReviewController::class, 'exportExcel'])->name('prospective-reviu.export-excel');

    // Hasil Lab
    Route::get('/hasil-lab', [App\Http\Controllers\HasilLabController::class, 'index'])->name('hasil-lab.index');
    Route::get('/hasil-lab/detail/{ono}', [App\Http\Controllers\HasilLabController::class, 'detail'])->name('hasil-lab.detail');
    Route::get('/hasil-lab/json/{ono}', [App\Http\Controllers\HasilLabController::class, 'getDetailJson'])->name('hasil-lab.json');
    Route::get('/hasil-lab/pdf/{ono}', [App\Http\Controllers\HasilLabController::class, 'downloadPDF'])->name('hasil-lab.pdf');
    Route::post('/hasil-lab/send-wa', [App\Http\Controllers\HasilLabController::class, 'sendWhatsApp'])->name('hasil-lab.send-wa');

    Route::get('/surat-persetujuan-rawat-inap', [App\Http\Controllers\SuratPersetujuanRawatInapController::class, 'index'])->name('surat-persetujuan-rawat-inap.index');
    Route::post('/surat-persetujuan-rawat-inap/store', [App\Http\Controllers\SuratPersetujuanRawatInapController::class, 'store'])->name('surat-persetujuan-rawat-inap.store');
    Route::get('/surat-persetujuan-rawat-inap/history', [App\Http\Controllers\SuratPersetujuanRawatInapController::class, 'history'])->name('surat-persetujuan-rawat-inap.history');
    Route::get('/surat-persetujuan-rawat-inap/download/{no_surat}', [App\Http\Controllers\SuratPersetujuanRawatInapController::class, 'downloadPDF'])->name('surat-persetujuan-rawat-inap.download');
    Route::get('/surat-persetujuan-rawat-inap/wa-template/{no_surat}', [App\Http\Controllers\SuratPersetujuanRawatInapController::class, 'getWaTemplate'])->name('surat-persetujuan-rawat-inap.wa-template');
    Route::post('/surat-persetujuan-rawat-inap/send-wa', [App\Http\Controllers\SuratPersetujuanRawatInapController::class, 'sendWhatsappManual'])->name('surat-persetujuan-rawat-inap.send-wa');

    Route::get('/web-permissions', [App\Http\Controllers\WebPermissionController::class, 'index'])->name('web-permissions.index');
    Route::post('/web-permissions', [App\Http\Controllers\WebPermissionController::class, 'store'])->name('web-permissions.store');
    Route::put('/web-permissions/{id}', [App\Http\Controllers\WebPermissionController::class, 'update'])->name('web-permissions.update');
    Route::delete('/web-permissions/{id}', [App\Http\Controllers\WebPermissionController::class, 'destroy'])->name('web-permissions.destroy');

    Route::get('/web-user-roles', [App\Http\Controllers\WebUserRoleController::class, 'index'])->name('web-user-roles.index');
    Route::post('/web-user-roles', [App\Http\Controllers\WebUserRoleController::class, 'store'])->name('web-user-roles.store');
    Route::delete('/web-user-roles/{id}', [App\Http\Controllers\WebUserRoleController::class, 'destroy'])->name('web-user-roles.destroy');
    Route::get('/search-users', [App\Http\Controllers\WebUserRoleController::class, 'searchUser'])->name('search-users');

    Route::get('/web-role-permissions', [App\Http\Controllers\WebRolePermissionController::class, 'index'])->name('web-role-permissions.index');
    Route::post('/web-role-permissions/sync', [App\Http\Controllers\WebRolePermissionController::class, 'sync'])->name('web-role-permissions.sync');

    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

        // Transfer Pasien Antar Ruangan
    Route::get('/transfer-pasien', [TransferPasienController::class, 'index'])->name('transfer-pasien.index');
    Route::get('/transfer-pasien/riwayat', [TransferPasienController::class, 'riwayat'])->name('transfer-pasien.riwayat');
    Route::post('/transfer-pasien/store', [TransferPasienController::class, 'store'])->name('transfer-pasien.store');
    Route::post('/transfer-pasien/update', [TransferPasienController::class, 'update'])->name('transfer-pasien.update');


    Route::get('/web-permissions', [App\Http\Controllers\WebPermissionController::class, 'index'])->name('web-permissions.index');
    Route::post('/web-permissions', [App\Http\Controllers\WebPermissionController::class, 'store'])->name('web-permissions.store');
    Route::put('/web-permissions/{id}', [App\Http\Controllers\WebPermissionController::class, 'update'])->name('web-permissions.update');
    Route::delete('/web-permissions/{id}', [App\Http\Controllers\WebPermissionController::class, 'destroy'])->name('web-permissions.destroy');

    Route::get('/web-user-roles', [App\Http\Controllers\WebUserRoleController::class, 'index'])->name('web-user-roles.index');
    Route::post('/web-user-roles', [App\Http\Controllers\WebUserRoleController::class, 'store'])->name('web-user-roles.store');
    Route::delete('/web-user-roles/{id}', [App\Http\Controllers\WebUserRoleController::class, 'destroy'])->name('web-user-roles.destroy');
    Route::get('/search-users', [App\Http\Controllers\WebUserRoleController::class, 'searchUser'])->name('search-users');

    Route::get('/web-role-permissions', [App\Http\Controllers\WebRolePermissionController::class, 'index'])->name('web-role-permissions.index');
    Route::post('/web-role-permissions/sync', [App\Http\Controllers\WebRolePermissionController::class, 'sync'])->name('web-role-permissions.sync');

    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

    // Transfer Pasien Antar Ruangan
    Route::get('/transfer-pasien', [App\Http\Controllers\TransferPasienController::class, 'index'])->name('transfer-pasien.index');
    Route::post('/transfer-pasien/store', [App\Http\Controllers\TransferPasienController::class, 'store'])->name('transfer-pasien.store');

    // WA Gateway Check
    Route::post('/wa-gateway/test-send', function () {
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
        $today = date('Y-m-d');
        $stats = [
            'total'   => \Illuminate\Support\Facades\DB::table('t_antrean_wa')->whereDate('created_at', $today)->count(),
            'pending' => \Illuminate\Support\Facades\DB::table('t_antrean_wa')->whereDate('created_at', $today)->where('status', 'pending')->count(),
            'sent'    => \Illuminate\Support\Facades\DB::table('t_antrean_wa')->whereDate('created_at', $today)->where('status', 'sent')->count(),
            'failed'  => \Illuminate\Support\Facades\DB::table('t_antrean_wa')->whereDate('created_at', $today)->where('status', 'failed')->count(),
            'processing' => \Illuminate\Support\Facades\DB::table('t_antrean_wa')->whereDate('created_at', $today)->where('status', 'processing')->count(),
        ];

        return view('wa_gateway_history', compact('stats', 'today'));
    })->name('wa-gateway.history');

    Route::post('/wa-gateway/history-data', function () {
        $draw     = request('draw', 1);
        $start    = request('start', 0);
        $length   = request('length', 20);
        $search   = request('search.value', '');
        $orderCol = request('order.0.column', 0);
        $orderDir = request('order.0.dir', 'desc');

        $dateFrom = request('date_from', date('Y-m-d'));
        $dateTo   = request('date_to', date('Y-m-d'));
        $status   = request('status', '');

        $columns = ['id', 'no_surat', 'no_telp', 'pesan', 'status', 'error_message', 'sent_at', 'created_at'];
        $sortCol = $columns[$orderCol] ?? 'id';

        $query = \Illuminate\Support\Facades\DB::table('t_antrean_wa')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($status && in_array($status, ['pending', 'processing', 'sent', 'failed'], true)) {
            $query->where('status', $status);
        }

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

        $fmtPhone = function ($n) {
            $n = preg_replace('/\D/', '', (string) $n);
            if (strlen($n) <= 9) {
                return $n;
            }
            if (str_starts_with($n, '0')) {
                $n = '62' . substr($n, 1);
            }
            if (str_starts_with($n, '62') && strlen($n) > 11) {
                $digits = substr($n, 2);
                $groups = [substr($digits, 0, 3), substr($digits, 3, 3)];
                $rest   = substr($digits, 6);
                if ($rest) {
                    $groups[] = str_split($rest, 4)[0];
                }
                return '+62 ' . implode('-', array_filter($groups));
            }
            return $n;
        };

        $fmtDate = function ($ts) {
            return $ts ? \Carbon\Carbon::parse($ts)->format('d/m/Y') : '';
        };
        $fmtTime = function ($ts) {
            return $ts ? \Carbon\Carbon::parse($ts)->format('H:i') : '';
        };
        $relTime = function ($ts) {
            return $ts ? \Carbon\Carbon::parse($ts)->diffForHumans() : '';
        };

        $data = $query->orderBy($sortCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($row) use ($fmtPhone, $fmtDate, $fmtTime, $relTime) {
                $statusBadge = '<span class="status-badge ' . $row->status . '"><span class="status-dot ' . $row->status . '"></span> ' . ucfirst($row->status) . '</span>';

                $pesanFull = $row->pesan ?? '';
                $hasFile   = !empty($row->file_path);

                $msgHtml  = '<div class="msg-cell">';
                $msgHtml .= '<div class="msg-preview">' . (strlen($pesanFull) ? e(\Illuminate\Support\Str::limit($pesanFull, 110)) : '<span class="text-subtle">-</span>') . '</div>';
                $msgHtml .= '<div class="msg-actions">';
                if ($hasFile) {
                    $msgHtml .= '<span class="file-tag"><i class="fas fa-paperclip"></i> Lampiran</span>';
                }
                $msgHtml .= '<button type="button" class="btn-view" data-id="' . $row->id . '"><i class="fas fa-comment-dots"></i> Lihat Isi</button>';
                $msgHtml .= '</div></div>';

                $errorFull = $row->error_message ?? '';
                $errorHtml = $errorFull
                    ? '<div class="error-cell"><div class="error-msg" title="' . e($errorFull) . '">' . e(\Illuminate\Support\Str::limit($errorFull, 60)) . '</div><button type="button" class="btn-view btn-view-error" data-id="' . $row->id . '" data-error="1"><i class="fas fa-bug"></i> Detail Error</button></div>'
                    : '<span class="text-subtle">-</span>';

                $sentAt  = $row->sent_at;
                $created = $row->created_at;

                $sentHtml = $sentAt
                    ? '<div class="time-cell"><span class="date">' . $fmtDate($sentAt) . '</span><span class="time">' . $fmtTime($sentAt) . '</span><span class="relative">' . $relTime($sentAt) . '</span></div>'
                    : '<span class="text-subtle">-</span>';

                $createdHtml = '<div class="time-cell"><span class="date">' . $fmtDate($created) . '</span><span class="time">' . $fmtTime($created) . '</span><span class="relative">' . $relTime($created) . '</span></div>';

                $fileName = $hasFile ? basename($row->file_path) : null;

                return [
                    'id'             => '<span class="text-mono">#' . $row->id . '</span>',
                    'no_surat'       => '<div class="doc-cell"><i class="fas fa-file-alt"></i><span class="text-mono">' . e($row->no_surat ?? '-') . '</span></div>',
                    'no_telp'        => '<div class="telp-cell"><i class="fab fa-whatsapp"></i><span class="text-mono">' . e($fmtPhone($row->no_telp)) . '</span></div>',
                    'pesan'          => $msgHtml,
                    'status'         => $statusBadge,
                    'error_message'  => $errorHtml,
                    'sent_at'        => $sentHtml,
                    'created_at'     => $createdHtml,

                    'row_id'         => $row->id,
                    'pesan_full'     => $pesanFull,
                    'no_surat_raw'   => $row->no_surat,
                    'no_telp_raw'    => $row->no_telp,
                    'no_telp_disp'   => $fmtPhone($row->no_telp),
                    'file_path'      => $fileName ? e($row->file_path) : null,
                    'file_name'      => $fileName ? e($fileName) : null,
                    'status_raw'     => $row->status,
                    'error_full'     => $errorFull,
                    'sent_at_raw'    => $sentAt ? \Carbon\Carbon::parse($sentAt)->format('d/m/Y H:i') : null,
                    'created_at_raw' => \Carbon\Carbon::parse($created)->format('d/m/Y H:i'),
                ];
            });

        return response()->json([
            'draw'            => (int) $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    })->name('wa-gateway.history-data');
    // Bank Darah


Route::get('/bank-darah/rekomendasi', [BankDarahController::class, 'rekomendasi'])->name('bank-darah.rekomendasi');
Route::post('/bank-darah/store', [BankDarahController::class, 'store'])->name('bank-darah.store');
Route::get('/bank-darah/validasi', [BankDarahController::class, 'validasi'])->name('bank-darah.validasi');
Route::get('/bank-darah/validasi/pasien', [BankDarahController::class, 'getPasienByRuangan'])->name('bank-darah.validasi.pasien');
Route::post('/bank-darah/validasi/transfusi', [BankDarahController::class, 'validasiTransfusi'])->name('bank-darah.validasi.transfusi');
Route::post('/bank-darah/validasi/penerimaan', [BankDarahController::class, 'validasiPenerimaan'])->name('bank-darah.validasi.penerimaan');
Route::get('/bank-darah/reaksi-transfusi', [BankDarahController::class, 'reaksiTransfusi'])->name('bank-darah.reaksi');
Route::post('/bank-darah/reaksi-transfusi/store', [BankDarahController::class, 'storeReaksiTransfusi'])->name('bank-darah.reaksi.store');
Route::get('/bank-darah/reaksi-transfusi/history', [BankDarahController::class, 'historyReaksiTransfusi'])->name('bank-darah.reaksi.history');
Route::get('/bank-darah/reaksi-transfusi/download/{id}', [BankDarahController::class, 'downloadReaksiTransfusiPDF'])->name('bank-darah.reaksi.download');
Route::get('/bank-darah/cari-pasien', [BankDarahController::class, 'cariPasien'])->name('bank-darah.cari-pasien');
Route::get('/bank-darah/cari-barcode', [BankDarahController::class, 'cariBarcode'])->name('bank-darah.cari-barcode');
Route::get('/bank-darah/history', [BankDarahController::class, 'history'])->name('bank-darah.history');
Route::get('/bank-darah/download/{id}', [BankDarahController::class, 'downloadPDF'])->name('bank-darah.download');
Route::get('/bank-darah/validasi/history', [BankDarahController::class, 'historyValidasi'])->name('bank-darah.validasi.history');
});

