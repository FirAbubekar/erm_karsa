<?php

use App\Http\Controllers\LoginController;
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
Route::match(['get', 'post'], '/general-consent/send-wa/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'sendWhatsappManual'])->name('general-consent.send-wa');
Route::get('/general-consent/wa-template/{no_surat}', [App\Http\Controllers\GeneralConsentController::class, 'getWaTemplate'])->name('general-consent.wa-template');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
