<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Hasil Laboratorium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
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
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-secondary: #334155;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; }

        .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; transition: transform 0.3s ease; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; flex-shrink: 0; }
        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; overflow-y: auto; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
        .nav-item:hover { background: var(--bg-main); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item svg { width: 20px; height: 20px; }
        .btn-logout { display: none; } 
        
        .mobile-header { display: none; }
        .sidebar-overlay { display: none; }

        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; max-width: calc(100vw - var(--sidebar-width)); min-height: 100vh; display: flex; flex-direction: column; }

        .page-hero { background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%); padding: 40px 40px 60px; position: relative; overflow: hidden; color: white; }
        .hero-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 12px; color: rgba(255,255,255,0.5); position: relative; z-index: 1; }
        .hero-title h1 { font-size: 26px; font-weight: 800; letter-spacing: -0.75px; margin-bottom: 6px; position: relative; z-index: 1; }

        .content-area { padding: 0 40px 40px; margin-top: -28px; position: relative; z-index: 2; flex: 1; }

        .card { background: white; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); margin-bottom: 24px; overflow: hidden; }
        .card-header { display: flex; align-items: center; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); }
        .card-icon { width: 40px; height: 40px; background: var(--accent-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--accent); }
        .card-title { font-size: 16px; font-weight: 700; color: var(--text-main); }
        .card-body { padding: 24px; }

        .search-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em; }
        .form-control { width: 100%; height: 44px; background: #F8FAFC; border: 1.5px solid var(--border); border-radius: 12px; padding: 0 16px; font-size: 14px; transition: all 0.2s; outline: none; }
        .form-control:focus { background: white; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-light); }
        
        .search-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid var(--border-light); }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; text-decoration: none; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-light { background: white; color: var(--text-secondary); border: 1.5px solid var(--border); }

        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: #F8FAFC; padding: 12px 24px; text-align: left; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .table td { padding: 16px 24px; border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        .table tr:hover { background: #FAFBFF; }

        .patient-info { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: 14px; background: #6366F1; }
        .patient-name { font-weight: 600; font-size: 14px; color: var(--text-main); }
        .patient-rm { font-size: 12px; color: var(--text-muted); font-family: monospace; }
        
        .badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; }
        .badge-lab { background: var(--primary-light); color: var(--primary); }

        .btn-detail { color: var(--accent); background: var(--accent-light); padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-detail:hover { background: var(--accent); color: white; }

        /* ─── Modal Styles ─── */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-container { background: white; width: 90%; max-width: 900px; max-height: 85vh; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; display: flex; flex-direction: column; transform: scale(0.95); transition: transform 0.3s ease; }
        .modal-overlay.active .modal-container { transform: scale(1); }
        .modal-header { padding: 24px 32px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #F8FAFC; }
        .modal-title-box h2 { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
        .modal-title-box p { font-size: 13px; color: var(--text-muted); }
        .btn-close { width: 36px; height: 36px; border-radius: 10px; border: none; background: #F1F5F9; color: var(--text-muted); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
        .btn-close:hover { background: #E2E8F0; color: #EF4444; }
        .modal-body { padding: 32px; overflow-y: auto; flex: 1; }
        .modal-footer { padding: 20px 32px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; background: #F8FAFC; }

        .modal-info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px; padding: 20px; background: #F8FAFC; border-radius: 16px; border: 1px solid var(--border); }
        .m-info-item label { display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; }
        .m-info-item div { font-size: 14px; font-weight: 600; color: var(--text-main); }

        .m-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .m-table th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; border-bottom: 2px solid var(--border); }
        .m-table td { padding: 12px 16px; border-bottom: 1px solid var(--border-light); font-size: 14px; }
        
        /* ─── Pagination Styles ─── */
        .pagination-container { display: flex; flex-direction: column; align-items: center; gap: 15px; border-top: 1px solid var(--border-light); }
        .pagination { display: flex; list-style: none; gap: 6px; padding: 0; margin: 0; }
        .page-item { display: inline-block; }
        .page-link { display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px; border-radius: 10px; background: white; border: 1.5px solid var(--border); color: var(--text-secondary); text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s; }
        .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: white; }
        .page-item.disabled .page-link { opacity: 0.5; cursor: not-allowed; background: #F8FAFC; color: var(--text-muted); }
        .page-link:hover:not(.active):not(.disabled) { background: var(--bg-main); border-color: var(--text-muted); color: var(--text-main); }
        .pagination-summary { font-size: 12px; color: var(--text-muted); font-weight: 500; }

        .m-val { font-weight: 700; }
        .m-flag-H { color: #EF4444; }
        .m-flag-L { color: #F59E0B; }
        .m-flag-N { color: var(--primary); }

        .animate-up { animation: slideUp 0.4s ease-out forwards; opacity: 0; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        #loading-overlay { display: none; position: absolute; inset: 0; background: rgba(255,255,255,0.8); z-index: 10; align-items: center; justify-content: center; }
        .spinner { width: 40px; height: 40px; border: 4px solid var(--border); border-top-color: var(--accent); border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Custom Select2 styling for wa-number-select */
        .modal-footer .select2-container {
            width: 180px !important;
        }
        .modal-footer .select2-container--default .select2-selection--single {
            height: 36px;
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 0 8px;
            font-size: 13px;
            color: var(--text-secondary);
            outline: none;
        }
        .modal-footer .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            color: var(--text-secondary);
            font-weight: 500;
        }
        .modal-footer .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px;
        }
    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>/</span>
                <span>Laboratorium</span>
                <span>/</span>
                <span class="current">Hasil Lab</span>
            </div>
            <div class="hero-title">
                <h1>🧪 Hasil Laboratorium (LIS)</h1>
            </div>
        </div>

        <div class="content-area">
            <div class="card animate-up" style="animation-delay: 0.1s;">
                <div class="card-header">
                    <div class="card-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                    <div class="card-title">Filter Pencarian (LIS)</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('hasil-lab.index') }}" method="GET">
                        <div class="search-grid">
                            <div class="form-group">
                                <label>Cari Nama / PID / ONO</label>
                                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Ketik kunci pencarian...">
                            </div>
                            <div class="form-group">
                                <label>Ruangan</label>
                                <select name="bangsal" class="form-control">
                                    <option value="">-- Semua Ruangan --</option>
                                    @foreach($bangsalList as $b)
                                        <option value="{{ $b->kd_bangsal }}" {{ $bangsal == $b->kd_bangsal ? 'selected' : '' }}>{{ $b->nm_bangsal }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <input type="date" name="tgl_akhir" value="{{ $tglAkhir }}" class="form-control">
                            </div>
                        </div>
                        <div class="search-actions">
                            @if($searched) <a href="{{ route('hasil-lab.index') }}" class="btn btn-light">Reset</a> @endif
                            <button type="submit" class="btn btn-primary">Cari Data</button>
                        </div>
                    </form>
                </div>
            </div>

            @if($searched)
            <div class="card animate-up" style="animation-delay: 0.2s;">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pasien</th>
                                <th>Order No (ONO)</th>
                                <th>Pemeriksaan</th>
                                <th>Waktu Order</th>
                                <th>Ruangan</th>
                                <th>Dokter Pengirim</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $row)
                            <tr>
                                <td>
                                    <div class="patient-info">
                                        <div class="avatar">{{ strtoupper(substr($row->PNAME ?? '?', 0, 1)) }}</div>
                                        <div>
                                            <div class="patient-name">{{ $row->PNAME ?? '-' }}</div>
                                            <div class="patient-rm">{{ $row->PID ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span style="font-family: monospace; font-size: 13px;">{{ $row->ONO }}</span></td>
                                <td>
                                    <div style="font-weight: 600; font-size: 14px;">{{ $row->nm_perawatan }}</div>
                                    @if($row->VISITNO) <span class="badge badge-lab">VISIT: {{ $row->VISITNO }}</span> @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($row->REQUEST_DT)->format('d M Y') }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ \Carbon\Carbon::parse($row->REQUEST_DT)->format('H:i') }} WIB</div>
                                </td>
                                <td style="font-size: 13px;">{{ $row->ROOM }}</td>
                                <td style="font-size: 13px;">{{ $row->CLINICIAN_NM }}</td>
                                <td style="text-align: center;">
                                    <button onclick="showDetail('{{ $row->ONO }}')" class="btn-detail">Lihat Detail</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" style="text-align: center; padding: 80px 0; color: var(--text-muted);">Data tidak ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($results->hasPages()) 
                <div class="pagination-container" style="padding: 24px;">
                    {{ $results->links('pagination::bootstrap-4') }}
                </div> 
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-container">
            <div id="loading-overlay"><div class="spinner"></div></div>
            <div class="modal-header">
                <div class="modal-title-box">
                    <h2 id="m-pasien-name">Detail Hasil Laboratorium</h2>
                    <p id="m-pemeriksaan-name">Memuat data...</p>
                </div>
                <button class="btn-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-info-grid">
                    <div class="m-info-item"><label>No. RM (PID)</label><div id="m-pid">-</div></div>
                    <div class="m-info-item"><label>Order No (ONO)</label><div id="m-ono">-</div></div>
                    <div class="m-info-item"><label>Ruangan</label><div id="m-room">-</div></div>
                    <div class="m-info-item"><label>Dokter Pengirim</label><div id="m-dokter">-</div></div>
                    <div class="m-info-item"><label>Waktu Order</label><div id="m-waktu">-</div></div>
                </div>
                <div class="table-responsive">
                    <table class="m-table">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Hasil</th>
                                <th>Satuan</th>
                                <th>Nilai Rujukan</th>
                                <th>Flag</th>
                            </tr>
                        </thead>
                        <tbody id="m-details-body">
                            <!-- Data injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="flex-wrap: wrap; gap: 10px; justify-content: flex-end;">
                <div style="flex-basis: 100%; display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 10px; border-radius: 10px; border: 1px solid var(--border); margin-bottom: 5px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="20" height="20" fill="#25D366" viewBox="0 0 24 24"><path d="M12.031 6.172c-2.32 0-4.525.903-6.163 2.541-3.398 3.398-3.398 8.927 0 12.324 1.547 1.547 3.602 2.45 5.787 2.535l.376.002c1.784 0 3.448-.518 4.859-1.411l.436-.276 3.407.893-1.071-3.322.239-.381c.883-1.412 1.35-3.048 1.35-4.744 0-4.838-3.937-8.775-8.775-8.775zm5.066 12.435c-.17.272-.823.504-1.136.54-.251.028-.578.046-.865.046-.432 0-.916-.046-1.55-.262-2.316-.782-4.14-2.836-5.184-4.845-.316-.607-.549-1.286-.549-1.996 0-.814.364-1.42.748-1.854.195-.219.467-.282.65-.282.164 0 .328.005.464.01.182.007.354-.038.528.223.18.27.616 1.499.669 1.611.053.111.088.241.015.388-.073.148-.11.24-.22.373-.11.133-.23.298-.328.4-.11.115-.226.241-.097.46.128.219.569.94 1.22 1.52.838.747 1.542.98 1.761 1.095.219.115.347.097.476-.051.129-.148.551-.64.698-.86.147-.219.294-.185.494-.111.2.074 1.27.599 1.489.71.22.111.366.166.421.259.053.093.053.54-.117.812zM21.3 2.7C19.1 0.5 16.1 -0.5 13.2 0.2c-2.9.7-5.4 2.6-6.9 5.2-1.5 2.6-1.9 5.6-1.1 8.5L2.3 22l8.3-2.2c2.2.9 4.6 1.3 7 1.2 2.4 0 4.7-.6 6.7-1.8 4.6-2.6 6.8-8.1 5.3-13.1-1.1-3.4-3.5-6.1-6.7-7.4z"/></svg>
                        <span style="font-size: 13px; font-weight: 600; color: var(--text-secondary);">No. WhatsApp:</span>
                    </div>
                    <div style="display: flex; align-items: center; flex-grow: 1; background: white; border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
                        <span style="padding: 0 10px; background: #f1f5f9; color: #475569; font-size: 14px; font-weight: 600; border-right: 1px solid var(--border); height: 36px; line-height: 36px;">+62</span>
                        <input type="text" id="wa-number" class="form-control" style="border: none; flex-grow: 1; height: 34px; padding: 0 12px; font-size: 14px; outline: none;" placeholder="8xxxxxxxxxx">
                    </div>
                    <select id="wa-number-select" class="form-control" style="width: auto; max-width: 180px; height: 36px; font-size: 13px; padding: 0 8px; border-radius: 8px; border: 1px solid var(--border); cursor: pointer; color: var(--text-secondary);">
                        <option value="">-- Pilih No. Telp --</option>
                    </select>
                </div>
                <button class="btn btn-light" type="button" onclick="closeModal()">Tutup</button>
                <a id="btn-preview-pdf" href="#" target="_blank" class="btn btn-accent" style="background: var(--accent); color: white; border: none; padding: 10px 20px; border-radius: 10px; display: flex; align-items: center; text-decoration: none; font-weight: 500; font-size: 14px; transition: all 0.2s;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Print Preview
                </a>
                <button id="btn-send-wa" type="button" onclick="sendToWA()" class="btn btn-primary" style="background: #10B981; color: white; border: none; padding: 10px 20px; border-radius: 10px; display: flex; align-items: center; font-weight: 500; font-size: 14px; transition: all 0.2s; cursor: pointer;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span id="wa-btn-text">Kirim WhatsApp</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDetail(ono) {
            const modal = document.getElementById('detailModal');
            const loader = document.getElementById('loading-overlay');
            const tbody = document.getElementById('m-details-body');
            
            modal.classList.add('active');
            loader.style.display = 'flex';
            tbody.innerHTML = '';
            
            
            // Set PDF preview link
            document.getElementById('btn-preview-pdf').href = `/hasil-lab/pdf/${ono}`;
            document.getElementById('detailModal').dataset.ono = ono;
            document.getElementById('wa-number').value = ''; // Reset first

            fetch(`/hasil-lab/json/${ono}`)
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';
                    
                    // Populate Header
                    document.getElementById('m-pasien-name').innerText = data.header.PNAME;
                    document.getElementById('m-pemeriksaan-name').innerText = data.header.ORDER_TESNM;
                    document.getElementById('m-pid').innerText = data.header.PID;
                    document.getElementById('m-ono').innerText = data.header.ONO;
                    document.getElementById('m-room').innerText = data.header.ROOM || '-';
                    document.getElementById('m-dokter').innerText = data.header.CLINICIAN_NM || '-';
                    document.getElementById('m-waktu').innerText = data.header.REQUEST_DT;
                    
                    // Set WA Number
                    const waInput = document.getElementById('wa-number');
                    let phone = data.header.no_tlp;
                    if (phone && phone !== '0' && phone !== '-') {
                        // Remove prefix if already there for the display
                        if (phone.startsWith('62')) phone = phone.substring(2);
                        else if (phone.startsWith('0')) phone = phone.substring(1);
                        waInput.value = phone;
                    } else {
                        waInput.value = '';
                    }

                    // Populate WA Number Select Options
                    const selectEl = document.getElementById('wa-number-select');
                    selectEl.innerHTML = '<option value="">-- Pilih No. Telp --</option>';

                    if (data.header.no_tlp && data.header.no_tlp !== '0' && data.header.no_tlp !== '-') {
                        let cleanPatientPhone = data.header.no_tlp;
                        if (cleanPatientPhone.startsWith('0')) {
                            cleanPatientPhone = '+62' + cleanPatientPhone.substring(1);
                        } else if (cleanPatientPhone.startsWith('62')) {
                            cleanPatientPhone = '+' + cleanPatientPhone;
                        } else if (!cleanPatientPhone.startsWith('+')) {
                            cleanPatientPhone = '+62' + cleanPatientPhone;
                        }

                        const opt = document.createElement('option');
                        opt.value = cleanPatientPhone;
                        opt.innerText = `No. Pasien (${cleanPatientPhone})`;
                        selectEl.appendChild(opt);
                    }

                    if (data.phone_list && data.phone_list.length > 0) {
                        data.phone_list.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.no_telp;
                            opt.innerText = `${item.nama} (${item.no_telp})`;
                            selectEl.appendChild(opt);
                        });
                    }

                    // Initialize Select2 on modal open
                    $('#wa-number-select').select2({
                        dropdownParent: $('#detailModal')
                    }).val(null).trigger('change.select2'); // Reset selection view

                    // Calculate Age
                    if (data.header.BIRTH_DT) {
                        const birth = new Date(data.header.BIRTH_DT);
                        const request = new Date(data.header.REQUEST_DT);
                        let years = request.getFullYear() - birth.getFullYear();
                        let months = request.getMonth() - birth.getMonth();
                        let days = request.getDate() - birth.getDate();

                        if (days < 0) {
                            months--;
                            days += new Date(request.getFullYear(), request.getMonth(), 0).getDate();
                        }
                        if (months < 0) {
                            years--;
                            months += 12;
                        }
                        document.getElementById('m-pemeriksaan-name').innerText = `${data.header.ORDER_TESNM} (${years} th ${months} bln ${days} hr)`;
                    }

                    // Populate Details
                    data.details.forEach(item => {
                        const flagClass = item.FLAG ? `m-flag-${item.FLAG.toUpperCase()}` : '';
                        const row = `
                            <tr>
                                <td style="font-weight:600">${item.TEST_NM}</td>
                                <td class="m-val ${flagClass}">${item.RESULT_VALUE || '-'}</td>
                                <td style="color:var(--text-muted)">${item.UNIT || '-'}</td>
                                <td style="font-size:12px; color:var(--text-secondary)">${item.REF_RANGE || '-'}</td>
                                <td><span class="badge ${flagClass}" style="padding:2px 8px">${item.FLAG || '-'}</span></td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data detail.');
                    closeModal();
                });
        }

        // Add event listener for WA selection change
        $(document).ready(function() {
            $('#wa-number-select').on('change', function() {
                const val = $(this).val();
                if (val) {
                    let cleanVal = val;
                    if (cleanVal.startsWith('+62')) cleanVal = cleanVal.substring(3);
                    else if (cleanVal.startsWith('62')) cleanVal = cleanVal.substring(2);
                    else if (cleanVal.startsWith('0')) cleanVal = cleanVal.substring(1);
                    $('#wa-number').val(cleanVal);
                }
            });
        });

        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        function sendToWA() {
            const ono = document.getElementById('detailModal').dataset.ono;
            let no_telp = document.getElementById('wa-number').value;
            const btn = document.getElementById('btn-send-wa');
            const btnText = document.getElementById('wa-btn-text');

            if (!no_telp) {
                alert('Silakan masukkan nomor WhatsApp terlebih dahulu.');
                return;
            }

            // Clean number and add 62
            no_telp = no_telp.replace(/\D/g, '');
            if (no_telp.startsWith('0')) no_telp = no_telp.substring(1);
            if (!no_telp.startsWith('62')) no_telp = '62' + no_telp;

            if (!confirm(`Kirim hasil lab ke nomor +${no_telp}?`)) return;

            // Loading state
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btnText.innerText = 'Mengirim...';

            fetch('{{ route("hasil-lab.send-wa") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ono, no_telp })
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.style.opacity = '1';
                btnText.innerText = 'Kirim WhatsApp';
                
                alert(data.message);
            })
            .catch(error => {
                btn.disabled = false;
                btn.style.opacity = '1';
                btnText.innerText = 'Kirim WhatsApp';
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim WhatsApp.');
            });
        }

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>
</html>
