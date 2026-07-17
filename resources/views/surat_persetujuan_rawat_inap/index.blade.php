<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Surat Persetujuan Rawat Inap</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
        .page-hero::before { content: ''; position: absolute; top: -50%; right: -20%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .page-hero::after { content: ''; position: absolute; bottom: -60%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
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
        .form-group { margin-bottom: 16px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em; }
        .form-control { width: 100%; padding: 11px 16px; background: #F8FAFC; border: 1.5px solid var(--border); border-radius: 12px; font-size: 14px; color: var(--text-main); transition: all 0.2s; outline: none; font-weight: 500; font-family: inherit; }
        .form-control:focus { background: white; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-light); }
        .form-control[readonly] { background: #F1F5F9; color: var(--text-secondary); cursor: default; }
        .form-row { display: flex; gap: 12px; align-items: center; }
        .section-divider { margin: 28px 0; border: none; border-top: 1.5px dashed var(--border); }
        .section-title { font-size: 13px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; color: var(--accent); text-transform: uppercase; letter-spacing: 0.04em; }

        /* Buttons */
        .btn { padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--accent-dark)); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3); }

        /* Document specific */
        .document-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid var(--text-main); padding-bottom: 15px; }
        .document-header h2 { font-size: 20px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .document-header p { font-style: italic; color: var(--text-secondary); margin-top: 5px; font-size: 13px; }
        .radio-group { display: flex; gap: 20px; flex-wrap: wrap; padding-top: 10px; }
        .radio-item { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .radio-item input[type="radio"], .radio-item input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; flex-shrink: 0; }
        .radio-item label { margin-bottom: 0; font-weight: 500; font-size: 14px; color: var(--text-main); text-transform: none; letter-spacing: normal; cursor: pointer; }
        .statement-box { background: #F8FAFC; border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .statement-text { font-size: 14px; line-height: 1.6; color: var(--text-main); margin-bottom: 12px; }
        .statement-list { padding-left: 20px; list-style-type: decimal; font-size: 14px; line-height: 1.6; color: var(--text-main); }
        .statement-list li { margin-bottom: 6px; }

        /* Modal Overlay */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); z-index: 100; display: none; align-items: center; justify-content: center; padding: 20px; }
        .modal-content { background: white; width: 100%; max-width: 1000px; max-height: 90vh; border-radius: 24px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.3); }

        @keyframes pulseRingSuccess {
            0% { transform: scale(0.95); opacity: 1; }
            80%, 100% { transform: scale(1.3); opacity: 0; }
        }

        #close-success-modal:hover { background: #F1F5F9 !important; color: #64748B !important; }
        #btn-close-success:hover { background: #059669 !important; transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(16, 185, 129, 0.3), 0 4px 6px -1px rgba(16, 185, 129, 0.2) !important; }
        #btn-close-success:active { transform: translateY(1px); }

        @media (max-width: 1024px) {
            .mobile-header { display: flex; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; padding-top: 64px; }
            .layout-grid { grid-template-columns: 1fr; }
        }

        /* ─── Signature ─── */
        .signature-wrapper { border: 2px solid var(--border); border-radius: 14px; background: #F8FAFC; position: relative; cursor: crosshair; overflow: hidden; transition: all 0.2s; }
        .signature-wrapper:hover { border-color: var(--accent); }
        .signature-preview-box { width: 100%; height: 160px; background: #FAFBFC; border: 2px dashed var(--border); border-radius: 16px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; position: relative; overflow: hidden; }
        .signature-preview-box:hover { border-color: var(--accent); background: white; }
        .signature-preview-box img { max-height: 100%; max-width: 100%; object-fit: contain; }
        .signature-placeholder { display: flex; flex-direction: column; align-items: center; gap: 8px; color: var(--text-muted); font-size: 13px; }
        .signature-placeholder svg { opacity: 0.4; }
        .signature-pad { width: 100%; height: 300px; background: white; border-radius: 12px; touch-action: none; outline: none; }
        .signature-start-hint { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); pointer-events: none; display: flex; flex-direction: column; align-items: center; gap: 4px; color: var(--accent); opacity: 0.6; font-size: 11px; font-weight: 600; border-left: 2px dashed var(--accent); padding-left: 10px; }

        /* Modal Header Customizations */
        .modal-header { padding: 20px 28px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #0F172A, #1E293B); }
        .modal-title { color: white; font-weight: 700; font-size: 16px; }
        .close-modal { background: none; border: none; color: rgba(255,255,255,0.6); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border-radius: 50%; padding: 6px; }
        .close-modal:hover { background: rgba(255,255,255,0.1); color: white; }
        .modal-body { padding: 24px; overflow-y: auto; flex-grow: 1; }
        .modal-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; align-items: center; gap: 12px; background: #FAFBFC; }

        /* ─── Phone Input Group ─── */
        .telp-input-group {
            display: flex;
            align-items: center;
            background: #F8FAFC;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s;
        }
        .telp-input-group:focus-within {
            background: white;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-light);
        }
        .telp-prefix {
            padding: 11px 12px 11px 16px;
            background: #F1F5F9;
            color: var(--text-secondary);
            font-weight: 700;
            font-size: 14px;
            border-right: 1.5px solid var(--border);
            user-select: none;
        }
        input.telp-prefix {
            border-top: none;
            border-left: none;
            border-bottom: none;
            outline: none;
            width: 64px;
            text-align: center;
            padding: 11px 0;
            cursor: text;
        }
        .telp-input-group .form-control {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            padding-left: 12px;
        }
        .pihak-pertama-three-cols {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            grid-column: span 2;
        }
        @media (max-width: 768px) {
            .pihak-pertama-three-cols {
                grid-template-columns: 1fr;
                grid-column: span 1;
            }
        }

        /* Premium Select2 Styling Overrides */
        .select2-container--default .select2-selection--single {
            background-color: #F8FAFC !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 12px !important;
            height: auto !important;
            padding: 10px 14px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            outline: none !important;
            transition: all 0.2s !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            background-color: white !important;
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px var(--accent-light) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-main) !important;
            padding-left: 0 !important;
            line-height: normal !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 12px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-dropdown {
            background-color: white !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
            margin-top: 4px !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--border) !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            outline: none !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--accent) !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--accent) !important;
            color: white !important;
        }
        .select2-container--default .select2-results__option {
            padding: 10px 14px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            color: var(--text-secondary) !important;
        }
    </style>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</head>
<body>

    @include('partials.sidebar')

    <main class="main-content">
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>/</span>
                <span class="current">Surat Persetujuan Rawat Inap</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>RM 02</h1>
                    <p>Formulir Surat Persetujuan Rawat Inap Pasien</p>
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
                            <input type="text" id="search-no-rm" class="form-control" placeholder="Masukkan No. Rekam Medis..." style="width: 100%;">
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
                            <h2>Surat Persetujuan Rawat Inap</h2>
                            <p>(diisi oleh pasien / wali, beri tanda &#9745; pada kolom yang sesuai)</p>
                        </div>

                        <form action="#" method="POST" id="formRawatInap">
                            @csrf
                            <input type="hidden" name="nip" value="{{ Session::get('user_id') }}">

                            {{-- DATA REGISTRASI --}}
                            <div class="section-title">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Data Pernyataan Persetujuan
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>No. Surat</label>
                                    <input type="text" class="form-control" name="no_surat" id="field-no-surat" value="SPRI-{{ date('YmdHis') }}" readonly required>
                                </div>
                                <div class="form-group">
                                    <label>No. Rawat</label>
                                    <input type="text" class="form-control" name="no_rawat" id="field-no-rawat" placeholder="Pilih dari riwayat atau isi otomatis" readonly required>
                                </div>
                                <div class="form-group">
                                    <label>No. KTP Pasien (NIK)</label>
                                    <input type="text" class="form-control" name="no_ktp_pasien" id="field-no-ktp" placeholder="NIK Pasien" readonly>
                                </div>
                                <div class="form-group">
                                    <label>No. HP / Telp Pasien</label>
                                    <input type="text" class="form-control" name="no_tlp_pasien" id="field-no-tlp" placeholder="Nomor Telepon Pasien" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" name="tanggal" id="field-tanggal" value="{{ date('Y-m-d') }}" readonly required>
                                </div>
                            </div>
                            

                            <hr class="section-divider">

                              <div class="section-title">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c0 1.657 2.008 3 4 3s4-1.343 4-3"></path></svg>
                                Identitas Pasien
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>No. Rekam Medis</label>
                                    <input type="text" class="form-control" name="no_rm" id="field-no-rm" placeholder="Contoh: 12-34-56" required readonly>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pasien</label>
                                    <input type="text" class="form-control" name="nama_pasien" id="field-nama-pasien" placeholder="Masukkan nama pasien" required readonly>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tgl_lahir" id="field-tgl-lahir" required readonly>
                                </div>
                                <div class="form-group">
                                    <label>Umur (Tahun / Bulan)</label>
                                    <div class="form-row">
                                        <input type="number" class="form-control" name="umur_tahun" id="field-umur-tahun" placeholder="Tahun" required readonly>
                                        <span style="font-size: 14px; color: var(--text-muted); white-space: nowrap;">/</span>
                                        <input type="number" class="form-control" name="umur_bulan" id="field-umur-bulan" placeholder="Bulan" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" id="jk_L" name="jk" value="Laki-Laki" required disabled>
                                            <label for="jk_L">Laki - Laki</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" id="jk_P" name="jk" value="Perempuan" disabled>
                                            <label for="jk_P">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group"></div>
                                <div class="form-group full-width">
                                    <label>Alamat Lengkap</label>
                                    <input type="text" class="form-control" name="alamat" id="field-alamat" placeholder="Nama Jalan / Perumahan" required readonly>
                                </div>
                                <div class="form-group">
                                    <label>Hak Kelas Perawatan</label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" id="hak_kelas_1" name="hak_kelas" value="Kelas I" required>
                                            <label for="hak_kelas_1">Kelas I</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" id="hak_kelas_2" name="hak_kelas" value="Kelas II">
                                            <label for="hak_kelas_2">Kelas II</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" id="hak_kelas_3" name="hak_kelas" value="Kelas III">
                                            <label for="hak_kelas_3">Kelas III</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kd_bangsal">Ruang Perawatan Yang Diinginkan</label>
                                    <select class="form-control" id="kd_bangsal" name="kd_bangsal" required style="width: 100%;">
                                        <option value="">Pilih Ruangan...</option>
                                        @foreach($kamarList as $k)
                                            <option value="{{ $k->kd_bangsal }}|{{ $k->kelas }}">
                                                {{ $k->nm_bangsal }} - {{ $k->kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group full-width">
                                    <label>Penanggung Jawab Biaya</label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" id="biaya_mandiri" name="pj_biaya" value="Bayar Sendiri" required>
                                            <label for="biaya_mandiri">Bayar Sendiri (Pasien Umum)</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" id="biaya_jkn" name="pj_biaya" value="JKN">
                                            <label for="biaya_jkn">JKN</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" id="biaya_asuransi" name="pj_biaya" value="Asuransi Lain">
                                            <label for="biaya_asuransi">Asuransi Lain:</label>
                                            <input type="text" class="form-control" id="nama_asuransi" name="nama_asuransi" placeholder="Nama Asuransi" style="width: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- BAGIAN 1: PIHAK PERTAMA --}}
                            <div class="section-title">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Pihak Pertama (Yang Bertanda Tangan)
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="pj_nama" id="field-pj-nama" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label>No. KTP / NIK Penanggung Jawab</label>
                                    <input type="text" class="form-control" name="no_ktp" id="field-pj-ktp" value="-" required>
                                </div>
                                <div class="form-group">
                                    <label>Umur (Tahun)</label>
                                    <input type="number" class="form-control" name="pj_umur" id="field-pj-umur" placeholder="Contoh: 35" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label>Pendidikan Penanggung Jawab</label>
                                    <input type="text" class="form-control" name="pendidikan_pj" id="field-pj-pendidikan" value="-" required>
                                </div>
                                <div class="pihak-pertama-three-cols">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label>Jenis Kelamin</label>
                                        <div class="radio-group" style="height: 48px; display: flex; align-items: center; margin-top: 0;">
                                            <div class="radio-item">
                                                <input type="radio" id="pj_jk_L" name="pj_jk" value="Laki-Laki" required>
                                                <label for="pj_jk_L">Laki - Laki</label>
                                            </div>
                                            <div class="radio-item">
                                                <input type="radio" id="pj_jk_P" name="pj_jk" value="Perempuan">
                                                <label for="pj_jk_P">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label>Hubungan Dengan Pasien</label>
                                        <select class="form-control" name="pj_hubungan" id="field-pj-hubungan" required>
                                            <option value="">Pilih Hubungan...</option>
                                            <option value="Suami">Suami</option>
                                            <option value="Istri">Istri</option>
                                            <option value="Anak">Anak</option>
                                            <option value="Ayah">Ayah</option>
                                            <option value="Ibu">Ibu</option>
                                            <option value="Saudara">Saudara</option>
                                            <option value="Diri Sendiri">Diri Sendiri</option>
                                            <option value="Keponakan">Keponakan</option>
                                            <option value="Cucu">Cucu</option>
                                            <option value="Kakek">Kakek</option>
                                            <option value="Nenek">Nenek</option>
                                            <option value="Kakak">Kakak</option>
                                            <option value="Adik">Adik</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                        <textarea class="form-control" id="field-pj-hubungan-text" placeholder="Masukkan hubungan dengan pasien..." style="display: none; min-height: 80px;"></textarea>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label>No. HP / Telepon</label>
                                        <div class="telp-input-group">
                                            <!-- Select Prefix Container -->
                                            <div id="telp-prefix-select-container" style="display: flex; align-items: center; background: #F1F5F9; border-right: 1.5px solid var(--border);">
                                                <select id="field-pj-telp-prefix-select" style="border: none; background: transparent; font-weight: 700; font-size: 14px; padding: 11px 24px 11px 16px; outline: none; cursor: pointer; color: var(--text-secondary); width: 85px; height: 100%; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill=\'%23475569\' height=\'24\' viewBox=\'0 0 24 24\' width=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7 10l5 5 5-5z\'/></svg>'); background-repeat: no-repeat; background-position: right 8px center; background-size: 16px;">
                                                    <option value="+62" selected>+62</option>
                                                    <option value="+60">+60</option>
                                                    <option value="+65">+65</option>
                                                    <option value="custom">Lainnya...</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Custom Prefix Input Container (Hidden by default) -->
                                            <div id="telp-prefix-custom-container" style="display: none; align-items: center; background: #F1F5F9; border-right: 1.5px solid var(--border);">
                                                <input type="text" class="telp-prefix" id="field-pj-telp-prefix" name="pj_telp_prefix" value="+62" placeholder="+62" style="border: none !important; width: 55px; text-align: center; padding: 11px 0; background: transparent;" required>
                                                <button type="button" id="btn-cancel-custom-prefix" style="border: none; background: transparent; padding: 0 8px 0 4px; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; height: 100%;">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>

                                            <input type="text" class="form-control telp-input" name="pj_telp" id="field-pj-telp" placeholder="8xxxxxxxxxx" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group full-width">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <label style="margin-bottom: 0;">Alamat Lengkap</label>
                                        <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-secondary); font-weight: 500;">
                                            <input type="checkbox" id="copy-alamat-pasien" style="cursor: pointer; width: 16px; height: 16px; accent-color: var(--primary);">
                                            <label for="copy-alamat-pasien" style="margin-bottom: 0; cursor: pointer; user-select: none;">Sama dengan alamat pasien</label>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="pj_alamat" id="field-pj-alamat" placeholder="Masukkan alamat lengkap penanggung jawab..." required>
                                </div>
                                <div class="form-group full-width" style="display: none;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <label style="margin-bottom: 0;">Nama & Alamat Keluarga Terdekat (Yang Dapat Dihubungi Dalam Keadaan Darurat)</label>
                                        <button type="button" id="copy-alamat-keluarga" style="background: none; border: none; color: var(--accent); font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 0;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                            Salin Alamat Pasien
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" name="nama_alamat_keluarga_terdekat" id="field-pj-keluarga-terdekat" value="-" required>
                                </div>
                            </div>

                            <hr class="section-divider">

                            {{-- SIGNATURE SECTION --}}
                            <div class="form-group" style="margin-top: 24px;">
                                <label>Tanda Tangan Penanggung Jawab</label>
                                <div class="signature-preview-box" id="btn-open-signature">
                                    <div class="signature-placeholder" id="signature-placeholder">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px; color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        <span>Klik untuk Tanda Tangan</span>
                                    </div>
                                    <img id="signature-preview" style="display: none;">
                                </div>
                                <input type="hidden" name="tanda_tangan" id="input-signature">
                            </div>

                            <hr class="section-divider">

                            {{-- BAGIAN 2: PERNYATAAN --}}
                            <div class="section-title">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pernyataan
                            </div>

                            <div class="statement-box">
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label>Keputusan Rawat Inap</label>
                                    <select class="form-control" name="keputusan" style="max-width: 320px; font-weight: 700; color: var(--accent);" required>
                                        <option value="PERSETUJUAN">MEMBERIKAN PERSETUJUAN</option>
                                        <option value="PENOLAKAN">MEMBERIKAN PENOLAKAN</option>
                                    </select>
                                </div>
                                <p class="statement-text" style="font-size: 20px;font-weight: 800; text-align: center; padding-bottom: 60px;">Bersedia menanggung biaya perawatan apabila naik kelas atau menjadi pasien umum atau apabila syarat dan ketentuan tidak sesuai dengan ketentuan penjamin *)</p>   
                                <p class="statement-text">Dengan ini menyatakan dengan sesungguhnya memberikan keputusan di atas untuk dilakukan rawat inap di RSUD Karsa Husada Batu serta setuju untuk :</p>
                                <ul class="statement-list">
                                    <li>Memberikan keterangan tentang riwayat penyakit and kesehatannya;</li>
                                    <li>Menjalani pemeriksaan fisik;</li>
                                    <li>Menjalani pemeriksaan penunjang;</li>
                                    <li>Mendapatkan tindakan medis non operatif serta tindakan perawatan yang dibutuhkan terkait dengan perawatan pasien;</li>
                                    <li>Memenuhi semua persyaratan administrasi yang diperlukan;</li>
                                    <li>Mentaati seluruh peraturan yang ditetapkan RSUD Karsa Husada Batu;</li>
                                    <li>Bersedia mengganti peralatan yang hilang (peralatan makan, minum, sprei, selimut, dan lain-lain yang terdaftar dalam ruang rawat inap pasien) akibat kelalaian pasien dan keluarga.</li>
                                </ul>
                            </div>

                            <hr class="section-divider">

                            {{-- BAGIAN 3: IDENTITAS PASIEN --}}
                          
                            <div style="margin-top: 40px; display: flex; justify-content: flex-end;">
                                <button type="button" class="btn btn-primary" id="btn-save-document">
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

    {{-- SIGNATURE MODAL --}}
    <div class="modal-overlay" id="signature-modal">
        <div class="modal-content" style="max-width: 600px; margin: auto;">
            <div class="modal-header">
                <div class="modal-title">Tanda Tangan Penanggung Jawab</div>
                <button class="close-modal" id="close-signature-modal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div class="signature-wrapper" style="border: 2px solid var(--border); border-radius: 14px; background: white; position: relative;">
                    <div class="signature-start-hint" id="signature-start-hint">
                        <span>START</span>
                        <span>HERE</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    </div>
                    <canvas id="signature-pad" class="signature-pad" tabindex="0"></canvas>
                </div>
                <p style="margin-top: 12px; font-size: 13px; color: var(--text-muted); text-align: center;">
                    Silakan goreskan tanda tangan Anda mulai dari sisi kiri kotak.
                </p>
            </div>
            <div class="modal-footer" style="gap: 12px;">
                <button type="button" class="btn" id="clear-signature" style="background: #F1F5F9; color: var(--text-main);">Hapus</button>
                <button type="button" class="btn btn-primary" id="save-signature-modal">Simpan Tanda Tangan</button>
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    <div class="modal-overlay" id="success-modal" style="z-index: 1050; padding: 20px;">
        <div class="modal-content" style="max-width: 380px; text-align: center; padding: 40px 32px 32px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid rgba(16, 185, 129, 0.1); position: relative; animation: modalBounce 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: white; margin: auto;">
            <button id="close-success-modal" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: #94A3B8; cursor: pointer; padding: 8px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; outline: none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div style="width: 72px; height: 72px; background: #ECFDF5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: #10B981; position: relative;">
                <div style="position: absolute; inset: -4px; border-radius: 50%; border: 2px solid #6EE7B7; opacity: 0.5; animation: pulseRingSuccess 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;"></div>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 36px; height: 36px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #1E293B; margin: 0 0 12px; letter-spacing: -0.02em;">Berhasil!</h3>
            <p id="success-modal-message" style="font-size: 14px; font-weight: 500; color: #64748B; margin: 0 0 28px; line-height: 1.6;"></p>
            <button id="btn-close-success" style="width: 100%; padding: 14px 24px; background: #10B981; color: white; border: none; border-radius: 12px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2), 0 2px 4px -1px rgba(16, 185, 129, 0.1); outline: none;">
                Selesai
            </button>
        </div>
    </div>

    {{-- ERROR MODAL --}}
    <div class="modal-overlay" id="error-modal" style="z-index: 1050; padding: 20px;">
        <div class="modal-content" style="max-width: 380px; text-align: center; padding: 40px 32px 32px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid rgba(239, 68, 68, 0.1); position: relative; background: white; margin: auto;">
            <button id="close-error-modal" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: #94A3B8; cursor: pointer; padding: 8px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; outline: none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div style="width: 72px; height: 72px; background: #FEF2F2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: #EF4444; position: relative;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 36px; height: 36px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #1E293B; margin: 0 0 12px; letter-spacing: -0.02em;">Gagal!</h3>
            <p id="error-modal-message" style="font-size: 14px; font-weight: 500; color: #64748B; margin: 0 0 28px; line-height: 1.6;"></p>
            <button id="btn-close-error" style="width: 100%; padding: 14px 24px; background: #EF4444; color: white; border: none; border-radius: 12px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.2s; outline: none;">
                Tutup
            </button>
        </div>
    </div>

    <!-- jQuery and Select2 JS CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kd_bangsal').select2({
                placeholder: "Pilih Ruangan...",
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
        });

        // Modal Control Helper
        function showError(message) {
            document.getElementById('error-modal-message').textContent = message;
            document.getElementById('error-modal').style.display = 'flex';
        }
        function hideError() {
            document.getElementById('error-modal').style.display = 'none';
        }
        function showSuccess(message) {
            document.getElementById('success-modal-message').textContent = message;
            document.getElementById('success-modal').style.display = 'flex';
        }
        function hideSuccess() {
            document.getElementById('success-modal').style.display = 'none';
        }

        document.getElementById('close-error-modal').addEventListener('click', hideError);
        document.getElementById('btn-close-error').addEventListener('click', hideError);
        document.getElementById('close-success-modal').addEventListener('click', hideSuccess);
        document.getElementById('btn-close-success').addEventListener('click', hideSuccess);

        // Cost Responsibility Control
        document.querySelectorAll('input[name="pj_biaya"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const asuransiInput = document.getElementById('nama_asuransi');
                if (e.target.value === 'Asuransi Lain') {
                    asuransiInput.setAttribute('required', 'required');
                    asuransiInput.focus();
                } else {
                    asuransiInput.removeAttribute('required');
                    asuransiInput.value = '';
                }
            });
        });

        // Patient Search, History Table, & Alamat Copy state
        const btnSearch = document.getElementById('btn-search');
        const inputNoRm = document.getElementById('search-no-rm');
        const historyTableBody = document.getElementById('history-table-body');
        
        let activePatientData = null;
        let activePatientFullAddress = '';

        // Auto Fill Pihak Pertama Hubungan "Diri Sendiri" option trigger for convenience
        document.getElementById('field-pj-hubungan').addEventListener('change', (e) => {
            if (!activePatientData) return;
            if (e.target.value === 'Diri Sendiri') {
                document.getElementById('field-pj-nama').value = activePatientData.nm_pasien || '';
                document.getElementById('field-pj-ktp').value = '-';
                document.getElementById('field-pj-alamat').value = activePatientFullAddress;
                document.getElementById('copy-alamat-pasien').checked = true;
                
                // Auto Fill & Sanitize Phone Number
                let phoneVal = activePatientData.no_tlp || '';
                let prefixVal = '+62';
                if (phoneVal.startsWith('+')) {
                    let match = phoneVal.match(/^\+(\d{1,4})/);
                    if (match) {
                        prefixVal = '+' + match[1];
                        phoneVal = phoneVal.substring(prefixVal.length);
                    }
                } else if (phoneVal.startsWith('62')) {
                    prefixVal = '+62';
                    phoneVal = phoneVal.substring(2);
                } else if (phoneVal.startsWith('0')) {
                    prefixVal = '+62';
                    phoneVal = phoneVal.substring(1);
                }
                
                const prefixSelectEl = document.getElementById('field-pj-telp-prefix-select');
                const prefixSelectContainerEl = document.getElementById('telp-prefix-select-container');
                const prefixCustomContainerEl = document.getElementById('telp-prefix-custom-container');
                
                document.getElementById('field-pj-telp-prefix').value = prefixVal;
                document.getElementById('field-pj-telp').value = phoneVal;
                
                if (prefixSelectEl) {
                    const options = Array.from(prefixSelectEl.options).map(opt => opt.value);
                    if (options.includes(prefixVal)) {
                        prefixSelectEl.value = prefixVal;
                        prefixSelectContainerEl.style.display = 'flex';
                        prefixCustomContainerEl.style.display = 'none';
                    } else {
                        prefixSelectEl.value = 'custom';
                        prefixSelectContainerEl.style.display = 'none';
                        prefixCustomContainerEl.style.display = 'flex';
                    }
                }
                
                // Parse Age for PJ
                if (activePatientData.tgl_lahir) {
                    const birthDate = new Date(activePatientData.tgl_lahir);
                    const today = new Date();
                    let ageYears = today.getFullYear() - birthDate.getFullYear();
                    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                        ageYears--;
                    }
                    document.getElementById('field-pj-umur').value = ageYears;
                }
                
                // Parse Gender for PJ
                if (activePatientData.jk === 'L') {
                    document.getElementById('pj_jk_L').checked = true;
                } else if (activePatientData.jk === 'P') {
                    document.getElementById('pj_jk_P').checked = true;
                }
            } else {
                document.getElementById('copy-alamat-pasien').checked = false;
            }
        });

        // Toggle textarea when "Lainnya" is selected on hubungan
        function toggleHubunganLainnya() {
            const select = document.getElementById('field-pj-hubungan');
            const textarea = document.getElementById('field-pj-hubungan-text');
            if (select.value === 'Lainnya') {
                select.style.display = 'none';
                select.removeAttribute('name');
                select.removeAttribute('required');
                textarea.style.display = 'block';
                textarea.setAttribute('name', 'pj_hubungan');
                textarea.setAttribute('required', 'required');
                textarea.focus();
            } else {
                textarea.style.display = 'none';
                textarea.removeAttribute('name');
                textarea.removeAttribute('required');
                select.style.display = 'block';
                select.setAttribute('name', 'pj_hubungan');
                select.setAttribute('required', 'required');
            }
        }
        document.getElementById('field-pj-hubungan').addEventListener('change', toggleHubunganLainnya);

        // Copy patient address checkbox handler
        document.getElementById('copy-alamat-pasien').addEventListener('change', (e) => {
            const pjAlamatInput = document.getElementById('field-pj-alamat');
            if (e.target.checked) {
                if (!activePatientFullAddress) {
                    showError('Silakan cari pasien terlebih dahulu.');
                    e.target.checked = false;
                    return;
                }
                pjAlamatInput.value = activePatientFullAddress;
            } else {
                pjAlamatInput.value = '';
            }
        });

        // Copy patient address to emergency contact handler
        document.getElementById('copy-alamat-keluarga').addEventListener('click', (e) => {
            e.preventDefault();
            if (!activePatientFullAddress) {
                showError('Silakan cari pasien terlebih dahulu.');
                return;
            }
            const keluargaInput = document.getElementById('field-pj-keluarga-terdekat');
            const currentValue = keluargaInput.value.trim();
            
            if (currentValue === '') {
                keluargaInput.value = '[Nama] ([Hubungan]) - ' + activePatientFullAddress;
                keluargaInput.focus();
                keluargaInput.setSelectionRange(1, 5); // Selects "Nama" template text
            } else if (currentValue.endsWith('-')) {
                keluargaInput.value = currentValue + ' ' + activePatientFullAddress;
                keluargaInput.focus();
            } else if (!currentValue.includes('-')) {
                keluargaInput.value = currentValue + ' - ' + activePatientFullAddress;
                keluargaInput.focus();
            } else {
                const parts = currentValue.split('-');
                const namePart = parts[0].trim();
                keluargaInput.value = namePart + ' - ' + activePatientFullAddress;
                keluargaInput.focus();
            }
        });

        inputNoRm.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchPatient();
            }
        });

        btnSearch.addEventListener('click', searchPatient);

        async function searchPatient() {
            const noRm = inputNoRm.value.trim();
            if (!noRm) return showError('Silakan masukkan No. RM terlebih dahulu.');

            btnSearch.disabled = true;
            btnSearch.innerHTML = 'Mencari...';

            try {
                const response = await fetch(`/pasien/search?no_rm=${noRm}`);
                const result = await response.json();

                if (response.ok) {
                    const { pasien, history } = result;
                    activePatientData = pasien;
                    window.historyData = history;

                    // Fill Patient Identity
                    document.getElementById('field-no-rm').value = pasien.no_rkm_medis || '';
                    document.getElementById('field-nama-pasien').value = pasien.nm_pasien || '';
                    document.getElementById('field-tgl-lahir').value = pasien.tgl_lahir || '';
                    document.getElementById('field-no-ktp').value = pasien.no_ktp || '-';
                    document.getElementById('field-no-tlp').value = pasien.no_tlp || '-';

                    // Parse Gender
                    if (pasien.jk === 'L') {
                        document.getElementById('jk_L').checked = true;
                    } else if (pasien.jk === 'P') {
                        document.getElementById('jk_P').checked = true;
                    }

                    // Construct full patient address and fill it
                    activePatientFullAddress = (pasien.alamat || '') + 
                        (pasien.rt && pasien.rt !== '-' ? ' RT ' + pasien.rt : '') + 
                        (pasien.rw && pasien.rw !== '-' ? ' RW ' + pasien.rw : '') + 
                        (pasien.nm_kel && pasien.nm_kel !== '-' ? ', Kel. ' + pasien.nm_kel : '') + 
                        (pasien.nm_kec && pasien.nm_kec !== '-' ? ', Kec. ' + pasien.nm_kec : '') + 
                        (pasien.nm_kab && pasien.nm_kab !== '-' ? ', ' + pasien.nm_kab : '');
                    
                    document.getElementById('field-alamat').value = activePatientFullAddress || '-';
                    document.getElementById('copy-alamat-pasien').checked = false;
                    document.getElementById('field-pj-alamat').value = '';
                    document.getElementById('field-pj-hubungan').value = '';
                    document.getElementById('field-pj-hubungan-text').value = '';
                    document.getElementById('field-pj-hubungan').style.display = 'block';
                    document.getElementById('field-pj-hubungan').setAttribute('name', 'pj_hubungan');
                    document.getElementById('field-pj-hubungan').setAttribute('required', 'required');
                    document.getElementById('field-pj-hubungan-text').style.display = 'none';
                    document.getElementById('field-pj-hubungan-text').removeAttribute('name');
                    document.getElementById('field-pj-hubungan-text').removeAttribute('required');

                    // Parse Age
                    if (pasien.tgl_lahir) {
                        const birthDate = new Date(pasien.tgl_lahir);
                        const today = new Date();
                        let ageYears = today.getFullYear() - birthDate.getFullYear();
                        let ageMonths = today.getMonth() - birthDate.getMonth();
                        if (ageMonths < 0 || (ageMonths === 0 && today.getDate() < birthDate.getDate())) {
                            ageYears--;
                            ageMonths += 12;
                        }
                        document.getElementById('field-umur-tahun').value = ageYears;
                        document.getElementById('field-umur-bulan').value = ageMonths;
                    }

                    // Populate Registration History Table
                    historyTableBody.innerHTML = '';
                    if (history && history.length > 0) {
                        history.forEach((reg, index) => {
                            const row = document.createElement('tr');
                            row.style.cursor = 'pointer';

                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${reg.tgl_registrasi}</td>
                                <td>${reg.no_rawat}</td>
                                <td>
                                    <button type="button" class="btn" style="background: var(--primary-light); color: var(--primary); padding: 4px 10px; font-size: 11px; border-radius: 8px;">Pilih</button>
                                </td>
                            `;

                            const selectRow = () => {
                                document.getElementById('field-no-rawat').value = reg.no_rawat;
                                // document.getElementById('field-tanggal').value = reg.tgl_registrasi;
                                
                                // Highlight Row
                                Array.from(historyTableBody.children).forEach(r => r.style.background = '');
                                row.style.background = 'var(--primary-light)';
                            };

                            row.addEventListener('click', selectRow);
                            historyTableBody.appendChild(row);
                        });

                        // Select the latest registration by default
                        document.getElementById('field-no-rawat').value = history[0].no_rawat;
                        // document.getElementById('field-tanggal').value = history[0].tgl_registrasi;
                        historyTableBody.children[0].style.background = 'var(--primary-light)';
                    } else {
                        historyTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Pasien ditemukan, tapi tidak ada riwayat pendaftaran.</td></tr>';
                        document.getElementById('field-no-rawat').value = '';
                        // document.getElementById('field-tanggal').value = '';
                    }

                } else {
                    showError(result.error || 'Pasien tidak ditemukan.');
                }
            } catch (error) {
                console.error(error);
                showError('Gagal menghubungi server untuk pencarian pasien.');
            } finally {
                btnSearch.disabled = false;
                btnSearch.innerHTML = 'Cari';
            }
        }

        // Geolocation coordinate fetcher for form submission
        const getCoordinates = () => {
            return new Promise((resolve) => {
                if (navigator.geolocation && (window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            resolve({
                                lat: position.coords.latitude.toFixed(6),
                                lng: position.coords.longitude.toFixed(6)
                            });
                        },
                        function(error) {
                            // Fallback to IP API
                            fetch('http://ip-api.com/json/')
                                .then(res => res.json())
                                .then(data => resolve({ lat: data.lat, lng: data.lon }))
                                .catch(() => resolve({ lat: '-', lng: '-' }));
                        },
                        { timeout: 3000 } // 3 seconds timeout
                    );
                } else {
                    // Fallback to IP API
                    fetch('http://ip-api.com/json/')
                        .then(res => res.json())
                        .then(data => resolve({ lat: data.lat, lng: data.lon }))
                        .catch(() => resolve({ lat: '-', lng: '-' }));
                }
            });
        };

        // Form Submit Simpan Dokumen
        const btnSaveDocument = document.getElementById('btn-save-document');
        btnSaveDocument.addEventListener('click', async () => {
            const form = document.getElementById('formRawatInap');
            
            // Trigger browser validations
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Signature Validation
            const signatureData = document.getElementById('input-signature').value;
            if (!signatureData) {
                showError('Silakan tanda tangan terlebih dahulu.');
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
                
                const response = await fetch('/surat-persetujuan-rawat-inap/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showSuccess(result.message || 'Formulir Surat Persetujuan Rawat Inap (RM 02) berhasil disimpan!');
                    
                    // Reload page on close success
                    const reloadAction = () => {
                        window.location.reload();
                    };
                    document.getElementById('btn-close-success').onclick = reloadAction;
                    document.getElementById('close-success-modal').onclick = reloadAction;
                } else {
                    showError(result.message || 'Gagal menyimpan dokumen.');
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

        // ─── Signature Pad Engine ───
        const signatureModal = document.getElementById('signature-modal');
        const btnOpenSignature = document.getElementById('btn-open-signature');
        const btnCloseSignatureModal = document.getElementById('close-signature-modal');
        const btnSaveSignatureModal = document.getElementById('save-signature-modal');
        const btnClearSignature = document.getElementById('clear-signature');
        const canvas = document.getElementById('signature-pad');
        const signaturePreview = document.getElementById('signature-preview');
        const signaturePlaceholder = document.getElementById('signature-placeholder');
        const inputSignature = document.getElementById('input-signature');

        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: '#1E293B'
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        btnOpenSignature.addEventListener('click', () => {
            signatureModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                resizeCanvas();
                signaturePad.clear();
                document.getElementById('signature-start-hint').style.display = 'flex';
                canvas.focus();
            }, 50);
        });

        signaturePad.onBegin = () => {
            document.getElementById('signature-start-hint').style.display = 'none';
        };

        function closeSignatureModal() {
            signatureModal.style.display = 'none';
            document.body.style.overflow = '';
        }

        btnCloseSignatureModal.addEventListener('click', closeSignatureModal);

        btnClearSignature.addEventListener('click', () => {
            signaturePad.clear();
            document.getElementById('signature-start-hint').style.display = 'flex';
        });

        btnSaveSignatureModal.addEventListener('click', () => {
            if (signaturePad.isEmpty()) {
                showError('Silakan tanda tangan terlebih dahulu.');
                return;
            }
            
            const dataURL = signaturePad.toDataURL('image/png');
            signaturePreview.src = dataURL;
            signaturePreview.style.display = 'block';
            signaturePlaceholder.style.display = 'none';
            inputSignature.value = dataURL;
            
            // Highlight preview box border
            btnOpenSignature.style.borderStyle = 'solid';
            btnOpenSignature.style.borderColor = 'var(--primary)';
            
            closeSignatureModal();
        });

        // Phone Number Normalization Logic
        document.querySelectorAll('.telp-input').forEach(input => {
            input.addEventListener('input', function(e) {
                // Only allow digits
                let value = this.value.replace(/\D/g, '');
                
                // Strip leading zero
                if (value.startsWith('0')) {
                    value = value.substring(1);
                }
                
                // Update input value
                this.value = value;
            });
            
            // Handle paste
            input.addEventListener('paste', function(e) {
                setTimeout(() => {
                    let value = this.value.replace(/\D/g, '');
                    if (value.startsWith('0')) {
                        value = value.substring(1);
                    }
                    if (value.startsWith('62')) {
                        value = value.substring(2);
                    }
                    this.value = value;
                }, 0);
            });
        });

        // Normalize prefix input
        const prefixInput = document.getElementById('field-pj-telp-prefix');
        if (prefixInput) {
            prefixInput.addEventListener('input', function(e) {
                let val = this.value;
                if (!val.startsWith('+')) {
                    val = '+' + val.replace(/\D/g, '');
                } else {
                    val = '+' + val.substring(1).replace(/\D/g, '');
                }
                this.value = val;
            });
        }

        // Phone Prefix Selector & Custom Typing Logic
        const prefixSelect = document.getElementById('field-pj-telp-prefix-select');
        const prefixSelectContainer = document.getElementById('telp-prefix-select-container');
        const prefixCustomContainer = document.getElementById('telp-prefix-custom-container');
        const btnCancelCustomPrefix = document.getElementById('btn-cancel-custom-prefix');

        if (prefixSelect && prefixInput) {
            prefixSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    prefixSelectContainer.style.display = 'none';
                    prefixCustomContainer.style.display = 'flex';
                    prefixInput.value = '+';
                    prefixInput.focus();
                } else {
                    prefixInput.value = this.value;
                }
            });

            btnCancelCustomPrefix.addEventListener('click', function() {
                prefixCustomContainer.style.display = 'none';
                prefixSelectContainer.style.display = 'flex';
                prefixSelect.value = '+62';
                prefixInput.value = '+62';
            });
        }

        // Check for edit query parameters on load
        window.addEventListener('DOMContentLoaded', async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const noRm = urlParams.get('no_rm');
            const noRawat = urlParams.get('no_rawat');
            const isEdit = urlParams.get('edit') === 'true';

            if (noRm) {
                const searchInput = document.getElementById('search-no-rm');
                if (searchInput) {
                    searchInput.value = noRm;
                    // Trigger search
                    await searchPatient();
                    
                    if (noRawat) {
                        // Find and select the corresponding registration row
                        const rows = Array.from(document.getElementById('history-table-body').children);
                        const targetRow = rows.find(row => {
                            const cells = row.getElementsByTagName('td');
                            return cells.length > 2 && cells[2].textContent.trim() === noRawat;
                        });

                        if (targetRow) {
                            // Trigger click event on the target row to select it
                            targetRow.click();

                            if (isEdit && window.historyData) {
                                // If edit mode, populate form with existing SPRI details
                                const reg = window.historyData.find(h => h.no_rawat === noRawat);
                                if (reg && reg.surat_persetujuan_rawat_inap) {
                                    const consent = reg.surat_persetujuan_rawat_inap;
                                    
                                    // 1. Populate basic identity fields
                                    if (consent.no_surat) {
                                        document.getElementById('field-no-surat').value = consent.no_surat;
                                    }
                                    
                                    // 2. Parse and populate pj_nama, pj_umur, pj_jk
                                    const namePj = consent.nama_pj || '';
                                    let parsedName = namePj;
                                    let parsedAge = '';
                                    let parsedGender = '';

                                    const match = namePj.match(/^(.*)\((\d+)\)\(([LP])\)$/i);
                                    if (match) {
                                        parsedName = match[1].trim();
                                        parsedAge = match[2];
                                        parsedGender = match[3].toUpperCase();
                                    }

                                    document.getElementById('field-pj-nama').value = parsedName;
                                    document.getElementById('field-pj-umur').value = parsedAge;
                                    
                                    if (parsedGender === 'L') {
                                        document.getElementById('pj_jk_L').checked = true;
                                    } else if (parsedGender === 'P') {
                                        document.getElementById('pj_jk_P').checked = true;
                                    }

                                    // 3. Populate other identity fields
                                    document.getElementById('field-pj-ktp').value = consent.no_ktppj || '';
                                    document.getElementById('field-pj-pendidikan').value = consent.pendidikan_pj || '';
                                    
                                    // 4. Map and populate hubungan
                                    let mappedHubungan = '';
                                    if (consent.hubungan === 'Diri Saya') {
                                        mappedHubungan = 'Diri Sendiri';
                                    } else if (consent.hubungan === 'Suami') {
                                        mappedHubungan = 'Suami';
                                    } else if (consent.hubungan === 'Istri') {
                                        mappedHubungan = 'Istri';
                                    } else if (consent.hubungan === 'Anak') {
                                        mappedHubungan = 'Anak';
                                    } else if (consent.hubungan === 'Ayah' || consent.hubungan === 'Ibu') {
                                        mappedHubungan = 'Orang Tua';
                                    } else {
                                        mappedHubungan = consent.hubungan || '';
                                    }
                                    
                                    const hubunganSelect = document.getElementById('field-pj-hubungan');
                                    const hubunganTextarea = document.getElementById('field-pj-hubungan-text');
                                    if (hubunganSelect) {
                                        const options = Array.from(hubunganSelect.options).map(opt => opt.value);
                                        if (options.includes(mappedHubungan)) {
                                            hubunganSelect.value = mappedHubungan;
                                            toggleHubunganLainnya();
                                        } else {
                                            hubunganSelect.value = 'Lainnya';
                                            toggleHubunganLainnya();
                                            hubunganTextarea.value = mappedHubungan;
                                        }
                                    }

                                    // 5. Parse and populate pj_telp & prefix
                                    let phoneVal = consent.no_telppj || '';
                                    let prefixVal = '+62';
                                    if (phoneVal.startsWith('62')) {
                                        prefixVal = '+62';
                                        phoneVal = phoneVal.substring(2);
                                    } else if (phoneVal.startsWith('60')) {
                                        prefixVal = '+60';
                                        phoneVal = phoneVal.substring(2);
                                    } else if (phoneVal.startsWith('65')) {
                                        prefixVal = '+65';
                                        phoneVal = phoneVal.substring(2);
                                    } else if (phoneVal.startsWith('+')) {
                                        let prefixMatch = phoneVal.match(/^\+(\d{1,4})/);
                                        if (prefixMatch) {
                                            prefixVal = '+' + prefixMatch[1];
                                            phoneVal = phoneVal.substring(prefixVal.length);
                                        }
                                    } else if (phoneVal.startsWith('0')) {
                                        phoneVal = phoneVal.substring(1);
                                        prefixVal = '+62';
                                    }

                                    const prefixSelectEl = document.getElementById('field-pj-telp-prefix-select');
                                    const prefixSelectContainerEl = document.getElementById('telp-prefix-select-container');
                                    const prefixCustomContainerEl = document.getElementById('telp-prefix-custom-container');
                                    const prefixInputEl = document.getElementById('field-pj-telp-prefix');
                                    
                                    if (prefixInputEl) {
                                        prefixInputEl.value = prefixVal;
                                    }
                                    document.getElementById('field-pj-telp').value = phoneVal;

                                    if (prefixSelectEl) {
                                        const options = Array.from(prefixSelectEl.options).map(opt => opt.value);
                                        if (options.includes(prefixVal)) {
                                            prefixSelectEl.value = prefixVal;
                                            prefixSelectContainerEl.style.display = 'flex';
                                            prefixCustomContainerEl.style.display = 'none';
                                        } else {
                                            prefixSelectEl.value = 'custom';
                                            prefixSelectContainerEl.style.display = 'none';
                                            prefixCustomContainerEl.style.display = 'flex';
                                        }
                                    }

                                    // 6. Populate pj_alamat
                                    document.getElementById('field-pj-alamat').value = consent.alamatpj || '';
                                    if (activePatientFullAddress && consent.alamatpj === activePatientFullAddress) {
                                        document.getElementById('copy-alamat-pasien').checked = true;
                                    }

                                    // 7. Populate keluarga terdekat
                                    document.getElementById('field-pj-keluarga-terdekat').value = consent.nama_alamat_keluarga_terdekat || '';

                                    // 8. Map and populate hak_kelas
                                    let mappedHakKelas = '';
                                    if (consent.hak_kelas === 'Kelas 1') {
                                        mappedHakKelas = 'Kelas I';
                                    } else if (consent.hak_kelas === 'Kelas 2') {
                                        mappedHakKelas = 'Kelas II';
                                    } else if (consent.hak_kelas === 'Kelas 3') {
                                        mappedHakKelas = 'Kelas III';
                                    }
                                    if (mappedHakKelas) {
                                        const radioEl = document.querySelector(`input[name="hak_kelas"][value="${mappedHakKelas}"]`);
                                        if (radioEl) radioEl.checked = true;
                                    }

                                    // 9. Map and populate kd_bangsal select (via Select2)
                                    const kdKamarSelect = $('#kd_bangsal');
                                    if (kdKamarSelect.length && consent.ruang) {
                                        const targetText = (consent.ruang + ' - ' + (consent.kelas || '')).trim();
                                        let matched = false;
                                        kdKamarSelect.find('option').each(function () {
                                            if ($(this).text().trim() === targetText) {
                                                kdKamarSelect.val($(this).val()).trigger('change');
                                                matched = true;
                                                return false;
                                            }
                                        });
                                        if (!matched) {
                                            kdKamarSelect.find('option').each(function () {
                                                if ($(this).text().startsWith(consent.ruang)) {
                                                    kdKamarSelect.val($(this).val()).trigger('change');
                                                    return false;
                                                }
                                            });
                                        }
                                    }

                                    // 10. Map and populate pj_biaya & nama_asuransi
                                    const bayarSecara = consent.bayar_secara || '';
                                    const asuransiInput = document.getElementById('nama_asuransi');
                                    if (bayarSecara === 'Bayar Sendiri') {
                                        document.getElementById('biaya_mandiri').checked = true;
                                        if (asuransiInput) {
                                            asuransiInput.removeAttribute('required');
                                            asuransiInput.value = '';
                                        }
                                    } else if (bayarSecara === 'JKN') {
                                        document.getElementById('biaya_jkn').checked = true;
                                        if (asuransiInput) {
                                            asuransiInput.removeAttribute('required');
                                            asuransiInput.value = '';
                                        }
                                    } else {
                                        document.getElementById('biaya_asuransi').checked = true;
                                        if (asuransiInput) {
                                            asuransiInput.setAttribute('required', 'required');
                                            asuransiInput.value = bayarSecara;
                                        }
                                    }

                                    // 11. Populate signature preview and hidden field
                                    const signaturePreview = document.getElementById('signature-preview');
                                    const signaturePlaceholder = document.getElementById('signature-placeholder');
                                    const btnOpenSignature = document.getElementById('btn-open-signature');
                                    const inputSignature = document.getElementById('input-signature');

                                    if (reg.signature_pasien && reg.signature_pasien.signature_path && signaturePreview && signaturePlaceholder) {
                                        const signatureUrl = '/storage/' + reg.signature_pasien.signature_path;
                                        signaturePreview.src = signatureUrl;
                                        signaturePreview.style.display = 'block';
                                        signaturePlaceholder.style.display = 'none';
                                        if (btnOpenSignature) {
                                            btnOpenSignature.style.borderStyle = 'solid';
                                            btnOpenSignature.style.borderColor = 'var(--primary)';
                                        }
                                        if (inputSignature) {
                                            inputSignature.value = signatureUrl;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
