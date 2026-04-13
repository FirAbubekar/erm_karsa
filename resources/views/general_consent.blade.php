<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | General Consent</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

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
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-secondary: #334155;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --accent-blue: #3B82F6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }

        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; }

        /* ─── Sidebar (shared) ─── */
        .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; transition: transform 0.3s ease; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; }
        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
        .nav-item:hover { background: var(--bg-main); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item svg { width: 20px; height: 20px; }
        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); margin-top: auto; }
        .btn-logout { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; width: 100%; background: #FEF2F2; color: #EF4444; border: 1px solid #FEE2E2; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
        .btn-logout:hover { background: #FEE2E2; border-color: #FECACA; transform: translateY(-1px); }
        .btn-logout svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ─── Main Content ─── */
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; max-width: calc(100vw - var(--sidebar-width)); }

        /* ─── Hero Header ─── */
        .page-hero {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            padding: 32px 40px 52px;
            position: relative;
            overflow: hidden;
        }
        .page-hero::before { content: ''; position: absolute; top: -50%; right: -20%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .page-hero::after { content: ''; position: absolute; bottom: -60%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .hero-top { display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 1; }
        .hero-title h1 { font-size: 26px; font-weight: 800; color: white; letter-spacing: -0.75px; margin-bottom: 6px; }
        .hero-title p { color: rgba(255,255,255,0.5); font-size: 14px; }
        .hero-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 12px; color: rgba(255,255,255,0.4); position: relative; z-index: 1; }
        .hero-breadcrumb a { color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
        .hero-breadcrumb a:hover { color: rgba(255,255,255,0.8); }
        .hero-breadcrumb .current { color: rgba(255,255,255,0.7); font-weight: 600; }
        .user-chip { display: flex; align-items: center; gap: 10px; padding: 8px 16px 8px 8px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); border-radius: 50px; backdrop-filter: blur(10px); }
        .user-chip .avatar { width: 32px; height: 32px; background: linear-gradient(135deg, var(--accent), var(--primary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; color: white; }
        .user-chip span { color: rgba(255,255,255,0.85); font-size: 13px; font-weight: 600; }

        /* ─── Content Area ─── */
        .content-area { padding: 0 40px 40px; margin-top: -28px; position: relative; z-index: 2; }

        .header-title h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 4px; }
        .header-title p { color: var(--text-muted); font-size: 14px; }

        /* ─── Glass Card ─── */
        .glass-card {
            background: white;
            padding: 28px;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 10px 15px -3px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }

        /* ─── Form ─── */
        .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em; }

        .form-control {
            width: 100%;
            padding: 11px 16px;
            background: #F8FAFC;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.2s;
            outline: none;
            font-weight: 500;
        }
        .form-control:focus { background: white; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-light); }
        .form-control[readonly] { background: #F1F5F9; color: var(--text-secondary); cursor: default; }

        .form-row { display: flex; gap: 16px; margin-bottom: 16px; }
        .section-divider { margin: 28px 0; border: none; border-top: 1.5px dashed var(--border); }

        .section-title {
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .section-title svg { color: var(--accent); }

        /* ─── Search ─── */
        .search-container { display: flex; gap: 10px; margin-bottom: 24px; }

        /* ─── Buttons ─── */
        .btn { padding: 11px 22px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--accent-dark)); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3); }
        .btn-save-invalid { background: #EF4444 !important; color: white !important; box-shadow: none !important; transform: none !important; opacity: 0.8; cursor: not-allowed; }

        /* ─── Table ─── */
        .history-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .history-table th { text-align: left; padding: 10px 16px; background: linear-gradient(to bottom, #FAFBFC, #F6F8FA); color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
        .history-table td { padding: 12px 16px; border-bottom: 1px solid #F1F5F9; font-size: 13px; }
        .history-table tr:hover { background: rgba(99, 102, 241, 0.03); }
        .history-table tr { cursor: pointer; transition: background 0.15s; }

        .badge { background: var(--primary-light); color: var(--primary); padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }

        /* ─── Layout Grid ─── */
        .layout-grid { display: grid; grid-template-columns: 380px 1fr; gap: 24px; align-items: start; }
        .left-column { display: flex; flex-direction: column; gap: 24px; }
        .right-column { display: flex; flex-direction: column; gap: 24px; }

        /* ─── Collapsible ─── */
        .collapsible-header { display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
        .collapsible-content { transition: max-height 0.3s ease-out, opacity 0.3s ease-out; max-height: 1000px; opacity: 1; overflow: hidden; }
        .collapsible-content.collapsed { max-height: 0; opacity: 0; }
        .toggle-icon { transition: transform 0.3s ease; }
        .toggle-icon.rotated { transform: rotate(-90deg); }

        /* ─── User Profile (legacy, kept for header fallback) ─── */
        .user-profile { display: flex; align-items: center; gap: 12px; padding: 8px 16px; background: white; border: 1px solid var(--border); border-radius: 14px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .avatar { width: 32px; height: 32px; background: #E2E8F0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: var(--text-muted); }
        .user-info span { font-size: 13px; font-weight: 600; display: block; }

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

        /* ─── Modal ─── */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); z-index: 100; display: none; align-items: center; justify-content: center; padding: 20px; }
        .modal-content { background: white; width: 100%; max-width: 1000px; max-height: 90vh; border-radius: 24px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.3); animation: modalSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes modalSlideUp { from { transform: translateY(30px) scale(0.97); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
        .modal-header { padding: 20px 28px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #0F172A, #1E293B); }
        .modal-title { font-size: 16px; font-weight: 700; color: white; }
        .modal-body { padding: 28px; overflow-y: auto; flex-grow: 1; line-height: 1.7; color: var(--text-main); font-size: 14px; }
        .modal-footer { padding: 16px 28px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; background: #FAFBFC; }
        .close-modal { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); cursor: pointer; color: rgba(255,255,255,0.7); padding: 8px; border-radius: 10px; transition: all 0.2s; }
        .close-modal:hover { background: rgba(255,255,255,0.2); color: white; }

        .modal-section { margin-bottom: 28px; }
        .modal-section-title { font-weight: 700; font-size: 14px; color: var(--accent); margin-bottom: 16px; border-bottom: 2px solid var(--accent-light); padding-bottom: 8px; text-transform: uppercase; letter-spacing: 0.04em; }
        .modal-list { padding-left: 20px; list-style-type: decimal; }
        .modal-list li { margin-bottom: 10px; line-height: 1.7; }
        .modal-note { background: #FFFBEB; border-left: 4px solid #F59E0B; padding: 16px; border-radius: 12px; margin-top: 16px; font-style: italic; }
        .consent-info-box { background: var(--accent-light); padding: 24px; border-radius: 16px; margin-top: 24px; border: 1px dashed var(--accent); }

        /* ─── Agreement Checkbox ─── */
        .modal-agreement { margin: 24px 0; padding: 18px 20px; background: var(--accent-light); border: 1.5px dashed var(--accent); border-radius: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.2s; }
        .modal-agreement:hover { background: rgba(99, 102, 241, 0.12); }
        .modal-agreement input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; accent-color: var(--accent); }
        .modal-agreement label { font-weight: 600; color: var(--accent); cursor: pointer; user-select: none; margin-bottom: 0; font-size: 13px; }
        .btn-save-invalid::after { content: ' (Harap centang persetujuan)'; font-size: 0.8em; opacity: 0.9; }

        /* ─── Animations ─── */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeInUp 0.4s ease-out both; }

        /* ─── Mobile ─── */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; max-width: 100%; }
            .page-hero { padding: 80px 20px 50px; }
            .content-area { padding: 0 20px 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .layout-grid { grid-template-columns: 1fr; }
            .mobile-header { display: flex; position: fixed; top:0; left:0; right:0; height:64px; background:white; border-bottom:1px solid var(--border); align-items:center; padding: 0 20px; z-index:40; justify-content: space-between; }
            .hamburger { cursor:pointer; padding:8px; border-radius:8px; background:var(--bg-main); border:none; }
            .sidebar-overlay { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:45; }
            .sidebar-overlay.active { display:block; }
        }
        @media (min-width: 1025px) { .mobile-header { display: none; } }

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
        .telp-input-group .form-control {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            padding-left: 12px;
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <!-- Hero Header -->
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                <span class="current">General Consent RM 1</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>📝 General Consent RM 1</h1>
                    <p>Persetujuan umum untuk rawat inap / rawat jalan.</p>
                </div>
                <div class="user-chip">
                    <div class="avatar">{{ strtoupper(substr(Session::get('user_id', '?'), 0, 1)) }}</div>
                    <span>{{ Session::get('user_id') }}</span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
        <!-- Main Layout Grid -->
        <div class="layout-grid animate-in">
            <!-- Left Column: Search & History -->
            <div class="left-column">
                <div class="glass-card">
                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
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

            <!-- Right Column: Forms -->
            <div class="right-column">
                <!-- Input Form Section -->
                <div class="glass-card">
                    <div class="collapsible-header" id="biodata-toggle">
                        <div class="section-title" style="margin-bottom: 0;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Data Pernyataan Persetujuan (Biodata Pasien)
                        </div>
                        <svg class="toggle-icon" id="biodata-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>

                    <div class="collapsible-content" id="biodata-content" style="margin-top: 20px;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>No. Rawat</label>
                                <input type="text" id="field-no-rawat" class="form-control" placeholder="Pilih dari riwayat atau isi otomatis" readonly>
                            </div>
                            <div class="form-group">
                                <label>No. RM / Nama Pasien</label>
                                <div class="form-row">
                                    <input type="text" id="field-no-rm" class="form-control" style="width: 120px;" placeholder="No. RM" readonly>
                                    <input type="text" id="field-nm-pasien" class="form-control" placeholder="Nama Pasien" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tgl. Lahir</label>
                                <input type="text" id="field-tgl-lahir" class="form-control" placeholder="YYYY-MM-DD" readonly>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>No. KTP (NIK)</label>
                                <input type="text" id="field-no-ktp" class="form-control" placeholder="NIK Pasien" readonly>
                            </div>
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <input type="text" id="field-jk" class="form-control" placeholder="L/P" readonly>
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" id="field-agama" class="form-control" placeholder="Agama" readonly>
                            </div>
                        </div>

                        <div class="form-grid" style="grid-template-columns: 1fr 2fr;">
                            <div class="form-group">
                                <label>No. HP / Telp</label>
                                <input type="text" id="field-no-tlp" class="form-control" placeholder="No. Telp" readonly>
                            </div>
                            <div class="form-group">
                                <label>Alamat Pasien</label>
                                <input type="text" id="field-alamat" class="form-control" placeholder="Alamat lengkap" readonly>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Tanggal Periksa</label>
                                <input type="date" id="field-tgl-periksa" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Petugas</label>
                                <input type="text" class="form-control" value="{{ Session::get('user_id') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>No. Pernyataan</label>
                                <input type="text" id="field-no-pernyataan" class="form-control" value="PPU{{ date('YmdHis') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Penanggung Jawab Pasien
                    </div>

                    <div class="form-grid" style="grid-template-columns: 1fr;">
                        <div class="form-group">
                            <label>Nama Penanggung Jawab</label>
                            <input type="text" id="field-pj-nama" class="form-control" placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>Umur (th)</label>
                            <input type="number" id="field-pj-umur" class="form-control" placeholder="0" value="0">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Hubungan</label>
                            <select id="field-pj-hubungan" class="form-control">
                                <option value="Suami">Suami</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Ayah">Ayah</option>
                                <option value="Ibu">Ibu</option>
                                <option value="Saudara">Saudara</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select id="field-pj-jk" class="form-control">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telp / HP</label>
                            <div class="telp-input-group">
                                <div class="telp-prefix">+62</div>
                                <input type="text" id="field-pj-telp" class="form-control telp-input" placeholder="8xxxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="form-grid" style="display: none; grid-template-columns: 1fr 2fr;">
                        <div class="form-group">
                            <label>Nomor KTP</label>
                            <input type="text" id="field-pj-ktp" class="form-control" placeholder="NIK 16 digit" value="-">
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <input type="text" id="field-pj-alamat" class="form-control" placeholder="Alamat lengkap" value="-">
                        </div>
                    </div>

                    <div class="form-group" style="display: none;">
                        <label>Nilai Kepercayaan (Agama/Budaya dlm pengobatan)</label>
                        <input type="text" id="field-pj-kepercayaan" class="form-control" placeholder="Isi jika ada nilai kepercayaan khusus dalam pengobatan" value="-">
                    </div>

                    <div class="section-divider"></div>

                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Pelepasan Informasi (Pihak yang Berwenang)
                    </div>

                    <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="space-y-3">
                            <div class="form-group">
                                <label>Pihak Berwenang 1 (Nama)</label>
                                <input type="text" id="field-auth-name-1" class="form-control" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <label>No. HP / Telp 1</label>
                                <div class="telp-input-group">
                                    <div class="telp-prefix">+62</div>
                                    <input type="text" id="field-auth-telp-1" class="form-control telp-input" placeholder="8xxxxxxxxxx">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Pihak Berwenang 2 (Nama)</label>
                                <input type="text" id="field-auth-name-2" class="form-control" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <label>No. HP / Telp 2</label>
                                <div class="telp-input-group">
                                    <div class="telp-prefix">+62</div>
                                    <input type="text" id="field-auth-telp-2" class="form-control telp-input" placeholder="8xxxxxxxxxx">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="form-group">
                                <label>Pihak Berwenang 3 (Nama)</label>
                                <input type="text" id="field-auth-name-3" class="form-control" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <label>No. HP / Telp 3</label>
                                <div class="telp-input-group">
                                    <div class="telp-prefix">+62</div>
                                    <input type="text" id="field-auth-telp-3" class="form-control telp-input" placeholder="8xxxxxxxxxx">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Pihak Berwenang 4 (Nama)</label>
                                <input type="text" id="field-auth-name-4" class="form-control" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <label>No. HP / Telp 4</label>
                                <div class="telp-input-group">
                                    <div class="telp-prefix">+62</div>
                                    <input type="text" id="field-auth-telp-4" class="form-control telp-input" placeholder="8xxxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 24px;">
                        <label>Tanda Tangan Penanggung Jawab</label>
                        <div class="signature-preview-box" id="btn-open-signature">
                            <div class="signature-placeholder" id="signature-placeholder">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                <span>Klik untuk Tanda Tangan</span>
                            </div>
                            <img id="signature-preview" style="display: none;">
                        </div>
                    </div>

                    <div class="modal-agreement" id="agreement-box" style="margin-bottom: 24px;">
                        <input type="checkbox" id="check-consent-agree">
                        <label for="check-consent-agree">Saya telah membaca, memahami, dan menyetujui seluruh Ketentuan Rawat Inap & Persetujuan Umum di atas.</label>
                    </div>

                    <div style="margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="button" class="btn" id="btn-show-terms" style="background: var(--primary-light); color: var(--primary);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Lihat Ketentuan & Persetujuan
                        </button>
                        <button class="btn" style="background: #E2E8F0; color: var(--text-main);">Batal</button>
                        <button class="btn btn-primary" id="btn-save-consent">Simpan Pernyataan</button>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- /.content-area -->
    </div>

    <!-- General Consent Modal -->
    <div class="modal-overlay" id="terms-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">KETENTUAN RAWAT INAP DAN PERSETUJUAN UMUM (GENERAL CONSENT)</div>
                <button class="close-modal" id="close-terms-modal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 24px;">
                    <div style="font-weight: 700; font-size: 16px;">NO. PSU20260205001</div>
                    <div style="color: var(--text-muted);">Tanggal 05-02-2026</div>
                </div>

                <div class="modal-section">
                    <ol class="modal-list" style="list-style-type: decimal; padding-left: 20px;">
                        <li style="margin-bottom: 12px;"><strong>HAK DAN KEWAJIBAN SEBAGAI PASIEN</strong> : Dengan menandatangani dokumen ini saya mengakui bahwa pada proses pendaftaran untuk mendapatkan perawatan di RSUD Karsa Husada Batu telah mendapatkan informasi tentang hak-hak dan kewajiban saya sebagai pasien;</li>
                        <li style="margin-bottom: 12px;"><strong>PERSETUJUAN PELAYANAN KESEHATAN</strong> : Saya mengetahui bahwa saya memiliki kondisi yang membutuhkan perawatan medis, saya mengizinkan dokter dan profesional kesehatan lainnya untuk melakukan prosedur diagnostik dan untuk memberikan pengobatan medis seperti yang diperlukan dalam penilaian profesional mereka. Hal ini mencakup seluruh pemeriksaan dan prosedur diagnostik (kecuali yang membutuhkan persetujuan khusus/tertulis)</li>
                        <li style="margin-bottom: 12px;"><strong>AKSES INFORMASI KESEHATAN</strong> : Saya memberi kuasa kepada setiap dan seluruh orang yang merawat saya untuk memeriksa dan atau memberitahukan informasi kesehatan saya kepada pemberi kesehatan lain yang turut merawat saya selama di rumah sakit ini.</li>
                        <li style="margin-bottom: 12px;"><strong>RAHASIA KEDOKTERAN</strong> : Saya setuju RSUD Karsa Husada Batu untuk menjaga privasi dan kerahasiaan informasi medis saya baik untuk kepentingan perawatan dan pengobatan, pendidikan maupun penelitian.</li>
                        <li style="margin-bottom: 12px;"><strong>PELEPASAN INFORMASI</strong> : Saya setuju untuk membuka rahasia kedokteran terkait dengan kondisi kesehatan, asuhan, dan pengobatan yang saya terima kepada :
                            <ol style="list-style-type: lower-alpha; padding-left: 20px; margin-top: 8px;">
                                <li>Perusahaan asuransi kesehatan / perusahaan lainnya atau pihak lain yang menjamin pembiayaan saya;</li>
                                <li>Anggota keluarga saya / pihak yang berwenang :
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px;">
                                        <div>1) <span id="modal-auth-name-1">.................................</span> (No. HP : <span id="modal-auth-telp-1">.................</span>)</div>
                                        <div>3) <span id="modal-auth-name-3">.................................</span> (No. HP : <span id="modal-auth-telp-3">.................</span>)</div>
                                        <div>2) <span id="modal-auth-name-2">.................................</span> (No. HP : <span id="modal-auth-telp-2">.................</span>)</div>
                                        <div>4) <span id="modal-auth-name-4">.................................</span> (No. HP : <span id="modal-auth-telp-4">.................</span>)</div>
                                    </div>
                                </li>
                            </ol>
                        </li>
                        <li style="margin-bottom: 12px;"><strong>BARANG PRIBADI</strong> : Saya setuju untuk tidak membawa barang–barang berharga yang tidak diperlukan (seperti perhiasan, elektronik, dll) selama dalam perawatan di RSUD Karsa Husada Batu, dan saya menyetujui jika membawanya maka RSUD Karsa Husada Batu tidak bertanggung jawab terhadap kehilangan, kerusakan, atau pencurian.</li>
                        <li style="margin-bottom: 12px;"><strong>PENGAJUAN KELUHAN</strong> : Saya menyatakan bahwa saya telah menerima informasi tentang adanya tata cara mengajukan dan mengatasi keluhan terkait pelayanan medik yang diberikan terhadap diri saya. Saya Setuju untuk mengikuti tata cara mengajukan keluhan sesuai dengan prosedur yang ada.</li>
                        <li style="margin-bottom: 12px;"><strong>KEWAJIBAN PEMBAYARAN</strong> : Saya memahami tentang informasi biaya pengobatan atau biaya tindakan yang telah dijelaskan oleh Petugas RSUD Karsa Husada Batu. Saya bersedia membayar selisih biaya akibat kenaikan kelas diatas hak kepesertaan JKN, atas permintaan sendiri dan atau bersedia membayar seluruh biaya yang timbul akibat perawatan yang dianggap tidak layak klaim oleh JKN/asuransi kesehatan lainnya.</li>
                        <li style="margin-bottom: 12px;"><strong>RUMAH SAKIT PENDIDIKAN</strong> : Saya mengetahui bahwa RSUD Karsa Husada Batu merupakan rumah sakit pendidikan yang menjadi tempat praktik klinik bagi mahasiswa kedokteran dan profesi-profesi kesehatan lainnya, karena itu mereka mungkin berpartisipasi dan atau terlibat dalam perawatan saya dan saya menyetujui bahwa mereka berpartisipasi dalam perawatan saya sepanjang di bawah supervisi dokter penanggung jawab pasien.</li>
                        <li style="margin-bottom: 12px;"><strong>TATA TERTIB</strong> : Selama dalam perawatan saya dan keluarga akan mematuhi ketentuan yang telah dibuat oleh RSUD Karsa Husada Batu untuk selalu menjaga kebersihan dan ketertiban, mematuhi waktu jam berkunjung serta larangan merokok dan larangan mengambil dan menyimpan gambar/video dokumen dan aktivitas pelayanan selama di RSUD Karsa Husada Batu.</li>
                        <li style="margin-bottom: 12px;">Melalui dokumen ini, saya menegaskan kembali bahwa saya mempercayakan kepada semua tenaga kesehatan rumah sakit untuk memberikan perawatan, diagnostik dan terapi kepada saya sebagai pasien rawat inap atau rawat jalan atau Instalasi gawat darurat (IGD), termasuk semua pemeriksaan penunjang, yang dibutuhkan untuk pengobatan dan tindakan yang diperlukan.</li>
                    </ol>
                    <p style="margin-top: 20px; font-weight: 600;">Saya menyetujui setiap pernyataan yang terdapat pada formulir ini dan mendatangani tanpa paksaan dan dengan kesadaran penuh.</p>
                </div>

                <div class="consent-info-box">
                    <div style="font-weight: 700; margin-bottom: 12px; font-size: 15px; border-bottom: 1px solid var(--primary); padding-bottom: 4px;">IV. PERNYATAAN</div>
                    <p>Saya yang bertanda tangan dibawah ini :</p>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; margin: 12px 0;">
                        <div>Nama</div><div style="font-weight: 600;" id="modal-pj-nama">-</div>
                        <div style="display: none;">Umur / Jenis Kelamin</div><div id="modal-pj-info" style="display: none;">-</div>
                        <div style="display: none;">Nomor Identitas</div><div id="modal-pj-ktp" style="display: none;">-</div>
                        <div>Nomor Telp/HP</div><div id="modal-pj-telp">-</div>
                        <div>Bertindak Untuk</div><div style="font-weight: 600;" id="modal-pj-hubungan">-</div>
                    </div>
                    <p style="margin-top: 16px;">Dari pasien RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU dengan :</p>
                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; margin: 12px 0;">
                        <div>Nama Pasien</div><div style="font-weight: 600;" id="modal-pasien-nama">-</div>
                        <div>Nomor Rekam Medis</div><div id="modal-pasien-rm">-</div>
                        <div>Jenis Kelamin</div><div id="modal-pasien-jk">-</div>
                        <div>Tanggal Lahir</div><div id="modal-pasien-tgl-lahir">-</div>
                    </div>
                    <div class="modal-note">
                        Bahwa saya telah membaca, memahami dan mendapatkan penjelasan tentang ketentuan yang berlaku di RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU. Demikian pernyataan ini dibuat dalam keadaan penuh kesadaran dan tanpa paksaan dari pihak manapun.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="btn-close-modal-bottom">Tutup & Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Detail History Modal -->
    <div class="modal-overlay" id="detail-history-modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <div class="modal-title">Detail General Consent</div>
                <button class="close-modal" onclick="closeDetailModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="modal-body" id="detail-modal-body">
                <div class="grid grid-cols-2 gap-6">
                    <div class="modal-section">
                        <div class="modal-section-title">Data Pasien</div>
                        <div class="space-y-2">
                            <div class="flex justify-between border-b pb-1"><span class="text-muted">No. Rawat</span> <span id="det-no-rawat" class="font-semibold">-</span></div>
                            <div class="flex justify-between border-b pb-1"><span class="text-muted">No. RM</span> <span id="det-no-rm" class="font-semibold">-</span></div>
                            <div class="flex justify-between border-b pb-1"><span class="text-muted">Nama Pasien</span> <span id="det-nm-pasien" class="font-semibold">-</span></div>
                            <div class="flex justify-between border-b pb-1"><span class="text-muted">Tgl. Lahir</span> <span id="det-tgl-lahir">-</span></div>
                        </div>
                    </div>
                    <div class="modal-section">
                        <div class="modal-section-title">Penanggung Jawab</div>
                        <div class="space-y-2">
                            <div class="flex justify-between border-b pb-1"><span class="text-muted">Nama PJ</span> <span id="det-nm-pj" class="font-semibold">-</span></div>
                            <div class="flex justify-between border-b pb-1"><span class="text-muted">Hubungan</span> <span id="det-hubungan">-</span></div>
                            <div class="flex justify-between border-b pb-1" style="display: none;"><span class="text-muted">No. KTP</span> <span id="det-ktp-pj">-</span></div>
                            <div class="flex justify-between border-b pb-1"><span class="text-muted">No. Telp</span> <span id="det-telp-pj">-</span></div>
                        </div>
                    </div>
                </div>
                <div class="modal-section mt-4" style="display: none;">
                    <div class="modal-section-title">Informasi Tambahan</div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <div class="text-xs text-muted mb-1 font-bold uppercase">Nilai Kepercayaan</div>
                        <div id="det-kepercayaan" class="italic">-</div>
                    </div>
                </div>
                <div class="modal-section mt-4">
                    <div class="modal-section-title">Tanda Tangan</div>
                    <div class="flex justify-center p-4 bg-white border-2 border-dashed border-gray-200 rounded-2xl">
                        <img id="det-signature" src="" alt="Signature" style="max-height: 150px; display: none;">
                        <span id="det-no-signature" class="text-muted text-xs italic">Tidak ada tanda tangan</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>
    
    <!-- Signature Modal -->
    <div class="modal-overlay" id="signature-modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <div class="modal-title">Tanda Tangan Penanggung Jawab</div>
                <button class="close-modal" id="close-signature-modal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div class="signature-wrapper" style="border: 2px solid var(--border); border-radius: 14px; background: white; position: relative;">
                    <div class="signature-start-hint" id="signature-start-hint">
                        <span>START</span>
                        <span>HERE</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
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

    <script>
        // Patient Search Logic
        const btnSearch = document.getElementById('btn-search');
        const inputNoRm = document.getElementById('search-no-rm');
        const historyTableBody = document.getElementById('history-table-body');
 
        inputNoRm.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchPatient();
            }
        });
 
        async function searchPatient() {
            const noRm = inputNoRm.value;
            if (!noRm) return alert('Silakan masukkan No. RM');

            btnSearch.disabled = true;
            btnSearch.innerHTML = 'Mencari...';

            try {
                const response = await fetch(`/pasien/search?no_rm=${noRm}`);
                const result = await response.json();

                if (response.ok) {
                    const { pasien, history, latest_auth_parties } = result;

                    // Update Form Fields
                    document.getElementById('field-no-rm').value = pasien.no_rkm_medis;
                    document.getElementById('field-nm-pasien').value = pasien.nm_pasien;
                    document.getElementById('field-tgl-lahir').value = pasien.tgl_lahir;
                    document.getElementById('field-no-ktp').value = pasien.no_ktp || '-';
                    document.getElementById('field-jk').value = pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan';
                    document.getElementById('field-agama').value = pasien.agama || '-';
                    document.getElementById('field-no-tlp').value = pasien.no_tlp || '-';
                    document.getElementById('field-alamat').value = pasien.alamat || '-';

                    // Clear and Populate Authorized Parties
                    for (let i = 1; i <= 4; i++) {
                        document.getElementById(`field-auth-name-${i}`).value = '';
                        document.getElementById(`field-auth-telp-${i}`).value = '';
                    }

                    if (latest_auth_parties && latest_auth_parties.length > 0) {
                        latest_auth_parties.forEach((party, index) => {
                            if (index < 4) {
                                document.getElementById(`field-auth-name-${index + 1}`).value = party.nama || '';
                                // Strip 62 from phone number for display if it exists, as the prefix +62 is already there
                                let phone = party.no_telp || '';
                                if (phone.startsWith('62')) phone = phone.substring(2);
                                document.getElementById(`field-auth-telp-${index + 1}`).value = phone;
                            }
                        });
                    }

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

                        // Automatically select latest registration
                        document.getElementById('field-no-rawat').value = history[0].no_rawat;
                        document.getElementById('field-tgl-periksa').value = history[0].tgl_registrasi;
                        historyTableBody.children[0].style.background = 'var(--primary-light)';
                    } else {
                        historyTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Pasien ditemukan, tapi tidak ada riwayat rawat.</td></tr>';
                        document.getElementById('field-no-rawat').value = '';
                        document.getElementById('field-tgl-periksa').value = '';
                    }
                } else {
                    alert(result.error || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Search error:', error);
                alert('Gagal menghubungi server');
            } finally {
                btnSearch.disabled = false;
                btnSearch.innerHTML = 'Cari';
            }
        }

        btnSearch.addEventListener('click', searchPatient);

        // Initialize Signature Pad
        const signatureModal = document.getElementById('signature-modal');
        const btnOpenSignature = document.getElementById('btn-open-signature');
        const btnCloseSignatureModal = document.getElementById('close-signature-modal');
        const btnSaveSignatureModal = document.getElementById('save-signature-modal');
        const btnClearSignature = document.getElementById('clear-signature');
        const canvas = document.getElementById('signature-pad');
        const signaturePreview = document.getElementById('signature-preview');
        const signaturePlaceholder = document.getElementById('signature-placeholder');

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
            // Ensure signature pad is ready and focused
            setTimeout(() => {
                resizeCanvas();
                signaturePad.clear();
                // Visual feedback: show hint when empty
                document.getElementById('signature-start-hint').style.display = 'flex';
                // Pentab focus
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
                return alert('Silakan tanda tangan terlebih dahulu.');
            }
            
            const dataURL = signaturePad.toDataURL('image/png');
            signaturePreview.src = dataURL;
            signaturePreview.style.display = 'block';
            signaturePlaceholder.style.display = 'none';
            
            // Add a "locked" class or state to the preview box
            btnOpenSignature.style.borderStyle = 'solid';
            btnOpenSignature.style.borderColor = 'var(--primary)';
            
            closeSignatureModal();
        });

        signatureModal.addEventListener('click', (e) => {
            // "Locked" - prevent closing by clicking outside
            // Only allow closing via Close button or Save
        });

        // Modal Controls
        const termsModal = document.getElementById('terms-modal');
        const btnShowTerms = document.getElementById('btn-show-terms');
        const btnCloseModalX = document.getElementById('close-terms-modal');
        const btnCloseModalBottom = document.getElementById('btn-close-modal-bottom');

        function updateModalData() {
            // Patient Data
            document.getElementById('modal-pasien-nama').textContent = document.getElementById('field-nm-pasien').value || '-';
            document.getElementById('modal-pasien-rm').textContent = document.getElementById('field-no-rm').value || '-';
            document.getElementById('modal-pasien-jk').textContent = document.getElementById('field-jk').value || '-';
            document.getElementById('modal-pasien-tgl-lahir').textContent = document.getElementById('field-tgl-lahir').value || '-';

            // PJ Data
            document.getElementById('modal-pj-nama').textContent = document.getElementById('field-pj-nama').value || '-';
            const jk = document.getElementById('field-pj-jk').value;
            document.getElementById('modal-pj-info').textContent = `- / ${jk}`;
            document.getElementById('modal-pj-ktp').textContent = '-';
            document.getElementById('modal-pj-telp').textContent = document.getElementById('field-pj-telp').value || '-';
            document.getElementById('modal-pj-hubungan').textContent = document.getElementById('field-pj-hubungan').value || '-';
            // document.getElementById('modal-pj-hubungan-lite').textContent = document.getElementById('field-pj-hubungan').value || '-';
            // document.getElementById('modal-pj-kepercayaan').value = document.getElementById('field-pj-kepercayaan').value || '';

            // Auth Parties Data
            for (let i = 1; i <= 4; i++) {
                const name = document.getElementById(`field-auth-name-${i}`).value || '.................................';
                const telp = document.getElementById(`field-auth-telp-${i}`).value || '.................';
                document.getElementById(`modal-auth-name-${i}`).textContent = name;
                document.getElementById(`modal-auth-telp-${i}`).textContent = telp;
            }
        }

        btnShowTerms.addEventListener('click', () => {
            updateModalData();
            termsModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        [btnCloseModalX, btnCloseModalBottom].forEach(btn => {
            btn.addEventListener('click', () => {
                termsModal.style.display = 'none';
                document.body.style.overflow = '';
            });
        });

        // Validation Logic for Simpan Button
        const btnSave = document.getElementById('btn-save-consent');
        const fieldKepercayaan = document.getElementById('field-pj-kepercayaan');
        const checkAgree = document.getElementById('check-consent-agree');

        function validateConsent() {
            const isAgreed = checkAgree.checked;

            if (!isAgreed) {
                btnSave.classList.add('btn-save-invalid');
                btnSave.disabled = true;
            } else {
                btnSave.classList.remove('btn-save-invalid');
                btnSave.disabled = false;
            }
        }

        // Add listeners for validation
        fieldKepercayaan.addEventListener('input', validateConsent);
        checkAgree.addEventListener('change', validateConsent);

        // Initial validation call
        validateConsent();

        // Two-way sync for Nilai Kepercayaan removed as it's no longer in the modal text

        termsModal.addEventListener('click', (e) => {
            if (e.target === termsModal) {
                termsModal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        // Save Consent Logic
        btnSave.addEventListener('click', async () => {
            const signatureData = signaturePreview.src;
            if (!signatureData || signaturePreview.style.display === 'none') {
                return alert('Silakan tanda tangan terlebih dahulu.');
            }

            if (!checkAgree.checked) {
                return alert('Anda harus menyetujui ketentuan untuk menyimpan.');
            }

            const formData = {
                no_surat: document.getElementById('field-no-rawat').value + '_GC', // Temporary logic if no_pernyataan is not provided or just use field
                no_rawat: document.getElementById('field-no-rawat').value,
                no_rm: document.getElementById('field-no-rm').value,
                tanggal: document.getElementById('field-tgl-periksa').value,
                pengobatan_kepada: document.getElementById('field-pj-hubungan').value,
                nilai_kepercayaan: '-',
                nama_pj: document.getElementById('field-pj-nama').value,
                umur_pj: '-',
                no_ktppj: '-',
                jkpj: document.getElementById('field-pj-jk').value,
                bertindak_atas: document.getElementById('field-pj-hubungan').value,
                no_telp: document.getElementById('field-pj-telp').value,
                signature: signatureData,
                auth_name_1: document.getElementById('field-auth-name-1').value,
                auth_telp_1: document.getElementById('field-auth-telp-1').value,
                auth_name_2: document.getElementById('field-auth-name-2').value,
                auth_telp_2: document.getElementById('field-auth-telp-2').value,
                auth_name_3: document.getElementById('field-auth-name-3').value,
                auth_telp_3: document.getElementById('field-auth-telp-3').value,
                auth_name_4: document.getElementById('field-auth-name-4').value,
                auth_telp_4: document.getElementById('field-auth-telp-4').value,
            };

            // Override no_surat with actual No. Pernyataan from field if exists
            const noPernyataanField = document.getElementById('field-no-pernyataan');
            if (noPernyataanField) {
                formData.no_surat = noPernyataanField.value;
            }

            // Basic Validation
            if (!formData.no_rawat || !formData.nama_pj) {
                return alert('Mohon lengkapi data pasien dan penanggung jawab.');
            }

            btnSave.disabled = true;
            btnSave.innerHTML = '<svg class="animate-spin" style="width:18px; height:18px;" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';

            try {
                const response = await fetch('{{ route("general-consent.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    // Success Notification
                    const toast = document.createElement('div');
                    toast.style.cssText = `
                        position: fixed;
                        top: 24px;
                        right: 24px;
                        background: #10B981;
                        color: white;
                        padding: 16px 24px;
                        border-radius: 12px;
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                        z-index: 1000;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        font-weight: 600;
                        animation: toastSlideIn 0.3s ease-out;
                    `;
                    toast.innerHTML = `
                        <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        ${result.message}
                    `;
                    document.body.appendChild(toast);

                    // Add keyframe animation if not exists
                    if (!document.getElementById('toast-animation-style')) {
                        const style = document.createElement('style');
                        style.id = 'toast-animation-style';
                        style.innerHTML = `
                            @keyframes toastSlideIn {
                                from { transform: translateX(100%); opacity: 0; }
                                to { transform: translateX(0); opacity: 1; }
                            }
                            @keyframes toastSlideOut {
                                from { transform: translateX(0); opacity: 1; }
                                to { transform: translateX(100%); opacity: 0; }
                            }
                        `;
                        document.head.appendChild(style);
                    }

                    setTimeout(() => {
                        toast.style.animation = 'toastSlideOut 0.3s ease-in forwards';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);

                    // Reset signature preview
                    signaturePreview.src = '';
                    signaturePreview.style.display = 'none';
                    signaturePlaceholder.style.display = 'flex';
                    signaturePad.clear();
                } else {
                    alert(result.message || 'Gagal menyimpan data.');
                }
            } catch (error) {
                console.error('Save error:', error);
                alert('Terjadi kesalahan saat menghubungi server.');
            } finally {
                btnSave.disabled = false;
                btnSave.innerHTML = 'Simpan Pernyataan';
                validateConsent(); // Reset button state
            }
        });

        // Collapsible Logic
        const biodataToggle = document.getElementById('biodata-toggle');
        const biodataContent = document.getElementById('biodata-content');
        const biodataIcon = document.getElementById('biodata-icon');

        biodataToggle.addEventListener('click', () => {
            biodataContent.classList.toggle('collapsed');
            biodataIcon.classList.toggle('rotated');
        });

        // Detail Modal Functions
        function showDetail(noRawat) {
            const reg = window.historyData.find(h => h.no_rawat === noRawat);
            if (!reg || !reg.general_consent) return;

            const consent = reg.general_consent;
            const detModal = document.getElementById('detail-history-modal');

            // Set content
            document.getElementById('det-no-rawat').textContent = reg.no_rawat;
            document.getElementById('det-no-rm').textContent = reg.no_rkm_medis;
            document.getElementById('det-nm-pasien').textContent = document.getElementById('field-nm-pasien').value;
            document.getElementById('det-tgl-lahir').textContent = document.getElementById('field-tgl-lahir').value;
            
            document.getElementById('det-nm-pj').textContent = consent.nama_pj;
            document.getElementById('det-hubungan').textContent = consent.bertindak_atas;
            document.getElementById('det-ktp-pj').textContent = consent.no_ktppj;
            document.getElementById('det-telp-pj').textContent = consent.no_telp;
            
            document.getElementById('det-kepercayaan').textContent = consent.nilai_kepercayaan || '-';

            // Show Authorized Parties in Detail Body (if exist)
            const detailModalBody = document.getElementById('detail-modal-body');
            let authSection = document.getElementById('det-auth-section');
            if (!authSection) {
                authSection = document.createElement('div');
                authSection.id = 'det-auth-section';
                authSection.className = 'modal-section mt-4';
                authSection.innerHTML = `
                    <div class="modal-section-title">Pelepasan Informasi</div>
                    <div class="grid grid-cols-2 gap-4 text-sm bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                        <div id="det-auth-list-1"></div>
                        <div id="det-auth-list-2"></div>
                    </div>
                `;
                detailModalBody.insertBefore(authSection, document.querySelector('.modal-section.mt-4:last-of-type'));
            }

            const list1 = document.getElementById('det-auth-list-1');
            const list2 = document.getElementById('det-auth-list-2');
            list1.innerHTML = '';
            list2.innerHTML = '';

            for (let i = 1; i <= 4; i++) {
                const name = consent[`auth_name_${i}`];
                const telp = consent[`auth_telp_${i}`];
                if (name || telp) {
                    const item = `<div class="mb-2"><strong>${i})</strong> ${name || '-'} <br><span class="text-muted text-xs">HP: ${telp || '-'}</span></div>`;
                    if (i <= 2) list1.innerHTML += item;
                    else list2.innerHTML += item;
                }
            }

            if (!list1.innerHTML && !list2.innerHTML) {
                authSection.style.display = 'none';
            } else {
                authSection.style.display = 'block';
            }

            const sigImg = document.getElementById('det-signature');
            const noSig = document.getElementById('det-no-signature');
            
            if (reg.signature_pasien && reg.signature_pasien.signature_path) {
                sigImg.src = '/storage/' + reg.signature_pasien.signature_path;
                sigImg.style.display = 'block';
                noSig.style.display = 'none';
            } else {
                sigImg.style.display = 'none';
                noSig.style.display = 'block';
            }

            detModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detail-history-modal').style.display = 'none';
            document.body.overflow = '';
        }

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
    </script>
</body>
</html>
