<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Form Pernyataan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            --danger: #EF4444;
            --danger-light: rgba(239, 68, 68, 0.1);
            --warning: #F59E0B;
            --blue: #3B82F6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; }

        /* Mobile */
        .mobile-header { display: none; }
        .sidebar-overlay { display: none; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; }
        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
        .nav-item:hover { background: var(--bg-main); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item svg { width: 20px; height: 20px; }
        
        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; max-width: calc(100vw - var(--sidebar-width)); }
        
        .page-hero {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            padding: 32px 40px 52px;
            position: relative;
            overflow: hidden;
        }
        .hero-top { display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 1; }
        .hero-title h1 { font-size: 26px; font-weight: 800; color: white; letter-spacing: -0.75px; margin-bottom: 6px; }
        .hero-title p { color: rgba(255,255,255,0.5); font-size: 14px; }
        .hero-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 12px; color: rgba(255,255,255,0.4); }
        .hero-breadcrumb a { color: rgba(255,255,255,0.5); text-decoration: none; }
        .hero-breadcrumb .current { color: rgba(255,255,255,0.7); font-weight: 600; }
        .user-chip { display: flex; align-items: center; gap: 10px; padding: 8px 16px 8px 8px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); border-radius: 50px; backdrop-filter: blur(10px); }
        .user-chip .avatar { width: 32px; height: 32px; background: linear-gradient(135deg, var(--accent), var(--primary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; color: white; }
        .user-chip span { color: rgba(255,255,255,0.85); font-size: 13px; font-weight: 600; }

        .content-area { padding: 0 40px 40px; margin-top: -28px; position: relative; z-index: 2; }
        
        .glass-card {
            background: white;
            padding: 28px;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 10px 15px -3px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }

        .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em; }
        .form-control { width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; transition: all 0.2s; background: var(--bg-main); color: var(--text-main); }
        .form-control:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px var(--primary-light); }
        .form-control:read-only, .form-control:disabled { background: var(--border-light); color: var(--text-muted); cursor: not-allowed; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748B'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; background-size: 16px; padding-right: 40px; cursor: pointer; }
        
        .btn-primary { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; box-shadow: 0 4px 6px -1px var(--primary-light); }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-primary:disabled { background: var(--text-muted); cursor: not-allowed; transform: none; box-shadow: none; }
        
        .btn-secondary { background: white; color: var(--text-main); border: 1px solid var(--border); padding: 12px 24px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s; }
        .btn-secondary:hover { background: var(--bg-main); }

        /* Two column layout for patient search */
        .search-layout { display: grid; grid-template-columns: 350px 1fr; gap: 24px; align-items: start; margin-bottom: 24px; }
        .search-layout .glass-card { margin-bottom: 0; }
        
        /* History Table */
        .history-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .history-table th { background: var(--bg-main); padding: 12px; text-align: left; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; border-bottom: 2px solid var(--border); }
        .history-table td { padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px; }
        .history-table tbody tr { cursor: pointer; transition: background 0.2s; }
        .history-table tbody tr:hover { background: var(--bg-main); }
        .table-scroll-wrapper { max-height: 250px; overflow-y: auto; }
        .badge-blue { background: var(--accent-light); color: var(--accent); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }

        /* Signature Box */
        .signature-box { border: 2px dashed var(--border); border-radius: 12px; height: 120px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; background: #F8FAFC; position: relative; overflow: hidden; margin-bottom: 12px; }
        .signature-box:hover { border-color: var(--primary); background: var(--primary-light); }
        .signature-box.has-signature { border-style: solid; border-color: var(--primary); background: white; }
        .signature-placeholder { font-size: 13px; font-weight: 600; color: var(--text-muted); pointer-events: none; }
        
        .signature-clear { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: none; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; width: 100%; display: none; }
        .signature-clear:hover { background: var(--danger); color: white; }
        .signature-copy { background: rgba(59, 130, 246, 0.1); color: var(--primary); border: none; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; width: 100%; display: none; margin-top: 4px;}
        .signature-copy:hover { background: var(--primary); color: white; }
        .signature-label { font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px; display: block; text-align: center; }
        
        .signatures-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 24px; }

        /* Modal Overlay */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1000; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-content { background: white; border-radius: 24px; width: 100%; max-width: 600px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; transform: scale(0.95); transition: transform 0.3s; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .modal-title { font-size: 18px; font-weight: 700; color: var(--text-main); }
        .close-modal { background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; border-radius: 8px; transition: all 0.2s; }
        .close-modal:hover { background: var(--bg-main); color: var(--danger); }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); background: #F8FAFC; display: flex; justify-content: space-between; gap: 12px; }
        
        .modal-signature-pad { width: 100%; height: 300px; border: 2px dashed var(--border); border-radius: 12px; cursor: crosshair; touch-action: none; background: white; }

        /* Validation Styles */
        .is-invalid { border-color: var(--danger) !important; background-color: #FEF2F2 !important; }
        .invalid-feedback { color: var(--danger); font-size: 12px; margin-top: 6px; font-weight: 600; display: block; animation: fadeIn 0.3s ease; }
        .select2-container--default.is-invalid .select2-selection { border-color: var(--danger) !important; background-color: #FEF2F2 !important; }
        .signature-box.is-invalid { border-color: var(--danger) !important; background-color: #FEF2F2 !important; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
        /* Data Table */
        .points-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 16px; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .points-table th { background: #F8FAFC; padding: 16px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
        .points-table td { padding: 16px; font-size: 14px; border-bottom: 1px solid var(--border); vertical-align: top; }
        .points-table tr:last-child td { border-bottom: none; }
        
        .radio-group { display: flex; gap: 20px; align-items: center; margin-top: 8px; }
        .radio-label { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 500; }
        
        /* Layout Adjustments */
        .section-title { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: var(--accent); }

        /* Premium Select2 Styling Overrides */
        .select2-container--default .select2-selection--single {
            background-color: var(--bg-main) !important;
            border: 1px solid var(--border) !important;
            border-radius: 12px !important;
            height: auto !important;
            padding: 8px 12px !important;
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
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px var(--primary-light) !important;
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
            border: 1px solid var(--border) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
            margin-top: 4px !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--border) !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            outline: none !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary) !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary) !important;
            color: white !important;
        }
        .select2-container--default .select2-results__option {
            padding: 10px 14px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            color: var(--text-secondary) !important;
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-hero">
            <div class="hero-top">
                <div>
                    <div class="hero-breadcrumb">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="current">Form Pernyataan</span>
                    </div>
                    <div class="hero-title">
                        <h1>📄 Form Persetujuan / Penolakan Tindakan</h1>
                        <p>Kelola formulir RM 007 persetujuan atau penolakan tindakan medis.</p>
                    </div>
                </div>
                <div class="user-chip">
                    <div class="avatar">{{ substr(Session::get('user_id', 'U'), 0, 1) }}</div>
                    <span>{{ Session::get('user_id', 'User') }}</span>
                </div>
            </div>
        </div>

        <div class="content-area">
            <div class="search-layout">
                <!-- 1A. Cari Data Pasien (Left Column) -->
                <div class="glass-card" id="card-search">
                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Data Pasien
                    </div>
                    
                    <div style="display: flex; gap: 8px; margin-bottom: 20px;">
                        <input type="text" id="search-no-rm" class="form-control" placeholder="Masukkan No. Rekam Medis...">
                        <button type="button" id="btn-search-rm" class="btn-primary" style="padding: 12px 20px; background: var(--accent);">Cari</button>
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
                                    <td colspan="4" style="text-align:center;padding:20px;color:var(--text-muted);font-size:12px">
                                        Masukkan No. RM untuk melihat riwayat...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 1B. Data Pasien & Template (Right Column) -->
                <div class="glass-card" id="card-data-pasien">
                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Data Pasien & Pilihan Template
                    </div>
                    
                    <div class="form-grid" style="grid-template-columns: 1fr 2fr;">
                        <div class="form-group">
                            <label>No. RM</label>
                            <input type="text" id="field-no-rm" class="form-control" placeholder="No. RM" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Pasien</label>
                            <input type="text" id="field-nm-pasien" class="form-control" placeholder="Nama Pasien" readonly>
                        </div>
                    </div>
                    
                    <div class="form-grid" style="grid-template-columns: 1.5fr 1fr 1fr;">
                        <div class="form-group">
                            <label>No. Rawat</label>
                            <input type="text" id="no_rawat" name="no_rawat" class="form-control" placeholder="Pilih dari riwayat..." readonly form="form-pernyataan">
                        </div>
                        <div class="form-group">
                            <label>Tgl Registrasi</label>
                            <input type="text" id="tgl_registrasi" class="form-control" placeholder="Auto" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="text" id="tgl_lahir" class="form-control" placeholder="Tgl Lahir" readonly>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 12px; padding-top: 20px; border-top: 1px dashed var(--border);">
                        <label>Pilih Template Formulir</label>
                        <select id="select-template" name="template_id" class="form-control" onchange="loadTemplate(this.value)" form="form-pernyataan" required>
                            <option value="">-- Pilih Template Pernyataan --</option>
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl->kode_dokumen }}">{{ $tpl->judul_formulir }} ({{ $tpl->kode_dokumen }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <form id="form-pernyataan" novalidate onsubmit="savePernyataan(event)">
                <input type="hidden" name="no_pernyataan" id="no_pernyataan" value="{{ $editData ? $editData->no_pernyataan : '' }}">
                <!-- 2. Pemberian Informasi -->
                <div class="glass-card">
                    <div class="section-title">2. Pemberian Informasi</div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Dokter Penanggung Jawab</label>
                            <select id="kd_dokter" name="kd_dokter" class="form-control select2-dokter" style="width: 100%;" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pemberi Informasi</label>
                            <input type="text" class="form-control" value="{{ $loggedInUser ? $loggedInUser->nama : Session::get('user_id') }}" readonly style="background: #F8FAFC;">
                            <input type="hidden" id="pemberi_informasi_id" name="pemberi_informasi_id" value="{{ Session::get('user_id') }}">
                        </div>
                        <div class="form-group">
                            <label>Waktu Persetujuan</label>
                            <input type="datetime-local" id="waktu_persetujuan" name="waktu_persetujuan" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Penerima Informasi (Pasien/Keluarga)</label>
                            <input type="text" id="nama_penerima_informasi" name="nama_penerima_informasi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Hubungan Penerima dengan Pasien</label>
                            <select id="hubungan_penerima" name="hubungan_penerima" class="form-control select2-relation" required>
                                <option value="Diri Sendiri">Diri Sendiri</option>
                                <option value="Suami">Suami</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Ayah">Ayah</option>
                                <option value="Ibu">Ibu</option>
                                <option value="Saudara">Saudara</option>
                                <option value="Kakak">Kakak</option>
                                <option value="Adik">Adik</option>
                                <option value="Keponakan">Keponakan</option>
                                <option value="Cucu">Cucu</option>
                                <option value="Kakek">Kakek</option>
                                <option value="Nenek">Nenek</option>
                            </select>
                        </div>
                    </div>

                    <div id="template-points-container" style="display: none; margin-top: 24px;">
                        <label style="font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; display: block; text-transform: uppercase;">Rincian Informasi (Sesuai Template)</label>
                        <table class="points-table" id="points-table">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th width="250">Jenis Informasi</th>
                                    <th>Isi Informasi</th>
                                    <th width="100">Tanda (✓)</th>
                                </tr>
                            </thead>
                            <tbody id="points-tbody">
                                <!-- Injected by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Persetujuan / Penolakan -->
                <div class="glass-card">
                    <div class="section-title">3. Persetujuan / Penolakan Tindakan</div>
                    
                    <div class="form-group" style="margin-bottom: 24px; padding: 16px; background: #F8FAFC; border-radius: 12px; border: 1px solid var(--border);">
                        <label>Keputusan Pasien/Keluarga</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="keputusan" value="Setuju" checked onchange="toggleAlasan()">
                                <span style="color: var(--primary-dark);">Setuju Dilakukan Tindakan</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="keputusan" value="Tolak" onchange="toggleAlasan()">
                                <span style="color: var(--danger);">Menolak Dilakukan Tindakan</span>
                            </label>
                        </div>
                        
                        <!-- Descriptor Texts -->
                        <div id="desc-setuju" style="margin-top: 16px; font-size: 13px; color: var(--text-secondary); line-height: 1.5; border-left: 3px solid var(--primary); padding-left: 12px;">
                            Dengan ini menyatakan <strong>Persetujuan</strong> untuk dilakukannya Tindakan medis berupa <strong class="nama-tindakan">[Nama Tindakan]</strong>.<br><br>
                            Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul.<br>
                            Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.
                        </div>
                        <div id="desc-tolak" style="display: none; margin-top: 16px; font-size: 13px; color: var(--text-secondary); line-height: 1.5; border-left: 3px solid var(--danger); padding-left: 12px;">
                            Dengan ini menyatakan <strong>Penolakan</strong> untuk dilakukannya Tindakan medis berupa <strong class="nama-tindakan">[Nama Tindakan]</strong>.<br><br>
                            Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul.<br>
                            Saya bertanggung jawab secara penuh atas segala akibat yang mungkin timbul sebagai akibat tidak dilakukannya tindakan medis tersebut.
                        </div>
                    </div>

                    <div class="form-group" id="container-alasan" style="display: none;">
                        <label>Alasan Penolakan</label>
                        <textarea id="alasan_penolakan" name="alasan_penolakan" class="form-control" rows="3" placeholder="Sebutkan alasan penolakan..."></textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Yang Menyatakan</label>
                            <input type="text" id="nama_yang_menyatakan" name="nama_yang_menyatakan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Hubungan Yang Menyatakan</label>
                            <select id="hubungan_yang_menyatakan" name="hubungan_yang_menyatakan" class="form-control select2-relation" required>
                                <option value="Diri Sendiri">Diri Sendiri</option>
                                <option value="Suami">Suami</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Ayah">Ayah</option>
                                <option value="Ibu">Ibu</option>
                                <option value="Saudara">Saudara</option>
                                <option value="Kakak">Kakak</option>
                                <option value="Adik">Adik</option>
                                <option value="Keponakan">Keponakan</option>
                                <option value="Cucu">Cucu</option>
                                <option value="Kakek">Kakek</option>
                                <option value="Nenek">Nenek</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid" style="margin-top: 16px;">
                        <div class="form-group">
                            <label>Saksi I (Keluarga)</label>
                            <input type="text" id="nama_saksi_keluarga" name="nama_saksi_keluarga" class="form-control" placeholder="Nama Saksi Keluarga">
                        </div>
                        <div class="form-group">
                            <label>Saksi II (Pihak RS)</label>
                            <input type="text" id="saksi_rs" name="saksi_rs" class="form-control" placeholder="Nama Saksi Rumah Sakit">
                        </div>
                    </div>
                </div>

                <!-- 4. Tanda Tangan -->
                <div class="glass-card">
                    <div class="section-title">4. Tanda Tangan</div>
                    
                    <div class="signatures-grid">
                        <div class="signature-wrapper">
                            <span class="signature-label">Penerima Informasi</span>
                            <div class="signature-box" id="box_penerima" onclick="openSignatureModal('penerima')">
                                <span class="signature-placeholder" id="placeholder_penerima">Klik untuk Tanda Tangan</span>
                                <img id="img_penerima" style="display:none; max-width:100%; max-height:100%; object-fit:contain;" />
                            </div>
                            <button type="button" class="signature-clear" id="clear_penerima" onclick="clearSignature('penerima')">Hapus Tanda Tangan</button>
                            <button type="button" class="signature-copy" id="copy_penerima" onclick="copySignature()">Salin ke Yang Menyatakan</button>
                            <input type="hidden" name="path_ttd_penerima_informasi" id="path_ttd_penerima">
                        </div>
                        <div class="signature-wrapper">
                            <span class="signature-label">Yang Menyatakan (Pasien/Keluarga)</span>
                            <div class="signature-box" id="box_menyatakan" onclick="openSignatureModal('menyatakan')">
                                <span class="signature-placeholder" id="placeholder_menyatakan">Klik untuk Tanda Tangan</span>
                                <img id="img_menyatakan" style="display:none; max-width:100%; max-height:100%; object-fit:contain;" />
                            </div>
                            <button type="button" class="signature-clear" id="clear_menyatakan" onclick="clearSignature('menyatakan')">Hapus Tanda Tangan</button>
                            <input type="hidden" name="path_ttd_yang_menyatakan" id="path_ttd_menyatakan">
                        </div>
                        <div class="signature-wrapper">
                            <span class="signature-label">Saksi I (Keluarga)</span>
                            <div class="signature-box" id="box_saksi_keluarga" onclick="openSignatureModal('saksi_keluarga')">
                                <span class="signature-placeholder" id="placeholder_saksi_keluarga">Klik untuk Tanda Tangan</span>
                                <img id="img_saksi_keluarga" style="display:none; max-width:100%; max-height:100%; object-fit:contain;" />
                            </div>
                            <button type="button" class="signature-clear" id="clear_saksi_keluarga" onclick="clearSignature('saksi_keluarga')">Hapus Tanda Tangan</button>
                            <input type="hidden" name="path_ttd_saksi_keluarga" id="path_ttd_saksi_keluarga">
                        </div>
                        <div class="signature-wrapper">
                            <span class="signature-label">Saksi II (Rumah Sakit)</span>
                            <div style="margin-top: 16px;">
                                <input type="text" class="form-control" style="text-align: center; font-weight: 600; background: #F8FAFC;" value="{{ $loggedInUser ? $loggedInUser->nama : Session::get('user_id') }}" readonly>
                                <input type="hidden" name="saksi_rs" value="{{ Session::get('user_id') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="reset" class="btn-secondary">Reset Form</button>
                        <button type="submit" class="btn-primary" id="btn-save">Simpan Formulir</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Signature Modal -->
    <div class="modal-overlay" id="signature-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Tanda Tangan</div>
                <button type="button" class="close-modal" onclick="closeSignatureModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="modal-body">
                <canvas id="modal-signature-pad" class="modal-signature-pad"></canvas>
                <p style="margin-top:12px;font-size:13px;color:var(--text-muted);text-align:center;">Silakan goreskan tanda tangan Anda di dalam area di atas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="modalPad.clear()">Hapus Ulang</button>
                <button type="button" class="btn-primary" onclick="saveSignatureFromModal()">Simpan Tanda Tangan</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let modalPad;
        let currentSignatureTarget = null;
        
        // Init Signature Modal Pad
        window.addEventListener("load", () => {
            const canvas = document.getElementById('modal-signature-pad');
            modalPad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'black'
            });
        });

        function resizeModalCanvas() {
            const canvas = document.getElementById('modal-signature-pad');
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            modalPad.clear();
        }

        function openSignatureModal(targetId) {
            currentSignatureTarget = targetId;
            document.getElementById('signature-modal').classList.add('active');
            // Slight delay to allow CSS transition so offsetWidth is correct
            setTimeout(resizeModalCanvas, 50);
        }

        function closeSignatureModal() {
            document.getElementById('signature-modal').classList.remove('active');
            currentSignatureTarget = null;
        }

        function saveSignatureFromModal() {
            if(modalPad.isEmpty()) {
                alert('Tanda tangan masih kosong!');
                return;
            }
            if(currentSignatureTarget) {
                const dataUrl = modalPad.toDataURL('image/png');
                
                // Update UI
                const box = document.getElementById('box_' + currentSignatureTarget);
                const img = document.getElementById('img_' + currentSignatureTarget);
                const placeholder = document.getElementById('placeholder_' + currentSignatureTarget);
                const btnClear = document.getElementById('clear_' + currentSignatureTarget);
                const inputHidden = document.getElementById('path_ttd_' + currentSignatureTarget);
                
                img.src = dataUrl;
                img.style.display = 'block';
                placeholder.style.display = 'none';
                box.classList.add('has-signature');
                btnClear.style.display = 'block';
                
                // Save to hidden input for form submit
                inputHidden.value = dataUrl;
                
                if (currentSignatureTarget === 'penerima') {
                    document.getElementById('copy_penerima').style.display = 'block';
                }
            }
            closeSignatureModal();
        }

        function clearSignature(targetId) {
            document.getElementById('img_' + targetId).style.display = 'none';
            document.getElementById('img_' + targetId).src = '';
            document.getElementById('placeholder_' + targetId).style.display = 'block';
            document.getElementById('box_' + targetId).classList.remove('has-signature');
            document.getElementById('clear_' + targetId).style.display = 'none';
            document.getElementById('path_ttd_' + targetId).value = 'DELETE'; // mark for deletion in backend
            
            if (targetId === 'penerima') {
                document.getElementById('copy_penerima').style.display = 'none';
            }
        }

        function copySignature() {
            const srcInput = document.getElementById('path_ttd_penerima');
            const srcImg = document.getElementById('img_penerima');
            
            if(!srcInput.value) {
                Swal.fire({icon: 'warning', title: 'Oops', text: 'Tanda tangan Penerima Informasi masih kosong!'});
                return;
            }
            
            const dstInput = document.getElementById('path_ttd_menyatakan');
            const dstImg = document.getElementById('img_menyatakan');
            const dstBox = document.getElementById('box_menyatakan');
            const dstClear = document.getElementById('clear_menyatakan');
            const dstPlaceholder = document.getElementById('placeholder_menyatakan');
            
            dstInput.value = srcInput.value;
            dstImg.src = srcImg.src;
            dstImg.style.display = 'block';
            dstPlaceholder.style.display = 'none';
            dstBox.classList.add('has-signature');
            dstClear.style.display = 'block';
            
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Tanda tangan berhasil disalin ke kolom Yang Menyatakan.',
                timer: 1500,
                showConfirmButton: false
            });
        }

        $(document).ready(function() {
            $('#select-template').select2({
                placeholder: "-- Pilih Template Pernyataan --",
                allowClear: true,
                width: '100%'
            }).on('select2:select select2:clear', function(e) {
                // Trigger change event to fire loadTemplate
                loadTemplate(this.value);
            });
            
            // Auto-focus search input inside Select2
            $('#select-template').on('select2:open', function() {
                setTimeout(function() {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) searchField.focus();
                }, 50);
            });

            // Initialize select2 for relationships
            $('.select2-relation').select2({
                width: '100%'
            });

            // Initialize select2 for dokter
            $('.select2-dokter').select2({
                placeholder: "Cari Dokter...",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route("persetujuan-penolakan.search-dokter") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.kd_dokter,
                                    text: item.nm_dokter + ' (' + item.kd_dokter + ')'
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        // Set default datetime to now
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('waktu_persetujuan').value = now.toISOString().slice(0,16);

        // Search Patient
        document.getElementById('search-no-rm').addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('btn-search-rm').click();
            }
        });

        document.getElementById('btn-search-rm').addEventListener('click', async () => {
            const rm = document.getElementById('search-no-rm').value;
            const btn = document.getElementById('btn-search-rm');
            const tbody = document.getElementById('history-table-body');
            
            if(!rm) return alert('Masukkan No RM');
            if (rm.length < 5) return alert('No. RM harus minimal 5 karakter');
            
            btn.disabled = true;
            btn.innerHTML = 'Mencari...';

            try {
                const res = await fetch(`/pasien/search?no_rm=${rm}`);
                const result = await res.json();
                
                if(res.ok) {
                    const { pasien, history } = result;
                    document.getElementById('field-no-rm').value = pasien.no_rkm_medis;
                    document.getElementById('field-nm-pasien').value = pasien.nm_pasien;
                    document.getElementById('tgl_lahir').value = pasien.tgl_lahir;
                    
                    document.getElementById('nama_penerima_informasi').value = pasien.nm_pasien;
                    $('#hubungan_penerima').val('Diri Sendiri').trigger('change');
                    document.getElementById('nama_yang_menyatakan').value = pasien.nm_pasien;
                    $('#hubungan_yang_menyatakan').val('Diri Sendiri').trigger('change');

                    tbody.innerHTML = '';
                    if (history && history.length > 0) {
                        history.forEach((reg, i) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `<td>${i+1}</td><td>${reg.tgl_registrasi}</td><td>${reg.no_rawat}</td><td><span class="badge-blue">Pilih</span></td>`;
                            
                            row.addEventListener('click', () => {
                                document.getElementById('no_rawat').value = reg.no_rawat;
                                document.getElementById('tgl_registrasi').value = reg.tgl_registrasi;
                                
                                Array.from(tbody.children).forEach(r => r.style.background = '');
                                row.style.background = 'var(--primary-light)';
                            });
                            tbody.appendChild(row);
                        });
                        
                        // Select first by default
                        document.getElementById('no_rawat').value = history[0].no_rawat;
                        document.getElementById('tgl_registrasi').value = history[0].tgl_registrasi;
                        tbody.children[0].style.background = 'var(--primary-light)';

                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-muted);font-size:12px">Tidak ada riwayat rawat.</td></tr>';
                        document.getElementById('no_rawat').value = '';
                        document.getElementById('tgl_registrasi').value = '';
                    }

                } else {
                    alert('Pasien tidak ditemukan');
                }
            } catch(e) {
                alert('Gagal mencari pasien');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Cari';
            }
        });


        // Load Template
        async function loadTemplate(kode) {
            if(!kode) {
                document.getElementById('template-points-container').style.display = 'none';
                document.querySelectorAll('.nama-tindakan').forEach(el => el.textContent = '[Nama Tindakan]');
                return;
            }
            try {
                const res = await fetch(`/persetujuan-penolakan/template/${kode}`);
                const data = await res.json();
                const tbody = document.getElementById('points-tbody');
                tbody.innerHTML = '';
                
                // Update text placeholder
                document.querySelectorAll('.nama-tindakan').forEach(el => el.textContent = data.judul_formulir || '[Nama Tindakan]');

                if (data.details && data.details.length > 0) {
                    data.details.forEach((pt, idx) => {
                        const tr = document.createElement('tr');
                        const isEdit = pt.is_editable ? '' : 'readonly';
                        tr.innerHTML = `
                            <td>${pt.urutan}</td>
                            <td style="white-space: pre-wrap; vertical-align: top;"><input type="hidden" name="details[${idx}][jenis_informasi]" value="${pt.jenis_informasi ? pt.jenis_informasi.replace(/"/g, '&quot;') : ''}">${pt.jenis_informasi}</td>
                            <td>
                                <textarea name="details[${idx}][isi_informasi]" class="form-control" rows="2" ${isEdit}>${pt.isi_informasi || ''}</textarea>
                            </td>
                            <td style="text-align:center;">
                                <input type="checkbox" name="details[${idx}][is_checked]" value="1" style="width: 20px; height: 20px;" checked>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                    document.getElementById('template-points-container').style.display = 'block';
                } else {
                    document.getElementById('template-points-container').style.display = 'none';
                }
            } catch(e) {
                alert('Gagal mengambil template');
            }
        }

        // Toggle Alasan
        function toggleAlasan() {
            const val = document.querySelector('input[name="keputusan"]:checked').value;
            const container = document.getElementById('container-alasan');
            const descSetuju = document.getElementById('desc-setuju');
            const descTolak = document.getElementById('desc-tolak');
            
            if(val === 'Tolak') {
                container.style.display = 'block';
                descSetuju.style.display = 'none';
                descTolak.style.display = 'block';
                document.getElementById('alasan_penolakan').required = true;
            } else {
                container.style.display = 'none';
                descSetuju.style.display = 'block';
                descTolak.style.display = 'none';
                document.getElementById('alasan_penolakan').required = false;
            }
        }

        // Autocomplete Pegawai / Dokter
        $("#search_dokter").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('persetujuan-penolakan.search-pegawai') }}",
                    data: { q: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return { label: item.nama + ' (' + item.nik + ')', value: item.nama, id: item.nik };
                        }));
                    }
                });
            },
            select: function(event, ui) { document.getElementById("kd_dokter").value = ui.item.id; }
        });

        $("#search_pemberi").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('persetujuan-penolakan.search-pegawai') }}",
                    data: { q: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return { label: item.nama + ' (' + item.nik + ')', value: item.nama, id: item.nik };
                        }));
                    }
                });
            },
            select: function(event, ui) { document.getElementById("pemberi_informasi_id").value = ui.item.id; }
        });

        // Save Form
        async function savePernyataan(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btn-save');
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan...';

            try {
                const formData = new FormData(e.target);
                
                // Reset all validations
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                let firstInvalidEl = null;

                function showError(elementId, message, isSelect2 = false, isSignature = false) {
                    const el = document.getElementById(elementId);
                    if(!el) return;
                    
                    if (isSelect2) {
                        const container = el.nextElementSibling;
                        if(container) container.classList.add('is-invalid');
                        el.parentNode.insertAdjacentHTML('beforeend', `<div class="invalid-feedback">${message}</div>`);
                        if (!firstInvalidEl) firstInvalidEl = container || el;
                    } else if (isSignature) {
                        const box = document.getElementById('box_' + elementId);
                        if(box) box.classList.add('is-invalid');
                        const wrapper = document.getElementById('clear_' + elementId).parentNode;
                        wrapper.insertAdjacentHTML('beforeend', `<div class="invalid-feedback" style="text-align:center;">${message}</div>`);
                        if (!firstInvalidEl) firstInvalidEl = box || el;
                    } else {
                        el.classList.add('is-invalid');
                        el.parentNode.insertAdjacentHTML('beforeend', `<div class="invalid-feedback">${message}</div>`);
                        if (!firstInvalidEl) firstInvalidEl = el;
                    }
                }

                // Manual Validations
                const noRawat = document.getElementById('no_rawat').value;
                if(!noRawat) showError('no_rawat', 'Harap cari dan pilih riwayat rawat pasien terlebih dahulu!');
                
                const template = document.getElementById('select-template').value;
                if(!template) showError('select-template', 'Harap pilih template pernyataan!', true);
                
                const kdDokter = document.getElementById('kd_dokter').value;
                if(!kdDokter) showError('kd_dokter', 'Dokter Penanggung Jawab harus dipilih!', true);

                const penerima = document.getElementById('nama_penerima_informasi').value;
                if(!penerima) showError('nama_penerima_informasi', 'Nama Penerima Informasi harus diisi!');
                
                const menyatakan = document.getElementById('nama_yang_menyatakan').value;
                if(!menyatakan) showError('nama_yang_menyatakan', 'Nama Yang Menyatakan harus diisi!');
                
                const keputusan = document.querySelector('input[name="keputusan"]:checked').value;
                if(keputusan === 'Tolak') {
                    const alasan = document.getElementById('alasan_penolakan').value;
                    if(!alasan.trim()) showError('alasan_penolakan', 'Alasan Penolakan harus diisi!');
                }

                const ttdMenyatakan = document.getElementById('path_ttd_menyatakan').value;
                if(!ttdMenyatakan) showError('menyatakan', 'Tanda Tangan Yang Menyatakan harus diisi!', false, true);

                if (firstInvalidEl) {
                    firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    throw new Error('VALIDATION_ERROR');
                }

                formData.append('template_id', template);
                
                const res = await fetch("{{ route('persetujuan-penolakan.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const data = await res.json();
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Form Pernyataan Berhasil Disimpan!',
                        confirmButtonColor: 'var(--primary)'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                if (error.message !== 'VALIDATION_ERROR') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message
                    });
                }
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Simpan Formulir';
            }
        }
        const editData = @json($editData ?? null);
        if (editData) {
            document.getElementById('no_rawat').value = editData.no_rawat;
            
            // Auto search patient
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('no_rm')) {
                document.getElementById('search-no-rm').value = urlParams.get('no_rm');
                document.getElementById('btn-search-rm').click();
            }

            setTimeout(async () => {
                $('#select-template').val(editData.template_id).trigger('change.select2'); // update visually without triggering change event
                
                await loadTemplate(editData.template_id);
                
                // set details
                if(editData.details) {
                    editData.details.forEach(d => {
                        const rows = Array.from(document.querySelectorAll('#points-tbody tr'));
                        const row = rows.find(r => {
                            // Strip all whitespace/newlines for a robust comparison
                            const textInDom = r.cells[1].textContent.replace(/\s+/g, '');
                            const textInDb = d.jenis_informasi.replace(/\s+/g, '');
                            return textInDom.includes(textInDb) || textInDb.includes(textInDom);
                        });
                        
                        if(row) {
                            row.querySelector('textarea').value = d.isi_informasi;
                            row.querySelector('input[type="checkbox"]').checked = d.is_checked == 1;
                        }
                    });
                }

                if (editData.dokter) {
                    $('#kd_dokter').append(new Option(editData.dokter.nm_dokter, editData.kd_dokter, true, true)).trigger('change');
                }
                if (editData.pemberi_informasi) {
                    $('#pemberi_informasi_id').append(new Option(editData.pemberi_informasi.nama, editData.pemberi_informasi_id, true, true)).trigger('change');
                }
                
                document.getElementById('nama_penerima_informasi').value = editData.nama_penerima_informasi;
                $('#hubungan_penerima').val(editData.hubungan_penerima).trigger('change');
                
                document.getElementById('nama_yang_menyatakan').value = editData.nama_yang_menyatakan;
                $('#hubungan_yang_menyatakan').val(editData.hubungan_yang_menyatakan).trigger('change');
                
                const keputusanFormatted = editData.keputusan.toLowerCase() === 'tolak' ? 'Tolak' : 'Setuju';
                const radio = document.querySelector(`input[name="keputusan"][value="${keputusanFormatted}"]`);
                if (radio) {
                    radio.checked = true;
                    toggleAlasan();
                }
                
                document.getElementById('alasan_penolakan').value = editData.alasan_penolakan || '';
                document.getElementById('waktu_persetujuan').value = editData.waktu_persetujuan.slice(0, 16);
                document.getElementById('nama_saksi_keluarga').value = editData.nama_saksi_keluarga || '';
                
                if(editData.saksi_rs_pegawai) {
                    $('#saksi_rs').append(new Option(editData.saksi_rs_pegawai.nama, editData.saksi_rs, true, true)).trigger('change');
                }

                // Signatures
                if (editData.base64_ttd_penerima) {
                    document.getElementById('img_penerima').src = editData.base64_ttd_penerima;
                    document.getElementById('img_penerima').style.display = 'block';
                    document.getElementById('placeholder_penerima').style.display = 'none';
                    document.getElementById('clear_penerima').style.display = 'block';
                    document.getElementById('box_penerima').classList.add('has-signature');
                    document.getElementById('copy_penerima').style.display = 'block';
                }
                
                if (editData.base64_ttd_menyatakan) {
                    document.getElementById('img_menyatakan').src = editData.base64_ttd_menyatakan;
                    document.getElementById('img_menyatakan').style.display = 'block';
                    document.getElementById('placeholder_menyatakan').style.display = 'none';
                    document.getElementById('clear_menyatakan').style.display = 'block';
                    document.getElementById('box_menyatakan').classList.add('has-signature');
                }
                
                if (editData.base64_ttd_saksi) {
                    document.getElementById('img_saksi_keluarga').src = editData.base64_ttd_saksi;
                    document.getElementById('img_saksi_keluarga').style.display = 'block';
                    document.getElementById('placeholder_saksi_keluarga').style.display = 'none';
                    document.getElementById('clear_saksi_keluarga').style.display = 'block';
                    document.getElementById('box_saksi_keluarga').classList.add('has-signature');
                }
            }, 1000);
        }
    </script>
</body>
</html>
