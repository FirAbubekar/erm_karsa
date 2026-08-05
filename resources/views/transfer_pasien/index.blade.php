<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Transfer Pasien Antar Ruangan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #10B981;
            --primary-dark: #059669;
            --primary-light: rgba(16, 185, 129, 0.1);
            --accent: #6366F1;
            --accent-light: rgba(99, 102, 241, 0.08);
            --accent-dark: #4F46E5;
            --bg-main: #F1F5F9;
            --text-main: #0F172A;
            --text-secondary: #334155;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --warning: #F59E0B;
            --warning-light: rgba(245, 158, 11, 0.1);
            --info: #3B82F6;
            --info-light: rgba(59, 130, 246, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; transition: transform 0.3s ease; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; }
        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; overflow-y: auto; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
        .nav-item:hover { background: var(--bg-main); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item svg { width: 20px; height: 20px; }
        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); margin-top: auto; }
        .mobile-header { display: none; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: white; border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 45; }
        .sidebar-overlay.active { display: block; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; width: calc(100% - var(--sidebar-width)); min-height: 100vh; }

        /* Hero */
        .page-hero { background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%); padding: 40px 40px 60px; position: relative; overflow: hidden; }
        .page-hero::before { content: ''; position: absolute; top: -50%; right: -20%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .page-hero::after { content: ''; position: absolute; bottom: -60%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .hero-top { display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 1; }
        .hero-title h1 { font-size: 28px; font-weight: 800; color: white; letter-spacing: -0.75px; margin-bottom: 6px; }
        .hero-title p { color: rgba(255,255,255,0.5); font-size: 14px; }
        .hero-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; font-size: 12px; color: rgba(255,255,255,0.4); position: relative; z-index: 1; }
        .hero-breadcrumb a { color: rgba(255,255,255,0.5); text-decoration: none; }
        .hero-breadcrumb .current { color: rgba(255,255,255,0.7); font-weight: 600; }

        /* Content */
        .content-area { padding: 32px 40px 40px; margin-top: -28px; position: relative; z-index: 2; }
        .glass-card { background: white; padding: 32px; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 10px 15px -3px rgba(0,0,0,0.04); margin-bottom: 24px; }

        /* Layout Grid */
        .layout-grid { display: grid; grid-template-columns: 380px 1fr; gap: 24px; align-items: start; }
        .left-column { display: flex; flex-direction: column; gap: 24px; }
        .right-column { display: flex; flex-direction: column; gap: 24px; }

        /* Search Container */
        .search-container { display: flex; gap: 10px; margin-bottom: 24px; }

        /* History Table */
        .history-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .history-table th { text-align: left; padding: 10px 16px; background: linear-gradient(to bottom, #FAFBFC, #F6F8FA); color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
        .history-table td { padding: 12px 16px; border-bottom: 1px solid #F1F5F9; font-size: 13px; }
        .history-table tr:hover { background: rgba(99, 102, 241, 0.03); }
        .history-table tr { cursor: pointer; transition: background 0.15s; }

        /* Form */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .form-group { margin-bottom: 16px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em; }
        .form-control { width: 100%; padding: 11px 16px; background: #F8FAFC; border: 1.5px solid var(--border); border-radius: 12px; font-size: 14px; color: var(--text-main); transition: all 0.2s; outline: none; font-weight: 500; font-family: inherit; }
        .form-control:focus { background: white; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-light); }
        .form-control[readonly] { background: #F1F5F9; color: var(--text-secondary); cursor: default; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        select.form-control { cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill="%23475569" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 18px; padding-right: 40px; }
        .form-row { display: flex; gap: 12px; align-items: center; }
        .section-divider { margin: 28px 0; border: none; border-top: 1.5px dashed var(--border); }
        .section-title { font-size: 13px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; color: var(--accent); text-transform: uppercase; letter-spacing: 0.04em; }
        .section-title.blue { color: var(--info); }
        .section-title.amber { color: var(--warning); }

        /* Buttons */
        .btn { padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--accent-dark)); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3); }
        .btn-secondary { background: #F8FAFC; color: var(--text-secondary); border: 1.5px solid var(--border); }
        .btn-secondary:hover { background: #F1F5F9; border-color: #CBD5E1; }
        .btn-success { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); }

        /* Document header */
        .document-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid var(--text-main); padding-bottom: 15px; }
        .document-header h2 { font-size: 20px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .document-header p { font-style: italic; color: var(--text-secondary); margin-top: 5px; font-size: 13px; }

        /* Radio group */
        .radio-group { display: flex; gap: 20px; flex-wrap: wrap; padding-top: 4px; }
        .radio-item { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .radio-item input[type="radio"], .radio-item input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; flex-shrink: 0; }
        .radio-item label { margin-bottom: 0; font-weight: 500; font-size: 14px; color: var(--text-main); text-transform: none; letter-spacing: normal; cursor: pointer; }

        /* Vital Signs Card */
        .vital-card { background: #F8FAFC; border: 1.5px solid var(--border); border-radius: 16px; padding: 24px; }
        .vital-card .vital-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .vital-item { display: flex; align-items: center; gap: 8px; }
        .vital-item .vital-label { font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.03em; min-width: 50px; }
        .vital-item .vital-input { width: 80px; padding: 8px 12px; background: white; border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px; color: var(--text-main); font-weight: 600; outline: none; transition: all 0.2s; text-align: center; font-family: inherit; }
        .vital-item .vital-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-light); }
        .vital-item .vital-unit { font-size: 12px; color: var(--text-muted); font-weight: 600; }

        /* Signature Section */
        .signature-section { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .signature-box { background: #FAFBFC; border: 2px dashed var(--border); border-radius: 16px; padding: 20px; text-align: center; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s; }
        .signature-box:hover { border-color: var(--accent); background: white; }
        .signature-box .sig-label { font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 4px; }
        .signature-box .sig-name { font-size: 13px; color: var(--text-secondary); }
        .signature-box .sig-placeholder { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .signature-box .sig-placeholder svg { opacity: 0.3; }
        .signature-box .sig-placeholder span { font-size: 12px; color: var(--text-muted); }

        /* Two columns vital */
        .two-col-vital { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

        /* Status badge */
        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .status-badge.info { background: var(--info-light); color: var(--info); }
        .status-badge.warning { background: var(--warning-light); color: var(--warning); }

        @media (max-width: 1024px) {
            .mobile-header { display: flex; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; padding-top: 64px; }
            .layout-grid { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .form-grid-3 { grid-template-columns: 1fr; }
            .two-col-vital { grid-template-columns: 1fr; }
            .signature-section { grid-template-columns: 1fr; }
            .vital-card .vital-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    @include('partials.sidebar')

    <main class="main-content">
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>/</span>
                <span class="current">Transfer Pasien Antar Ruangan</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>Transfer Pasien Antar Ruangan</h1>
                    <p>Formulir dokumentasi pemindahan pasien antar ruangan perawatan</p>
                </div>
            </div>
        </div>

        <div class="content-area">
            <div class="layout-grid">

                {{-- LEFT COLUMN: SEARCH & HISTORY --}}
                <div class="left-column">
                    <div class="glass-card">
                        <div class="section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari Data Pasien
                        </div>
                        <div class="search-container">
                            <input type="text" id="search-no-rm" class="form-control" placeholder="Masukkan No. Rekam Medis..." oninput="var v=this.value.replace(/[^0-9\/]/g,'');var s=v.split('/');while(s.length>4)s.pop();this.value=s.join('/').slice(0,17)">
                            <button class="btn btn-primary" id="btn-search" style="white-space: nowrap;">
                                Cari
                            </button>
                        </div>

                        <!-- History Table -->
                        <div style="overflow-x: auto;">
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
                                    <tr id="empty-history">
                                        <td colspan="4" style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 12px;">Masukkan No. RM untuk melihat riwayat...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: MAIN FORM --}}
                <div class="right-column">
                    <div class="glass-card">
                        <div class="document-header">
                            <h2>Transfer Pasien Antar Ruangan</h2>
                            <p>Formulir pemindahan pasien dari satu ruangan ke ruangan lain</p>
                        </div>

                        <form action="#" method="POST" id="formTransferPasien">
                            @csrf

                            {{-- DATA REGISTRASI --}}
                            <div class="section-title">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Data Registrasi
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>No. Rawat</label>
                                    <input type="text" class="form-control" name="no_rawat" id="field-no-rawat" placeholder="Pilih dari riwayat" readonly>
                                </div>
                                <div class="form-group">
                                    <label>No. Rekam Medis</label>
                                    <input type="text" class="form-control" name="no_rm" id="field-no-rm" placeholder="Contoh: 12-34-56" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pasien</label>
                                    <input type="text" class="form-control" name="nama_pasien" id="field-nama-pasien" placeholder="Nama pasien" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="text" class="form-control" name="tgl_lahir" id="field-tgl-lahir" placeholder="Tanggal lahir" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <input type="text" class="form-control" name="jk" id="field-jk" placeholder="Jenis kelamin" readonly>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- WAKTU & INDIKASI PINDAH --}}
                            <div class="section-title blue">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Waktu & Indikasi Pindah
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Tanggal & Jam Masuk</label>
                                    <input type="datetime-local" class="form-control" name="tgl_masuk" id="field-tgl-masuk" value="{{ date('Y-m-d\TH:i:s') }}">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal & Jam Pindah</label>
                                    <input type="datetime-local" class="form-control" name="tgl_pindah" id="field-tgl-pindah" value="{{ date('Y-m-d\TH:i:s') }}">
                                </div>
                                <div class="form-group">
                                    <label>Indikasi Pindah</label>
                                    <select class="form-control" name="indikasi_pindah" id="field-indikasi-pindah">
                                        <option value="">Pilih Indikasi Pindah...</option>
                                        <option value="Kondisi Pasien Stabil">Kondisi Pasien Stabil</option>
                                        <option value="Kondisi Pasien Memburuk">Kondisi Pasien Memburuk</option>
                                        <option value="Permintaan Pasien/Keluarga">Permintaan Pasien/Keluarga</option>
                                        <option value="Kebutuhan Perawatan Khusus">Kebutuhan Perawatan Khusus</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan Indikasi</label>
                                    <input type="text" class="form-control" name="ket_indikasi" id="field-ket-indikasi" value="-" placeholder="Keterangan tambahan (opsional)">
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- ASAL & TUJUAN RUANGAN --}}
                            <div class="section-title amber">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                Asal & Tujuan Ruangan
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Asal Ruang Rawat / Poliklinik</label>
                                    <select name="kd_bangsal" id="kd_bangsal" class="form-control">
                                        <option value="">-- Pilih Bangsal --</option>
                                        @foreach($bangsalList as $bangsal)
                                            <option value="{{ $bangsal->kd_bangsal }}">
                                                {{ $bangsal->nm_bangsal }}
                                            </option>
                                        @endforeach
                                    </select>   
                                </div>
                                <div class="form-group">
                                    <label>Ruang Rawat Selanjutnya</label>
                                    <select name="tujuan_bangsal" id="tujuan_bangsal" class="form-control">
                                        <option value="">-- Pilih Bangsal --</option>
                                        @foreach($bangsalList as $bangsal)
                                            <option value="{{ $bangsal->kd_bangsal }}">
                                                {{ $bangsal->nm_bangsal }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Metode Pemindahan</label>
                                    <select class="form-control" name="metode_pemindahan" id="field-metode-pemindahan">
                                        <option value="">Pilih Metode...</option>
                                        <option selected value="Tempat Tidur">Tempat Tidur</option>
                                        <option value="Kursi Roda">Kursi Roda</option>
                                        <option value="Brankar">Brankar</option>
                                        <option value="Jalan Kaki">Jalan Kaki</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- DIAGNOSA & PROSEDUR --}}
                            <div class="section-title">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Diagnosa & Prosedur
                            </div>

                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label>Diagnosa Utama</label>
                                    <input type="text" class="form-control" name="diagnosa_utama" id="field-diagnosa-utama" placeholder="Masukkan diagnosa utama pasien">
                                </div>
                                <div class="form-group">
                                    <label>Diagnosa Sekunder</label>
                                    <textarea class="form-control" name="diagnosa_sekunder" id="field-diagnosa-sekunder" placeholder="Masukkan diagnosa sekunder (jika ada)" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Prosedur Yang Sudah Dilakukan</label>
                                    <textarea class="form-control" name="prosedur" id="field-prosedur" placeholder="Masukkan prosedur yang sudah dilakukan" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Obat Yang Telah Diberikan</label>
                                    <textarea class="form-control" name="obat_diberikan" id="field-obat-diberikan" placeholder="Masukkan daftar obat yang telah diberikan" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Pemeriksaan Penunjang Yang Sudah Dilakukan</label>
                                    <textarea class="form-control" name="pemeriksaan_penunjang" id="field-pemeriksaan-penunjang" placeholder="Masukkan pemeriksaan penunjang yang sudah dilakukan" rows="3"></textarea>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- PERALATAN & PERSETUJUAN --}}
                            <div class="section-title blue">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                Peralatan & Persetujuan
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Peralatan Yang Menyertai</label>
                                    <select class="form-control" name="peralatan_menyertai" id="field-peralatan">
                                        <option value="">Pilih Peralatan...</option>
                                        <option value="Oksigen Portable">Oksigen Portable</option>
                                        <option value="Infus Pump">Infus Pump</option>
                                        <option value="Syringe Pump">Syringe Pump</option>
                                        <option value="Monitor">Monitor</option>
                                        <option value="Ventilator">Ventilator</option>
                                        <option value="Tidak Ada">Tidak Ada</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan Peralatan</label>
                                    <input type="text" class="form-control" name="ket_peralatan" id="field-ket-peralatan" placeholder="Keterangan tambahan peralatan">
                                </div>
                                <div class="form-group">
                                    <label>Pasien/Keluarga Mengetahui & Menyetujui Alasan Pemindahan</label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" id="persetujuan_ya" name="persetujuan_pindah" value="Ya">
                                            <label for="persetujuan_ya">Ya</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" id="persetujuan_tidak" name="persetujuan_pindah" value="Tidak">
                                            <label for="persetujuan_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Pemberi Persetujuan --}}
                            <div class="form-grid" style="margin-top: 8px;">
                                <div class="form-group full-width" id="pemberi-persetujuan-section">
                                    <label style="font-size: 13px; color: var(--info); font-weight: 700; margin-bottom: 12px; text-transform: none; letter-spacing: normal;">
                                        Bila Pemberi Persetujuan Adalah Keluarga / Penanggung Jawab Pasien
                                    </label>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" name="nama_pj" id="field-nama-pj" placeholder="Nama penanggung jawab">
                                        </div>
                                        <div class="form-group">
                                            <label>Hubungan</label>
                                            <select class="form-control" name="hubungan_pj" id="field-hubungan-pj">
                                                <option value="">Pilih Hubungan...</option>
                                                <option value="Suami">Suami</option>
                                                <option value="Istri">Istri</option>
                                                <option value="Anak">Anak</option>
                                                <option value="Ayah">Ayah</option>
                                                <option value="Ibu">Ibu</option>
                                                <option value="Saudara">Saudara</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- KEADAAN PASIEN SEBELUM TRANSFER --}}
                            <div class="two-col-vital">
                                <div>
                                    <div class="section-title amber">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        Keadaan Sebelum Transfer
                                    </div>
                                    <div class="vital-card">
                                        <div class="form-group">
                                            <label>Keadaan Umum</label>
                                            <select class="form-control" name="keadaan_umum_sebelum" id="field-keadaan-sebelum">
                                                <option value="Compos Mentis">Compos Mentis</option>
                                                <option value="Somnolen">Somnolen</option>
                                                <option value="Sopor">Sopor</option>
                                                <option value="Koma">Koma</option>
                                                <option value="Apatis">Apatis</option>
                                            </select>
                                        </div>
                                        <div class="vital-grid" style="margin-top: 16px;">
                                            <div class="vital-item">
                                                <span class="vital-label">TD</span>
                                                <input type="text" class="vital-input" name="td_sebelum" id="field-td-sebelum" placeholder="120/80">
                                                <span class="vital-unit">mmHg</span>
                                            </div>
                                            <div class="vital-item">
                                                <span class="vital-label">Nadi</span>
                                                <input type="text" class="vital-input" name="nadi_sebelum" id="field-nadi-sebelum" placeholder="80">
                                                <span class="vital-unit">x/menit</span>
                                            </div>
                                            <div class="vital-item">
                                                <span class="vital-label">RR</span>
                                                <input type="text" class="vital-input" name="rr_sebelum" id="field-rr-sebelum" placeholder="20">
                                                <span class="vital-unit">x/menit</span>
                                            </div>
                                            <div class="vital-item">
                                                <span class="vital-label">Suhu</span>
                                                <input type="text" class="vital-input" name="suhu_sebelum" id="field-suhu-sebelum" placeholder="36.5">
                                                <span class="vital-unit">°C</span>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-top: 16px; margin-bottom: 0;">
                                            <label>Keluhan Utama</label>
                                            <textarea class="form-control" name="keluhan_sebelum" id="field-keluhan-sebelum" placeholder="Keluhan utama pasien sebelum transfer" rows="3" style="min-height: 80px;"></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- KEADAAN PASIEN SETELAH TRANSFER --}}
                                <div>
                                    <div class="section-title" style="color: var(--primary);">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Keadaan Setelah Transfer
                                    </div>
                                    <div class="vital-card">
                                        <div class="form-group">
                                            <label>Keadaan Umum</label>
                                            <select class="form-control" name="keadaan_umum_setelah" id="field-keadaan-setelah">
                                                <option value="-">-</option>
                                                <option value="Compos Mentis">Compos Mentis</option>
                                                <option value="Somnolen">Somnolen</option>
                                                <option value="Sopor">Sopor</option>
                                                <option value="Koma">Koma</option>
                                                <option value="Apatis">Apatis</option>
                                            </select>
                                        </div>
                                        <div class="vital-grid" style="margin-top: 16px;">
                                            <div class="vital-item">
                                                <span class="vital-label">TD</span>
                                                <input type="text" class="vital-input" name="td_setelah" id="field-td-setelah" placeholder="120/80">
                                                <span class="vital-unit">mmHg</span>
                                            </div>
                                            <div class="vital-item">
                                                <span class="vital-label">Nadi</span>
                                                <input type="text" class="vital-input" name="nadi_setelah" id="field-nadi-setelah" placeholder="80">
                                                <span class="vital-unit">x/menit</span>
                                            </div>
                                            <div class="vital-item">
                                                <span class="vital-label">RR</span>
                                                <input type="text" class="vital-input" name="rr_setelah" id="field-rr-setelah" placeholder="20">
                                                <span class="vital-unit">x/menit</span>
                                            </div>
                                            <div class="vital-item">
                                                <span class="vital-label">Suhu</span>
                                                <input type="text" class="vital-input" name="suhu_setelah" id="field-suhu-setelah" placeholder="36.5">
                                                <span class="vital-unit">°C</span>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-top: 16px; margin-bottom: 0;">
                                            <label>Keluhan Utama</label>
                                            <textarea class="form-control" name="keluhan_setelah" id="field-keluhan-setelah" placeholder="Keluhan utama pasien setelah transfer" rows="3" style="min-height: 80px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- PETUGAS / PERAWAT --}}
                            <div class="section-title blue">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Petugas / Perawat
                            </div>

                            <div class="signature-section">
                                <div>
                                    <div class="form-group">
                                        <label>Perawat Menyerahkan</label>
                                        <select class="form-control select2-petugas" name="perawat_menyerahkan" id="field-perawat-menyerahkan" style="width: 100%;">
                                            <option value="">Pilih Perawat Menyerahkan...</option>
                                            @foreach($petugasList as $petugas)
                                                <option value="{{ $petugas->nik }}">{{ $petugas->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label>Perawat Menerima</label>
                                        <select class="form-control select2-petugas" name="perawat_menerima" id="field-perawat-menerima" style="width: 100%;">
                                            <option value="">Pilih Perawat Menerima...</option>
                                            @foreach($petugasList as $petugas)
                                                <option value="{{ $petugas->nik }}">{{ $petugas->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- SUBMIT --}}
                            <div style="margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
                                <button type="button" class="btn btn-secondary" id="btn-reset">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Reset
                                </button>
                                <button type="button" class="btn btn-success" id="btn-save-document">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    Simpan Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery (required by Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#kd_bangsal').select2({
                placeholder: "Pilih Ruang & Poliklinik...",
                allowClear: true,
                width: '100%'
            });

            // Auto focus on search box when opened
            $('#kd_bangsal').on('select2:open', function() {
                setTimeout(function() {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                    }
                }, 50);
            });

            $('#tujuan_bangsal').select2({
                placeholder: "Pilih Ruang & Poliklinik...",
                allowClear: true,
                width: '100%'
            });

            // Auto focus on search box when opened
            $('#tujuan_bangsal').on('select2:open', function() {
                setTimeout(function() {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                    }
                }, 50);
            });

            $('#field-perawat-menyerahkan').select2({
                placeholder: "Pilih Perawat Menyerahkan...",
                allowClear: true,
                width: '100%'
            });

            $('#field-perawat-menerima').select2({
                placeholder: "Pilih Perawat Menerima...",
                allowClear: true,
                width: '100%'
            });

            $('#field-perawat-menyerahkan').on('select2:open', function() {
                setTimeout(function() {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                    }
                }, 50);
            });

            $('#field-perawat-menerima').on('select2:open', function() {
                setTimeout(function() {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                    }
                }, 50);
            });
        });
        
        const btnSearch = document.getElementById('btn-search');
        const inputNoRm = document.getElementById('search-no-rm');
        const historyTableBody = document.getElementById('history-table-body');

        // Reset button
        document.getElementById('btn-reset')?.addEventListener('click', function() {
            document.getElementById('formTransferPasien').reset();
        });

        // Enter key search (single handler)
        inputNoRm.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchPatient();
            }
        });

        btnSearch.addEventListener('click', searchPatient);
 
        var isSearching = false;

        async function searchPatient() {
            if (isSearching) return;

            const noRm = inputNoRm.value;
            if (!noRm) return showError('Silakan masukkan No. RM');

            isSearching = true;
            btnSearch.disabled = true;
            btnSearch.innerHTML = 'Mencari...';

            try {
                const response = await fetch(`/pasien/search?no_rm=${noRm}`);
                const result = await response.json();

                if (response.ok) {
                    // Tutup modal error jika sebelumnya terbuka
                    var errModal = document.getElementById('error-modal');
                    if (errModal) { errModal.classList.remove('active'); errModal.style.display = 'none'; }

                    const { pasien, history, latest_auth_parties, matched_no_rawat } = result;
                    activePatientData = pasien;
                    window.matchedNoRawat = matched_no_rawat;

                    // Update Form Fields
                    document.getElementById('field-no-rm').value = pasien.no_rkm_medis;
                    // document.getElementById('field-no-rawat').value = pasien.no_rawat;
                    document.getElementById('field-nama-pasien').value = pasien.nm_pasien;
                    document.getElementById('field-tgl-lahir').value = pasien.tgl_lahir;
                    document.getElementById('field-jk').value = pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan';                    

                    // Auto-fill Pemberi Persetujuan from pasien.namakeluarga / keluarga
                    var namaKeluarga = pasien.namakeluarga || '';
                    var keluargaDb = (pasien.keluarga || '').trim().toUpperCase();
                    var hubunganMap2 = {
                        'AYAH': 'Ayah',
                        'IBU': 'Ibu',
                        'ISTRI': 'Istri',
                        'SUAMI': 'Suami',
                        'SAUDARA': 'Saudara',
                        'ANAK': 'Anak',
                        'LAIN-LAIN': 'Lainnya'
                    };
                    if (namaKeluarga) {
                        document.getElementById('field-nama-pj').value = namaKeluarga;
                    }
                    if (keluargaDb && hubunganMap2[keluargaDb]) {
                        var hj = document.getElementById('field-hubungan-pj');
                        var optExists = Array.from(hj.options).some(function(o) { return o.value === hubunganMap2[keluargaDb]; });
                        hj.value = optExists ? hubunganMap2[keluargaDb] : 'Lainnya';
                    }

                    // // Clear and Populate Authorized Parties
                    // for (let i = 1; i <= 4; i++) {
                    //     document.getElementById(`field-auth-name-${i}`).value = '';
                    //     document.getElementById(`field-auth-telp-${i}`).value = '';
                    // }

                    // if (latest_auth_parties && latest_auth_parties.length > 0) {
                    //     latest_auth_parties.forEach((party, index) => {
                    //         if (index < 4) {
                    //             document.getElementById(`field-auth-name-${index + 1}`).value = party.nama || '';
                    //             // Strip 62 from phone number for display if it exists, as the prefix +62 is already there
                    //             let phone = party.no_telp || '';
                    //             if (phone.startsWith('62')) phone = phone.substring(2);
                    //             document.getElementById(`field-auth-telp-${index + 1}`).value = phone;
                    //         }
                    //     });
                    // }

                    // Update History Table
                    historyTableBody.innerHTML = '';
                    if (history.length > 0) {
                        history.forEach((reg, index) => {
                            const row = document.createElement('tr');
                            row.style.cursor = 'pointer';
                            
                            const hasConsent = reg.general_consent != null;
                            const btnActions = hasConsent 
                                ? `<div style="display: flex; gap: 8px;">
                                    <button class="btn" style="background: var(--primary-light); color: var(--primary); padding: 4px 10px; font-size: 11px; border-radius: 8px;" onclick="showDetail('${reg.no_rawat}')">Detail</button>
                                    <a href="/general-consent/download/${reg.general_consent.no_surat}" target="_blank" class="btn" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 10px; font-size: 11px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        PDF
                                    </a>
                                   </div>`
                                : `<span class="text-muted" style="font-size: 10px; font-style: italic;">Belum ada GC</span>`;

                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${reg.tgl_registrasi}</td>
                                <td>${reg.no_rawat}</td>
                                <td style="padding: 8px 16px;">${btnActions}</td>
                            `;
                            
                            row.addEventListener('click', (e) => {
                                if (e.target.tagName === 'BUTTON') return;
                                
                                document.getElementById('field-no-rawat').value = reg.no_rawat;
                                document.getElementById('field-tgl-periksa').value = reg.tgl_registrasi;
                                // Highlight selected row
                                Array.from(historyTableBody.children).forEach(r => r.style.background = '');
                                row.style.background = 'var(--primary-light)';
                            });
                            historyTableBody.appendChild(row);
                        });

                        // Cache history data for detail modal
                        window.historyData = history;

                        // Automatically select latest registration, or the searched no_rawat if present
                        document.getElementById('field-no-rawat').value = window.matchedNoRawat || history[0].no_rawat;
                        //document.getElementById('field-tgl-periksa').value = history[0].tgl_registrasi;
                        historyTableBody.children[0].style.background = 'var(--primary-light)';
                    } else {
                        historyTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Pasien ditemukan, tapi tidak ada riwayat rawat.</td></tr>';
                        document.getElementById('field-no-rawat').value = '';
                        // document.getElementById('field-tgl-periksa').value = '';
                    }
                } else {
                    showError(result.error || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Search error:', error);
                showError('Gagal menghubungi server');
            } finally {
                isSearching = false;
                btnSearch.disabled = false;
                btnSearch.innerHTML = 'Cari';
            }
        }

        // Tambahkan fungsi getCoordinates jika belum ada
        async function getCoordinates() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation tidak didukung oleh browser ini.'));
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        });
                    },
                    (error) => {
                        // Jika user menolak atau terjadi error, tetap lanjutkan dengan koordinat default
                        console.warn('Gagal mendapatkan lokasi:', error.message);
                        resolve({ lat: null, lng: null });
                    },
                    { timeout: 10000, enableHighAccuracy: false }
                );
            });
        }

        function hideModal(id) {
            var m = document.getElementById(id);
            if (m) { m.classList.remove('active'); m.style.display = 'none'; }
        }

        // Fungsi untuk menampilkan modal error
        function showError(message) {
            const modal = document.getElementById('error-modal');
            const messageEl = document.getElementById('error-message');
            if (modal && messageEl) {
                messageEl.textContent = message;
                modal.style.display = 'flex';
            } else {
                alert(message);
            }
        }

        // Fungsi untuk menampilkan modal success
        function showSuccess(message) {
            const modal = document.getElementById('success-modal');
            const messageEl = document.getElementById('success-message');
            if (modal && messageEl) {
                messageEl.textContent = message;
                modal.style.display = 'flex';
            } else {
                alert(message);
            }
        }

        // Close modal on overlay click & button click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.style.display = 'none';
            }
        });
        document.getElementById('close-error-modal')?.addEventListener('click', function() {
            hideModal('error-modal');
        });

        // Event listener untuk tombol simpan
        const btnSaveDocument = document.getElementById('btn-save-document');
        btnSaveDocument.addEventListener('click', async () => {
            const form = document.getElementById('formTransferPasien');
            
            // Trigger browser validations
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Validasi tambahan untuk form Transfer Pasien
            const kdBangsal = document.getElementById('kd_bangsal').value;
            const tujuanBangsal = document.getElementById('tujuan_bangsal').value;
            
            if (!kdBangsal) {
                showError('Silakan pilih asal ruang rawat / poliklinik.');
                return;
            }
            
            if (!tujuanBangsal) {
                showError('Silakan pilih ruang rawat selanjutnya.');
                return;
            }

            if (kdBangsal === tujuanBangsal) {
                showError('Asal ruangan dan tujuan ruangan tidak boleh sama.');
                return;
            }

            const persetujuanPindah = document.querySelector('input[name="persetujuan_pindah"]:checked');
            if (!persetujuanPindah) {
                showError('Silakan pilih persetujuan pemindahan pasien.');
                return;
            }

            btnSaveDocument.disabled = true;
            btnSaveDocument.innerHTML = `
                <svg class="animate-spin" style="animation: spin 1s linear infinite; width: 18px; height: 18px; display: inline-block; vertical-align: middle; margin-right: 8px;" fill="none" viewBox="0 0 24 24">
                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            // 1. Fetch Geolocation coordinates first
            const coords = await getCoordinates();

            try {
                const formData = new FormData(form);
                formData.append('lat', coords.lat);
                formData.append('lng', coords.lng);

                 // === CARA MELIHAT DATA ===
        console.log('=== DATA TRANSFER PASIEN ===');
        
        // Method 1: Iterasi semua data
        console.log('Method 1 - Iterasi:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }
        
        // Method 2: Convert ke object
        console.log('Method 2 - Object:');
        const dataObj = {};
        formData.forEach((value, key) => {
            dataObj[key] = value;
        });
        console.log(dataObj);
        
        // Method 3: Tabel (paling rapi)
        console.log('Method 3 - Table:');
        console.table(dataObj);
        
        // Method 4: Cek field penting
        console.log('Method 4 - Field penting:');
        console.log('No Rawat:', formData.get('no_rawat') || '(kosong)');
        console.log('Nama Pasien:', formData.get('nama_pasien') || '(kosong)');
        console.log('Diagnosa:', formData.get('diagnosa_utama') || '(kosong)');
        console.log('Asal:', formData.get('kd_bangsal') || '(kosong)');
        console.log('Tujuan:', formData.get('tujuan_bangsal') || '(kosong)');
        
        // Tambahkan koordinat
        formData.append('lat', coords.lat);
        formData.append('lng', coords.lng);
        
        // Cek setelah append
        console.log('Koordinat:', {
            lat: formData.get('lat'),
            lng: formData.get('lng')
        });
                
                const response = await fetch('/transfer-pasien/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Formulir Transfer Pasien berhasil disimpan!');
                    
                    // Reload page on close success
                    const reloadAction = () => {
                        window.location.reload();
                    };
                    
                    // Pastikan tombol close success ada di modal
                    const btnCloseSuccess = document.getElementById('btn-close-success');
                    const closeSuccessModal = document.getElementById('close-success-modal');
                    
                    if (btnCloseSuccess) {
                        btnCloseSuccess.onclick = reloadAction;
                    }
                    if (closeSuccessModal) {
                        closeSuccessModal.onclick = reloadAction;
                    }
                } else {
                    showError(result.message || 'Gagal menyimpan dokumen transfer pasien.');
                }
            } catch (error) {
                console.error(error);
                showError('Terjadi kesalahan jaringan atau server saat menyimpan dokumen.');
            } finally {
                btnSaveDocument.disabled = false;
                btnSaveDocument.innerHTML = `
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Dokumen
                `;
            }
        });

        // Event listener untuk tombol reset
        document.getElementById('btn-reset')?.addEventListener('click', () => {
            const form = document.getElementById('formTransferPasien');
            if (form) {
                form.reset();
                // Reset select2 jika menggunakan
                $('.select2-petugas').val('').trigger('change');
            }
        });

        // Tambahkan validasi real-time untuk asal dan tujuan ruangan
        document.getElementById('kd_bangsal')?.addEventListener('change', function() {
            const tujuan = document.getElementById('tujuan_bangsal');
            if (tujuan.value === this.value && this.value !== '') {
                tujuan.value = '';
                showError('Tujuan ruangan tidak boleh sama dengan asal ruangan.');
            }
        });

        document.getElementById('tujuan_bangsal')?.addEventListener('change', function() {
            const asal = document.getElementById('kd_bangsal');
            if (asal.value === this.value && this.value !== '') {
                this.value = '';
                showError('Tujuan ruangan tidak boleh sama dengan asal ruangan.');
            }
        });

        btnSearch.addEventListener('click', searchPatient);
    </script>

    {{-- Success Modal --}}
    <div id="success-modal" class="modal-overlay" style="display: none;">
        <div class="modal-card success-card">
            <div class="modal-icon success-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h3 class="modal-title">Berhasil</h3>
            <p class="modal-message" id="success-message"></p>
            <div class="modal-actions">
                <button id="btn-close-success" class="btn btn-success">OK</button>
            </div>
        </div>
    </div>

    {{-- Error Modal --}}
    <div id="error-modal" class="modal-overlay" style="display: none;">
        <div class="modal-card error-card">
            <div class="modal-icon error-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <h3 class="modal-title">Gagal</h3>
            <p class="modal-message" id="error-message"></p>
            <div class="modal-actions">
                <button id="close-error-modal" class="btn btn-error">Tutup</button>
            </div>
        </div>
    </div>

    <style>
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
            z-index: 9999; display: flex; align-items: center; justify-content: center;
        }
        .modal-card {
            background: #fff; border-radius: 20px; padding: 40px 32px 28px;
            width: 400px; max-width: 90vw; text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-icon { margin-bottom: 16px; }
        .modal-title {
            font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 8px;
        }
        .modal-message {
            font-size: 15px; color: #64748b; margin: 0 0 24px; line-height: 1.5;
        }
        .modal-actions { display: flex; justify-content: center; gap: 12px; }
        .modal-actions .btn {
            padding: 10px 36px; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; box-shadow: 0 4px 12px rgba(16,185,129,0.3);
        }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,185,129,0.4); }
        .btn-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff; box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        }
        .btn-error:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(239,68,68,0.4); }
        .success-icon svg { filter: drop-shadow(0 4px 8px rgba(16,185,129,0.3)); }
        .error-icon svg { filter: drop-shadow(0 4px 8px rgba(220,38,38,0.3)); }
    </style>
</body>
</html>
