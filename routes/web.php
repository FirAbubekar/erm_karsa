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
Route::get('/general-consent/send-wa/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'sendWhatsappManual'])->name('general-consent.send-wa');
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

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
