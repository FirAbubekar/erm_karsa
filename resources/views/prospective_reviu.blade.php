<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Prospective Reviu</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('prospective_reviu.styles')
</head>

<body>
    @include('partials.sidebar')

    <div class="main-content">
        <!-- Hero Header -->
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <span>Farmasi</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <span class="current">Prospective Reviu</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>🧪 Formulir Prospective Reviu Penggunaan Antibiotik</h1>
                    <p>Lengkapi data reviu dengan memilih nomor rawat pasien di bawah ini.</p>
                </div>
                <div class="user-chip">
                    <div class="avatar">{{ strtoupper(substr(Session::get('user_id', '?'), 0, 1)) }}</div>
                    <span>{{ Session::get('user_id') }}</span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="layout-grid animate-in">

                <!-- ═══ Left Column: Search ═══ -->
                <div>
                    <div class="glass-card search-card" id="card-search">
                        <div class="section-title farmasi-accent">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari Data Pasien
                        </div>
                        <div class="search-container">
                            <input type="text" id="search-no-rm" class="form-control"
                                placeholder="Masukkan No. Rekam Medis..." style="width:100%">
                            <button class="btn btn-farmasi" id="btn-search" style="white-space:nowrap">Cari</button>
                        </div>
                        <div class="table-scroll-wrapper">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No. Rawat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="history-table-body">
                                    <tr>
                                        <td colspan="4"
                                            style="text-align:center;padding:20px;color:var(--text-muted);font-size:12px">
                                            Masukkan No. RM untuk melihat riwayat...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══ Right Column: Identitas Pasien ═══ -->
                <div>
                    <div class="glass-card" id="card-data-pasien">
                        <div class="section-title farmasi-accent">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Identitas Pasien
                        </div>
                        <div class="form-grid" style="grid-template-columns: 1fr 2fr 1fr">
                            <div class="form-group"><label>No. RM</label><input type="text" id="field-no-rm"
                                    class="form-control" placeholder="No. RM" readonly></div>
                            <div class="form-group"><label>Nama Pasien</label><input type="text" id="field-nm-pasien"
                                    class="form-control" placeholder="Nama Pasien" readonly></div>
                            <div class="form-group"><label>Jenis Kelamin</label><input type="text" id="field-jk"
                                    class="form-control" placeholder="L/P" readonly></div>
                        </div>
                        <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr">
                            <div class="form-group"><label>No. Rawat</label><input type="text" id="field-no-rawat"
                                    class="form-control" placeholder="Pilih dari riwayat" readonly></div>
                            <div class="form-group"><label>Tgl Registrasi</label><input type="text"
                                    id="field-tgl-registrasi" class="form-control" placeholder="Auto" readonly></div>
                            <div class="form-group"><label>Tanggal Lahir</label><input type="text"
                                    id="field-tgl-lahir" class="form-control" placeholder="Tgl Lahir" readonly></div>
                        </div>
                        <div class="form-grid" style="grid-template-columns: 1fr 1fr">
                            <div class="form-group"><label>Tanggal Reviu</label><input type="date"
                                    id="field-tgl-reviu" class="form-control" value="{{ date('Y-m-d') }}"></div>
                            <div class="form-group"><label>Diagnosis</label><input type="text"
                                    id="field-diagnosis" class="form-control" placeholder="Masukkan diagnosis"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══════════ Full-Width Sections ══════════ -->

            <!-- ── Regimen Saat Ini ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                        </path>
                    </svg>
                    Regimen Saat Ini
                </div>
                <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start">
                    <div class="form-group">
                        <label>Antibiotik *</label>
                        <div class="checkbox-group">
                            <label><input type="radio" name="antibiotik_jenis" value="Empiris"> Empiris</label>
                            <label><input type="radio" name="antibiotik_jenis" value="Definitif"> Definitif</label>
                            <label><input type="radio" name="antibiotik_jenis" value="Profilaksis">
                                Profilaksis</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Hari ke-</label>
                        <input type="number" id="field-hari-ke" class="form-control" placeholder="Hari ke-"
                            min="1">
                    </div>
                </div>
            </div>

            <!-- ── Parameter Klinis ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Parameter Klinis
                </div>

                <div class="param-grid" style="margin-bottom:20px">
                    <div class="form-group"><label>TD</label><input type="text" id="field-td"
                            class="form-control" placeholder="mmHg"></div>
                    <div class="form-group"><label>Suhu</label><input type="text" id="field-suhu"
                            class="form-control" placeholder="°C"></div>
                    <div class="form-group"><label>RR</label><input type="text" id="field-rr"
                            class="form-control" placeholder="x/mnt"></div>
                    <div class="form-group"><label>SpO2</label><input type="text" id="field-spo2"
                            class="form-control" placeholder="%"></div>
                    <div class="form-group"><label>GCS</label><input type="text" id="field-gcs"
                            class="form-control" placeholder="3-15"></div>
                </div>

                <div style="display:grid;grid-template-columns:1fr;gap:12px 24px;align-items:start;margin-bottom:20px">
                    <div class="form-group" style="margin-bottom:0">
                        <label>Demam</label>
                        <div class="radio-group">
                            <label><input type="radio" name="demam" value="1"> Ya</label>
                            <label><input type="radio" name="demam" value="0"> Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="param-grid-2col">
                    <div class="form-group">
                        <label>Leukosit</label>
                        <div style="display:flex;gap:12px;align-items:center">
                            <input type="text" id="field-leukosit" class="form-control" placeholder="Leukosit">
                            <span
                                style="font-size:12px;color:var(--text-muted);white-space:nowrap;font-weight:600">/μL</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>% Neutrofil</label>
                        <div style="display:flex;gap:12px;align-items:center">
                            <input type="text" id="field-neutrofil-persen" class="form-control"
                                placeholder="Neutrofil">
                            <span
                                style="font-size:12px;color:var(--text-muted);white-space:nowrap;font-weight:600">%</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kreatinin</label>
                        <input type="text" id="field-kreatinin" class="form-control" placeholder="Kreatinin">
                    </div>
                    <div class="form-group">
                        <label>Ureum</label>
                        <input type="text" id="field-ureum" class="form-control" placeholder="Ureum">
                    </div>
                </div>
            </div>

            <!-- ── Kultur ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Kultur
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
                    <div class="form-group">
                        <label>Hasil Kultur *</label>
                        <div class="radio-group" style="flex-direction:column;gap:10px">
                            <label><input type="radio" name="hasil_kultur" value="Positif"> Positif: <input
                                    type="text" class="form-control" id="field-kultur-hasil-positif"
                                    style="display:inline-block;width:200px;padding:6px 10px;font-size:12px;margin-left:8px"
                                    placeholder="Sebutkan..."></label>
                            <label><input type="radio" name="hasil_kultur" value="Negatif"> Negatif</label>
                            <label><input type="radio" name="hasil_kultur" value="Menunggu Hasil"> Menunggu
                                Hasil</label>
                            <label><input type="radio" name="hasil_kultur" value="Belum"> Belum</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Rekomendasi Antibiotik</label>
                        <textarea id="field-rekomendasi-antibiotik" class="form-control" placeholder="Masukkan rekomendasi antibiotik..."
                            rows="4"></textarea>
                    </div>
                </div>
            </div>

            <!-- ── Penilaian Appropriateness ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Penilaian Appropriateness
                </div>
                <div style="overflow-x:auto">
                    <table class="appropriateness-table">
                        <thead>
                            <tr>
                                <th style="min-width:180px;text-align:left">Aspek Penilaian</th>
                                <th style="min-width:200px">Hasil Penilaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Indikasi</td>
                                <td>
                                    <div class="radio-group">
                                        <label><input type="radio" name="indikasi" value="1"> Tepat</label>
                                        <label><input type="radio" name="indikasi" value="0"> Tidak
                                            tepat</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Jenis antibiotik</td>
                                <td>
                                    <div class="radio-group">
                                        <label><input type="radio" name="jenis_antibiotik" value="1">
                                            Tepat</label>
                                        <label><input type="radio" name="jenis_antibiotik" value="0">
                                            Tidak tepat</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Dosis</td>
                                <td>
                                    <div class="radio-group">
                                        <label><input type="radio" name="dosis_appropriateness" value="1">
                                            Tepat</label>
                                        <label><input type="radio" name="dosis_appropriateness" value="0">
                                            Tidak Tepat</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Durasi</td>
                                <td>
                                    <div class="radio-group">
                                        <label><input type="radio" name="durasi" value="1"> Sesuai</label>
                                        <label><input type="radio" name="durasi" value="0"> Tidak
                                            Sesuai</label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── Rekomendasi Tim PGA ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    Rekomendasi Tim PGA
                </div>
                <div class="form-group">
                    <div class="checkbox-group" style="gap:16px">
                        <label><input type="checkbox" name="rekomendasi_pga[]" value="Continue"> Continue</label>
                        <label><input type="checkbox" name="rekomendasi_pga[]" value="Escalation"> Escalation</label>
                        <label><input type="checkbox" name="rekomendasi_pga[]" value="De-escalation">
                            De-escalation</label>
                        <label><input type="checkbox" name="rekomendasi_pga[]" value="Switch IV to PO"> Switch IV to
                            PO</label>
                        <label><input type="checkbox" name="rekomendasi_pga[]" value="Stop"> Stop</label>
                        <label><input type="checkbox" name="rekomendasi_pga[]" value="Dose adjustment"> Dose
                            adjustment</label>
                        <label><input type="checkbox" name="rekomendasi_pga[]" value="Lainnya"> Lainnya</label>
                    </div>
                    <input type="text" id="field-pga-lainnya" class="form-control"
                        placeholder="Sebutkan rekomendasi lainnya..."
                        style="display:none;margin-top:12px;font-size:12px">
                </div>
            </div>

            <!-- ── Respon DPJP & Catatan ── -->
            <div class="glass-card">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px">
                    <div>
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            Respon DPJP
                        </div>
                        <div class="form-group">
                            <div class="radio-group" style="flex-direction:column;gap:12px">
                                <label><input type="radio" name="respon_dpjp" value="Menerima"> Menerima
                                    Rekomendasi Tim PGA</label>
                                <label><input type="radio" name="respon_dpjp" value="Menolak"> Menolak Rekomendasi
                                    Tim PGA</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Catatan
                        </div>
                        <div class="form-group">
                            <textarea id="field-catatan" class="form-control" rows="4" placeholder="Tulis catatan tambahan..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Tanda Tangan ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                    Tanda Tangan
                </div>
                <div class="ttd-grid">
                    <div class="ttd-item">
                        <label>Apoteker Klinis</label>
                        <div class="form-control"
                            style="background:#f8fafc;padding:12px;text-align:center;font-weight:600;border:1px dashed var(--border-color)">
                            {{ Session::get('user_id', 'Apoteker') }}
                        </div>
                    </div>
                    <div class="ttd-item">
                        <label>Perawat</label>
                        <select id="ttd-perawat-input" class="form-control select2" style="width:100%"></select>
                    </div>
                    <div class="ttd-item">
                        <label>DPJP</label>
                        <select id="ttd-dpjp-input" class="form-control select2" style="width:100%"></select>
                    </div>
                    <div class="ttd-item">
                        <label>KPRA</label>
                        <div class="form-control"
                            style="background:#f8fafc;padding:12px;text-align:center;font-weight:600;border:1px dashed var(--border-color)">
                            DRRESTI
                        </div>
                    </div>
                </div>

                <div style="margin-top:28px;display:flex;justify-content:flex-end">
                    <button class="btn btn-farmasi" id="btn-save-reviu" style="padding:14px 36px;font-size:14px">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Prospective Reviu
                    </button>
                </div>
            </div>

            <div class="glass-card animate-in" id="card-history" style="display:none; margin-top:24px">
                <div class="section-title farmasi-accent">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat Prospective Reviu
                </div>
                <div style="overflow-x:auto;">
                    <table class="table" id="table-history"
                        style="width:100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                                <th style="padding: 12px;">Tanggal</th>
                                <th style="padding: 12px;">Hari Ke</th>
                                <th style="padding: 12px;">Tipe Antibiotik</th>
                                <th style="padding: 12px;">Apoteker</th>
                                <th style="padding: 12px;">DPJP</th>
                                <th style="padding: 12px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="history-tbody">
                            <!-- rows injected via js -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div> {{-- /content-area --}}
    </div> {{-- /main-content --}}

    @include('prospective_reviu.modals')
    @include('prospective_reviu.scripts')
</body>

</html>
