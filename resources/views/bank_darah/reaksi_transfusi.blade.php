<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Formulir Penelusuran Reaksi Transfusi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #DC2626;
            --primary-dark: #B91C1C;
            --primary-light: rgba(220, 38, 38, 0.08);
            --primary-glow: rgba(220, 38, 38, 0.15);
            --primary-surface: #FEF2F2;
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-subtle: #94A3B8;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --success: #10B981;
            --error: #EF4444;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.03);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg-main);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(255,255,255,0.98);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; height: 100vh; z-index: 50;
            transition: transform 0.3s ease;
        }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 16px;
            box-shadow: 0 4px 8px var(--primary-glow);
        }
        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
        .nav-item:hover { background: var(--primary-surface); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 28px 32px; max-width: calc(100vw - var(--sidebar-width)); }

        header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .header-title h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.3px; color: var(--text-main); }
        .header-title p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-subtle); margin-bottom: 6px; }
        .breadcrumb i { font-size: 10px; }
        .breadcrumb span { color: var(--text-muted); }

        .user-profile { display: flex; align-items: center; gap: 10px; padding: 6px 14px 6px 6px; background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
        .avatar { width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: white; }
        .user-info span { font-size: 13px; font-weight: 500; display: block; }

        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .form-header-banner {
            background: linear-gradient(135deg, #1E293B, #0F172A);
            color: white;
            padding: 24px;
            text-align: center;
            border-bottom: 4px solid var(--primary);
        }
        .form-header-banner h2 { font-size: 20px; font-weight: 800; letter-spacing: 0.5px; color: #F8FAFC; text-transform: uppercase; margin-bottom: 4px; }
        .form-header-banner h3 { font-size: 15px; font-weight: 700; color: #94A3B8; letter-spacing: 0.3px; margin-bottom: 2px; }
        .form-header-banner h4 { font-size: 14px; font-weight: 600; color: #FCA5A5; text-transform: uppercase; margin-bottom: 12px; }
        .alert-bdrs-box {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(220, 38, 38, 0.2); border: 1px solid #EF4444;
            color: #FECACA; padding: 6px 16px; border-radius: 20px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.5px;
        }

        .card-body { padding: 28px; }

        .form-section { margin-bottom: 28px; }
        .form-section-title {
            font-size: 14px; font-weight: 800; color: var(--primary-dark); text-transform: uppercase;
            letter-spacing: 0.05em; margin-bottom: 16px; padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-light); display: flex; align-items: center; gap: 8px;
        }
        .form-section-title i { color: var(--primary); }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .form-grid .full-width { grid-column: 1 / -1; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-size: 12px; font-weight: 700; color: var(--text-main); }
        .form-group input[type="text"], 
        .form-group input[type="date"], 
        .form-group input[type="time"], 
        .form-group select, 
        .form-group textarea {
            padding: 10px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-family: inherit; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--card-bg); color: var(--text-main);
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .form-group textarea { resize: vertical; min-height: 70px; }

        /* Custom Radio & Checkbox Styling */
        .radio-group, .checkbox-group-grid { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; padding-top: 4px; }
        .checkbox-group-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
        
        .custom-check-item {
            display: flex; align-items: center; gap: 8px; cursor: pointer;
            font-size: 13px; font-weight: 500; color: var(--text-main);
            padding: 8px 14px; background: #F8FAFC; border: 1px solid var(--border);
            border-radius: var(--radius-sm); transition: all 0.2s;
        }
        .custom-check-item:hover { background: var(--primary-surface); border-color: #FECACA; }
        .custom-check-item input[type="checkbox"], 
        .custom-check-item input[type="radio"] {
            accent-color: var(--primary); width: 16px; height: 16px; cursor: pointer;
        }

        /* RT Section Styles */
        .rt-section {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .rt-section-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            background: linear-gradient(135deg, #F8FAFC, #F1F5F9);
            border-bottom: 1px solid var(--border);
        }
        .section-badge {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 3px 8px var(--primary-glow);
        }
        .section-title {
            font-size: 15px; font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.2px;
        }
        .section-subtitle {
            font-size: 12px; font-weight: 500;
            color: var(--text-muted);
            margin-top: 1px;
        }
        .rt-section-body {
            padding: 20px;
        }
        .fl {
            font-size: 12px; font-weight: 700; color: var(--text-main);
        }
        .fc {
            padding: 10px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-family: inherit; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--card-bg); color: var(--text-main); width: 100%;
        }
        .fc:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .req {
            color: var(--error);
        }

        /* Checkbox Grid Pills (Komponen Darah) */
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .checkbox-grid.col-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s;
            background: #FAFBFC;
            font-size: 13px;
            font-weight: 600;
            user-select: none;
        }
        .checkbox-item:hover {
            border-color: var(--primary);
            background: var(--primary-surface);
        }
        .checkbox-item.checked {
            border-color: var(--primary);
            background: var(--primary-surface);
            box-shadow: 0 0 0 2px var(--primary-glow);
        }
        .checkbox-item input[type="radio"] {
            display: none;
        }
        .checkbox-icon {
            width: 20px; height: 20px;
            border: 2px solid var(--border);
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .checkbox-item.checked .checkbox-icon {
            background: var(--primary);
            border-color: var(--primary);
        }
        .checkbox-icon svg {
            width: 10px; height: 10px;
            stroke: white;
            stroke-width: 2;
            fill: none;
            display: none;
        }
        .checkbox-item.checked .checkbox-icon svg {
            display: block;
        }
        .checkbox-label {
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Section Separator */
        .section-sep {
            border: none;
            border-top: 1px solid var(--border);
            margin: 18px 0;
        }

        /* Golongan Darah Pills */
        .gol-darah-pills {
            display: flex;
            gap: 10px;
        }
        .gol-pill {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px; height: 48px;
            border-radius: 50%;
            border: 2px solid var(--border);
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
            background: #FAFBFC;
        }
        .gol-pill input[type="radio"] {
            display: none;
        }
        .gol-pill.A {
            color: #DC2626; border-color: #FECACA;
        }
        .gol-pill.A.selected {
            background: #DC2626; color: white; border-color: #DC2626;
        }
        .gol-pill.B {
            color: #2563EB; border-color: #BFDBFE;
        }
        .gol-pill.B.selected {
            background: #2563EB; color: white; border-color: #2563EB;
        }
        .gol-pill.AB {
            color: #7C3AED; border-color: #DDD6FE;
        }
        .gol-pill.AB.selected {
            background: #7C3AED; color: white; border-color: #7C3AED;
        }
        .gol-pill.O {
            color: #059669; border-color: #A7F3D0;
        }
        .gol-pill.O.selected {
            background: #059669; color: white; border-color: #059669;
        }
        .gol-pill:hover {
            transform: scale(1.08);
        }
        .gol-pill.selected {
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        /* Span 3 columns in col-3 grid */
        .form-grid.col-3 .span-3 {
            grid-column: 1 / -1;
        }

        /* Field with inline input */
        .field-with-input {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #FAFBFC;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
        }
        .field-with-input span {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
        }
        .field-with-input input {
            border: none;
            background: transparent;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            flex: 1;
            color: var(--text-main);
        }
        .field-with-input input::placeholder {
            color: var(--text-subtle);
        }

        /* Radio Grid */
        .radio-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .radio-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s;
            background: #FAFBFC;
            font-size: 13px;
            font-weight: 500;
            user-select: none;
        }
        .radio-item:hover {
            border-color: var(--primary);
            background: var(--primary-surface);
        }
        .radio-item.checked {
            border-color: var(--primary);
            background: var(--primary-light);
            box-shadow: 0 0 0 2px var(--primary-glow);
        }
        .radio-item input[type="radio"] {
            display: none;
        }
        .radio-icon {
            width: 20px; height: 20px;
            border: 2px solid var(--border);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .radio-item.checked .radio-icon {
            border-color: var(--primary);
        }
        .radio-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--primary);
            display: none;
        }
        .radio-item.checked .radio-dot {
            display: block;
        }

        /* Span 2 columns */
        .span-2 {
            grid-column: span 2;
        }

        /* Vital Signs Table */
        .vital-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .vital-table th {
            background: #F8FAFC;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 12px 16px;
            border-bottom: 2px solid var(--border);
            text-align: center;
        }
        .vital-table td {
            padding: 8px 12px;
            border-bottom: 1px solid var(--border-light);
            font-weight: 600;
            color: var(--text-main);
        }
        .vital-input {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-family: inherit;
            outline: none;
            background: var(--card-bg);
            color: var(--text-main);
            text-align: center;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .vital-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .vital-input::placeholder {
            color: var(--text-subtle);
            font-size: 11px;
        }

        .search-box { margin-bottom: 12px; }
        .search-input-group { display: flex; gap: 8px; align-items: flex-end; }
        .btn-search {
            padding: 10px 20px; background: var(--primary); color: white; border: none;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; font-family: inherit;
            cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px;
            white-space: nowrap; height: 41px;
        }
        .btn-search:hover { background: var(--primary-dark); }
        .search-results {
            margin-top: 8px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            max-height: 220px; overflow-y: auto; background: var(--card-bg);
        }
        .search-results table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .search-results th { background: var(--primary-surface); color: var(--primary); font-weight: 600; padding: 8px 10px; text-align: left; border-bottom: 1px solid var(--border); position: sticky; top: 0; }
        .search-results td { padding: 8px 10px; border-bottom: 1px solid var(--border-light); cursor: pointer; }
        .search-results tr:hover td { background: var(--primary-surface); }

        .form-actions-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            margin-top: 20px;
        }
        .info-text {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }
        .info-text strong { color: var(--error); }
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: var(--card-bg);
            color: var(--text-muted);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-cancel:hover {
            background: var(--bg-main);
            color: var(--text-main);
        }
        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 24px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px var(--primary-glow);
        }
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .toast {
            position: fixed; bottom: 24px; right: 24px; padding: 12px 20px;
            background: var(--text-main); color: white; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 600; opacity: 0; transform: translateY(10px);
            transition: all 0.3s ease; z-index: 999; pointer-events: none;
            box-shadow: var(--shadow-md);
        }
        .toast.show { opacity: 1; transform: translateY(0); }
        .toast.success { background: var(--success); }
        .toast.error { background: var(--error); }

        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); margin-top: auto; }
        .btn-logout { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; width: 100%; background: #FEF2F2; color: #EF4444; border: 1px solid #FEE2E2; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; font-family: inherit; }
        .btn-logout:hover { background: #FEE2E2; border-color: #FECACA; }

        @media (max-width: 1024px) {
            :root { --sidebar-width: 280px; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; max-width: 100vw; }
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
            .mobile-header { display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: rgba(255,255,255,0.98); border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
            .hamburger { cursor: pointer; padding: 8px; border-radius: var(--radius-sm); background: var(--bg-main); border: none; color: var(--text-main); }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index: 45; }
            .sidebar-overlay.active { display: block; }
            header { flex-direction: column; align-items: flex-start; gap: 16px; }
            .user-profile { width: 100%; justify-content: center; }
        }
        @media (min-width: 1025px) { .mobile-header { display: none; } }

        /* Select2 Overrides */
        .select2-container .select2-selection--single {
            height: 41px !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
            color: var(--text-main);
            padding-left: 14px;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--text-subtle);
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 39px !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            padding: 8px 12px;
        }
        .select2-dropdown {
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            box-shadow: var(--shadow-md);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background: var(--primary-surface) !important;
            color: var(--primary) !important;
        }
        .select2-container--default .select2-results__option--selected {
            background: var(--primary-light) !important;
            color: var(--primary) !important;
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <header>
            <div class="header-title">
                <div class="breadcrumb">
                    <i class="fas fa-home"></i> Dashboard <i class="fas fa-chevron-right"></i>
                    <span>Bank Darah</span> <i class="fas fa-chevron-right"></i>
                    <span>Reaksi Transfusi</span>
                </div>
                <h1>Formulir Reaksi Transfusi</h1>
                <p>Monitoring & Penelusuran Reaksi Transfusi Darah / Produk Darah</p>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ substr((string) Session::get('user_id', 'U'), 0, 1) }}</div>
                <div class="user-info"><span>{{ Session::get('user_id', 'User') }}</span></div>
            </div>
        </header>

        <form id="form-reaksi" method="POST">
            @csrf

            <div class="card">
                <div class="form-header-banner">
                    <h2>RSUD KARSA HUSADA BATU</h2>
                    <h3>MONITORING TRANSFUSI DARAH/ PRODUK DARAH</h3>
                    <h4>FORMULIR PENELUSURAN REAKSI TRANSFUSI</h4>
                    <div class="alert-bdrs-box">
                        <i class="fas fa-phone-alt"></i> TELEPON KE BDRS (PSW.140) BILA ANDA MENEMUKAN GEJALA DIBAWAH INI
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search Patient -->
                    <div class="rt-section" style="border: 2px solid var(--primary-light);">
                        <div class="rt-section-header" style="background: linear-gradient(135deg, var(--primary-surface), #FEF2F2);">
                            <div class="section-badge" style="background: linear-gradient(135deg, #64748B, #475569); box-shadow: 0 3px 8px rgba(100,116,139,0.25);">
                                <i class="fas fa-search" style="font-size: 14px;"></i>
                            </div>
                            <div>
                                <div class="section-title">Pencarian Pasien</div>
                                <div class="section-subtitle">Cari berdasarkan No. RM / Nama / No. Rawat / Barcode</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            <div class="search-box">
                                <div class="search-input-group">
                                    <div class="form-group" style="flex:1">
                                        <label class="fl">Kata Kunci Pencarian</label>
                                        <input type="text" id="search_keyword" class="fc" placeholder="Ketik no. RM, nama pasien, atau barcode kantong..." maxlength="12" onkeypress="return event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode >= 65 && event.keyCode <= 90 || event.keyCode >= 97 && event.keyCode <= 122 || event.keyCode == 8 || event.keyCode == 32 || event.keyCode == 46 || event.keyCode == 45">
                                    </div>
                                    <button type="button" id="btn-cari" class="btn-search" style="margin-top: 22px;">
                                        <i class="fas fa-search"></i> Cari Pasien
                                    </button>
                                </div>
                                <div id="search-results" class="search-results" style="display:none"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI A ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header">
                            <div class="section-badge">A</div>
                            <div>
                                <div class="section-title">Identitas Pasien</div>
                                <div class="section-subtitle">Data identitas lengkap pasien penerima transfusi</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="fl">Rumah Sakit</label>
                                    <input type="text" name="rumah_sakit" class="fc" value="RSUD Karsa Husada Batu" placeholder="Nama rumah sakit...">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Ruangan / Unit</label>
                                    <select name="ruangan" class="fc select2">
                                        <option value="">Pilih Ruang / Bagian...</option>
                                        @foreach($ruangList as $r)
                                            <option value="{{ $r }}" {{ old('ruangan') == $r ? 'selected' : '' }}>{{ $r }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="fl">Nama Pasien <span class="req">*</span></label>
                                    <input type="text" name="nama_pasien" class="fc" value="{{ old('nama_pasien') }}" required placeholder="Nama lengkap pasien...">
                                </div>
                                <div class="form-group">
                                    <label class="fl">No. Rekam Medis</label>
                                    <input type="text" name="no_rkm_medis" class="fc" value="{{ old('no_rkm_medis') }}" placeholder="Contoh: 00-00-0000">
                                </div>
                                <div class="form-group">
                                    <label class="fl">No. Rawat</label>
                                    <input type="text" name="no_rawat" class="fc" value="{{ old('no_rawat') }}" placeholder="Nomor registrasi rawat...">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Jenis Kelamin</label>
                                    <select name="jk" class="fc">
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="fl">Tanggal Lahir / Umur</label>
                                    <input type="text" name="tgl_lahir_umur" class="fc" value="{{ old('tgl_lahir_umur') }}" placeholder="Tgl Lahir / Umur pasien...">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Diagnosa</label>
                                    <input type="text" name="diagnosa" class="fc" value="{{ old('diagnosa') }}" placeholder="Diagnosa utama pasien...">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Dokter Penanggung Jawab</label>
                                    <select name="dpjp" class="fc select2">
                                        <option value="">-- Pilih Dokter --</option>
                                        @foreach($pegawaiList as $p)
                                            <option value="{{ $p->nama }}" {{ old('dpjp') == $p->nama ? 'selected' : '' }}>{{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI B ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header">
                            <div class="section-badge">B</div>
                            <div>
                                <div class="section-title">Data Transfusi</div>
                                <div class="section-subtitle">Informasi kantong darah dan pelaksanaan transfusi</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            {{-- Jenis Komponen --}}
                            <div class="form-group" style="margin-bottom:18px;">
                                <label class="fl" style="margin-bottom:8px;">Jenis Komponen Darah</label>
                                <div class="checkbox-grid col-3">
                                    @foreach(['WB','PRC','TC','FFP','Cryo'] as $komp)
                                    <label class="checkbox-item {{ old('jenis_komponen') == $komp ? 'checked' : '' }}" onclick="selectKomponen(this, '{{ $komp }}')">
                                        <input type="radio" name="jenis_komponen" value="{{ $komp }}" {{ old('jenis_komponen') == $komp ? 'checked' : '' }}>
                                        <div class="checkbox-icon">
                                            <svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg>
                                        </div>
                                        <span class="checkbox-label">{{ $komp }}</span>
                                    </label>
                                    @endforeach
                                    <label class="checkbox-item {{ old('jenis_komponen') == 'Lainnya' ? 'checked' : '' }}" onclick="selectKomponen(this, 'Lainnya')">
                                        <input type="radio" name="jenis_komponen" value="Lainnya" {{ old('jenis_komponen') == 'Lainnya' ? 'checked' : '' }}>
                                        <div class="checkbox-icon"><svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg></div>
                                        <span class="checkbox-label">Lainnya</span>
                                    </label>
                                </div>
                                <div id="komponen-lain-wrap" style="margin-top:10px; {{ old('jenis_komponen') == 'Lainnya' ? '' : 'display:none;' }}">
                                    <input type="text" name="jenis_komponen_lain" class="fc" value="{{ old('jenis_komponen_lain') }}" placeholder="Sebutkan jenis komponen lainnya...">
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="fl">No. Permintaan</label>
                                    <input type="text" name="no_permintaan" class="fc" value="{{ old('no_permintaan') }}" placeholder="Otomatis dari barcode" readonly style="background:#F1F5F9;">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Nomor Kantong Darah (Barcode)</label>
                                    <div style="display:flex;gap:8px;">
                                        <input type="text" name="barcode" id="barcode" class="fc" value="{{ old('barcode') }}" placeholder="Scan atau ketik barcode..." style="flex:1;">
                                        <button type="button" id="btn-cari-barcode" class="btn-search" style="white-space:nowrap;height:41px;">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                    <div id="barcode-results" class="search-results" style="display:none;margin-top:8px;"></div>
                                </div>
                            </div>

                            <hr class="section-sep">

                            {{-- Golongan Darah --}}
                            <div class="form-grid" style="margin-bottom:16px;">
                                <div class="form-group">
                                    <label class="fl" style="margin-bottom:8px;">Gol. Darah Pasien</label>
                                    <div class="gol-darah-pills" id="gol-pasien-wrap">
                                        @foreach(['A','B','AB','O'] as $g)
                                        <label class="gol-pill {{ $g }} {{ old('gol_darah') == $g ? 'selected' : '' }}" onclick="selectGoldar(this, 'gol-pasien-wrap', 'gol_darah', '{{ $g }}')">
                                            <input type="radio" name="gol_darah" value="{{ $g }}" {{ old('gol_darah') == $g ? 'checked' : '' }}>{{ $g }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="fl" style="margin-bottom:8px;">Gol. Darah Donor</label>
                                    <div class="gol-darah-pills" id="gol-donor-wrap">
                                        @foreach(['A','B','AB','O'] as $g)
                                        <label class="gol-pill {{ $g }} {{ old('gol_darah_donor') == $g ? 'selected' : '' }}" onclick="selectGoldar(this, 'gol_donor_pills', 'gol_darah_donor', '{{ $g }}')">
                                            <input type="radio" name="gol_darah_donor" value="{{ $g }}" {{ old('gol_darah_donor') == $g ? 'checked' : '' }}>{{ $g }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-grid col-3">
                                <div class="form-group">
                                    <label class="fl">Volume (mL)</label>
                                    <input type="text" name="volume_transfusi" class="fc" value="{{ old('volume_transfusi') }}" placeholder="Contoh: 250 mL">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Tanggal Transfusi <span class="req">*</span></label>
                                    <input type="date" name="tanggal_transfusi" class="fc" value="{{ old('tanggal_transfusi', date('Y-m-d')) }}">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Tanggal Reaksi <span class="req">*</span></label>
                                    <input type="date" name="tanggal_reaksi" class="fc" value="{{ old('tanggal_reaksi', date('Y-m-d')) }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="fl">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" class="fc" value="{{ old('jam_mulai') }}">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Jam Reaksi Terjadi</label>
                                    <input type="time" name="jam_reaksi" class="fc" value="{{ old('jam_reaksi') }}">
                                </div>
                                <div class="form-group">
                                    <label class="fl">Jam Selesai / Dihentikan</label>
                                    <input type="time" name="jam_selesai" class="fc" value="{{ old('jam_selesai') }}">
                                </div>
                                <div class="form-group span-3">
                                    <label class="fl">Petugas Pemberi Transfusi</label>
                                    <select name="petugas_transfusi" class="fc select2">
                                        <option value="">-- Pilih Petugas Pemberi Transfusi --</option>
                                        @foreach($pegawaiList as $p)
                                            <option value="{{ $p->nama }}" {{ old('petugas_transfusi') == $p->nama ? 'selected' : '' }}>{{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI C ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header">
                            <div class="section-badge">C</div>
                            <div>
                                <div class="section-title">Tanda dan Gejala Reaksi Transfusi</div>
                                <div class="section-subtitle">Centang semua gejala yang dialami pasien</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            @php $gejalaDefs = ['Demam', 'Menggigil', 'Gatal / Urtikaria', 'Sesak Napas']; @endphp
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;">
                                @foreach($gejalaDefs as $label)
                                <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#FAFBFC;border:1.5px solid var(--border);border-radius:var(--radius-sm);">
                                    <input type="checkbox" name="gejala_check[]" value="{{ $label }}" style="accent-color:var(--primary);width:16px;height:16px;cursor:pointer;flex-shrink:0;">
                                    <span style="font-size:13px;font-weight:600;white-space:nowrap;">{{ $label }}</span>
                                    <input type="text" name="gejala_val[{{ $label }}]" placeholder="Keterangan..." style="flex:1;border:none;background:transparent;font-size:13px;font-family:inherit;outline:none;color:var(--text-main);border-bottom:1px dashed var(--border);">
                                </div>
                                @endforeach
                            </div>
                            <div class="field-with-input">
                                <span>Lain-lain :</span>
                                <input type="text" name="gejala_lain" value="{{ old('gejala_lain') }}" placeholder="Sebutkan gejala lainnya jika ada...">
                            </div>
                            <hr class="section-sep">
                            <div class="form-group" style="margin-top: 4px;">
                                <label class="fl">Klasifikasi / Jenis Reaksi</label>
                                <select name="jenis_reaksi" class="fc select2">
                                    <option value="">-- Pilih Jenis Reaksi --</option>
                                    @foreach(['Reaksi Febril Non Hemolitik','Reaksi Alergi','Reaksi Hemolitik','Overload Sirkulasi','TRALI','Tidak terbukti reaksi transfusi','Alergi Ringan','Alergi Berat','Reaksi Demam','Reaksi Bakteri (Sepsis)','Reaksi Anafilaksis','Lainnya'] as $jr)
                                    <option value="{{ $jr }}" {{ old('jenis_reaksi') == $jr ? 'selected' : '' }}>{{ $jr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-top:14px;">
                                <label class="fl">Deskripsi Gejala Tambahan</label>
                                <textarea name="gejala" class="fc" placeholder="Tuliskan deskripsi gejala secara lengkap jika diperlukan...">{{ old('gejala') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI D ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header">
                            <div class="section-badge">D</div>
                            <div>
                                <div class="section-title">Tanda Vital</div>
                                <div class="section-subtitle">Pengukuran sebelum transfusi, saat reaksi, dan setelah tindakan</div>
                            </div>
                        </div>
                        <div class="rt-section-body" style="padding: 0;">
                            <div style="overflow-x:auto;">
                                <table class="vital-table">
                                    <thead>
                                        <tr>
                                            <th style="width:160px; text-align:left;">Parameter</th>
                                            <th>Sebelum Transfusi</th>
                                            <th>
                                                <label style="display:inline-flex;align-items:center;justify-content:center;gap:4px;cursor:pointer;">
                                                    <input type="checkbox" id="check-saat-reaksi" style="accent-color:var(--primary);width:14px;height:14px;cursor:pointer;">
                                                    Saat Reaksi
                                                </label>
                                            </th>
                                            <th>
                                                <label style="display:inline-flex;align-items:center;justify-content:center;gap:4px;cursor:pointer;">
                                                    <input type="checkbox" id="check-setelah-reaksi" style="accent-color:var(--primary);width:14px;height:14px;cursor:pointer;">
                                                    Setelah Reaksi
                                                </label>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Tekanan Darah</td>
                                            <td><input type="text" name="td_sebelum" class="vital-input" value="{{ old('td_sebelum') }}" placeholder="mmHg"></td>
                                            <td><input type="text" name="td_reaksi" class="vital-input" value="{{ old('td_reaksi') }}" placeholder="mmHg"></td>
                                            <td><input type="text" name="td_setelah" class="vital-input" value="{{ old('td_setelah') }}" placeholder="mmHg"></td>
                                        </tr>
                                        <tr>
                                            <td>Nadi</td>
                                            <td><input type="text" name="nadi_sebelum" class="vital-input" value="{{ old('nadi_sebelum') }}" placeholder="x/menit"></td>
                                            <td><input type="text" name="nadi_reaksi" class="vital-input" value="{{ old('nadi_reaksi') }}" placeholder="x/menit"></td>
                                            <td><input type="text" name="nadi_setelah" class="vital-input" value="{{ old('nadi_setelah') }}" placeholder="x/menit"></td>
                                        </tr>
                                        <tr>
                                            <td>Suhu</td>
                                            <td><input type="text" name="suhu_sebelum" class="vital-input" value="{{ old('suhu_sebelum') }}" placeholder="°C"></td>
                                            <td><input type="text" name="suhu_reaksi" class="vital-input" value="{{ old('suhu_reaksi') }}" placeholder="°C"></td>
                                            <td><input type="text" name="suhu_setelah" class="vital-input" value="{{ old('suhu_setelah') }}" placeholder="°C"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI E ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header">
                            <div class="section-badge">E</div>
                            <div>
                                <div class="section-title">Tindakan yang Dilakukan</div>
                                <div class="section-subtitle">Centang semua tindakan yang telah dilakukan</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            @php $tindakanDefs = ['Transfusi dihentikan','Jalur infus dipertahankan dengan NaCl 0,9%','Dilaporkan ke dokter','Dilaporkan ke BDRS/UTD','Pemeriksaan laboratorium ulang']; @endphp
                            <div style="display:grid;grid-template-columns:1fr;gap:8px;margin-bottom:14px;">
                                @foreach($tindakanDefs as $label)
                                <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#FAFBFC;border:1.5px solid var(--border);border-radius:var(--radius-sm);">
                                    <input type="checkbox" name="tindakan_check[]" value="{{ $label }}" style="accent-color:var(--primary);width:16px;height:16px;cursor:pointer;flex-shrink:0;">
                                    <span style="font-size:13px;font-weight:600;white-space:nowrap;min-width:300px;">{{ $label }}</span>
                                    <input type="text" name="tindakan_val[{{ $label }}]" placeholder="Keterangan..." style="flex:1;border:none;background:transparent;font-size:13px;font-family:inherit;outline:none;color:var(--text-main);border-bottom:1px dashed var(--border);">
                                </div>
                                @endforeach
                            </div>
                            <div class="field-with-input" style="margin-bottom:10px;">
                                <span>Pemberian obat :</span>
                                <input type="text" name="tindakan_obat" value="{{ old('tindakan_obat') }}" placeholder="Nama obat yang diberikan...">
                            </div>
                            <div class="field-with-input">
                                <span>Lain-lain :</span>
                                <input type="text" name="tindakan_lain" value="{{ old('tindakan_lain') }}" placeholder="Tindakan lainnya...">
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI F ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header">
                            <div class="section-badge">F</div>
                            <div>
                                <div class="section-title">Spesimen yang Dikirim ke BDRS/UTD</div>
                                <div class="section-subtitle">Centang spesimen yang dikirimkan untuk investigasi</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            @php $spesimenDefs = ['Kantong darah sisa transfusi','Selang transfusi','Sampel darah pasien pasca transfusi','Formulir investigasi reaksi transfusi','Urin pasien']; @endphp
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;">
                                @foreach($spesimenDefs as $label)
                                <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#FAFBFC;border:1.5px solid var(--border);border-radius:var(--radius-sm);">
                                    <input type="checkbox" name="spesimen_check[]" value="{{ $label }}" style="accent-color:var(--primary);width:16px;height:16px;cursor:pointer;flex-shrink:0;">
                                    <span style="font-size:13px;font-weight:600;white-space:nowrap;">{{ $label }}</span>
                                    <input type="text" name="spesimen_val[{{ $label }}]" placeholder="Keterangan..." style="flex:1;border:none;background:transparent;font-size:13px;font-family:inherit;outline:none;color:var(--text-main);border-bottom:1px dashed var(--border);">
                                </div>
                                @endforeach
                            </div>
                            <div class="field-with-input">
                                <span>Lain-lain :</span>
                                <input type="text" name="spesimen_lain" value="{{ old('spesimen_lain') }}" placeholder="Spesimen lainnya...">
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI G ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header" style="background: linear-gradient(135deg, #1e3a5f, #2563eb);">
                            <div class="section-badge">G</div>
                            <div>
                                <div class="section-title" style="color:white">Hasil Investigasi BDRS/UTD</div>
                                <div class="section-subtitle" style="color:white">Diisi oleh petugas BDRS/UTD setelah investigasi</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            @php $investigasiDefs = ['Pemeriksaan Golongan Darah','Crossmatch Ulang','Uji Hemolisis','DAT (Direct Antiglobulin Test)','Kultur (bila perlu)']; @endphp
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;">
                                @foreach($investigasiDefs as $label)
                                <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#FAFBFC;border:1.5px solid var(--border);border-radius:var(--radius-sm);{{ $loop->last ? 'grid-column:1/-1;' : '' }}">
                                    <input type="checkbox" name="investigasi_check[]" value="{{ $label }}" style="accent-color:var(--primary);width:16px;height:16px;cursor:pointer;flex-shrink:0;">
                                    <span style="font-size:13px;font-weight:600;white-space:nowrap;">{{ $label }}</span>
                                    <input type="text" name="investigasi_val[{{ $label }}]" placeholder="Hasil pemeriksaan..." style="flex:1;border:none;background:transparent;font-size:13px;font-family:inherit;outline:none;color:var(--text-main);border-bottom:1px dashed var(--border);">
                                </div>
                                @endforeach
                            </div>
                            <hr class="section-sep">
                            <div class="form-group" style="margin-bottom:10px;">
                                <label class="fl" style="margin-bottom:10px;">Kesimpulan Reaksi Transfusi</label>
                                <div class="radio-grid">
                                    @foreach($kesimpulanList as $ks)
                                    <label class="radio-item {{ old('kesimpulan_reaksi') == $ks ? 'checked' : '' }}" onclick="selectRadio(this, 'kesimpulan_reaksi')">
                                        <input type="radio" name="kesimpulan_reaksi" value="{{ $ks }}" {{ old('kesimpulan_reaksi') == $ks ? 'checked' : '' }}>
                                        <div class="radio-icon"><div class="radio-dot"></div></div>
                                        <span class="radio-label">{{ $ks }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="field-with-input" style="margin-top:12px;">
                                <span>Lainnya :</span>
                                <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Kesimpulan lainnya...">
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ SEKSI H ================================================ -->
                    <div class="rt-section">
                        <div class="rt-section-header" style="background: linear-gradient(135deg, #134e4a, #0f766e);">
                            <div class="section-badge">H</div>
                            <div>
                                <div class="section-title" style="color:white">Tindak Lanjut</div>
                                <div class="section-subtitle" style="color:white">Rencana tindak lanjut dan penandatanganan</div>
                            </div>
                        </div>
                        <div class="rt-section-body">
                            <div class="form-group" style="margin-bottom:20px;">
                                <label class="fl">Rencana Tindak Lanjut</label>
                                <textarea name="tindak_lanjut" class="fc" rows="4" placeholder="Tuliskan rencana tindak lanjut yang akan dilakukan...">{{ old('tindak_lanjut') }}</textarea>
                            </div>
                            <div class="form-grid">
                                    <div style="background: #f8fafc; border-radius: 10px; padding: 18px; border: 1px solid #e2e8f0;">
                                        <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                                            Petugas Ruangan
                                        </div>
                                        <div class="form-group">
                                            <label class="fl">Nama Petugas</label>
                                            <select name="nama_petugas_ruangan" class="fc select2">
                                                <option value="">-- Pilih Petugas Ruangan --</option>
                                                @foreach($pegawaiList as $p)
                                                    <option value="{{ $p->nama }}" {{ old('nama_petugas_ruangan') == $p->nama ? 'selected' : '' }}>{{ $p->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div style="background: #f8fafc; border-radius: 10px; padding: 18px; border: 1px solid #e2e8f0;">
                                        <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                                            Petugas BDRS/UTD
                                        </div>
                                        <div class="form-group">
                                            <label class="fl">Nama Petugas</label>
                                            <select name="nama_petugas_bdrs" class="fc select2">
                                                <option value="">-- Pilih Petugas BDRS/UTD --</option>
                                                @foreach($pegawaiList as $p)
                                                    <option value="{{ $p->nama }}" {{ old('nama_petugas_bdrs') == $p->nama ? 'selected' : '' }}>{{ $p->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================================================ ACTION BAR ================================================ -->
                    <div class="form-actions-bar">
                        <p class="info-text">Kolom bertanda <strong>*</strong> wajib diisi. Pastikan semua data terisi dengan benar sebelum menyimpan.</p>
                        <div style="display:flex;gap:10px;">
                            <a href="{{ route('bank-darah.reaksi.history') }}" class="btn-cancel">
                                <i class="fas fa-times" style="width:15px;height:15px;"></i> Batal
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save" style="width:15px;height:15px;"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="toast" id="toast"></div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function showToast(msg, type) {
            var t = $('#toast');
            t.text(msg).removeClass('success error show');
            if (type) t.addClass(type);
            t.addClass('show');
            clearTimeout(t.data('timer'));
            t.data('timer', setTimeout(function () { t.removeClass('show'); }, 3000));
        }

        function fillPatient(row) {
            $('input[name="no_rkm_medis"]').val(row.no_rkm_medis);
            $('input[name="no_rawat"]').val(row.no_rawat);
            $('input[name="nama_pasien"]').val(row.nm_pasien);
            $('input[name="tgl_lahir_umur"]').val(row.tgl_lahir ? (row.tgl_lahir + ' (' + row.umur + ')') : row.umur);

            // Reset semua gol-darah pills
            $('#gol-pasien-wrap .gol-pill, #gol-donor-wrap .gol-pill').removeClass('selected').find('input').prop('checked', false);
            if (row.gol_darah && row.gol_darah !== '-') {
                var gol = row.gol_darah.replace(/[^ABO]/g, '');
                if (gol) {
                    $('#gol-pasien-wrap .gol-pill, #gol-donor-wrap .gol-pill').each(function() {
                        if ($(this).find('input').val() === gol) {
                            $(this).addClass('selected').find('input').prop('checked', true);
                        }
                    });
                }
            }
            if (row.ruang) {
                var bangsalName = row.ruang.split(' - ')[0];
                $('select[name="ruangan"] option').each(function() {
                    if ($(this).text() === bangsalName) { $(this).prop('selected', true); }
                });
                $('select[name="ruangan"]').trigger('change.select2');
            }
            if (row.nm_dokter && row.nm_dokter !== '-') {
                $('select[name="dpjp"]').val(row.nm_dokter).trigger('change.select2');
            }
            if (row.jk) {
                $('select[name="jk"]').val(row.jk);
            }
            if (row.diagnosa_awal) {
                $('input[name="diagnosa"]').val(row.diagnosa_awal);
            }

            // Jika hasil pencarian juga mengandung data barcode, isi Data Transfusi
            if (row._barcode) {
                $('input[name="barcode"]').val(row._barcode);
                $('input[name="no_permintaan"]').val(row._id_permintaan);
                if (row._volume) {
                    $('input[name="volume_transfusi"]').val(row._volume);
                }
                // Reset komponen pills
                $('.checkbox-item').removeClass('checked').find('input[type="radio"]').prop('checked', false);
                $('#komponen-lain-wrap').hide().find('input').val('');
                if (row._gol_rhesus) {
                    var komp = row._gol_rhesus.toUpperCase().trim();
                    var found = false;
                    $('.checkbox-item').each(function() {
                        var val = $(this).find('input[type="radio"]').val();
                        if (val && val.toUpperCase() === komp) {
                            $(this).addClass('checked').find('input').prop('checked', true);
                            found = true;
                        }
                    });
                    if (!found && komp !== '') {
                        $('.checkbox-item').each(function() {
                            var val = $(this).find('input[type="radio"]').val();
                            if (val === 'Lainnya') {
                                $(this).addClass('checked').find('input').prop('checked', true);
                                $('#komponen-lain-wrap').show().find('input').val(komp);
                            }
                        });
                    }
                }
            }

            // isi tanda vital dari pemeriksaan_ralan / pemeriksaan_ranap
            if (row.tensi) $('input[name="td_sebelum"]').val(row.tensi);
            if (row.suhu_tubuh) $('input[name="suhu_sebelum"]').val(row.suhu_tubuh);
            if (row.nadi) $('input[name="nadi_sebelum"]').val(row.nadi);

            $('#search-results').hide().html('');
            showToast('Data pasien berhasil dipilih', 'success');
        }

        function fillBarcodeData(row) {
            $('input[name="barcode"]').val(row.barcode);
            $('input[name="no_permintaan"]').val(row.id_permintaan);

            // Reset semua gol-darah pills
            $('#gol-pasien-wrap .gol-pill, #gol-donor-wrap .gol-pill').removeClass('selected').find('input').prop('checked', false);
            if (row.gol_darah) {
                var gol = row.gol_darah.replace(/[^ABO]/g, '');
                if (gol) {
                    $('#gol-pasien-wrap .gol-pill, #gol-donor-wrap .gol-pill').each(function() {
                        if ($(this).find('input').val() === gol) {
                            $(this).addClass('selected').find('input').prop('checked', true);
                        }
                    });
                }
            }

            // Reset semua komponen pills
            $('.checkbox-item').removeClass('checked').find('input[type="radio"]').prop('checked', false);
            $('#komponen-lain-wrap').hide().find('input').val('');
            if (row.gol_rhesus) {
                var komp = row.gol_rhesus.toUpperCase().trim();
                var found = false;
                $('.checkbox-item').each(function() {
                    var val = $(this).find('input[type="radio"]').val();
                    if (val && val.toUpperCase() === komp) {
                        $(this).addClass('checked').find('input').prop('checked', true);
                        found = true;
                    }
                });
                if (!found && komp !== '') {
                    $('.checkbox-item').each(function() {
                        var val = $(this).find('input[type="radio"]').val();
                        if (val === 'Lainnya') {
                            $(this).addClass('checked').find('input').prop('checked', true);
                            $('#komponen-lain-wrap').show().find('input').val(komp);
                        }
                    });
                }
            }

            if (row.volume) {
                $('input[name="volume_transfusi"]').val(row.volume);
            }
            if (row.nama_pasien) {
                $('input[name="nama_pasien"]').val(row.nama_pasien);
            }
            if (row.ruangan) {
                $('select[name="ruangan"] option').each(function() {
                    if ($(this).text() === row.ruangan) { $(this).prop('selected', true); }
                });
                $('select[name="ruangan"]').trigger('change.select2');
            }
            $('#barcode-results').hide().html('');
            showToast('Data kantong darah berhasil dipilih', 'success');
        }

        function selectKomponen(el, val) {
            $('.checkbox-item').removeClass('checked');
            $(el).addClass('checked');
            $(el).find('input[type="radio"]').prop('checked', true);
            if (val === 'Lainnya') {
                $('#komponen-lain-wrap').show().find('input').focus();
            } else {
                $('#komponen-lain-wrap').hide().find('input').val('');
            }
        }

        function selectGoldar(el, wrapId, inputName, val) {
            $('#' + wrapId + ' .gol-pill').removeClass('selected');
            $(el).addClass('selected');
            $(el).find('input[type="radio"]').prop('checked', true);
        }

        function toggleCheck(el) {
            $(el).toggleClass('checked');
            var $input = $(el).find('input[type="checkbox"]');
            $input.prop('checked', !$input.prop('checked'));
        }

        function selectRadio(el, name) {
            $('.radio-item').removeClass('checked');
            $(el).addClass('checked');
            $(el).find('input[type="radio"]').prop('checked', true);
        }

        $(document).ready(function () {
            // Set jam ke waktu sekarang
            function setCurrentTime() {
                var now = new Date();
                var hh = String(now.getHours()).padStart(2, '0');
                var mm = String(now.getMinutes()).padStart(2, '0');
                var time = hh + ':' + mm;
                if (!$('input[name="jam_mulai"]').val()) $('input[name="jam_mulai"]').val(time);
                if (!$('input[name="jam_reaksi"]').val()) $('input[name="jam_reaksi"]').val(time);
                if (!$('input[name="jam_selesai"]').val()) $('input[name="jam_selesai"]').val(time);
            }
            setCurrentTime();

            $('.select2').select2({
                width: '100%',
                placeholder: function() { return $(this).data('placeholder') || '-- Pilih --'; },
                allowClear: true,
            });

            $('#btn-cari').on('click', function () {
                var keyword = $('#search_keyword').val().trim();
                if (keyword.length < 2) {
                    showToast('Masukkan minimal 2 karakter untuk mencari', 'error');
                    return;
                }

                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mencari...');

                $.ajax({
                    url: '{{ route("bank-darah.cari-pasien") }}',
                    method: 'GET',
                    data: { keyword: keyword },
                    success: function (res) {
                        btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari Pasien');
                        if (!res.success || !res.data.length) {
                            $('#search-results').html('<div style="padding:12px;text-align:center;color:var(--text-muted);font-size:13px">Data pasien tidak ditemukan</div>').show();
                            return;
                        }
                        var html = '<table><thead><tr><th>No. RM</th><th>Nama Pasien</th><th>No. Rawat</th><th>Ruang</th><th>Sumber</th><th>Barcode</th></tr></thead><tbody>';
                        $.each(res.data, function (i, r) {
                            var isBarcode = r._barcode ? true : false;
                            var sumber = r.status_lanjut || '-';
                            var sumberBadge = sumber === 'Ranap' ? '<span style="background:#DBEAFE;color:#1D4ED8;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;">Ranap</span>' :
                                             sumber === 'Ralan' ? '<span style="background:#D1FAE5;color:#047857;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;">Ralan</span>' : '-';
                            html += '<tr data-index="' + i + '" style="' + (isBarcode ? 'background:#F0FDF4;' : '') + '">';
                            html += '<td>' + (r.no_rkm_medis || '-') + '</td>';
                            html += '<td><strong>' + r.nm_pasien + '</strong></td>';
                            html += '<td>' + (r.no_rawat || '-') + '</td>';
                            html += '<td>' + (r.ruang || '-') + '</td>';
                            html += '<td>' + sumberBadge + '</td>';
                            html += '<td>' + (r._barcode || '-') + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        var $results = $('#search-results').html(html).show();
                        $results.find('tr[data-index]').on('click', function () {
                            var idx = $(this).data('index');
                            fillPatient(res.data[idx]);
                        });
                    },
                    error: function () {
                        btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari Pasien');
                        showToast('Gagal mencari data pasien', 'error');
                    }
                });
            });

            $('#search_keyword').on('input', function() {
                $(this).val($(this).val().replace(/[^a-zA-Z0-9\s\.\-]/g, ''));
            });

            $('#search_keyword').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#btn-cari').click();
                }
            });

            $('#btn-cari-barcode').on('click', function () {
                var barcode = $('#barcode').val().trim();
                if (barcode.length < 2) {
                    showToast('Masukkan minimal 2 karakter barcode', 'error');
                    return;
                }

                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mencari...');

                $.ajax({
                    url: '{{ route("bank-darah.cari-barcode") }}',
                    method: 'GET',
                    data: { barcode: barcode },
                    success: function (res) {
                        btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari');
                        if (!res.success || !res.data.length) {
                            $('#barcode-results').html('<div style="padding:12px;text-align:center;color:var(--text-muted);font-size:13px">Barcode tidak ditemukan</div>').show();
                            return;
                        }
                        var html = '<table><thead><tr><th>Barcode</th><th>Pasien</th><th>Gol. Darah</th><th>Produk</th><th>Volume</th></tr></thead><tbody>';
                        $.each(res.data, function (i, r) {
                            html += '<tr data-index="' + i + '" style="cursor:pointer;">';
                            html += '<td><strong>' + r.barcode + '</strong></td>';
                            html += '<td>' + (r.nama_pasien || '-') + '</td>';
                            html += '<td>' + (r.gol_darah || '-') + '</td>';
                            html += '<td>' + (r.gol_rhesus || '-') + '</td>';
                            html += '<td>' + (r.volume || '-') + ' mL</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        var $results = $('#barcode-results').html(html).show();
                        $results.find('tr[data-index]').on('click', function () {
                            var idx = $(this).data('index');
                            fillBarcodeData(res.data[idx]);
                        });
                    },
                    error: function () {
                        btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari');
                        showToast('Gagal mencari barcode', 'error');
                    }
                });
            });

            // Checkbox copy tanda vital dari Sebelum Transfusi
            $('#check-saat-reaksi').on('change', function() {
                var copy = $(this).prop('checked');
                $('input[name="td_reaksi"]').val(copy ? $('input[name="td_sebelum"]').val() : '');
                $('input[name="nadi_reaksi"]').val(copy ? $('input[name="nadi_sebelum"]').val() : '');
                $('input[name="suhu_reaksi"]').val(copy ? $('input[name="suhu_sebelum"]').val() : '');
            });
            $('#check-setelah-reaksi').on('change', function() {
                var copy = $(this).prop('checked');
                $('input[name="td_setelah"]').val(copy ? $('input[name="td_sebelum"]').val() : '');
                $('input[name="nadi_setelah"]').val(copy ? $('input[name="nadi_sebelum"]').val() : '');
                $('input[name="suhu_setelah"]').val(copy ? $('input[name="suhu_sebelum"]').val() : '');
            });

            $('#barcode').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#btn-cari-barcode').click();
                }
            });

            $('#form-reaksi').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: '{{ route("bank-darah.reaksi.store") }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function (res) {
                        showToast(res.message, 'success');
                        setTimeout(function () {
                            window.location.href = '{{ route("bank-darah.reaksi.download", ":id") }}'.replace(':id', res.id);
                        }, 1000);
                    },
                    error: function (xhr) {
                        var msg = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showToast(msg, 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan & Cetak PDF');
                    }
                });
            });
        });
    </script>
</body>
</html>
