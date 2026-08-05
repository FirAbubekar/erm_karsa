<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Validasi Darah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --sidebar-width: 280px;
            --primary: #DC2626;
            --primary-dark: #B91C1C;
            --primary-light: rgba(220,38,38,0.08);
            --primary-surface: #FEF2F2;
            --primary-gradient: linear-gradient(135deg, #DC2626, #B91C1C);
            --primary-glow: rgba(220,38,38,0.25);
            --bg-main: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-subtle: #94A3B8;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --success: #10B981;
            --success-light: #D1FAE5;
            --warning: #F59E0B;
            --warning-light: #FEF3C7;
            --error: #EF4444;
            --error-light: #FEE2E2;
            --info: #3B82F6;
            --info-light: #DBEAFE;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.08);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }
        body {
            background: var(--bg-main);
            background-image: radial-gradient(circle at 100% 0%, rgba(220,38,38,0.03) 0%, transparent 50%),
                              radial-gradient(circle at 0% 100%, rgba(59,130,246,0.03) 0%, transparent 50%);
            color: var(--text-main); display: flex; min-height: 100vh; font-family: 'Inter', sans-serif;
        }
        .sidebar { width: var(--sidebar-width); background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px; box-shadow: 0 4px 12px var(--primary-glow); }
        .logo-text { font-weight: 700; font-size: 18px; letter-spacing: -0.5px; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 4px; transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
        .nav-item:hover { background: var(--primary-surface); color: var(--text-main); transform: translateX(4px); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; box-shadow: inset 3px 0 0 var(--primary); }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 28px 32px; max-width: calc(100vw - var(--sidebar-width)); }

        .page-header {
            display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px;
            background: var(--card-bg); border-radius: var(--radius-xl); border: 1px solid var(--border);
            padding: 22px 28px; box-shadow: var(--shadow-sm); position: relative; overflow: hidden;
        }
        .page-header::before {
            content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
            background: var(--primary-gradient); border-radius: 0 4px 4px 0;
        }
        .page-header-left h1 { font-size: 24px; font-weight: 800; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; }
        .page-header-left h1 i { color: var(--primary); font-size: 22px; background: var(--primary-surface); padding: 8px; border-radius: 10px; }
        .page-header-left p { color: var(--text-muted); font-size: 14px; margin-top: 4px; margin-left: 46px; }
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-subtle); margin-bottom: 6px; margin-left: 46px; }
        .breadcrumb span { color: var(--text-muted); }
        .page-header-right { display: flex; gap: 10px; align-items: center; }
        .user-profile {
            display: flex; align-items: center; gap: 10px; padding: 6px 16px 6px 6px;
            background: var(--bg-main); border: 1px solid var(--border); border-radius: 999px;
            box-shadow: var(--shadow-sm); transition: all 0.2s;
        }
        .user-profile:hover { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
        .avatar {
            width: 30px; height: 30px; background: var(--primary-gradient);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px; color: white; box-shadow: 0 2px 8px var(--primary-glow);
        }
        .user-info span { font-size: 12px; font-weight: 600; }

        .card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; transition: box-shadow 0.3s; }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header {
            background: linear-gradient(135deg, #1E293B, #334155); padding: 14px 22px;
            display: flex; align-items: center; gap: 10px; color: white; font-weight: 600; font-size: 13px;
        }
        .card-header i { color: rgba(255,255,255,0.8); }
        .card-header h3 { font-size: 13px; font-weight: 600; }
        .card-body { padding: 20px 22px; }
        .filter-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 16px; }
        .filter-grid .full { grid-column: 1 / -1; }
        .filter-row { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-bottom: 0; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .form-group select, .form-group input {
            padding: 8px 12px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-family: inherit; outline: none; background: var(--bg-main); color: var(--text-main);
            transition: all 0.2s;
        }
        .form-group select:focus, .form-group input:focus {
            border-color: var(--primary); background: var(--card-bg); box-shadow: 0 0 0 4px var(--primary-light);
        }
        .form-group select:hover, .form-group input:hover { border-color: var(--text-subtle); }
        .btn-primary {
            padding: 9px 22px; background: var(--primary-gradient); color: white; border: none;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; font-family: inherit;
            cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;
            box-shadow: 0 2px 8px var(--primary-glow);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px var(--primary-glow); }
        .btn-primary:active { transform: translateY(0); }
        .btn-outline {
            padding: 8px 16px; background: var(--bg-main); color: var(--text-muted);
            border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500; font-family: inherit; cursor: pointer;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
        }
        .btn-outline:hover { background: var(--card-bg); border-color: var(--text-subtle); color: var(--text-main); }
        .btn-outline.active { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }
        .btn-sm { padding: 6px 14px; font-size: 12px; }

        .table-wrap { overflow-x: auto; overflow-y: auto; max-height: 620px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead { background: linear-gradient(180deg, #F8FAFC, #F1F5F9); }
        thead th {
            padding: 13px 14px !important; text-align: left !important; font-weight: 600 !important;
            font-size: 11px !important; text-transform: uppercase !important; color: var(--text-muted) !important;
            letter-spacing: 0.05em !important; border-bottom: 2px solid var(--border) !important;
            white-space: nowrap; cursor: pointer; user-select: none; transition: color 0.15s;
            position: sticky; top: 0; z-index: 5; background: #F1F5F9;
        }
        thead th:hover { color: var(--primary) !important; }
        thead th .sort-icon { margin-left: 4px; font-size: 10px; opacity: 0.4; }
        thead th.active .sort-icon { opacity: 1; color: var(--primary); }
        tbody td { padding: 13px 14px !important; border-bottom: 1px solid var(--border-light) !important; vertical-align: middle !important; }
        tbody tr:nth-child(even) td { background: rgba(248,250,252,0.5); }
        tbody tr:hover td { background: var(--primary-surface) !important; }
        tbody tr:last-child td { border-bottom: none !important; }
        td strong { color: var(--text-main); }
        .badge {
            display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px;
            border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap;
        }
        .badge i { font-size: 10px; }
        .badge-success { background: var(--success-light); color: #065F46; }
        .badge-warning { background: var(--warning-light); color: #92400E; }
        .badge-danger { background: var(--error-light); color: #991B1B; }
        .badge-info { background: var(--info-light); color: #1E40AF; }
        .badge-secondary { background: #F3F4F6; color: #6B7280; }
        .text-muted { color: var(--text-muted); }
        .text-center { text-align: center; }

        .info-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 12px; font-size: 12px; color: var(--text-muted);
            padding: 10px 0; border-bottom: 1px solid var(--border-light);
        }
        .info-bar strong { color: var(--text-main); }
        .loading { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .loading i { font-size: 28px; margin-bottom: 12px; color: var(--border); }
        .loading p { font-size: 14px; }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 44px; color: var(--border); margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 14px; }

        /* Summary KPI cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }
        .summary-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .summary-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .summary-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
        }
        .summary-card.c-total::after { background: linear-gradient(90deg, #DC2626, #F87171); }
        .summary-card.c-diterima::after { background: linear-gradient(90deg, #3B82F6, #60A5FA); }
        .summary-card.c-transfusi::after { background: linear-gradient(90deg, #10B981, #34D399); }
        .summary-card.c-menunggu::after { background: linear-gradient(90deg, #F59E0B, #FBBF24); }
        .summary-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .summary-icon.i-total { background: var(--primary-surface); color: var(--primary); }
        .summary-icon.i-diterima { background: var(--info-light); color: var(--info); }
        .summary-icon.i-transfusi { background: var(--success-light); color: var(--success); }
        .summary-icon.i-menunggu { background: var(--warning-light); color: var(--warning); }
        .summary-body .summary-value { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.1; }
        .summary-body .summary-label { font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; margin-top: 2px; }

        .btn-terima, .btn-transfusi {
            padding: 6px 12px; font-size: 11px; border: none; border-radius: 8px;
            cursor: pointer; color: #fff; font-weight: 600; font-family: inherit;
            display: inline-flex; align-items: center; gap: 5px; white-space: nowrap;
            transition: all 0.2s;
        }
        .btn-terima { background: linear-gradient(135deg, #3B82F6, #2563EB); box-shadow: 0 2px 8px rgba(59,130,246,0.25); }
        .btn-terima:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(59,130,246,0.35); }
        .btn-transfusi { background: linear-gradient(135deg, #10B981, #059669); box-shadow: 0 2px 8px rgba(16,185,129,0.25); }
        .btn-transfusi:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(16,185,129,0.35); }

        @media (max-width: 1024px) { .summary-grid { grid-template-columns: repeat(2, 1fr); } }

        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 1000;
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: var(--card-bg); border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2); width: 420px; max-width: 90vw;
            animation: modalIn 0.2s ease; overflow: hidden;
        }
        @keyframes modalIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header {
            padding: 18px 22px; border-bottom: 1px solid var(--border-light);
            display: flex; align-items: center; gap: 10px; position: relative;
        }
        .modal-header-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;
            background: var(--info-light); color: var(--info);
        }
        .modal-header h3 { font-size: 15px; font-weight: 700; flex: 1; }
        .modal-close {
            background: none; border: none; font-size: 24px; cursor: pointer;
            color: var(--text-subtle); padding: 0; line-height: 1; transition: color 0.15s;
        }
        .modal-close:hover { color: var(--text-main); }
        .modal-body { padding: 20px 22px; font-size: 14px; line-height: 1.5; }
        .modal-body p { color: var(--text-muted); }
        .modal-footer {
            padding: 14px 22px; border-top: 1px solid var(--border-light);
            display: flex; gap: 10px; justify-content: flex-end;
        }
        .modal-patient-card {
            display: flex; align-items: center; gap: 12px;
            background: var(--bg-main); border: 1px solid var(--border-light);
            border-radius: var(--radius-md); padding: 14px 16px; margin-top: 16px;
        }
        .mp-avatar {
            width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
            background: var(--primary-surface); color: var(--primary);
            display: flex; align-items: center; justify-content: center; font-size: 17px;
        }
        .mp-info { min-width: 0; }
        .mp-label {
            display: block; font-size: 10.5px; font-weight: 700; color: var(--text-subtle);
            text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 2px;
        }
        .mp-name { font-size: 14px; font-weight: 700; color: var(--text-main); word-break: break-word; }
        .modal-barcode-row {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            background: var(--primary-surface); border: 1px solid rgba(220,38,38,0.12);
            border-radius: var(--radius-md); padding: 12px 16px; margin-top: 10px;
        }
        .mp-barcode {
            font-family: 'SF Mono', 'Consolas', monospace;
            font-weight: 700; font-size: 13px; color: var(--primary);
            word-break: break-all; text-align: right;
        }

        .pagination-wrap {
            display: flex; justify-content: space-between; align-items: center;
            padding: 16px 0 0; flex-wrap: wrap; gap: 10px;
        }
        .pagination-info { font-size: 12px; color: var(--text-muted); }
        .pagination { display: flex; align-items: center; gap: 4px; }
        .page-btn {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 10px;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: 12px; font-weight: 500; color: var(--text-main);
            background: var(--card-bg); cursor: pointer; transition: all 0.2s; font-family: inherit;
        }
        .page-btn:hover:not(:disabled):not(.active) { background: var(--primary-surface); border-color: var(--primary); color: var(--primary); }
        .page-btn.active { background: var(--primary-gradient); color: white; border-color: transparent; box-shadow: 0 2px 8px var(--primary-glow); }
        .page-btn:disabled { opacity: 0.4; cursor: default; }
        .page-dots { padding: 0 4px; color: var(--text-subtle); font-size: 13px; }

        .toast {
            position: fixed; bottom: 24px; right: 24px; padding: 12px 22px;
            background: var(--text-main); color: white; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500; opacity: 0; transform: translateY(10px);
            transition: all 0.3s ease; z-index: 999; pointer-events: none;
            box-shadow: var(--shadow-lg); display: flex; align-items: center; gap: 8px;
        }
        .toast.show { opacity: 1; transform: translateY(0); }
        .toast.success { background: var(--success); }
        .toast.error { background: var(--error); }

        .select2-container--default .select2-selection--single {
            height: 38px !important; border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important; background: var(--bg-main) !important;
            transition: all 0.2s !important;
        }
        .select2-container--default .select2-selection--single:hover { border-color: var(--text-subtle) !important; }
        .select2-container--default.select2-container--open .select2-selection--single { border-color: var(--primary) !important; box-shadow: 0 0 0 4px var(--primary-light) !important; background: var(--card-bg) !important; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px !important; font-size: 13px !important; color: var(--text-main) !important; padding-left: 10px !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; right: 6px !important; }
        .select2-container--default .select2-dropdown { border-color: var(--border) !important; border-radius: 0 0 var(--radius-sm) var(--radius-sm) !important; box-shadow: var(--shadow-md) !important; }
        .select2-container--default .select2-results__option--highlighted[aria-selected] { background: var(--primary-surface) !important; color: var(--primary) !important; }
        .select2-container--default .select2-results__option[aria-selected=true] { background: var(--primary-light) !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-header { animation: fadeInUp 0.4s ease-out; }
        .card { animation: fadeInUp 0.4s ease-out 0.1s both; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; max-width: 100vw; }
            .filter-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; gap: 12px; }
            .page-header-left p, .breadcrumb { margin-left: 0; }
            .filter-row { flex-direction: column; align-items: stretch; }
        }
        @media (min-width: 1025px) { .mobile-header { display: none; } }
    </style>
</head>
<body>
    @include('partials.sidebar')
    <div class="main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="fas fa-check-double"></i> Validasi Darah</h1>
                <div class="breadcrumb">
                    <i class="fas fa-home"></i> Dashboard <i class="fas fa-chevron-right"></i>
                    <span>Bank Darah</span> <i class="fas fa-chevron-right"></i>
                    <span>Validasi Darah</span>
                </div>
                <p><i class="fas fa-database" style="font-size:12px;margin-right:4px;color:var(--text-subtle);"></i> Validasi permintaan darah pasien rawat inap per ruangan</p>
            </div>
            <div class="page-header-right">
                <div class="user-profile">
                    <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                    <div class="user-info"><span>{{ Session::get('user_id') }}</span></div>
                </div>
            </div>
        </div>

        <div class="summary-grid" id="summary-grid">
            <div class="summary-card c-total">
                <div class="summary-icon i-total"><i class="fas fa-droplet"></i></div>
                <div class="summary-body">
                    <div class="summary-value" id="sum-total">-</div>
                    <div class="summary-label">Total Permintaan</div>
                </div>
            </div>
            <div class="summary-card c-diterima">
                <div class="summary-icon i-diterima"><i class="fas fa-box-open"></i></div>
                <div class="summary-body">
                    <div class="summary-value" id="sum-diterima">-</div>
                    <div class="summary-label">Sudah Diterima</div>
                </div>
            </div>
            <div class="summary-card c-transfusi">
                <div class="summary-icon i-transfusi"><i class="fas fa-syringe"></i></div>
                <div class="summary-body">
                    <div class="summary-value" id="sum-transfusi">-</div>
                    <div class="summary-label">Sudah Transfusi</div>
                </div>
            </div>
            <div class="summary-card c-menunggu">
                <div class="summary-icon i-menunggu"><i class="fas fa-hourglass-half"></i></div>
                <div class="summary-body">
                    <div class="summary-value" id="sum-menunggu">-</div>
                    <div class="summary-label">Menunggu Proses</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-sliders-h"></i> Filter Pencarian</div>
            <div class="card-body">
                <div class="filter-grid">
                    <div class="form-group">
                        <label><i class="fas fa-door-open" style="margin-right:3px;"></i> Ruangan / Bangsal</label>
                        <select id="filter_ruangan" style="width:100%">
                            <option value="">-- Semua Ruangan --</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room }}">{{ $room }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt" style="margin-right:3px;"></i> Tanggal Awal</label>
                        <input type="date" id="tgl_awal" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-check" style="margin-right:3px;"></i> Tanggal Akhir</label>
                        <input type="date" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-flag" style="margin-right:3px;"></i> Status Permintaan</label>
                        <select id="filter_status">
                            <option value="">-- Semua Status --</option>
                            @foreach ($statusList as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-flask" style="margin-right:3px;"></i> Produk / Komponen</label>
                        <select id="filter_produk">
                            <option value="">-- Semua Produk --</option>
                            @foreach ($produkList as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-vial" style="margin-right:3px;"></i> Hasil Crossmatch</label>
                        <select id="filter_hasil">
                            <option value="">-- Semua Hasil --</option>
                            @foreach ($hasilList as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-fingerprint" style="margin-right:3px;"></i> No. Rekamedis</label>
                        <input type="text" id="filter_no_rekamedis" placeholder="Cari No. RM..." style="width:100%;" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
                    </div>
                </div>

                <div class="filter-row">
                    <div class="form-group" style="flex-direction:row;align-items:center;gap:8px">
                        <label style="text-transform:none;letter-spacing:0;font-size:12px;">Urutkan:</label>
                        <select id="sort_by" style="width:auto;min-width:150px;padding:6px 10px;">
                            <option value="tanggal_permintaan">Tgl Permintaan</option>
                            <option value="nama_pasien">Nama Pasien</option>
                            <option value="no_rekamedis">No. RM</option>
                            <option value="gol_darah">Gol. Darah</option>
                            <option value="gol_rhesus">Produk</option>
                            <option value="status">Status</option>
                            <option value="hasil">Hasil Cross</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex-direction:row;align-items:center;gap:4px">
                        <button type="button" class="btn-outline btn-sm sort-dir active" data-dir="desc">
                            <i class="fas fa-arrow-down"></i> Terbaru
                        </button>
                        <button type="button" class="btn-outline btn-sm sort-dir" data-dir="asc">
                            <i class="fas fa-arrow-up"></i> Terlama
                        </button>
                    </div>
                    <button type="button" class="btn-primary" onclick="currentPage=1;loadData()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button type="button" class="btn-outline" onclick="resetFilter()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>

                <div id="table-container" style="margin-top:20px;">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Pilih ruangan atau masukkan No. RM, lalu klik "Cari" untuk menampilkan data</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modalValidasi">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-header-icon" id="modal-header-icon"><i class="fas fa-box-open"></i></div>
                <h3 id="modal-title">Validasi Penerimaan Darah</h3>
                <button class="modal-close" id="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p id="modal-desc">Apakah Anda yakin akan memvalidasi penerimaan darah berikut?</p>
                <div class="modal-patient-card">
                    <div class="mp-avatar"><i class="fas fa-user-injured"></i></div>
                    <div class="mp-info">
                        <span class="mp-label">Pasien</span>
                        <span class="mp-name" id="modal-pasien">-</span>
                    </div>
                </div>
                <div class="modal-barcode-row">
                    <span class="mp-label">Barcode Kantong Darah</span>
                    <span id="modal-barcode" class="mp-barcode">-</span>
                </div>
                <input type="hidden" id="modal-id-permintaan">
                <input type="hidden" id="modal-mode" value="penerimaan">
            </div>
            <div class="modal-footer">
                <button class="btn-outline" id="modal-batal-btn">Batal</button>
                <button class="btn-primary" id="modal-confirm-btn"><i class="fas fa-check"></i> Validasi</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var currentSortBy = 'tanggal_permintaan';
        var currentSortDir = 'desc';
        var currentPage = 1;
        var perPage = 20;

        function h(str) { return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

        function showToast(msg, type) {
            var t = $('#toast');
            var icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : type === 'error' ? '<i class="fas fa-exclamation-circle"></i>' : '';
            t.html(icon + msg).removeClass('success error show');
            if (type) t.addClass(type);
            t.addClass('show');
            clearTimeout(t.data('timer'));
            t.data('timer', setTimeout(function () { t.removeClass('show'); }, 3000));
        }

        function setSortDir(dir) {
            currentSortDir = dir;
            currentPage = 1;
            $('.sort-dir').removeClass('active');
            $('.sort-dir[data-dir="' + dir + '"]').addClass('active');
            if ($('#filter_ruangan').val() || $('#filter_no_rekamedis').val()) loadData();
        }

        function renderPagination(page, lastPage, total) {
            if (lastPage <= 1) return '';
            var html = '<div class="pagination-wrap">';
            html += '<div class="pagination-info"><i class="fas fa-table" style="margin-right:4px;"></i>Halaman ' + page + ' dari ' + lastPage + ' (' + total + ' data)</div>';
            html += '<div class="pagination">';
            html += '<button class="page-btn" onclick="goToPage(1)" ' + (page <= 1 ? 'disabled' : '') + '><i class="fas fa-angle-double-left"></i></button>';
            html += '<button class="page-btn" onclick="goToPage(' + (page - 1) + ')" ' + (page <= 1 ? 'disabled' : '') + '><i class="fas fa-angle-left"></i></button>';
            var startPage = Math.max(1, page - 2);
            var endPage = Math.min(lastPage, page + 2);
            if (startPage > 1) {
                html += '<button class="page-btn" onclick="goToPage(1)">1</button>';
                if (startPage > 2) html += '<span class="page-dots">...</span>';
            }
            for (var i = startPage; i <= endPage; i++) {
                html += '<button class="page-btn ' + (i === page ? 'active' : '') + '" onclick="goToPage(' + i + ')">' + i + '</button>';
            }
            if (endPage < lastPage) {
                if (endPage < lastPage - 1) html += '<span class="page-dots">...</span>';
                html += '<button class="page-btn" onclick="goToPage(' + lastPage + ')">' + lastPage + '</button>';
            }
            html += '<button class="page-btn" onclick="goToPage(' + (page + 1) + ')" ' + (page >= lastPage ? 'disabled' : '') + '><i class="fas fa-angle-right"></i></button>';
            html += '<button class="page-btn" onclick="goToPage(' + lastPage + ')" ' + (page >= lastPage ? 'disabled' : '') + '><i class="fas fa-angle-double-right"></i></button>';
            html += '</div></div>';
            return html;
        }

        function goToPage(page) {
            currentPage = page;
            loadData();
        }

        function sortBy(column) {
            if (currentSortBy === column) {
                currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                $('.sort-dir').removeClass('active');
                $('.sort-dir[data-dir="' + currentSortDir + '"]').addClass('active');
            } else {
                currentSortBy = column;
                currentSortDir = 'desc';
                $('.sort-dir').removeClass('active');
                $('.sort-dir[data-dir="desc"]').addClass('active');
            }
            currentPage = 1;
            $('#sort_by').val(column);
            if ($('#filter_ruangan').val() || $('#filter_no_rekamedis').val()) loadData();
        }

        function renderSummary(s) {
            if (!s) return;
            $('#sum-total').text(s.total || 0);
            $('#sum-diterima').text(s.diterima || 0);
            $('#sum-transfusi').text(s.transfusi || 0);
            $('#sum-menunggu').text(s.menunggu || 0);
        }

        function resetFilter() {
            $('#filter_ruangan').val('').trigger('change');
            $('#tgl_awal').val('{{ date('Y-m-d') }}');
            $('#tgl_akhir').val('{{ date('Y-m-d') }}');
            $('#filter_status').val('');
            $('#filter_produk').val('');
            $('#filter_hasil').val('');
            $('#filter_no_rekamedis').val('');
            $('#sort_by').val('tanggal_permintaan');
            currentSortBy = 'tanggal_permintaan';
            currentSortDir = 'desc';
            $('.sort-dir').removeClass('active');
            $('.sort-dir[data-dir="desc"]').addClass('active');
            $('#sum-total').text('-');
            $('#sum-diterima').text('-');
            $('#sum-transfusi').text('-');
            $('#sum-menunggu').text('-');
            $('#table-container').html('<div class="empty-state"><i class="fas fa-inbox"></i><p>Pilih ruangan atau masukkan No. RM, lalu klik "Cari" untuk menampilkan data</p></div>');
        }

        function loadData() {
            var ruangan = $('#filter_ruangan').val();
            var noRm = $('#filter_no_rekamedis').val().trim();
            if (!ruangan && !noRm) {
                showToast('Pilih ruangan atau masukkan No. RM terlebih dahulu', 'error');
                return;
            }

            var $container = $('#table-container');
            $container.html('<div class="loading"><i class="fas fa-spinner fa-pulse"></i><p style="margin-top:8px;">Memuat data...</p></div>');

            var params = {
                ruangan: ruangan,
                tgl_awal: $('#tgl_awal').val(),
                tgl_akhir: $('#tgl_akhir').val(),
                status_permintaan: $('#filter_status').val(),
                produk: $('#filter_produk').val(),
                hasil_cross: $('#filter_hasil').val(),
                no_rekamedis: $('#filter_no_rekamedis').val(),
                sort_by: currentSortBy,
                sort_order: currentSortDir,
                page: currentPage,
                per_page: perPage
            };

            $.ajax({
                url: '{{ route("bank-darah.validasi.pasien") }}',
                method: 'GET',
                data: params,
                success: function (res) {
                    if (!res.success || !res.data.length) {
                        $container.html('<div class="empty-state"><i class="fas fa-inbox"></i><p>Tidak ada permintaan darah untuk ruangan ini</p></div>');
                        renderSummary(res.summary);
                        return;
                    }

                    renderSummary(res.summary);
                    var sortIcon = currentSortDir === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down';
                    var html = '<div class="info-bar"><span><i class="fas fa-eye" style="margin-right:4px;"></i>Menampilkan <strong>' + res.from + '-' + res.to + '</strong> dari <strong>' + res.total + '</strong> permintaan darah</span></div>';
                    html += '<div class="table-wrap"><table>';
                    html += '<thead><tr>';
                    html += '<th style="min-width:140px;"><i class="fas fa-box-open"></i> Validasi Penerimaan</th>';
                    html += '<th style="min-width:140px;"><i class="fas fa-syringe"></i> Validasi Transfusi</th>';
                    html += '<th><i class="fas fa-door-open"></i> Ruangan</th>';
                    html += '<th onclick="sortBy(\'no_rekamedis\')" class="' + (currentSortBy === 'no_rekamedis' ? 'active' : '') + '"><i class="fas fa-fingerprint"></i> No. RM <i class="fas ' + (currentSortBy === 'no_rekamedis' ? sortIcon : 'fa-sort') + ' sort-icon"></i></th>';
                    html += '<th onclick="sortBy(\'nama_pasien\')" class="' + (currentSortBy === 'nama_pasien' ? 'active' : '') + '"><i class="fas fa-user"></i> Nama Pasien <i class="fas ' + (currentSortBy === 'nama_pasien' ? sortIcon : 'fa-sort') + ' sort-icon"></i></th>';
                    html += '<th onclick="sortBy(\'gol_darah\')" class="' + (currentSortBy === 'gol_darah' ? 'active' : '') + '"><i class="fas fa-tint"></i> Gol. Darah <i class="fas ' + (currentSortBy === 'gol_darah' ? sortIcon : 'fa-sort') + ' sort-icon"></i></th>';
                    html += '<th onclick="sortBy(\'gol_rhesus\')" class="' + (currentSortBy === 'gol_rhesus' ? 'active' : '') + '"><i class="fas fa-flask"></i> Produk <i class="fas ' + (currentSortBy === 'gol_rhesus' ? sortIcon : 'fa-sort') + ' sort-icon"></i></th>';
                    html += '<th><i class="fas fa-flask"></i> Volume</th>';
                    html += '<th><i class="fas fa-barcode"></i> Barcode</th>';
                    html += '<th onclick="sortBy(\'hasil\')" class="' + (currentSortBy === 'hasil' ? 'active' : '') + '"><i class="fas fa-vial"></i> Hasil Cross <i class="fas ' + (currentSortBy === 'hasil' ? sortIcon : 'fa-sort') + ' sort-icon"></i></th>';
                    html += '<th onclick="sortBy(\'tanggal_permintaan\')" class="' + (currentSortBy === 'tanggal_permintaan' ? 'active' : '') + '"><i class="fas fa-calendar"></i> Tgl Permintaan <i class="fas ' + (currentSortBy === 'tanggal_permintaan' ? sortIcon : 'fa-sort') + ' sort-icon"></i></th>';
                    html += '<th><i class="fas fa-hospital"></i> Status Ranap</th>';
                    html += '<th onclick="sortBy(\'status\')" class="' + (currentSortBy === 'status' ? 'active' : '') + '"><i class="fas fa-flag"></i> Status <i class="fas ' + (currentSortBy === 'status' ? sortIcon : 'fa-sort') + ' sort-icon"></i></th>';
                    html += '</tr></thead><tbody>';

                    $.each(res.data, function (i, r) {
                        var stsPenerimaan = (r.status_penerimaan || '').toLowerCase();
                        var stsPemberian = (r.status_pemberian || '').toLowerCase();
                        var stsTransfusi = stsPemberian === 'transfusi';

                        html += '<tr>';

                        html += '<td style="white-space:nowrap;">';
                        if (!stsPenerimaan || stsPenerimaan !== 'sudah') {
                            html += '<button class="btn-terima" data-id="' + h(r.id_permintaan) + '" data-pasien="' + h(r.nama_pasien || '') + '" data-barcode="' + h(r.barcode || '') + '"><i class="fas fa-check-circle"></i> Terima</button>';
                        } else {
                            html += '<span class="badge badge-success" style="font-size:12px;padding:6px 12px;"><i class="fas fa-check-circle"></i> Sudah</span>';
                        }
                        html += '</td>';

                        html += '<td style="white-space:nowrap;">';
                        if (!stsTransfusi && stsPenerimaan === 'sudah') {
                            html += '<button class="btn-transfusi" data-id="' + h(r.id_permintaan) + '" data-pasien="' + h(r.nama_pasien || '') + '" data-barcode="' + h(r.barcode || '') + '"><i class="fas fa-syringe"></i> Transfusi</button>';
                        } else if (stsTransfusi) {
                            html += '<span class="badge badge-success" style="font-size:12px;padding:6px 12px;"><i class="fas fa-check-circle"></i> Transfusi</span>';
                        } else {
                            html += '<button class="btn-outline btn-sm" style="padding:6px 12px;font-size:11px;opacity:0.5;cursor:not-allowed;" disabled><i class="fas fa-syringe"></i> Transfusi</button>';
                        }
                        html += '</td>';

                        html += '<td><span class="badge badge-secondary" style="font-size:12px;">' + h(r.ruangan || '-') + '</span></td>';

                        html += '<td style="font-family:\'SF Mono\',\'Consolas\',monospace;font-size:12px;font-weight:500;color:var(--text-muted);">' + (r.no_rekamedis || '-') + '</td>';
                        html += '<td><strong>' + (r.nama_pasien || '-') + '</strong></td>';
                        html += '<td><span class="badge badge-info">' + (r.gol_darah || '-') + '</span></td>';
                        html += '<td>' + (r.gol_rhesus || '-') + '</td>';
                        html += '<td>' + (r.volume || '-') + '</td>';
                        html += '<td style="font-family:\'SF Mono\',\'Consolas\',monospace;font-size:12px;">' + (r.barcode || '-') + '</td>';

                        var hasil = r.hasil || '';
                        var hasilBadge = 'badge-warning';
                        if (hasil === 'COMPATIBLE') hasilBadge = 'badge-success';
                        else if (hasil.indexOf('INCOMPATIBLE') !== -1) hasilBadge = 'badge-danger';
                        var hasilIcon = hasil === 'COMPATIBLE' ? '<i class="fas fa-check-circle"></i>' : hasil.indexOf('INCOMPATIBLE') !== -1 ? '<i class="fas fa-times-circle"></i>' : hasil ? '<i class="fas fa-exclamation-circle"></i>' : '';
                        html += '<td><span class="badge ' + hasilBadge + '">' + hasilIcon + ' ' + (hasil || '-') + '</span></td>';

                        html += '<td>' + (r.tanggal_permintaan || '-') + '</td>';

                        if (r.status_ranap === 'MASIH DIRAWAT') {
                            html += '<td><span class="badge badge-success"><i class="fas fa-check-circle"></i> Masih Dirawat</span></td>';
                        } else {
                            html += '<td><span class="badge badge-secondary"><i class="fas fa-clock"></i> Tidak dirawat / sudah pulang</span></td>';
                        }

                        var statusClass = 'badge-warning';
                        var statusIcon = '<i class="fas fa-clock"></i>';
                        if (r.status === 'SELESAI' || r.status === 'COMPATIBLE') { statusClass = 'badge-success'; statusIcon = '<i class="fas fa-check-circle"></i>'; }
                        else if (r.status === 'BATAL') { statusClass = 'badge-danger'; statusIcon = '<i class="fas fa-ban"></i>'; }
                        html += '<td><span class="badge ' + statusClass + '">' + statusIcon + ' ' + (r.status || 'Proses') + '</span></td>';

                        html += '</tr>';
                    });

                    html += '</tbody></table></div>';
                    html += renderPagination(res.current_page, res.last_page, res.total);
                    $container.html(html);
                },
                error: function () {
                    $container.html('<div class="empty-state"><i class="fas fa-exclamation-triangle" style="color:var(--error);"></i><p style="color:var(--error);">Gagal memuat data</p></div>');
                    showToast('Gagal memuat data', 'error');
                }
            });
        }

        $(document).ready(function () {
            $('#filter_ruangan, #filter_status, #filter_produk, #filter_hasil').select2({ width: '100%' });

            $('#sort_by').on('change', function () {
                currentSortBy = $(this).val();
                currentPage = 1;
                if ($('#filter_ruangan').val() || $('#filter_no_rekamedis').val()) loadData();
            });

            $('#filter_no_rekamedis').on('keypress', function (e) {
                if (e.which === 13) {
                    currentPage = 1;
                    loadData();
                }
            });

            $('.sort-dir').on('click', function () {
                setSortDir($(this).data('dir'));
            });

            $(document).on('click', '.btn-terima', function () {
                var id = $(this).data('id');
                var pasien = $(this).data('pasien');
                var barcode = $(this).data('barcode');
                $('#modal-id-permintaan').val(id);
                $('#modal-pasien').text(pasien);
                $('#modal-barcode').text(barcode);
                $('#modal-mode').val('penerimaan');
                $('#modal-header-icon').html('<i class="fas fa-box-open"></i>').css('background', 'var(--info-light)').css('color', 'var(--info)');
                $('#modal-title').text('Validasi Penerimaan Darah');
                $('#modal-desc').text('Apakah Anda yakin akan memvalidasi penerimaan darah berikut?');
                $('#modal-confirm-btn').prop('disabled', false).html('<i class="fas fa-check"></i> Validasi Penerimaan');
                $('#modalValidasi').addClass('active');
            });

            $(document).on('click', '.btn-transfusi', function () {
                var id = $(this).data('id');
                var pasien = $(this).data('pasien');
                var barcode = $(this).data('barcode');
                $('#modal-id-permintaan').val(id);
                $('#modal-pasien').text(pasien);
                $('#modal-barcode').text(barcode);
                $('#modal-mode').val('transfusi');
                $('#modal-header-icon').html('<i class="fas fa-syringe"></i>').css('background', 'var(--success-light)').css('color', 'var(--success)');
                $('#modal-title').text('Validasi Transfusi Darah');
                $('#modal-desc').text('Apakah Anda yakin akan memvalidasi transfusi darah berikut?');
                $('#modal-confirm-btn').prop('disabled', false).html('<i class="fas fa-syringe"></i> Validasi Transfusi');
                $('#modalValidasi').addClass('active');
            });

            $('#modal-close-btn, #modal-batal-btn').on('click', function () {
                $('#modalValidasi').removeClass('active');
                $('#modal-confirm-btn').prop('disabled', false);
            });

            $('#modal-confirm-btn').on('click', function () {
                var id = $('#modal-id-permintaan').val();
                var mode = $('#modal-mode').val();
                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-pulse"></i> Memvalidasi...');

                var url = mode === 'penerimaan' ? '{{ route("bank-darah.validasi.penerimaan") }}' : '{{ route("bank-darah.validasi.transfusi") }}';
                var label = mode === 'penerimaan' ? 'Penerimaan' : 'Transfusi';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_permintaan: id
                    },
                    success: function (res) {
                        $('#modalValidasi').removeClass('active');
                        showToast(res.message || label + ' berhasil divalidasi', 'success');
                        loadData();
                    },
                    error: function (xhr) {
                        var msg = 'Gagal memvalidasi ' + label.toLowerCase();
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        showToast(msg, 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-check"></i> Validasi ' + label);
                    }
                });
            });
        });
    </script>
</body>
</html>
