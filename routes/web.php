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

Route::middleware([\App\Http\Middleware\CheckLoginSession::class])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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

    Route::get('/edukasi-pasien', function () {
        return app(App\Http\Controllers\PatientEducationController::class)->index();
    })->name('edukasi-pasien');

    Route::post('/edukasi-pasien/save-assessment', [App\Http\Controllers\PatientEducationController::class, 'storeAssessment'])->name('edukasi-pasien.store-assessment');
    Route::get('/edukasi-pasien/get-assessment', [App\Http\Controllers\PatientEducationController::class, 'getAssessment'])->name('edukasi-pasien.get-assessment');
    Route::post('/edukasi-pasien/save-implementation', [App\Http\Controllers\PatientEducationController::class, 'storeImplementation'])->name('edukasi-pasien.store-implementation');
    Route::get('/riwayat-edukasi-pasien', [App\Http\Controllers\PatientEducationController::class, 'history'])->name('edukasi-pasien.history');
    Route::get('/edukasi-pasien/{id_uuid}/pdf', [App\Http\Controllers\PatientEducationController::class, 'downloadPDF'])->name('edukasi-pasien.download-pdf');

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
});
