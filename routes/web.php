<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HasilLabController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    if (Session::get('is_logged_in')) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login', function () {
    return redirect('/');
});

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

Route::get('/edukasi-pasien', function () {
    if (!Session::get('is_logged_in')) {
        return redirect('/');
    }
    return app(App\Http\Controllers\PatientEducationController::class)->index();
})->name('edukasi-pasien');

Route::post('/edukasi-pasien/save-assessment', [App\Http\Controllers\PatientEducationController::class, 'storeAssessment'])->name('edukasi-pasien.store-assessment');
Route::get('/edukasi-pasien/get-assessment', [App\Http\Controllers\PatientEducationController::class, 'getAssessment'])->name('edukasi-pasien.get-assessment');
Route::post('/edukasi-pasien/save-implementation', [App\Http\Controllers\PatientEducationController::class, 'storeImplementation'])->name('edukasi-pasien.store-implementation');
Route::get('/riwayat-edukasi-pasien', [App\Http\Controllers\PatientEducationController::class, 'history'])->name('edukasi-pasien.history');
Route::get('/edukasi-pasien/{id_uuid}/pdf', [App\Http\Controllers\PatientEducationController::class, 'downloadPDF'])->name('edukasi-pasien.download-pdf');

// Prospective Reviu (Farmasi)
Route::get('/prospective-reviu', function (\Illuminate\Http\Request $request) {
    if (!Session::get('is_logged_in')) {
        return redirect('/');
    }
    return app(App\Http\Controllers\ProspectiveReviewController::class)->index($request);
})->name('prospective-reviu');

Route::post('/prospective-reviu/save', [App\Http\Controllers\ProspectiveReviewController::class, 'store'])->name('prospective-reviu.store');
Route::get('/prospective-reviu/search-dokter', [App\Http\Controllers\ProspectiveReviewController::class, 'searchDokter'])->name('prospective-reviu.search-dokter');
Route::get('/prospective-reviu/search-pegawai', [App\Http\Controllers\ProspectiveReviewController::class, 'searchPegawai'])->name('prospective-reviu.search-pegawai');
Route::get('/prospective-reviu/history', [App\Http\Controllers\ProspectiveReviewController::class, 'history'])->name('prospective-reviu.history');
Route::get('/prospective-reviu/pdf/{id_uuid}', [App\Http\Controllers\ProspectiveReviewController::class, 'downloadPdf'])->name('prospective-reviu.pdf');
Route::get('/riwayat-prospective-reviu', [App\Http\Controllers\ProspectiveReviewController::class, 'historyPage'])->name('prospective-reviu.history-page');

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

use App\Http\Controllers\WebPermissionController;

Route::get('/web-permissions', [WebPermissionController::class, 'index'])->name('web-permissions.index');
Route::post('/web-permissions', [WebPermissionController::class, 'store'])->name('web-permissions.store');
Route::put('/web-permissions/{id}', [WebPermissionController::class, 'update'])->name('web-permissions.update');
Route::delete('/web-permissions/{id}', [WebPermissionController::class, 'destroy'])->name('web-permissions.destroy');

use App\Http\Controllers\WebUserRoleController;

Route::get('/web-user-roles', [WebUserRoleController::class, 'index'])->name('web-user-roles.index');
Route::post('/web-user-roles', [WebUserRoleController::class, 'store'])->name('web-user-roles.store');
Route::delete('/web-user-roles/{id}', [WebUserRoleController::class, 'destroy'])->name('web-user-roles.destroy');
Route::get('/search-users', [WebUserRoleController::class, 'searchUser'])->name('search-users');

use App\Http\Controllers\WebRolePermissionController;

Route::get('/web-role-permissions', [WebRolePermissionController::class, 'index'])->name('web-role-permissions.index');
Route::post('/web-role-permissions/sync', [WebRolePermissionController::class, 'sync'])->name('web-role-permissions.sync');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
