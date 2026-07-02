<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | RM10 Edukasi Pasien</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    @include('edukasi_pasien.styles')
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
                <span class="current">RM10 Edukasi Pasien</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>📋 Formulir Pengkajian Kebutuhan Informasi, Edukasi, Privasi Pasien</h1>
                    <p>Lengkapi data pengkajian dengan memilih nomor rawat pasien di bawah ini.</p>
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

                <!-- ═══ Left Column: Search & History ═══ -->
                <div>
                    <div class="glass-card search-card" id="card-search">
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari Data Pasien
                        </div>
                        <div class="search-container">
                            <input type="text" id="search-no-rm" class="form-control"
                                placeholder="Masukkan No. Rekam Medis..." style="width:100%">
                            <button class="btn btn-primary" id="btn-search" style="white-space:nowrap">Cari</button>
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

                <!-- ═══ Right Column: Data Pasien ═══ -->
                <div>

                    <!-- ── Data Pasien ── -->
                    <div class="glass-card" id="card-data-pasien">
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Pasien
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
                        <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr">
                            <div class="form-group"><label>Nama Penerima Info *</label><input type="text"
                                    id="field-nama-penerima" class="form-control"
                                    placeholder="Masukkan nama penerima"></div>
                            <div class="form-group">
                                <label>Hubungan dgn Pasien</label>
                                <select id="field-hubungan-pasien" class="form-control">
                                    <option value="" disabled selected>Pilih hubungan...</option>
                                    <option value="Diri Sendiri">Diri Sendiri</option>
                                    <option value="Suami">Suami</option>
                                    <option value="Istri">Istri</option>
                                    <option value="Ayah">Ayah</option>
                                    <option value="Ibu">Ibu</option>
                                    <option value="Anak">Anak</option>
                                    <option value="Saudara">Saudara</option>
                                    <option value="Keluarga Lainnya">Keluarga Lainnya</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Tanggal</label><input type="date" id="field-tanggal"
                                    class="form-control" value="{{ date('Y-m-d') }}"></div>
                        </div>
                    </div>
                </div> {{-- /right-column --}}
            </div> {{-- /layout-grid --}}

            <!-- ══════════ Full-Width Sections ══════════ -->

            <!-- ── A. Asesmen Kebutuhan Pendidikan ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    A. Asesmen Kebutuhan Pendidikan
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
                    <!-- Left -->
                    <div>
                        <div class="form-group">
                            <label>Bahasa *</label>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="bahasa[]" value="Indonesia">
                                    Indonesia</label>
                                <label><input type="checkbox" name="bahasa[]" value="Inggris">
                                    Inggris</label>
                                <label><input type="checkbox" name="bahasa[]" value="Daerah"> Daerah</label>
                                <label><input type="checkbox" name="bahasa[]" value="Lain-lain"
                                        data-toggle-other="bahasa-lainnya"> Lain-lain</label>
                            </div>
                            <input type="text" id="bahasa-lainnya" class="form-control"
                                placeholder="Sebutkan bahasa lainnya..."
                                style="display:none;margin-top:8px;font-size:12px">
                        </div>
                        <div class="form-group">
                            <label>Perlu Penerjemah *</label>
                            <div class="radio-group">
                                <label><input type="radio" name="perlu_penerjemah" value="1">
                                    Ya</label>
                                <label><input type="radio" name="perlu_penerjemah" value="0">
                                    Tidak</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Baca dan Tulis *</label>
                            <div class="radio-group">
                                <label><input type="radio" name="baca_dan_tulis" value="Baik">
                                    Baik</label>
                                <label><input type="radio" name="baca_dan_tulis" value="Kurang">
                                    Kurang</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nilai Budaya *</label>
                            <div class="radio-group">
                                <label><input type="radio" name="nilai_budaya" value="Modern">
                                    Modern</label>
                                <label><input type="radio" name="nilai_budaya" value="Moderat">
                                    Moderat</label>
                                <label><input type="radio" name="nilai_budaya" value="Tradisional">
                                    Tradisional</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Literasi Kesehatan *</label>
                            <div class="radio-group">
                                <label><input type="radio" name="literasi_kesehatan" value="Baik">
                                    Baik</label>
                                <label><input type="radio" name="literasi_kesehatan" value="Cukup">
                                    Cukup</label>
                                <label><input type="radio" name="literasi_kesehatan" value="Kurang">
                                    Kurang</label>
                            </div>
                        </div>
                    </div>
                    <!-- Right -->
                    <div>
                        <div class="form-group">
                            <label>Pendidikan *</label>
                            <div class="radio-group">
                                <label><input type="radio" name="pendidikan" value="SD"> SD</label>
                                <label><input type="radio" name="pendidikan" value="SLTP"> SLTP</label>
                                <label><input type="radio" name="pendidikan" value="SLTA"> SLTA</label>
                                <label><input type="radio" name="pendidikan" value="PT"> PT</label>
                                <label><input type="radio" name="pendidikan" value="Lain-lain"
                                        data-toggle-other-radio="pendidikan-lainnya"> Lain-lain</label>
                            </div>
                            <input type="text" id="pendidikan-lainnya" class="form-control"
                                placeholder="Sebutkan..." style="display:none;margin-top:8px;font-size:12px">
                        </div>
                        <div class="form-group">
                            <label>Gaya Pembelajaran *</label>
                            <div class="radio-group">
                                <label><input type="radio" name="gaya_pembelajaran" value="Visual">
                                    Visual</label>
                                <label><input type="radio" name="gaya_pembelajaran" value="Audio">
                                    Audio</label>
                                <label><input type="radio" name="gaya_pembelajaran" value="Kinestetik">
                                    Kinestetik</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hambatan Edukasi (full width) -->
                <div class="form-group" style="margin-top:8px">
                    <label>Hambatan Edukasi *</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Tidak Ada"> Tidak
                            Ada</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Penglihatan">
                            Penglihatan</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Bahasa">
                            Bahasa</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Kognitif Terbatas">
                            Kognitif Terbatas</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Emosional Terganggu">
                            Emosional Terganggu</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Budaya/Agama">
                            Budaya/Agama</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Motivasi Kurang">
                            Motivasi Kurang</label>

                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Pendengaran Terganggu">
                            Pendengaran Terganggu</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Gangguan Bicara">
                            Gangguan Bicara</label>

                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Fisik Lemah"> Fisik
                            Lemah</label>
                        <label><input type="checkbox" name="hambatan_edukasi[]" value="Lain-lain"
                                data-toggle-other="hambatan-lainnya"> Lain-lain</label>
                    </div>
                    <input type="text" id="hambatan-lainnya" class="form-control"
                        placeholder="Sebutkan hambatan lainnya..." style="display:none;margin-top:8px;font-size:12px">
                </div>

                <div class="form-group">
                    <label>Kesediaan Menerima *</label>
                    <div class="radio-group">
                        <label><input type="radio" name="kesediaan_menerima" value="1"> Ya</label>
                        <label><input type="radio" name="kesediaan_menerima" value="0"> Tidak</label>
                    </div>
                </div>
            </div>

            <!-- ── B. Rencana Kebutuhan Edukasi ── -->
            <div class="glass-card">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    B. Rencana Kebutuhan Edukasi
                </div>

                <!-- Checkboxes: 3 columns full-width -->
                <div class="rencana-grid" style="grid-template-columns: 1fr 1fr 1fr">
                    @foreach ($rencanaKebutuhan as $item)
                        <label><input type="checkbox" name="rencana_kebutuhan[]" value="{{ $item['kode'] }}">
                            {{ $item['deskripsi'] }}</label>
                    @endforeach
                    <label><input type="checkbox" name="rencana_kebutuhan[]" value="B_LAINNYA"
                            data-toggle-other="rencana-lainnya"> Lain-lain</label>
                </div>
                <input type="text" id="rencana-lainnya" class="form-control"
                    placeholder="Sebutkan rencana lainnya..." style="display:none;margin-top:8px;font-size:12px">

                <div class="section-divider"></div>

                <!-- Info boxes: 4 columns -->
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px">
                    <div class="info-box">
                        <div class="info-box-title">🩺 Tujuan Edukasi</div>
                        <ol>
                            @foreach ($tujuanEdukasi as $t)
                                <li>{{ $t }}</li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="info-box">
                        <div class="info-box-title">🎯 Metode Edukasi</div>
                        <ol>
                            @foreach ($metodeEdukasi as $me)
                                <li>{{ $me }}</li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="info-box">
                        <div class="info-box-title">📝 Materi Edukasi</div>
                        <ol>
                            @foreach ($materiEdukasi as $m)
                                <li>{{ $m }}</li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="info-box">
                        <div class="info-box-title">🔁 Evaluasi</div>
                        <ol>
                            @foreach ($evaluasiEdukasi as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- Konfirmasi Edukasi -->
                <!-- <div class="section-title" style="font-size:13px">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Konfirmasi Edukasi
                </div> -->
                <div class="konfirmasi-grid">
                    <div>
                        <div class="form-group"><label>Tanggal/Efektif *</label><input type="datetime-local"
                                id="field-tgl-edukasi" class="form-control" value="{{ date('Y-m-d\TH:i') }}"></div>
                        <div class="form-group"><label>Nama Pasien / Wali *</label><input type="text"
                                id="field-nama-wali-ttd" class="form-control"
                                placeholder="Masukkan nama yang menandatangani"></div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label>Tanda Tangan Pasien / Wali *</label>
                            <div class="signature-preview-box" id="konfirmasi-signature-box">
                                <div class="signature-placeholder">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:32px;height:32px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    <span>Klik untuk Tanda Tangan</span>
                                </div>
                            </div>
                            <p style="font-size:11px;color:#EF4444;margin-top:6px;font-style:italic">Harap
                                tanda tangan</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top:24px;display:flex;justify-content:flex-end">
                    <button class="btn btn-success" id="btn-save-edukasi" style="padding:14px 32px;font-size:14px">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Data Edukasi
                    </button>
                </div>
            </div>

            <!-- ── C. Pelaksanaan Edukasi ── -->
            <div class="glass-card" id="card-pelaksanaan">
                <!-- <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
                    <div class="section-title" style="margin-bottom:0">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        C. Pelaksanaan Edukasi
                    </div>
                    <button class="btn-add-row" id="btn-add-topik">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pelaksanaan
                    </button>
                </div> -->

                <div style="overflow-x:auto">
                    <table class="edukasi-table">
                        <thead>
                            <tr>
                                <th style="min-width:100px">POLI / UNIT</th>
                                <th style="min-width:220px">TOPIK EDUKASI *</th>
                                <th style="min-width:160px">TGL/JAM EDUKASI *</th>
                                <th style="min-width:110px">VERIFIKASI *</th>
                                <th style="min-width:100px">TANDA TANGAN PASIEN *</th>
                                <th style="min-width:100px">TGL REEDUKASI</th>
                                <th style="width:50px">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="topik-table-body">
                            @php $badgeColors = ['Pendaftaran'=>'badge-blue','Dokter'=>'badge-red','Gizi'=>'badge-green','Farmasi'=>'badge-orange','Rawat Inap'=>'badge-purple']; @endphp
                            @foreach ($topikEdukasi as $topik)
                                @php
                                    $canEditRow = false;
                                    $poliUnit = strtolower($topik['poli_unit'] ?? '');

                                    if ($poliUnit == 'pendaftaran' && hasPermission('ep.create.pendaftaran')) {
                                        $canEditRow = true;
                                    } elseif ($poliUnit == 'dokter' && hasPermission('ep.create.dpjp')) {
                                        $canEditRow = true;
                                    } elseif ($poliUnit == 'gizi' && hasPermission('ep.create.gizi')) {
                                        $canEditRow = true;
                                    } elseif ($poliUnit == 'farmasi' && hasPermission('ep.create.farmasi')) {
                                        $canEditRow = true;
                                    } elseif ($poliUnit == 'rawat inap' && hasPermission('ep.create.ranap')) {
                                        $canEditRow = true;
                                    } elseif (
                                        empty($poliUnit) &&
                                        (hasPermission('ep.create.ranap') ||
                                            hasPermission('ep.create.dpjp') ||
                                            hasPermission('ep.create.farmasi') ||
                                            hasPermission('ep.create.gizi') ||
                                            hasPermission('ep.create.pendaftaran'))
                                    ) {
                                        $canEditRow = true;
                                    }
                                @endphp
                                <tr data-kode="{{ $topik['kode'] }}"
                                    data-is-custom="{{ $topik['is_custom'] ? 'true' : 'false' }}">
                                    <td>
                                        @if ($topik['poli_unit'])
                                            <span
                                                class="badge {{ $badgeColors[$topik['poli_unit']] ?? 'badge-blue' }}">{{ $topik['poli_unit'] }}</span>
                                        @else
                                            <input type="text" class="poli-input" placeholder="Poli/Unit"
                                                {{ !$canEditRow ? 'disabled' : '' }}>
                                        @endif
                                    </td>
                                    <td class="topik-cell" data-original-topik="{{ $topik['topik'] }}">
                                        {!! nl2br(e(trim(str_ireplace('(sebutkan)', '', $topik['topik'])))) !!}
                                        <textarea class="topik-sebutkan-input form-control"
                                            style="font-size:11px;padding:4px 6px;margin-top:4px;width:100%;border-radius:4px;resize:vertical;min-height:50px;"
                                            placeholder="Sebutkan detail..." {{ !$canEditRow ? 'disabled' : '' }}></textarea>
                                    </td>
                                    <td>
                                        <div style="margin-bottom:6px">
                                            <label
                                                style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:2px">Mulai:</label>
                                            <input type="datetime-local" class="form-control input-start"
                                                style="font-size:11px;padding:4px 6px"
                                                value="{{ now()->format('Y-m-d\TH:i') }}"
                                                {{ !$canEditRow ? 'disabled' : '' }}>
                                        </div>
                                        <div>
                                            <label
                                                style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:2px">Selesai:</label>
                                            <input type="datetime-local" class="form-control input-end"
                                                style="font-size:11px;padding:4px 6px"
                                                {{ !$canEditRow ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td class="verif-cell">
                                        @foreach ($pilihanVerifikasi as $v)
                                            <div
                                                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                                <label
                                                    style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">{{ $v }}</label>
                                                <input type="text" maxlength="1"
                                                    data-verif="{{ $v }}"
                                                    oninput="this.value=this.value.replace(/[^1-4]/g,'')"
                                                    style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;"
                                                    placeholder="" {{ !$canEditRow ? 'disabled' : '' }}>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="ttd-cell">
                                        <div class="ttd-mini" id="ttd-pasien-{{ $topik['kode'] }}"
                                            @if ($canEditRow) onclick="openSignatureModal('ttd-pasien-{{ $topik['kode'] }}')" @else style="cursor:not-allowed;opacity:0.5;" @endif>
                                            <span class="ttd-placeholder"
                                                style="font-size:9px;color:var(--text-muted)">TTD</span>
                                        </div>
                                        <div style="margin-top:4px; text-align:center;display:none">
                                            <label
                                                style="font-size:9px;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;">
                                                <input type="checkbox" class="copy-ttd-cb"
                                                    data-target="ttd-pasien-{{ $topik['kode'] }}"
                                                    {{ !$canEditRow ? 'disabled' : '' }}> Salin TTD B
                                            </label>
                                        </div>
                                    </td>

                                    <td><input type="date" class="form-control"
                                            style="font-size:11px;padding:6px 8px"
                                            {{ !$canEditRow ? 'disabled' : '' }}></td>
                                    <td style="text-align:center">
                                        <button class="btn-icon btn-icon-save" title="Simpan baris"
                                            @if (!$canEditRow) disabled style="opacity:0.4;cursor:not-allowed;" @endif>
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                style="width:16px;height:16px">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                                </path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <button class="btn-add-row" id="btn-add-topik">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Pelaksanaan
                </button>
            </div>

        </div> {{-- /content-area --}}
    </div> {{-- /main-content --}}

    @include('edukasi_pasien.modals')
    @include('edukasi_pasien.scripts')

    @if (!hasPermission('ep.create.ranap'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Disable all form elements except search and section C (pelaksanaan)
                const containers = document.querySelectorAll(
                    '.content-area .glass-card:not(#card-search):not(#card-pelaksanaan)');
                containers.forEach(container => {
                    const elements = container.querySelectorAll('input, select, textarea, button');
                    elements.forEach(el => {
                        // Do not disable print buttons (e.g., .btn-print) if any
                        if (!el.classList.contains('btn-print') && !el.closest('.btn-print')) {
                            el.disabled = true;
                        }
                    });

                    // Block signature canvas
                    const canvases = container.querySelectorAll('canvas');
                    canvases.forEach(canvas => {
                        canvas.style.pointerEvents = 'none';
                        canvas.style.opacity = '0.7';
                    });
                });

                // Hide specific action buttons explicitly
                const actionBtns = document.querySelectorAll('#btn-save-edukasi, #btn-add-topik');
                actionBtns.forEach(btn => {
                    if (btn) btn.style.display = 'none';
                });

                // Show alert or info
                const rightColumn = document.querySelector('#card-data-pasien').parentNode;
                const alertDiv = document.createElement('div');
                alertDiv.innerHTML =
                    '<div style="background:#FEF2F2; color:#991B1B; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:500; border:1px solid #FCA5A5; display:flex; align-items:center; gap:8px;"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Anda berada dalam Mode Read-Only karena tidak memiliki hak akses.</div>';
                rightColumn.insertBefore(alertDiv, rightColumn.firstChild);
            });
        </script>
    @endif
</body>

</html>
