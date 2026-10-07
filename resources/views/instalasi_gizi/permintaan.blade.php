<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Permintaan Gizi</title>
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
            --primary-light: rgba(16,185,129,0.10);
            --primary-glow: rgba(16,185,129,0.18);
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; font-family: 'Inter', sans-serif; overflow-x: hidden; width: 100%; }
        .sidebar { width: var(--sidebar-width); background: rgba(255,255,255,0.98); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px; }
        .logo-text { font-weight: 700; font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 4px; }
        .nav-item:hover { background: #ECFDF5; color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 28px 32px; min-width: 0; width: calc(100% - var(--sidebar-width)); }

        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-header h1 { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
        .page-header p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }
        .header-actions { display: flex; gap: 10px; align-items: center; }
        .user-profile { display: flex; align-items: center; gap: 10px; padding: 6px 14px 6px 6px; background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
        .avatar { width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: white; }
        .user-info span { font-size: 13px; font-weight: 500; }

        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 20px 24px; box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 24px; height: 24px; }
        .stat-icon.blue { background: rgba(16,185,129,0.12); color: var(--primary); }
        .stat-icon.green { background: rgba(16,185,129,0.12); color: #10b981; }
        .stat-icon.purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
        .stat-info .stat-value { font-size: 28px; font-weight: 800; letter-spacing: -1px; line-height: 1; }
        .stat-info .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        .filter-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); margin-bottom: 20px; box-shadow: var(--shadow-sm); overflow: hidden; }
        .filter-head { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
        .filter-head-left { display: flex; align-items: center; gap: 10px; }
        .filter-head-left svg { width: 20px; height: 20px; color: rgba(255,255,255,0.9); }
        .filter-head-left span { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: 0.02em; }
        .filter-head-badge { background: rgba(255,255,255,0.2); color: #fff; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 20px; }
        .filter-body { padding: 18px 20px 20px; }
        .filter-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 14px; align-items: end; }
        .filter-row + .filter-row { margin-top: 12px; padding-top: 14px; border-top: 1px dashed var(--border-light); }
        .filter-group { display: flex; flex-direction: column; gap: 5px; width: 100%; }
        .filter-group label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 4px; }
        .filter-group label svg { width: 14px; height: 14px; color: var(--primary); }
        .filter-group select,
        .filter-group input { padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit; background: #FAFBFC; transition: all 0.2s; width: 100%; }
        .filter-group select:focus,
        .filter-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); background: #fff; }
        .filter-actions { display: flex; gap: 10px; align-items: center; padding-bottom: 1px; }
        .btn-filter { padding: 9px 22px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; border: none; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px var(--primary-glow); }
        .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 20px var(--primary-glow); }
        .btn-filter svg { width: 16px; height: 16px; }
        .btn-reset { padding: 9px 18px; background: transparent; color: var(--text-muted); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn-reset:hover { background: #FEF2F2; border-color: #FECACA; color: #DC2626; }
        .btn-reset svg { width: 14px; height: 14px; }

        .room-card { background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 20px; }
        .room-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; background: linear-gradient(135deg, #F8FAFC, #F1F5F9); border-bottom: 1px solid var(--border); }
        .room-title { display: flex; align-items: center; gap: 12px; }
        .room-title svg { width: 20px; height: 20px; color: var(--primary); }
        .room-title .name { font-size: 15px; font-weight: 700; }
        .room-title .badge-count { background: var(--primary-light); color: var(--primary); font-size: 11px; font-weight: 700; padding: 3px 12px; border-radius: 20px; }
        .table-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #fff; }
        th { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.04em; border-bottom: 1px solid var(--border); white-space: nowrap; }
        td { padding: 12px 16px; border-bottom: 1px solid var(--border-light); font-size: 13px; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: rgba(14,165,233,0.03); }
        .patient-cell { display: flex; align-items: center; gap: 12px; }
        .patient-avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; flex-shrink: 0; }
        .patient-name { font-weight: 600; font-size: 13px; }
        .patient-rm { font-size: 11px; color: var(--text-muted); font-family: monospace; }
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-muted { background: var(--bg-main); color: var(--text-muted); }
        .badge-info { background: var(--primary-light); color: var(--primary); }
        .text-muted { color: var(--text-muted); }
        .text-xs { font-size: 12px; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; font-size: 12px; }
        .diet-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600; background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; margin: 2px 4px 2px 0; }
        .diet-badge .diet-time { color: #059669; }

        /* Modal & Scrollbar Improvements */
        body.modal-open {
            overflow: hidden !important;
            height: 100vh !important;
        }
        
        /* Custom scrollbar styling to look premium, thin, and professional */
        .modal-overlay::-webkit-scrollbar,
        .modal-body::-webkit-scrollbar,
        .bp-dg-ket-wrap::-webkit-scrollbar,
        .pm-checkboxes-col::-webkit-scrollbar,
        .table-scroll::-webkit-scrollbar,
        body::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .modal-overlay::-webkit-scrollbar-track,
        .modal-body::-webkit-scrollbar-track,
        .bp-dg-ket-wrap::-webkit-scrollbar-track,
        .pm-checkboxes-col::-webkit-scrollbar-track,
        .table-scroll::-webkit-scrollbar-track,
        body::-webkit-scrollbar-track {
            background: transparent;
        }
        .modal-overlay::-webkit-scrollbar-thumb,
        .modal-body::-webkit-scrollbar-thumb,
        .bp-dg-ket-wrap::-webkit-scrollbar-thumb,
        .pm-checkboxes-col::-webkit-scrollbar-thumb,
        .table-scroll::-webkit-scrollbar-thumb,
        body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .modal-overlay::-webkit-scrollbar-thumb:hover,
        .modal-body::-webkit-scrollbar-thumb:hover,
        .bp-dg-ket-wrap::-webkit-scrollbar-thumb:hover,
        .pm-checkboxes-col::-webkit-scrollbar-thumb:hover,
        .table-scroll::-webkit-scrollbar-thumb:hover,
        body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .modal-overlay { 
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            bottom: 0; 
            background: rgba(15, 23, 42, 0.6); 
            backdrop-filter: blur(8px); 
            z-index: 9999; 
            display: none; 
            overflow-y: auto;
            padding: 40px 20px;
            -webkit-overflow-scrolling: touch;
        }
        .modal-overlay.active { display: block; }
        .modal-card { 
            background: #fff; 
            border-radius: 20px; 
            width: 80%;
            max-width: 820px; 
            margin: 0 auto 40px auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); 
            animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); 
            position: relative;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 80px);
            overflow: hidden;
        }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.97) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fff; border-radius: 20px 20px 0 0; flex-shrink: 0; }
        .modal-head h2 { font-size: 18px; font-weight: 700; }
        .modal-close { width: 32px; height: 32px; border-radius: 50%; border: none; background: var(--bg-main); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 20px; }
        .modal-close:hover { background: var(--border); }
        .modal-body { padding: 24px; overflow-y: auto; flex-grow: 1; -webkit-overflow-scrolling: touch; }
        .modal-foot { padding: 16px 24px; border-top: 1px solid var(--border-light); background: #fff; display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-shrink: 0; border-radius: 0 0 20px 20px; }
        .modal-section { margin-bottom: 24px; }
        .modal-section:last-child { margin-bottom: 0; }
        .modal-section-title { font-size: 13px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid var(--border-light); }
        .modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px; }
        .modal-field .ml { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.03em; }
        .modal-field .mv { font-size: 14px; margin-top: 2px; color: var(--text-main); line-height: 1.4; white-space: pre-wrap; }
        .modal-field.full { grid-column: 1 / -1; }

        .pm-checkboxes { display: flex; gap: 14px; flex-wrap: wrap; }
        .pm-checkboxes-col { flex-direction: column; gap: 8px; max-height: 220px; overflow-y: auto; }
        .pm-cb { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #F8FAFC; border: 1.5px solid var(--border); border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 13px; color: var(--text-main); }
        .pm-cb input { width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer; }
        .pm-input { width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: inherit; background: #FAFBFC; color: var(--text-main); transition: all 0.2s ease; }
        .pm-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); background: #fff; }
        .pm-input[readonly] { background: #F8FAFC !important; border-color: #E2E8F0 !important; color: #475569 !important; cursor: default !important; box-shadow: none !important; }

        .bp-dg-ket-wrap { margin-top: 12px; display: flex; flex-direction: column; gap: 8px; max-height: 260px; overflow-y: auto; }
        .bp-dg-ket-item { display: flex; flex-direction: column; gap: 5px; padding: 10px 12px; background: #F8FAFC; border: 1px solid var(--border); border-radius: 10px; }
        .bp-dg-ket-label { font-size: 13px; font-weight: 600; color: var(--text-main); }
        .bp-dg-ket-item .pm-input { background: #fff; }

        /* Professional Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            background-color: #FAFBFC !important;
            height: 38px !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 13px !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
        }
        .select2-container--default .select2-selection--multiple {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            background-color: #FAFBFC !important;
            min-height: 38px !important;
            padding: 2px 8px !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 13px !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            height: auto !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--primary) !important;
            background-color: #fff !important;
            box-shadow: 0 0 0 3px var(--primary-light) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-main) !important;
            line-height: 36px !important;
            padding-left: 12px !important;
            padding-right: 30px !important;
            width: 100% !important;
            text-align: left !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--text-muted) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--text-muted) transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
            margin-top: 0 !important;
        }
        .select2-container--default.select2-container--open .select2-selection__arrow b {
            border-color: transparent transparent var(--text-muted) transparent !important;
            border-width: 0 4px 5px 4px !important;
            margin-top: 0 !important;
        }
        /* Multiple Selection tags styling */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--primary-light) !important;
            border: 1px solid rgba(16,185,129,0.3) !important;
            color: var(--primary-dark) !important;
            border-radius: 6px !important;
            padding: 2px 8px !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            margin: 2px 4px 2px 0 !important;
            display: inline-flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: var(--primary-dark) !important;
            margin-right: 5px !important;
            border-right: 1px solid rgba(16,185,129,0.2) !important;
            padding: 0 4px !important;
            background: transparent !important;
            border-top-left-radius: 4px !important;
            border-bottom-left-radius: 4px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: rgba(16,185,129,0.2) !important;
            color: #dc2626 !important;
        }
        /* Dropdown styling */
        .select2-dropdown {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            box-shadow: var(--shadow-md) !important;
            overflow: hidden !important;
            z-index: 99999 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary) !important;
            color: #fff !important;
        }
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: var(--primary-light) !important;
            color: var(--primary-dark) !important;
        }
        .select2-results__option {
            padding: 8px 12px !important;
            font-size: 13px !important;
            text-align: left !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--border) !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            outline: none !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-light) !important;
        }

        .pm-waktu { border: 1.5px solid var(--border); border-radius: 14px; margin-bottom: 16px; overflow: hidden; }
        .pm-waktu-head { display: flex; justify-content: space-between; align-items: center; padding: 10px 16px; background: linear-gradient(135deg, #F8FAFC, #F1F5F9); border-bottom: 1px solid var(--border); }
        .pm-waktu-label { font-weight: 700; font-size: 14px; color: var(--primary); text-transform: uppercase; }
        .pm-waktu-kd { font-family: monospace; font-size: 12px; color: var(--text-muted); }
        .pm-waktu-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px; padding: 14px 16px; }
        .pm-diet-table { width: 100%; border-collapse: collapse; }
        .pm-diet-table th { padding: 9px 12px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); background: #FAFBFC; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        .pm-diet-table td { padding: 9px 12px; border-bottom: 1px solid var(--border-light); font-size: 12px; }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; min-width: 0; width: 100%; }
            .page-header { flex-direction: column; gap: 16px; }
            .stats-row { grid-template-columns: 1fr; }
            .mobile-header { display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: rgba(255,255,255,0.98); border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
            .hamburger { cursor: pointer; padding: 8px; border-radius: var(--radius-sm); background: var(--bg-main); border: none; color: var(--text-main); display: flex; align-items: center; justify-content: center; }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.4); z-index: 45; }
            .sidebar-overlay.active { display: block; }
        }
        @media (max-width: 768px) {
            .modal-overlay {
                padding: 16px 10px !important;
            }
            .modal-card {
                width: 96% !important;
                border-radius: 16px !important;
                margin: 0 auto 100px auto !important;
            }
            .modal-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
            .modal-body {
                padding: 16px !important;
            }
            .modal-head {
                padding: 16px !important;
            }
            .pm-waktu-meta {
                grid-template-columns: 1fr !important;
                padding: 12px !important;
                gap: 12px !important;
            }
            .filter-row {
                grid-template-columns: 1fr !important;
            }
            .filter-actions {
                width: 100% !important;
                justify-content: stretch !important;
            }
            .filter-actions button, .filter-actions a {
                flex: 1 !important;
                justify-content: center !important;
            }
        }
        /* Professional Success Modal Styling */
        .modal-card-success {
            background: #ffffff;
            border-radius: 24px;
            width: 90%;
            max-width: 580px;
            margin: 0 auto 40px auto;
            box-shadow: 0 25px 60px -15px rgba(16, 185, 129, 0.25), 0 0 0 1px rgba(16, 185, 129, 0.1);
            animation: modalScaleUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        @keyframes modalScaleUp {
            0% { opacity: 0; transform: scale(0.92) translateY(15px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .success-hero {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.03) 100%);
            padding: 32px 24px 20px 24px;
            text-align: center;
            position: relative;
            border-bottom: 1px solid rgba(16, 185, 129, 0.12);
        }
        .success-icon-wrapper {
            position: relative;
            width: 72px;
            height: 72px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-icon-pulse {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.2);
            animation: pulseGlow 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
        }
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.25); opacity: 0; }
        }
        .success-icon-circle {
            position: relative;
            z-index: 2;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.5);
            animation: checkPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.1s both;
        }
        @keyframes checkPop {
            0% { transform: scale(0); }
            80% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }
        .success-hero h2 {
            font-size: 20px;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 6px 0;
            letter-spacing: -0.02em;
        }
        .success-hero p {
            font-size: 13px;
            color: #64748B;
            margin: 0;
            line-height: 1.5;
        }
        .success-body {
            padding: 20px 24px;
            max-height: 55vh;
            overflow-y: auto;
            background: #ffffff;
        }
        .success-patient-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .spc-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .spc-info {
            flex-grow: 1;
            min-width: 0;
        }
        .spc-name {
            font-size: 15px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .spc-meta {
            font-size: 12px;
            color: #64748B;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .spc-badge-room {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 6px;
            background: #ECFDF5;
            color: #047857;
            border: 1px solid rgba(16, 185, 129, 0.2);
            font-weight: 600;
            font-size: 11px;
        }
        .success-details-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .sdl-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 14px;
            background: #FAFBFC;
            border: 1px solid #F1F5F9;
            border-radius: 10px;
            font-size: 13px;
        }
        .sdl-label {
            font-weight: 600;
            color: #64748B;
            width: 120px;
            flex-shrink: 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .sdl-value {
            font-weight: 600;
            color: #1E293B;
            text-align: right;
            flex-grow: 1;
            word-break: break-word;
        }
        .waktu-pill-wrap {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        .waktu-pill {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }
        .waktu-pill.pagi { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
        .waktu-pill.siang { background: #E0E7FF; color: #4338CA; border: 1px solid #C7D2FE; }
        .waktu-pill.sore { background: #FCE7F3; color: #BE185D; border: 1px solid #FBCFE8; }
        .diet-pill {
            display: inline-block;
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
            border: 1px solid rgba(16, 185, 129, 0.25);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 4px;
            margin-bottom: 2px;
        }
        .success-foot {
            padding: 16px 24px 20px 24px;
            background: #F8FAFC;
            border-top: 1px solid #E2E8F0;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        .btn-success-main {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: all 0.2s;
        }
        .btn-success-main:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        }
        .btn-success-secondary {
            background: #ffffff;
            color: #475569;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-success-secondary:hover {
            background: #F1F5F9;
            color: #0F172A;
        }

        /* ========================================================
           ULTRA PROFESSIONAL RIWAYAT GIZI MODAL STYLING
           ======================================================== */
        .modal-card-riwayat {
            background: #ffffff;
            border-radius: 20px;
            width: 95%;
            max-width: 1100px;
            margin: 0 auto 30px auto;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35);
            animation: modalIn 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 60px);
            overflow: hidden;
            border: 1px solid #E2E8F0;
        }
        .rw-head {
            padding: 18px 24px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .rw-head-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .rw-head-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            backdrop-filter: blur(4px);
        }
        .rw-head h2 {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin: 0;
        }
        .rw-head-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
        }
        .rw-head-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        /* Patient Banner */
        .rw-patient-banner {
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            flex-shrink: 0;
        }
        .rw-patient-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .rw-patient-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
        }
        .rw-patient-name {
            font-size: 15px;
            font-weight: 700;
            color: #0F172A;
            line-height: 1.2;
        }
        .rw-patient-meta {
            font-size: 12px;
            color: #64748B;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .rw-patient-tags {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .rw-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            color: #334155;
        }

        /* Filter Controls */
        .rw-filter-container {
            padding: 14px 24px;
            background: #ffffff;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            flex-shrink: 0;
        }
        .rw-search-wrapper {
            position: relative;
            flex: 1;
            min-width: 240px;
            max-width: 360px;
        }
        .rw-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
            width: 16px;
            height: 16px;
        }
        .rw-search-input {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            background: #F8FAFC;
            font-size: 13px;
            color: #0F172A;
            outline: none;
            transition: all 0.2s;
        }
        .rw-search-input:focus {
            background: #ffffff;
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }
        .rw-filter-groups {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .rw-pill-group {
            display: inline-flex;
            background: #F1F5F9;
            border-radius: 10px;
            padding: 3px;
            gap: 2px;
        }
        .rw-pill-btn {
            border: none;
            background: transparent;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            transition: all 0.15s;
        }
        .rw-pill-btn:hover {
            color: #0F172A;
        }
        .rw-pill-btn.active {
            background: #ffffff;
            color: #059669;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Enhanced Table Styling */
        .rw-table-body {
            padding: 0 24px 24px;
            overflow-y: auto;
            flex-grow: 1;
            background: #ffffff;
        }
        .rw-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
        }
        .rw-table thead th {
            position: sticky;
            top: 0;
            background: #FAFBFC;
            padding: 12px 14px;
            border-bottom: 2px solid #E2E8F0;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
            z-index: 10;
        }
        .rw-table tbody tr {
            transition: background 0.15s;
            border-bottom: 1px solid #F1F5F9;
        }
        .rw-table tbody tr:hover {
            background-color: #F8FAFC;
        }
        .rw-table tbody td {
            padding: 14px;
            vertical-align: top;
            border-bottom: 1px solid #F1F5F9;
            font-size: 13px;
        }

        /* Sesi Badges */
        .rw-sesi-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .rw-sesi-pill.pagi {
            background: #FEF3C7;
            color: #B45309;
            border: 1px solid #FDE68A;
        }
        .rw-sesi-pill.siang {
            background: #E0E7FF;
            color: #4338CA;
            border: 1px solid #C7D2FE;
        }
        .rw-sesi-pill.sore {
            background: #F3E8FF;
            color: #7E22CE;
            border: 1px solid #E9D5FF;
        }

        /* Diet Badges */
        .rw-diet-tag {
            display: inline-block;
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #A7F3D0;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin: 2px 4px 2px 0;
            line-height: 1.3;
        }

        /* Status Badges */
        .rw-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .rw-status-pill.aktif {
            background: #ECFDF5;
            color: #059669;
            border: 1px solid #A7F3D0;
        }
        .rw-status-pill.aktif .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10B981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3);
        }
        .rw-status-pill.riwayat {
            background: #F1F5F9;
            color: #64748B;
            border: 1px solid #CBD5E1;
        }

        /* Alergi tag */
        .rw-alergi-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Diagnosa & Ket cards */
        .rw-diag-box {
            display: flex;
            flex-direction: column;
            gap: 5px;
            max-width: 280px;
        }
        .rw-diag-item {
            font-size: 12px;
            line-height: 1.4;
            color: #334155;
        }
        .rw-diag-label {
            font-size: 10.5px;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .rw-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 11px;
            color: #475569;
            font-weight: 600;
            margin-top: 3px;
        }
        @media (min-width: 1025px) { .mobile-header { display: none; } }
    </style>
</head>
<body>
    @include('partials.sidebar')
    
    <div class="mobile-header">
        <button class="hamburger" onclick="document.querySelector('.sidebar').classList.toggle('active');document.querySelector('.sidebar-overlay').classList.toggle('active')">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <span style="font-weight:700;font-size:16px;color:var(--text-main);">Permintaan Gizi</span>
        <div class="avatar" style="width:28px;height:28px;font-size:11px;">{{ substr(Session::get('user_id'), 0, 1) }}</div>
    </div>
    <div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('active');document.querySelector('.sidebar-overlay').classList.remove('active')"></div>

    <div class="main-content">
        {{-- Header --}}
        <div class="page-header">
            <div>
                <h1>Permintaan Gizi</h1>
                <p>Monitoring pasien rawat inap per ruangan dan permintaan diet.</p>
            </div>
            <div class="header-actions">
                <div class="user-profile">
                    <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                    <div class="user-info"><span>{{ Session::get('user_id') }}</span></div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['total_pasien'] }}</div>
                    <div class="stat-label">Total Pasien Rawat Inap</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['total_ruangan'] }}</div>
                    <div class="stat-label">Ruangan Rawat Inap</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['total_beri_diet'] }}</div>
                    <div class="stat-label">Pasien dengan Diet ({{ $tanggal }})</div>
                </div>
            </div>
        </div>

        {{-- Filter Lanjutan --}}
        <div class="filter-card">
            <div class="filter-head">
                <div class="filter-head-left">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter Lanjutan</span>
                </div>
                <span class="filter-head-badge">{{ $stats['total_pasien'] }} pasien</span>
            </div>
            <div class="filter-body">
                <form method="GET" action="{{ route('gizi.permintaan') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Tanggal</label>
                            <input type="date" name="tanggal" value="{{ $tanggal }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Ruangan</label>
                            <select name="bangsal">
                                <option value="">Semua Ruangan</option>
                                @foreach($bangsalList as $b)
                                    <option value="{{ $b->kd_bangsal }}" {{ request('bangsal') == $b->kd_bangsal ? 'selected' : '' }}>{{ $b->nm_bangsal }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"></path></svg> Kelas</label>
                            <select name="kelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg> Status Permintaan</label>
                            <select name="filter_status">
                                <option value="">Semua Pasien</option>
                                <optgroup label="-- BELUM ORDER --">
                                    <option value="belum_pagi" {{ request('filter_status') == 'belum_pagi' ? 'selected' : '' }}>Belum Order PAGI</option>
                                    <option value="belum_siang" {{ request('filter_status') == 'belum_siang' ? 'selected' : '' }}>Belum Order SIANG</option>
                                    <option value="belum_sore" {{ request('filter_status') == 'belum_sore' ? 'selected' : '' }}>Belum Order SORE</option>
                                    <option value="belum_semua" {{ request('filter_status') == 'belum_semua' ? 'selected' : '' }}>Belum Ada Order (Sama Sekali)</option>
                                </optgroup>
                                <optgroup label="-- SUDAH ORDER --">
                                    <option value="sudah_pagi" {{ request('filter_status') == 'sudah_pagi' ? 'selected' : '' }}>Sudah Order PAGI</option>
                                    <option value="sudah_siang" {{ request('filter_status') == 'sudah_siang' ? 'selected' : '' }}>Sudah Order SIANG</option>
                                    <option value="sudah_sore" {{ request('filter_status') == 'sudah_sore' ? 'selected' : '' }}>Sudah Order SORE</option>
                                    <option value="sudah_semua" {{ request('filter_status') == 'sudah_semua' ? 'selected' : '' }}>Sudah Order LENGKAP (Pagi, Siang & Sore)</option>
                                    <option value="sudah_ada" {{ request('filter_status') == 'sudah_ada' ? 'selected' : '' }}>Sudah Ada Order (Minimal 1 Sesi)</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Nama Pasien</label>
                            <input type="text" name="nama_pasien" placeholder="Cari nama..." value="{{ request('nama_pasien') }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg> No. RM</label>
                            <input type="text" name="no_rm" placeholder="No. RM..." value="{{ request('no_rm') }}">
                        </div>
                        <div class="filter-group">
                            <label><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg> No. Rawat</label>
                            <input type="text" name="no_rawat" placeholder="No. rawat..." value="{{ request('no_rawat') }}">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-filter">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari
                            </button>
                            <a href="{{ route('gizi.permintaan') }}" class="btn-reset">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="tabs-container" style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
            <button type="button" class="tab-btn active" onclick="switchTab('diet-pasien-tab')" id="tab-btn-diet" style="padding: 10px 18px; border: none; background: var(--primary-light); color: var(--primary); font-weight: 600; font-size: 14px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Daftar Pasien & Diet
            </button>
            <button type="button" class="tab-btn" onclick="switchTab('rekap-dapur-tab')" id="tab-btn-rekap" style="padding: 10px 18px; border: none; background: transparent; color: var(--text-muted); font-weight: 500; font-size: 14px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4h-2m2 0a2 2 0 100-4h-2m2 4a2 2 0 110 4h-2m2-4v4a2 2 0 11-4 0v-4"></path></svg>
                Rekapitulasi Dapur
            </button>
        </div>

        <div id="diet-pasien-tab" class="tab-panel">
            {{-- Daftar Pasien Per Ruangan --}}
        @if (!$hasRoomFilter)
            <div class="room-card">
                <div class="empty-state">
                    <svg style="width:48px;height:48px;display:block;margin:0 auto 12px;color:#CBD5E1;" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <div style="font-size:16px;font-weight:600;color:var(--text-main);">Silakan Pilih Ruangan</div>
                    <div style="font-size:13px;margin-top:4px;max-width:420px;margin-left:auto;margin-right:auto;">Pilih ruangan rawat inap pada filter di atas, lalu klik <strong>Cari</strong> untuk menampilkan daftar pasien beserta permintaan dietnya.</div>
                </div>
            </div>
        @elseif($grouped->isEmpty())
            <div class="room-card">
                <div class="empty-state">
                    <svg style="width:48px;height:48px;display:block;margin:0 auto 12px;color:#CBD5E1;" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <div style="font-size:16px;font-weight:600;color:var(--text-main);">Tidak Ada Pasien</div>
                    <div style="font-size:13px;margin-top:4px;">Tidak ada pasien rawat inap yang sesuai dengan filter pada tanggal tersebut.</div>
                </div>
            </div>
        @else
            @foreach($grouped as $nmBangsal => $pasienList)
            <div class="room-card">
                <div class="room-head">
                    <div class="room-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="name">{{ $nmBangsal }}</span>
                        <span class="badge-count">{{ $pasienList->count() }} pasien</span>
                    </div>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Kamar</th>
                                <th>Pasien</th>
                                <th>Umur / JK</th>
                                <th>Diagnosa Awal</th>
                                <th>Tgl Masuk</th>
                                <th>Kelas</th>
                                @forelse($waktuColumns as $waktu)
                                    <th class="text-center">{{ $waktu }}</th>
                                @empty
                                    <th>Diet</th>
                                @endforelse
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pasienList as $pasien)
                                @php
                                    $umur = null;
                                    if ($pasien->tgl_lahir && $pasien->tgl_lahir !== '0000-00-00') {
                                        try {
                                            $umur = \Carbon\Carbon::parse($pasien->tgl_lahir)->age;
                                        } catch (\Throwable $e) { $umur = null; }
                                    }
                                    $jk = ($pasien->jk === 'L') ? 'Laki-laki' : ($pasien->jk === 'P' ? 'Perempuan' : $pasien->jk);
                                    $initial = strtoupper(substr($pasien->nm_pasien, 0, 1));
                                @endphp
                                <tr style="cursor:pointer;" onclick="openPermintaanModal('{{ $pasien->no_rawat }}')">
                                    <td><span class="font-mono">{{ $pasien->kd_kamar }}</span></td>
                                    <td>
                                        <div class="patient-cell">
                                            <div class="patient-avatar">{{ $initial }}</div>
                                            <div>
                                                <div class="patient-name">{{ $pasien->nm_pasien }}</div>
                                                <div class="patient-rm">{{ $pasien->no_rkm_medis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-xs">
                                        {{ $umur !== null ? $umur . ' th' : '-' }} / {{ $jk }}
                                    </td>
                                    <td class="text-xs">{{ $pasien->diagnosa_awal ?: '-' }}</td>
                                    <td class="text-xs">{{ \Carbon\Carbon::parse($pasien->tgl_masuk)->format('d/m/Y') }}</td>
                                    <td><span class="badge badge-muted">{{ $pasien->kelas ?: '-' }}</span></td>
                                    @forelse($waktuColumns as $waktu)
                                        @php $pm = $permintaanPerPasien[$pasien->no_rawat]['waktu'][$waktu] ?? null; @endphp
                                        <td class="text-center" style="white-space: nowrap;">
                                            @if($pm)
                                                <span class="diet-badge">{{ $pm['bentuk'] ?? '-' }}</span>
                                                @if(!empty($pm['diets']))
                                                    <div class="text-xs" style="color: var(--primary); margin-top: 2px;">{{ collect($pm['diets'])->pluck('namaDiet')->implode(', ') }}</div>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @empty
                                        <td>
                                            <span class="badge badge-muted">Belum ada permintaan gizi</span>
                                        </td>
                                    @endforelse
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        @endif
        </div>

        {{-- Tab Panel Rekapitulasi Dapur --}}
        <div id="rekap-dapur-tab" class="tab-panel" style="display: none;">
            @if(empty($rekapData))
                <div class="room-card">
                    <div class="empty-state">
                        <svg style="width:48px;height:48px;display:block;margin:0 auto 12px;color:#CBD5E1;" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <div style="font-size:16px;font-weight:600;color:var(--text-main);">Tidak Ada Rekapitulasi Dapur</div>
                        <div style="font-size:13px;margin-top:4px;">Belum ada data permintaan gizi aktif pada tanggal ini untuk ruangan yang terpilih.</div>
                    </div>
                </div>
            @else
                @foreach($rekapData as $roomName => $roomRekap)
                    <div class="room-card" style="margin-bottom: 24px;">
                        <div class="room-head" style="border-bottom: 1.5px solid var(--border); padding-bottom: 12px; margin-bottom: 16px;">
                            <div class="room-head-left">
                                <span class="name" style="font-size:16px; font-weight:700; color:var(--text-main);">{{ $roomName }}</span>
                            </div>
                        </div>
                        <div class="table-scroll">
                            <table class="pm-diet-table" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #FAFBFC;">
                                        <th style="width: 15%; padding: 12px; border-bottom: 2px solid var(--border); text-align: left; font-weight:600; font-size:12px; color:var(--text-muted); text-transform:uppercase;">Sesi Makan</th>
                                        <th style="width: 42%; padding: 12px; border-bottom: 2px solid var(--border); text-align: left; font-weight:600; font-size:12px; color:var(--text-muted); text-transform:uppercase;">Bentuk Makanan (Total Porsi)</th>
                                        <th style="width: 43%; padding: 12px; border-bottom: 2px solid var(--border); text-align: left; font-weight:600; font-size:12px; color:var(--text-muted); text-transform:uppercase;">Rincian Jenis Diet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['PAGI', 'SIANG', 'SORE'] as $sesi)
                                        @php
                                            $sesiRekap = $roomRekap['waktu'][$sesi] ?? null;
                                        @endphp
                                        <tr style="border-bottom: 1px solid var(--border-light);">
                                            <td style="padding: 14px 12px; font-weight: 700; color: var(--primary); font-size: 13px;">
                                                {{ $sesi }}
                                            </td>
                                            <td style="padding: 14px 12px; vertical-align: top;">
                                                @if($sesiRekap && !empty($sesiRekap['bentuk']))
                                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                                        @foreach($sesiRekap['bentuk'] as $bentuk => $count)
                                                            <div style="display: flex; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1px solid var(--border); border-radius: 8px; padding: 6px 12px; font-size: 13px;">
                                                                <span style="font-weight: 500; color: var(--text-main);">{{ $bentuk }}</span>
                                                                <span class="badge" style="background: var(--primary); color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 700;">{{ $count }} porsi</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span style="color: var(--text-muted); font-size: 13px; font-style: italic;">Tidak ada pesanan</span>
                                                @endif
                                            </td>
                                            <td style="padding: 14px 12px; vertical-align: top;">
                                                @if($sesiRekap && !empty($sesiRekap['diet']))
                                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                                        @foreach($sesiRekap['diet'] as $diet => $count)
                                                            <span class="diet-badge" style="display: inline-flex; align-items: center; gap: 6px; background: var(--primary-light); color: var(--primary-dark); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; padding: 4px 10px; font-size: 12px; font-weight: 600;">
                                                                {{ $diet }} 
                                                                <strong style="background: rgba(16,185,129,0.2); padding: 1px 5px; border-radius: 4px; font-size: 10px;">{{ $count }}</strong>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span style="color: var(--text-muted); font-size: 13px;">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    {{-- Modal Detail Permintaan Gizi --}}
    <div class="modal-overlay" id="permintaanModal">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Detail Permintaan Gizi</h2>
                <div style="display:flex; gap:8px; align-items:center;">
                    <button type="button" class="btn-reset" onclick="openRiwayatModal(currentPmNoRawat)" style="padding:8px 16px; font-size:12px; border:1px solid var(--border); background:white; color:var(--text-main); display:inline-flex; align-items:center; gap:4px; height:32px; border-radius:6px; cursor:pointer;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat
                    </button>
                    <button type="button" class="btn-filter" onclick="openBuatPermintaanModal(currentPmNoRawat)" style="padding:8px 16px; font-size:12px; display:inline-flex; align-items:center; gap:4px; height:32px; border-radius:6px; cursor:pointer;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Buat Permintaan
                    </button>
                    <button class="modal-close" onclick="closePermintaanModal()">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-section" id="pm-info">
                    <div class="modal-section-title">Informasi Permintaan</div>
                    <div class="modal-grid" id="pm-info-grid"></div>
                </div>

                <div class="modal-section">
                    <div class="modal-section-title">Pilih Waktu Makan</div>
                    <div class="pm-checkboxes" id="pm-checkboxes"></div>
                </div>

                <div class="modal-section">
                    <div class="modal-section-title">Detail Diet Pasien</div>
                    <div id="pm-detail"></div>
                </div>
            </div>
        </div>
    </div>    {{-- Modal Buat Permintaan Gizi --}}
    <div class="modal-overlay" id="buatPermintaanModal">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Buat Permintaan Gizi</h2>
                <button class="modal-close" onclick="closeBuatPermintaanModal()">&times;</button>
            </div>
            <form id="formBuatPermintaan" style="display: flex; flex-direction: column; flex-grow: 1; overflow: hidden; margin: 0;">
                @csrf
                <input type="hidden" name="kdPermintaan" id="bp-kdPermintaan" value="">
                <div class="modal-body">
                    <div class="modal-section">
                        <div class="modal-section-title" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;" onclick="togglePatientData()">
                            <span>Data Pasien</span>
                            <button type="button" id="btn-toggle-patient-data" style="background: none; border: none; color: var(--primary); font-weight: 600; font-size: 11px; cursor: pointer; display: flex; align-items: center; gap: 4px; text-transform: uppercase;">
                                <span id="text-toggle-patient-data">Sembunyikan</span>
                                <svg id="icon-toggle-patient-data" style="width: 14px; height: 14px; transition: transform 0.2s; transform: rotate(180deg);" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path></svg>
                            </button>
                        </div>
                        <div class="modal-grid" id="bp-patient-grid">
                            <div class="modal-field">
                                <div class="ml">No. Rawat</div>
                                <input type="text" class="pm-input" name="noRawat" id="bp-noRawat" readonly>
                            </div>
                            <div class="modal-field">
                                <div class="ml">No. Rekam Medis</div>
                                <input type="text" class="pm-input" name="no_rekamedis" id="bp-noRekam" readonly>
                            </div>
                            <div class="modal-field full">
                                <div class="ml">Nama Pasien</div>
                                <input type="text" class="pm-input" name="nama_pasien" id="bp-namaPasien" readonly>
                            </div>
                            <div class="modal-field">
                                <div class="ml">Ruangan (kdRanap)</div>
                                <input type="text" class="pm-input" name="kdRanap" id="bp-kdRanap" readonly>
                            </div>
                            <div class="modal-field">
                                <div class="ml">Nama Ruangan</div>
                                <input type="text" class="pm-input" name="nmRanap" id="bp-nmRanap" readonly>
                            </div>
                            <div class="modal-field">
                                <div class="ml">Kelas</div>
                                <select class="pm-input select2-pm" name="kdKelas" id="bp-kdKelas" style="width:100%;">
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-field">
                                <div class="ml">Bed</div>
                                <select class="pm-input select2-pm" name="kdBed" id="bp-kdBed" style="width:100%;">
                                    <!-- Will be populated dynamically via API -->
                                </select>
                            </div>
                            <div class="modal-field">
                                <div class="ml">Ahli Gizi</div>
                                <input type="text" class="pm-input" name="kdAhliGizi" id="bp-kdAhliGizi" value="{{ Session::get('user_id') }}">
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title">Waktu Makan <span style="color:#dc2626;">*</span></div>
                        <div class="pm-checkboxes">
                            @foreach(['PAGI', 'SIANG', 'SORE'] as $w)
                                <label class="pm-cb"><input type="checkbox" name="waktu[]" value="{{ $w }}"> {{ $w }}</label>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title">Bentuk Makanan <span style="color:#dc2626;">*</span></div>
                        <select class="pm-input select2-pm" name="kdBentukMakan" id="bp-bentukMakan" style="width:100%;">
                            <option value="">-- Pilih Bentuk Makanan --</option>
                            @foreach($bentukMakanOptions as $b)
                                <option value="{{ trim($b->kd_bentukmakan) }}">{{ $b->nama_bentukmakan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title">Diet</div>
                        <select class="pm-input select2-pm" name="kdDiet[]" id="bp-kdDiet" multiple style="width:100%;">
                            @foreach($dietOptions as $d)
                                @if(trim($d->kd_diet) !== '-')
                                    <option value="{{ trim($d->kd_diet) }}">{{ $d->diet }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-title">Alergi</div>
                        <select class="pm-input select2-pm" name="kdAlergi[]" id="bp-kdAlergi" multiple style="width:100%;">
                            @foreach($alergiOptions as $a)
                                <option value="{{ $a->kd_alergi }}">{{ $a->alergi }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="alergi" id="bp-alergi" value="">
                    </div>

                    <input type="hidden" name="diagnosaGizi" id="bp-diagnosaGizi" value="">
                    <input type="hidden" name="extraDiet" id="bp-extraDiet" value="">

                    <div class="modal-section">
                        <div class="modal-section-title">Keterangan</div>
                        <div class="modal-grid">
                            <div class="modal-field">
                                <div class="ml">Keterangan</div>
                                <textarea class="pm-input" name="keteranganDiet" rows="2" placeholder="Keterangan (opsional)"></textarea>
                            </div>
                            <div class="modal-field">
                                <div class="ml">Keterangan Snack</div>
                                <textarea class="pm-input" name="keteranganSnack" rows="2" placeholder="Keterangan snack (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-reset" onclick="closeBuatPermintaanModal()">Batal</button>
                    <button type="button" class="btn-filter" id="btn-save-permintaan">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Riwayat Permintaan Gizi Pasien (Enhanced Ultra-Clean) --}}
    <div class="modal-overlay" id="riwayatGiziModal">
        <div class="modal-card-riwayat">
            {{-- Head --}}
            <div class="rw-head">
                <div class="rw-head-title-wrap">
                    <div class="rw-head-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2>Riwayat Permintaan Gizi Pasien</h2>
                        <div style="font-size: 11.5px; opacity: 0.88; font-weight: 400;">Log perubahan diet, jadwal makan, dan riwayat klinis gizi</div>
                    </div>
                </div>
                <button class="rw-head-close" onclick="closeRiwayatModal()">&times;</button>
            </div>

            {{-- Patient Summary Banner --}}
            <div class="rw-patient-banner" id="rw-patient-banner">
                <div class="rw-patient-info">
                    <div class="rw-patient-avatar" id="rw-patient-avatar">P</div>
                    <div>
                        <div class="rw-patient-name" id="rw-patient-name">Nama Pasien</div>
                        <div class="rw-patient-meta">
                            <span>No. RM: <strong id="rw-patient-rm" style="color: #0F172A;">-</strong></span>
                            <span>•</span>
                            <span>No. Rawat: <strong id="rw-patient-rawat" style="color: #0F172A;" class="font-mono">-</strong></span>
                        </div>
                    </div>
                </div>
                <div class="rw-patient-tags">
                    <div class="rw-tag" id="rw-tag-room">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span id="rw-patient-room">-</span>
                    </div>
                    <div class="rw-tag" style="background:#ECFDF5; border-color:#A7F3D0; color:#047857;" id="rw-tag-count">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span id="rw-total-count">0 Riwayat</span>
                    </div>
                </div>
            </div>

            {{-- Filter & Search Toolbar --}}
            <div class="rw-filter-container">
                <div class="rw-search-wrapper">
                    <svg class="rw-search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z"></path></svg>
                    <input type="text" id="rw-search-input" class="rw-search-input" placeholder="Cari diet, bentuk, petugas, diagnosa..." oninput="filterRiwayatData()">
                </div>

                <div class="rw-filter-groups">
                    {{-- Sesi Filter --}}
                    <div style="display:flex; align-items:center; gap:6px;">
                        <span style="font-size:11px; font-weight:600; color:#64748B; text-transform:uppercase;">Sesi:</span>
                        <div class="rw-pill-group">
                            <button type="button" class="rw-pill-btn active" data-sesi="ALL" onclick="setRiwayatSesiFilter('ALL', this)">Semua</button>
                            <button type="button" class="rw-pill-btn" data-sesi="PAGI" onclick="setRiwayatSesiFilter('PAGI', this)">Pagi</button>
                            <button type="button" class="rw-pill-btn" data-sesi="SIANG" onclick="setRiwayatSesiFilter('SIANG', this)">Siang</button>
                            <button type="button" class="rw-pill-btn" data-sesi="SORE" onclick="setRiwayatSesiFilter('SORE', this)">Sore</button>
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div style="display:flex; align-items:center; gap:6px;">
                        <span style="font-size:11px; font-weight:600; color:#64748B; text-transform:uppercase;">Status:</span>
                        <div class="rw-pill-group">
                            <button type="button" class="rw-pill-btn active" data-status="ALL" onclick="setRiwayatStatusFilter('ALL', this)">Semua</button>
                            <button type="button" class="rw-pill-btn" data-status="1" onclick="setRiwayatStatusFilter('1', this)">Aktif</button>
                            <button type="button" class="rw-pill-btn" data-status="0" onclick="setRiwayatStatusFilter('0', this)">Riwayat</button>
                        </div>
                    </div>

                    <button type="button" onclick="resetRiwayatFilters()" title="Reset Filter" style="padding:6px 10px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; cursor:pointer; font-size:12px; color:#64748B; display:inline-flex; align-items:center; gap:4px; font-weight:600;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path></svg>
                        Reset
                    </button>
                </div>
            </div>

            {{-- Table Content --}}
            <div class="rw-table-body">
                <div class="table-scroll">
                    <table class="rw-table">
                        <thead>
                            <tr>
                                <th style="width: 14%;">Tgl & Jam</th>
                                <th style="width: 9%;">Sesi</th>
                                <th style="width: 14%;">Bentuk Makanan</th>
                                <th style="width: 20%;">Rincian Diet</th>
                                <th style="width: 12%;">Alergi</th>
                                <th style="width: 21%;">Diagnosa & Catatan</th>
                                <th style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="riwayat-gizi-table-body">
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <span>Memuat data riwayat...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Sukses Permintaan Gizi (Ultra Professional) --}}
    <div class="modal-overlay" id="modalSuccessPermintaan">
        <div class="modal-card-success">
            <div class="success-hero">
                <div class="success-icon-wrapper">
                    <div class="success-icon-pulse"></div>
                    <div class="success-icon-circle">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                </div>
                <h2 id="msp-title">Permintaan Gizi Berhasil Disimpan</h2>
                <p id="msp-subtitle">Permintaan menu gizi pasien telah tersimpan dan siap diproses oleh instalasi dapur.</p>
            </div>
            
            <div class="success-body">
                <div class="success-patient-card">
                    <div class="spc-avatar" id="msp-avatar">P</div>
                    <div class="spc-info">
                        <div class="spc-name" id="msp-nama-pasien">-</div>
                        <div class="spc-meta">
                            <span>No. RM: <strong id="msp-no-rm" style="color:#0F172A;">-</strong></span>
                            <span>&bull;</span>
                            <span>No. Rawat: <strong id="msp-no-rawat" style="color:#0F172A;">-</strong></span>
                        </div>
                        <div style="margin-top: 6px;">
                            <span class="spc-badge-room" id="msp-ruang-bed">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <span id="msp-text-ruang">-</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="success-details-list">
                    <div class="sdl-row">
                        <span class="sdl-label">Waktu Makan</span>
                        <div class="sdl-value">
                            <div class="waktu-pill-wrap" id="msp-waktu-wrap"></div>
                        </div>
                    </div>
                    <div class="sdl-row">
                        <span class="sdl-label">Bentuk Makanan</span>
                        <span class="sdl-value" id="msp-bentuk-makan" style="color:var(--primary); font-weight:700;">-</span>
                    </div>
                    <div class="sdl-row">
                        <span class="sdl-label">Diet Pasien</span>
                        <div class="sdl-value" id="msp-diet-wrap">
                            <span style="color:#64748B; font-weight:400; font-style:italic;">Biasa / Tanpa Diet Khusus</span>
                        </div>
                    </div>
                    <div class="sdl-row" id="msp-row-alergi">
                        <span class="sdl-label">Alergi</span>
                        <span class="sdl-value" id="msp-alergi">-</span>
                    </div>
                    <div class="sdl-row" id="msp-row-diagnosa" style="display:none;">
                        <span class="sdl-label">Diagnosa Gizi</span>
                        <span class="sdl-value" id="msp-diagnosa" style="font-size:12px; font-weight:500;">-</span>
                    </div>
                    <div class="sdl-row" id="msp-row-keterangan" style="display:none;">
                        <span class="sdl-label">Catatan</span>
                        <span class="sdl-value" id="msp-keterangan" style="font-size:12px; font-weight:500; font-style:italic;">-</span>
                    </div>
                </div>
            </div>

            <div class="success-foot">
                <button type="button" class="btn-success-secondary" onclick="closeSuccessModal(false)">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Input Pasien Lain
                </button>
                <button type="button" class="btn-success-main" onclick="closeSuccessModal(true)">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Selesai & Muat Ulang
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var permintaanData = @json($permintaanPerPasien);
        var pasienData = @json($pasienInfo);
        var currentPmNoRawat = null;
        var bedCache = {};

        function isSesiEditAllowed(waktu) {
            return true;
        }

        function openPermintaanModal(noRawat) {
            currentPmNoRawat = noRawat;
            var data = permintaanData[noRawat];

            if (!data) {
                document.getElementById('pm-info-grid').innerHTML =
                    '<div class="modal-field full"><div class="mv">Belum ada permintaan gizi untuk pasien ini.</div></div>';
                document.getElementById('pm-checkboxes').innerHTML = '';
                document.getElementById('pm-detail').innerHTML =
                    '<span class="badge badge-muted">Belum ada permintaan gizi. Klik "Buat Permintaan" untuk membuat.</span>';
                document.getElementById('permintaanModal').classList.add('active');
                document.body.classList.add('modal-open');
                return;
            }

            var info = data.info || {};

            var infoRows = [
                ['Tgl Permintaan', info.tglPermintaan],
                ['No. Rawat', info.noRawat],
                ['No. Rekam Medis', info.no_rekamedis],
                ['Nama Pasien', info.nama_pasien],
                ['Ruangan', (info.kdRanap || '') + (info.nmRanap ? ' - ' + info.nmRanap : '')],
                ['Kelas / Bed', (info.kdKelas || '-') + ' / ' + (info.kdBed || '-')],
                ['Alergi', info.alergi],
                ['Diagnosa Gizi', info.diagnosaGizi],
                ['Ahli Gizi', info.kdAhliGizi],
                ['Status', info.statusUpdate]
            ];

            var gridHtml = '';
            infoRows.forEach(function (r) {
                gridHtml += '<div class="modal-field' + (r[0] === 'Diagnosa Gizi' ? ' full' : '') + '">' +
                    '<div class="ml">' + r[0] + '</div>' +
                    '<div class="mv">' + (r[1] ? esc(r[1]) : '—') + '</div>' +
                    '</div>';
            });
            document.getElementById('pm-info-grid').innerHTML = gridHtml;

            // Checkboxes waktu
            var waktus = Object.keys(data.waktu || {});
            var cbHtml = '';
            waktus.forEach(function (w) {
                cbHtml += '<label class="pm-cb"><input type="checkbox" class="pm-cb-input" value="' + w + '" checked> ' + w + '</label>';
            });
            document.getElementById('pm-checkboxes').innerHTML = cbHtml || '<span class="text-muted">Tidak ada waktu makan</span>';

            // Detail per waktu
            var detailHtml = '';
            waktus.forEach(function (w) {
                var pm = data.waktu[w] || {};
                var diets = (pm.diets || []).map(function (d) {
                    return '<tr>' +
                        '<td class="font-mono">' + esc(d.kdPermintaan || '') + '</td>' +
                        '<td class="font-mono">' + esc(d.kdDiet || '') + '</td>' +
                        '<td>' + esc(d.namaDiet || '') + '</td>' +
                        '<td>' + esc(fmtDt(d.dateCreated)) + '</td>' +
                        '<td>' + esc(d.status || '') + '</td>' +
                        '</tr>';
                }).join('');
                var allowed = isSesiEditAllowed(w);
                var btnHtml = '';
                if (allowed) {
                    btnHtml = '<button type="button" class="btn-filter" onclick="editSesiGizi(\'' + esc(info.noRawat || '') + '\', \'' + esc(w) + '\')" style="padding:4px 8px; font-size:11px; background:var(--primary); color:white; display:inline-flex; align-items:center; gap:4px; border-radius:6px; cursor:pointer;">' +
                                '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>' +
                                'Edit Sesi' +
                            '</button>';
                } else {
                    btnHtml = '<button type="button" class="btn-filter" disabled style="padding:4px 8px; font-size:11px; background:#94A3B8; color:white; display:inline-flex; align-items:center; gap:4px; border-radius:6px; cursor:not-allowed; opacity:0.65;" title="Batas waktu edit sesi ini telah terlewati.">' +
                                '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>' +
                                'Edit Sesi (Terkunci)' +
                            '</button>';
                }

                detailHtml += '<div class="pm-waktu" data-waktu="' + w + '">' +
                    '<div class="pm-waktu-head">' +
                        '<span class="pm-waktu-label">' + w + '</span>' +
                        '<div style="display:flex; align-items:center; gap:12px;">' +
                            btnHtml +
                            '<span class="pm-waktu-kd">' + esc(pm.kdPermintaan || '') + '</span>' +
                        '</div>' +
                    '</div>' +
                    '<div class="pm-waktu-meta">' +
                        '<div class="modal-field"><div class="ml">Bentuk Makan</div><div class="mv">' + esc(pm.bentuk || '-') + '</div></div>' +
                        '<div class="modal-field"><div class="ml">Extra Diet</div><div class="mv">' + esc(pm.extraDiet || '-') + '</div></div>' +
                        '<div class="modal-field"><div class="ml">Keterangan Diet</div><div class="mv">' + esc(pm.keteranganDiet || '-') + '</div></div>' +
                        '<div class="modal-field"><div class="ml">Keterangan Snack</div><div class="mv">' + esc(pm.keteranganSnack || '-') + '</div></div>' +
                    '</div>' +
                    '<table class="pm-diet-table">' +
                        '<thead><tr><th>kdPermintaan</th><th>kdDiet</th><th>namaDiet</th><th>dateCreated</th><th>status</th></tr></thead>' +
                        '<tbody>' + (diets || '<tr><td colspan="5" class="text-muted">Tidak ada detail diet</td></tr>') + '</tbody>' +
                    '</table>' +
                    '</div>';
            });
            document.getElementById('pm-detail').innerHTML = detailHtml;

            document.getElementById('permintaanModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closePermintaanModal() {
            document.getElementById('permintaanModal').classList.remove('active');
            if (!document.querySelector('.modal-overlay.active')) {
                document.body.classList.remove('modal-open');
            }
        }

        function openBuatPermintaanModal(noRawat) {
            closePermintaanModal();
            var p = pasienData[noRawat];
            if (!p) {
                alert('Data pasien tidak ditemukan.');
                return;
            }

            currentPmNoRawat = noRawat;
            document.getElementById('formBuatPermintaan').reset();

            // Reset modal mode details to "Buat"
            document.getElementById('bp-kdPermintaan').value = '';
            document.querySelector('#buatPermintaanModal .modal-head h2').textContent = 'Buat Permintaan Gizi';
            document.getElementById('btn-save-permintaan').innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Permintaan';
            
            document.querySelectorAll('input[name="waktu[]"]').forEach(function (cb) {
                var allowed = isSesiEditAllowed(cb.value);
                cb.disabled = !allowed;
                cb.checked = false;
                if (allowed) {
                    cb.closest('.pm-cb').style.opacity = '1';
                    cb.closest('.pm-cb').style.cursor = 'pointer';
                    cb.closest('.pm-cb').setAttribute('title', '');
                } else {
                    cb.closest('.pm-cb').style.opacity = '0.4';
                    cb.closest('.pm-cb').style.cursor = 'not-allowed';
                    cb.closest('.pm-cb').setAttribute('title', 'Batas waktu pengisian untuk sesi ini telah terlewati.');
                }
            });

            // Ensure patient grid is visible when opening
            var grid = document.getElementById('bp-patient-grid');
            if (grid) {
                grid.style.display = 'grid';
                var text = document.getElementById('text-toggle-patient-data');
                if (text) text.textContent = 'Sembunyikan';
                var icon = document.getElementById('icon-toggle-patient-data');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }

            document.getElementById('bp-noRawat').value = p.noRawat || '';
            document.getElementById('bp-noRekam').value = p.no_rekamedis || '';
            document.getElementById('bp-namaPasien').value = p.nama_pasien || '';
            document.getElementById('bp-kdRanap').value = p.kdRanap || '';
            document.getElementById('bp-nmRanap').value = p.nmRanap || '';
            document.getElementById('bp-kdAhliGizi').value = '{{ Session::get('user_id') }}';
            document.getElementById('bp-alergi').value = '';
            document.getElementById('bp-diagnosaGizi').value = '';

            // Default Kelas
            $('#bp-kdKelas').val(p.kdKelas || '').trigger('change');

            // Load beds for kdRanap
            var kdRanap = p.kdRanap || '';
            var selectBed = $('#bp-kdBed');

            function populateBeds(beds) {
                selectBed.empty();
                if (beds && beds.length > 0) {
                    beds.forEach(function (bed) {
                        selectBed.append(new Option(bed, bed));
                    });
                } else {
                    selectBed.append('<option value="">Tidak ada bed</option>');
                }
                
                // Default Bed from SIMRS
                var defaultBed = p.kdBed || '';
                if (defaultBed) {
                    if (selectBed.find("option[value='" + defaultBed + "']").length === 0) {
                        selectBed.append(new Option(defaultBed, defaultBed));
                    }
                    selectBed.val(defaultBed).trigger('change');
                } else {
                    selectBed.trigger('change');
                }
            }

            if (kdRanap) {
                if (bedCache[kdRanap]) {
                    populateBeds(bedCache[kdRanap]);
                } else {
                    selectBed.empty().append('<option value="">Loading...</option>').trigger('change');
                    fetch('/instalasi-gizi/kamar/' + encodeURIComponent(kdRanap))
                        .then(response => response.json())
                        .then(result => {
                            if (result.success && result.data) {
                                bedCache[kdRanap] = result.data;
                                populateBeds(result.data);
                            } else {
                                populateBeds([]);
                            }
                        })
                        .catch(err => {
                            console.error('Error fetching beds:', err);
                            selectBed.empty().append('<option value="">Gagal memuat bed</option>').trigger('change');
                        });
                }
            } else {
                selectBed.empty().append('<option value="">Pilih ruangan dulu</option>').trigger('change');
            }

            $('#bp-bentukMakan').val('').trigger('change');
            $('#bp-kdDiet').val([]).trigger('change');
            $('#bp-kdAlergi').val('').trigger('change');
            document.getElementById('bp-diagnosaGizi').value = '';
            if (document.getElementById('bp-extraDiet')) document.getElementById('bp-extraDiet').value = '';
            if (document.querySelector('textarea[name="keteranganSnack"]')) document.querySelector('textarea[name="keteranganSnack"]').value = '';
            if (document.querySelector('textarea[name="keteranganDiet"]')) document.querySelector('textarea[name="keteranganDiet"]').value = '';

            // Sesuaikan alergi dengan data permintaan pasien (jika ada)
            var pmData = permintaanData[noRawat];
            if (pmData && pmData.info) {
                var info = pmData.info;
                if (info.alergi) {
                    var aSel = document.getElementById('bp-kdAlergi');
                    var matched = false;
                    for (var i = 0; i < aSel.options.length; i++) {
                        if (aSel.options[i].text === info.alergi) {
                            $('#bp-kdAlergi').val(aSel.options[i].value).trigger('change');
                            document.getElementById('bp-alergi').value = info.alergi;
                            matched = true;
                            break;
                        }
                    }
                }
            }

            document.getElementById('buatPermintaanModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeBuatPermintaanModal() {
            document.getElementById('buatPermintaanModal').classList.remove('active');
            if (!document.querySelector('.modal-overlay.active')) {
                document.body.classList.remove('modal-open');
            }
        }

        function togglePatientData() {
            var grid = document.getElementById('bp-patient-grid');
            var text = document.getElementById('text-toggle-patient-data');
            var icon = document.getElementById('icon-toggle-patient-data');
            if (grid) {
                if (grid.style.display === 'none') {
                    grid.style.display = 'grid';
                    if (text) text.textContent = 'Sembunyikan';
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    grid.style.display = 'none';
                    if (text) text.textContent = 'Tampilkan';
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            }
        }

        function switchTab(tabId) {
            // Hide all tab panels
            document.getElementById('diet-pasien-tab').style.display = 'none';
            document.getElementById('rekap-dapur-tab').style.display = 'none';
            
            // Remove active class from buttons
            document.getElementById('tab-btn-diet').classList.remove('active');
            document.getElementById('tab-btn-rekap').classList.remove('active');
            document.getElementById('tab-btn-diet').style.background = 'transparent';
            document.getElementById('tab-btn-diet').style.color = 'var(--text-muted)';
            document.getElementById('tab-btn-diet').style.fontWeight = '500';
            document.getElementById('tab-btn-rekap').style.background = 'transparent';
            document.getElementById('tab-btn-rekap').style.color = 'var(--text-muted)';
            document.getElementById('tab-btn-rekap').style.fontWeight = '500';

            // Show current tab panel & set active style
            var activePanel = document.getElementById(tabId);
            if (activePanel) {
                activePanel.style.display = 'block';
            }
            
            var btnId = tabId === 'diet-pasien-tab' ? 'tab-btn-diet' : 'tab-btn-rekap';
            var btn = document.getElementById(btnId);
            if (btn) {
                btn.classList.add('active');
                btn.style.background = 'var(--primary-light)';
                btn.style.color = 'var(--primary)';
                btn.style.fontWeight = '600';
            }
        }

        function editSesiGizi(noRawat, waktu) {
            if (!isSesiEditAllowed(waktu)) {
                alert('Batas waktu pengubahan untuk sesi ' + waktu + ' telah terlewati.');
                return;
            }
            closePermintaanModal();
            var p = pasienData[noRawat];
            var data = permintaanData[noRawat];
            if (!p || !data) {
                alert('Data tidak ditemukan.');
                return;
            }

            var pm = data.waktu[waktu];
            if (!pm) {
                alert('Data sesi tidak ditemukan.');
                return;
            }

            var info = data.info || {};

            currentPmNoRawat = noRawat;
            document.getElementById('formBuatPermintaan').reset();

            // Set modal header & save button to Edit Mode
            document.querySelector('#buatPermintaanModal .modal-head h2').textContent = 'Edit Permintaan Gizi (' + waktu + ')';
            document.getElementById('bp-kdPermintaan').value = pm.kdPermintaan || '';
            document.getElementById('btn-save-permintaan').innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan';

            // Ensure patient grid is visible
            var grid = document.getElementById('bp-patient-grid');
            if (grid) {
                grid.style.display = 'grid';
                var text = document.getElementById('text-toggle-patient-data');
                if (text) text.textContent = 'Sembunyikan';
                var icon = document.getElementById('icon-toggle-patient-data');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }

            // Fill patient fields
            document.getElementById('bp-noRawat').value = p.noRawat || '';
            document.getElementById('bp-noRekam').value = p.no_rekamedis || '';
            document.getElementById('bp-namaPasien').value = p.nama_pasien || '';
            document.getElementById('bp-kdRanap').value = p.kdRanap || '';
            document.getElementById('bp-nmRanap').value = p.nmRanap || '';
            document.getElementById('bp-kdAhliGizi').value = info.kdAhliGizi || '{{ Session::get('user_id') }}';
            document.getElementById('bp-alergi').value = info.alergi || '';
            document.getElementById('bp-diagnosaGizi').value = info.diagnosaGizi || '';

            // Default Kelas & Bed
            $('#bp-kdKelas').val(info.kdKelas || '').trigger('change');
            
            // Set Bed dropdown (cached or fetched)
            var kdRanap = p.kdRanap || '';
            var selectBed = $('#bp-kdBed');
            
            function populateBeds(beds) {
                selectBed.empty();
                if (beds && beds.length > 0) {
                    beds.forEach(function (bed) {
                        selectBed.append(new Option(bed, bed));
                    });
                } else {
                    selectBed.append('<option value="">Tidak ada bed</option>');
                }
                
                var defaultBed = info.kdBed || '';
                if (defaultBed) {
                    if (selectBed.find("option[value='" + defaultBed + "']").length === 0) {
                        selectBed.append(new Option(defaultBed, defaultBed));
                    }
                    selectBed.val(defaultBed).trigger('change');
                } else {
                    selectBed.trigger('change');
                }
            }

            if (kdRanap) {
                if (bedCache[kdRanap]) {
                    populateBeds(bedCache[kdRanap]);
                } else {
                    selectBed.empty().append('<option value="">Loading...</option>').trigger('change');
                    fetch('/instalasi-gizi/kamar/' + encodeURIComponent(kdRanap))
                        .then(response => response.json())
                        .then(result => {
                            if (result.success && result.data) {
                                bedCache[kdRanap] = result.data;
                                populateBeds(result.data);
                            } else {
                                populateBeds([]);
                            }
                        })
                        .catch(err => {
                            console.error('Error fetching beds:', err);
                            selectBed.empty().append('<option value="">Gagal memuat bed</option>').trigger('change');
                        });
                }
            } else {
                selectBed.empty().append('<option value="">Pilih ruangan dulu</option>').trigger('change');
            }

            // Lock and check the specific time checkbox
            document.querySelectorAll('input[name="waktu[]"]').forEach(function (cb) {
                if (cb.value === waktu) {
                    cb.checked = true;
                    cb.disabled = false;
                    cb.closest('.pm-cb').style.opacity = '1';
                } else {
                    cb.checked = false;
                    cb.disabled = true;
                    cb.closest('.pm-cb').style.opacity = '0.5';
                    cb.closest('.pm-cb').style.cursor = 'not-allowed';
                }
            });

            // Set Bentuk Makanan
            var bentukText = pm.bentuk;
            var bentukSel = document.getElementById('bp-bentukMakan');
            var bentukVal = '';
            for (var i = 0; i < bentukSel.options.length; i++) {
                if (bentukSel.options[i].text === bentukText || bentukSel.options[i].value === bentukText) {
                    bentukVal = bentukSel.options[i].value;
                    break;
                }
            }
            $('#bp-bentukMakan').val(bentukVal).trigger('change');

            // Set Diet
            var dietCodes = (pm.diets || []).map(function (d) { return d.kdDiet; });
            $('#bp-kdDiet').val(dietCodes).trigger('change');

            // Build a map of allergy names to codes
            var alergiNameToCode = {};
            $('#bp-kdAlergi option').each(function() {
                var code = $(this).val();
                var text = $(this).text().trim().toLowerCase();
                if (code && text) {
                    alergiNameToCode[text] = code;
                }
            });

            // Set Alergi
            var alergiText = info.alergi || '';
            var selectedAlergi = [];
            if (alergiText) {
                var parts = alergiText.split(',');
                parts.forEach(function (part) {
                    var trimmed = part.trim().toLowerCase();
                    if (trimmed && alergiNameToCode[trimmed]) {
                        selectedAlergi.push(alergiNameToCode[trimmed]);
                    }
                });
            }
            $('#bp-kdAlergi').val(selectedAlergi).trigger('change');

            // Set Diagnosa Gizi (hidden)
            document.getElementById('bp-diagnosaGizi').value = info.diagnosaGizi || '';
            
            // Set Keterangan textareas
            if (document.querySelector('textarea[name="extraDiet"]')) {
                document.querySelector('textarea[name="extraDiet"]').value = pm.extraDiet || '';
            }
            if (document.querySelector('textarea[name="keteranganSnack"]')) {
                document.querySelector('textarea[name="keteranganSnack"]').value = pm.keteranganSnack || '';
            }
            if (document.querySelector('textarea[name="keteranganDiet"]')) {
                document.querySelector('textarea[name="keteranganDiet"]').value = pm.keteranganDiet || '';
            }

            document.getElementById('buatPermintaanModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        var _rwRawData = [];
        var _rwSesiFilter = 'ALL';
        var _rwStatusFilter = 'ALL';

        function openRiwayatModal(noRawat) {
            _rwRawData = [];
            _rwSesiFilter = 'ALL';
            _rwStatusFilter = 'ALL';

            // Reset UI inputs
            var searchInput = document.getElementById('rw-search-input');
            if (searchInput) searchInput.value = '';

            // Reset filter pill buttons
            document.querySelectorAll('.rw-pill-btn[data-sesi]').forEach(function(btn) {
                btn.classList.toggle('active', btn.getAttribute('data-sesi') === 'ALL');
            });
            document.querySelectorAll('.rw-pill-btn[data-status]').forEach(function(btn) {
                btn.classList.toggle('active', btn.getAttribute('data-status') === 'ALL');
            });

            var tbody = document.getElementById('riwayat-gizi-table-body');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">' +
                '<div style="display: flex; align-items: center; justify-content: center; gap: 8px;">' +
                '<span>Memuat data riwayat...</span></div></td></tr>';

            // Reset Patient Summary Banner
            document.getElementById('rw-patient-name').textContent = 'Memuat data pasien...';
            document.getElementById('rw-patient-rm').textContent = '-';
            document.getElementById('rw-patient-rawat').textContent = noRawat || '-';
            document.getElementById('rw-patient-room').textContent = '-';
            document.getElementById('rw-total-count').textContent = '0 Riwayat';

            document.getElementById('riwayatGiziModal').classList.add('active');
            document.body.classList.add('modal-open');

            fetch('/instalasi-gizi/permintaan/riwayat/' + encodeURIComponent(noRawat))
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data && result.data.length > 0) {
                        _rwRawData = result.data;

                        // Populate Header banner from first record
                        var first = result.data[0];
                        if (first.nama_pasien) {
                            document.getElementById('rw-patient-name').textContent = first.nama_pasien;
                            document.getElementById('rw-patient-avatar').textContent = (first.nama_pasien.charAt(0) || 'P').toUpperCase();
                        }
                        if (first.no_rekamedis) {
                            document.getElementById('rw-patient-rm').textContent = first.no_rekamedis;
                        }
                        if (first.noRawat) {
                            document.getElementById('rw-patient-rawat').textContent = first.noRawat;
                        }
                        if (first.nmRanap || first.kdBed) {
                            var roomInfo = (first.nmRanap || '') + (first.kdBed ? ' (' + first.kdBed + ')' : '') + (first.kdKelas ? ' - ' + first.kdKelas : '');
                            document.getElementById('rw-patient-room').textContent = roomInfo || '-';
                        }
                        document.getElementById('rw-total-count').textContent = result.data.length + ' Riwayat';

                        renderRiwayatTable();
                    } else {
                        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 36px 20px; color: var(--text-muted);">' +
                            '<div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Belum Ada Riwayat Permintaan</div>' +
                            '<div style="font-size: 12px;">Tidak ditemukan data riwayat permintaan gizi untuk pasien ini.</div>' +
                            '</td></tr>';
                        document.getElementById('rw-total-count').textContent = '0 Riwayat';
                    }
                })
                .catch(err => {
                    console.error('Error fetching history:', err);
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 24px; color: #EF4444; font-weight: 500;">Gagal memuat data riwayat gizi.</td></tr>';
                });
        }

        function setRiwayatSesiFilter(sesi, btn) {
            _rwSesiFilter = sesi;
            document.querySelectorAll('.rw-pill-btn[data-sesi]').forEach(function(b) {
                b.classList.remove('active');
            });
            if (btn) btn.classList.add('active');
            renderRiwayatTable();
        }

        function setRiwayatStatusFilter(status, btn) {
            _rwStatusFilter = status;
            document.querySelectorAll('.rw-pill-btn[data-status]').forEach(function(b) {
                b.classList.remove('active');
            });
            if (btn) btn.classList.add('active');
            renderRiwayatTable();
        }

        function filterRiwayatData() {
            renderRiwayatTable();
        }

        function resetRiwayatFilters() {
            _rwSesiFilter = 'ALL';
            _rwStatusFilter = 'ALL';
            var searchInput = document.getElementById('rw-search-input');
            if (searchInput) searchInput.value = '';
            document.querySelectorAll('.rw-pill-btn[data-sesi]').forEach(function(btn) {
                btn.classList.toggle('active', btn.getAttribute('data-sesi') === 'ALL');
            });
            document.querySelectorAll('.rw-pill-btn[data-status]').forEach(function(btn) {
                btn.classList.toggle('active', btn.getAttribute('data-status') === 'ALL');
            });
            renderRiwayatTable();
        }

        function renderRiwayatTable() {
            var tbody = document.getElementById('riwayat-gizi-table-body');
            var searchInput = document.getElementById('rw-search-input');
            var q = searchInput ? searchInput.value.trim().toLowerCase() : '';

            var filtered = _rwRawData.filter(function(r) {
                // Sesi filter
                if (_rwSesiFilter !== 'ALL' && (r.waktu || '').toUpperCase() !== _rwSesiFilter) {
                    return false;
                }
                // Status filter
                if (_rwStatusFilter !== 'ALL' && String(r.statusUpdate) !== _rwStatusFilter) {
                    return false;
                }
                // Search query
                if (q) {
                    var haystack = [
                        r.tglPermintaan || '',
                        r.waktu || '',
                        r.bentuk || '',
                        (r.diets || []).join(' '),
                        r.alergi || '',
                        r.diagnosaGizi || '',
                        r.extraDiet || '',
                        r.keteranganDiet || '',
                        r.keteranganSnack || '',
                        r.kdAhliGizi || ''
                    ].join(' ').toLowerCase();

                    if (haystack.indexOf(q) === -1) {
                        return false;
                    }
                }
                return true;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 36px 20px; color: var(--text-muted);">' +
                    '<div style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">Tidak Ada Data Sesuai Filter</div>' +
                    '<div style="font-size: 12px;">Coba ubah kata kunci pencarian atau klik tombol Reset di atas.</div>' +
                    '</td></tr>';
                return;
            }

            var html = '';
            filtered.forEach(function(r) {
                // Status pill
                var statusBadge = r.statusUpdate === '1'
                    ? '<span class="rw-status-pill aktif"><span class="dot"></span> Aktif</span>'
                    : '<span class="rw-status-pill riwayat">Riwayat / Diedit</span>';

                // Sesi pill
                var sesiLower = (r.waktu || '').toLowerCase();
                var sesiClass = (sesiLower === 'pagi' || sesiLower === 'siang' || sesiLower === 'sore') ? sesiLower : 'pagi';
                var sesiBadge = '<span class="rw-sesi-pill ' + sesiClass + '">' + esc(r.waktu || '-') + '</span>';

                // Diets HTML
                var dietsHtml = '';
                if (r.diets && r.diets.length > 0) {
                    dietsHtml = r.diets.map(function(d) {
                        return '<span class="rw-diet-tag">' + esc(d) + '</span>';
                    }).join('');
                } else {
                    dietsHtml = '<span style="color:var(--text-muted); font-size:12px;">—</span>';
                }

                // Alergi
                var alergiHtml = '';
                if (r.alergi && r.alergi !== '—' && r.alergi !== '-') {
                    alergiHtml = '<span class="rw-alergi-tag"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path></svg> ' + esc(r.alergi) + '</span>';
                } else {
                    alergiHtml = '<span style="color:var(--text-muted); font-size:12px;">—</span>';
                }

                // Format DateTime
                var dateStr = fmtDt(r.tglPermintaan);

                // Diagnosa & Catatan
                var diagItems = [];
                if (r.diagnosaGizi && r.diagnosaGizi !== '—' && r.diagnosaGizi !== '-') {
                    diagItems.push('<div class="rw-diag-item"><span class="rw-diag-label" style="color: #059669;">Diag:</span> ' + esc(r.diagnosaGizi) + '</div>');
                }
                if (r.extraDiet && r.extraDiet !== '—' && r.extraDiet !== '-') {
                    diagItems.push('<div class="rw-diag-item"><span class="rw-diag-label">Extra:</span> ' + esc(r.extraDiet) + '</div>');
                }
                if (r.keteranganDiet && r.keteranganDiet !== '—' && r.keteranganDiet !== '-') {
                    diagItems.push('<div class="rw-diag-item"><span class="rw-diag-label">Ket. Diet:</span> ' + esc(r.keteranganDiet) + '</div>');
                }
                if (r.keteranganSnack && r.keteranganSnack !== '—' && r.keteranganSnack !== '-') {
                    diagItems.push('<div class="rw-diag-item"><span class="rw-diag-label">Snack:</span> ' + esc(r.keteranganSnack) + '</div>');
                }
                if (r.kdAhliGizi && r.kdAhliGizi !== '—' && r.kdAhliGizi !== '-') {
                    diagItems.push('<div class="rw-user-badge"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> ' + esc(r.kdAhliGizi) + '</div>');
                }

                var diagContent = diagItems.length > 0
                    ? '<div class="rw-diag-box">' + diagItems.join('') + '</div>'
                    : '<span style="color:var(--text-muted); font-size:12px;">—</span>';

                html += '<tr>' +
                    '<td><div style="font-weight:600; color:#0F172A;">' + esc(dateStr) + '</div></td>' +
                    '<td>' + sesiBadge + '</td>' +
                    '<td><div style="font-weight:600; color:#334155;">' + esc(r.bentuk || '—') + '</div></td>' +
                    '<td>' + dietsHtml + '</td>' +
                    '<td>' + alergiHtml + '</td>' +
                    '<td>' + diagContent + '</td>' +
                    '<td>' + statusBadge + '</td>' +
                    '</tr>';
            });

            tbody.innerHTML = html;
        }

        function closeRiwayatModal() {
            document.getElementById('riwayatGiziModal').classList.remove('active');
            if (!document.querySelector('.modal-overlay.active')) {
                document.body.classList.remove('modal-open');
            }
        }

        // Modal backdrop click disabled to prevent accidental closing (e.g., when selecting dropdowns)

        var bpDgSelected = [];

        function bpRenderDgKeterangan() {
            var wrap = document.getElementById('bp-dg-keterangan');
            if (!wrap) return;
            var sel = document.getElementById('bp-dg-select');
            if (!sel) return;
            var nextSelected = $(sel).val() || [];
            
            // 1. Ambil nilai input yang sedang diketik berdasarkan label teks diagnosa gizi
            var currentValues = {};
            var items = wrap.querySelectorAll('.bp-dg-ket-item');
            items.forEach(function (item) {
                var label = item.querySelector('.bp-dg-ket-label')?.textContent;
                var input = item.querySelector('input');
                if (label && input) {
                    currentValues[label] = input.value;
                }
            });

            // 2. Gambar ulang elemen input dengan mempertahankan nilai lama (jika ada)
            bpDgSelected = nextSelected;
            var html = '';
            bpDgSelected.forEach(function (v, i) {
                var savedVal = currentValues[v] || '';
                html += '<div class="bp-dg-ket-item">' +
                    '<div class="bp-dg-ket-label">' + esc(v) + '</div>' +
                    '<input type="text" class="pm-input" id="dg-ket-' + i + '" placeholder="Keterangan untuk poin ini (opsional)" value="' + esc(savedVal) + '">' +
                    '</div>';
            });
            wrap.innerHTML = html;
        }

        $(document).ready(function () {
            $('.select2-pm').each(function () {
                var isMulti = $(this).attr('multiple') !== undefined;
                $(this).select2({
                    width: '100%',
                    placeholder: isMulti ? 'Pilih...' : 'Pilih...',
                    allowClear: false,
                    dropdownParent: $('#buatPermintaanModal')
                });
            });

            $('#bp-dg-select').on('select2:select select2:unselect', bpRenderDgKeterangan);

            $('#bp-kdAlergi').on('select2:select select2:unselect', function () {
                var selectedOptions = Array.from(this.selectedOptions);
                var texts = selectedOptions.map(function(opt) { return opt.text; }).filter(function(txt) { return txt !== ''; });
                document.getElementById('bp-alergi').value = texts.join(', ');
            });
        });

        function showSuccessPermintaanModal(data) {
            // Title & Subtitle
            document.getElementById('msp-title').textContent = data.isEdit 
                ? 'Perubahan Permintaan Berhasil Disimpan' 
                : 'Permintaan Gizi Berhasil Disimpan';
            document.getElementById('msp-subtitle').textContent = data.isEdit
                ? 'Perubahan menu diet pasien telah diperbarui pada sistem gizi & dapur.'
                : 'Permintaan menu diet pasien telah berhasil dicatat dan siap diproses instalasi dapur.';

            // Avatar & Patient Header
            var initial = (data.namaPasien || 'P').trim().charAt(0).toUpperCase();
            document.getElementById('msp-avatar').textContent = initial || 'P';
            document.getElementById('msp-nama-pasien').textContent = data.namaPasien || '-';
            document.getElementById('msp-no-rm').textContent = data.noRekamMedis || '-';
            document.getElementById('msp-no-rawat').textContent = data.noRawat || '-';
            
            var roomBed = (data.nmRanap || data.kdRanap || 'Ruangan') + (data.kdBed ? ' • Bed ' + data.kdBed : '') + (data.kdKelas ? ' (' + data.kdKelas + ')' : '');
            document.getElementById('msp-text-ruang').textContent = roomBed;

            // Waktu Makan Badges
            var waktuWrap = document.getElementById('msp-waktu-wrap');
            waktuWrap.innerHTML = '';
            if (data.waktu && data.waktu.length > 0) {
                data.waktu.forEach(function (w) {
                    var span = document.createElement('span');
                    var wLower = w.toLowerCase();
                    span.className = 'waktu-pill ' + (wLower.indexOf('pagi') !== -1 ? 'pagi' : (wLower.indexOf('siang') !== -1 ? 'siang' : 'sore'));
                    span.textContent = w;
                    waktuWrap.appendChild(span);
                });
            } else {
                waktuWrap.innerHTML = '<span style="color:#94A3B8;">-</span>';
            }

            // Bentuk Makanan
            document.getElementById('msp-bentuk-makan').textContent = data.bentukMakanText || '-';

            // Diet
            var dietWrap = document.getElementById('msp-diet-wrap');
            dietWrap.innerHTML = '';
            if (data.dietTexts && data.dietTexts.length > 0) {
                data.dietTexts.forEach(function (d) {
                    var span = document.createElement('span');
                    span.className = 'diet-pill';
                    span.textContent = d;
                    dietWrap.appendChild(span);
                });
            } else {
                dietWrap.innerHTML = '<span style="color:#64748B; font-weight:400; font-style:italic;">Biasa / Tanpa Diet Khusus</span>';
            }

            // Alergi
            var rowAlergi = document.getElementById('msp-row-alergi');
            var alergiEl = document.getElementById('msp-alergi');
            if (data.alergiText && data.alergiText.trim() !== '') {
                alergiEl.innerHTML = '<span style="display:inline-flex; align-items:center; gap:4px; color:#DC2626; background:#FEF2F2; border:1px solid #FECACA; padding:2px 8px; border-radius:6px; font-size:12px; font-weight:600;"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>' + esc(data.alergiText) + '</span>';
            } else {
                alergiEl.innerHTML = '<span style="color:#059669; font-size:12px; font-weight:500;">✓ Tidak Ada Alergi</span>';
            }

            // Diagnosa Gizi
            var rowDiag = document.getElementById('msp-row-diagnosa');
            var diagEl = document.getElementById('msp-diagnosa');
            if (data.diagnosaGizi && data.diagnosaGizi.trim() !== '') {
                rowDiag.style.display = 'flex';
                diagEl.textContent = data.diagnosaGizi;
            } else {
                rowDiag.style.display = 'none';
            }

            // Catatan / Extra / Snack
            var rowKet = document.getElementById('msp-row-keterangan');
            var ketEl = document.getElementById('msp-keterangan');
            var notes = [];
            if (data.extraDiet) notes.push('Extra: ' + data.extraDiet);
            if (data.keteranganSnack) notes.push('Snack: ' + data.keteranganSnack);
            if (data.keteranganDiet) notes.push('Keterangan: ' + data.keteranganDiet);
            
            if (notes.length > 0) {
                rowKet.style.display = 'flex';
                ketEl.textContent = notes.join(' • ');
            } else {
                rowKet.style.display = 'none';
            }

            // Open Success Modal
            document.getElementById('modalSuccessPermintaan').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeSuccessModal(shouldReload) {
            document.getElementById('modalSuccessPermintaan').classList.remove('active');
            window.location.reload();
        }

        document.getElementById('btn-save-permintaan')?.addEventListener('click', async function () {
            var form = document.getElementById('formBuatPermintaan');

            var noRawat = document.getElementById('bp-noRawat').value;
            var bentuk = document.getElementById('bp-bentukMakan').value;
            var waktus = document.querySelectorAll('input[name="waktu[]"]:checked');

            if (!noRawat) { alert('Data pasien tidak ditemukan.'); return; }
            if (waktus.length === 0) { alert('Silakan centang minimal satu waktu makan.'); return; }
            if (!bentuk) { alert('Silakan pilih bentuk makanan.'); return; }

            var btn = this;
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan...';

            try {
                // Diagnosa gizi tidak diisi dari modal (dikosongkan secara default)
                var diagnosaGiziStr = '';
                document.getElementById('bp-diagnosaGizi').value = diagnosaGiziStr;

                // Alergi
                var aSel = document.getElementById('bp-kdAlergi');
                var aTexts = Array.from(aSel.selectedOptions).map(function(opt) { return opt.text; }).filter(function(txt) { return txt !== ''; });
                var alergiStr = aTexts.join(', ');
                document.getElementById('bp-alergi').value = alergiStr;

                // Bentuk Makan Text
                var bentukSel = document.getElementById('bp-bentukMakan');
                var bentukText = bentukSel.selectedOptions.length > 0 ? bentukSel.selectedOptions[0].text : bentuk;

                // Diet Texts
                var dietSel = document.getElementById('bp-kdDiet');
                var dietTexts = Array.from(dietSel.selectedOptions).map(function(opt) { return opt.text; }).filter(function(txt) { return txt !== ''; });

                var waktuArr = [];
                waktus.forEach(function(cb) { waktuArr.push(cb.value); });

                var namaPasien = document.getElementById('bp-namaPasien').value;
                var noRekamMedis = document.getElementById('bp-noRekam').value;
                var nmRanap = document.getElementById('bp-nmRanap').value;
                var kdRanap = document.getElementById('bp-kdRanap').value;
                var kdBed = document.getElementById('bp-kdBed').value;
                var kdKelas = document.getElementById('bp-kdKelas').value;
                var extraDiet = document.querySelector('textarea[name="extraDiet"]')?.value || '';
                var keteranganSnack = document.querySelector('textarea[name="keteranganSnack"]')?.value || '';
                var keteranganDiet = document.querySelector('textarea[name="keteranganDiet"]')?.value || '';

                var kdPermintaan = document.getElementById('bp-kdPermintaan').value;
                var isEdit = !!kdPermintaan;
                var url = isEdit ? '/instalasi-gizi/permintaan/update/' + encodeURIComponent(kdPermintaan) : '/instalasi-gizi/permintaan/store';

                var formData = new FormData(form);
                var response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                var result = await response.json();

                if (response.ok && result.success) {
                    closeBuatPermintaanModal();
                    showSuccessPermintaanModal({
                        isEdit: isEdit,
                        namaPasien: namaPasien,
                        noRawat: noRawat,
                        noRekamMedis: noRekamMedis,
                        nmRanap: nmRanap,
                        kdRanap: kdRanap,
                        kdBed: kdBed,
                        kdKelas: kdKelas,
                        waktu: waktuArr,
                        bentukMakanText: bentukText,
                        dietTexts: dietTexts,
                        alergiText: alergiStr,
                        diagnosaGizi: diagnosaGiziStr,
                        extraDiet: extraDiet,
                        keteranganSnack: keteranganSnack,
                        keteranganDiet: keteranganDiet
                    });
                } else {
                    alert(result.message || 'Gagal menyimpan permintaan gizi.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan atau server saat menyimpan.');
            } finally {
                btn.disabled = false;
                var kdPermintaan = document.getElementById('bp-kdPermintaan').value;
                var isEdit = !!kdPermintaan;
                btn.innerHTML = isEdit ? '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Perubahan' : '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Simpan Permintaan';
            }
        });

        // Toggle detail waktu via checkbox
        document.addEventListener('change', function (e) {
            if (e.target.classList && e.target.classList.contains('pm-cb-input')) {
                var w = e.target.value;
                var secs = document.querySelectorAll('.pm-waktu[data-waktu="' + w + '"]');
                secs.forEach(function (s) {
                    s.style.display = e.target.checked ? '' : 'none';
                });
            }
        });

        function esc(str) {
            if (str === null || str === undefined) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function fmtDt(dt) {
            if (!dt) return '';
            var d = new Date(dt);
            if (isNaN(d.getTime())) return dt;
            var dd = String(d.getDate()).padStart(2, '0');
            var mm = String(d.getMonth() + 1).padStart(2, '0');
            return dd + '/' + mm + '/' + d.getFullYear() + ' ' + String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
        }
    </script>
    </div>
</body>
</html>
