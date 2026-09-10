<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | History WA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: rgba(5, 150, 105, 0.08);
            --primary-glow: rgba(5, 150, 105, 0.15);
            --primary-surface: #ECFDF5;
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-subtle: #94A3B8;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --success: #10B981;
            --success-bg: #D1FAE5;
            --success-text: #065F46;
            --error: #EF4444;
            --error-bg: #FEF2F2;
            --error-text: #991B1B;
            --warning: #F59E0B;
            --warning-bg: #FFFBEB;
            --warning-text: #92400E;
            --info: #3B82F6;
            --info-bg: #EFF6FF;
            --info-text: #1E40AF;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.03);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.06);
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
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .logo-section {
            padding: 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-box {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 16px;
            box-shadow: 0 4px 8px var(--primary-glow);
        }

        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }

        .nav-label {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em;
        }

        .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px;
            border-radius: 12px; color: var(--text-muted); text-decoration: none;
            font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px;
        }

        .nav-item:hover { background: var(--primary-surface); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }

        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 28px 32px;
            max-width: calc(100vw - var(--sidebar-width));
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.3px;
            color: var(--text-main);
        }

        .header-title p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-subtle);
            margin-bottom: 6px;
        }

        .breadcrumb i { font-size: 10px; color: var(--text-subtle); }
        .breadcrumb span { color: var(--text-muted); }

        .user-profile {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 14px 6px 6px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .avatar {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 12px; color: white;
        }

        .user-info span { font-size: 13px; font-weight: 500; display: block; }

        .date-summary {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            font-size: 13px;
            color: var(--text-muted);
        }

        .date-summary i { color: var(--primary); font-size: 14px; }
        .date-summary strong { color: var(--text-main); font-weight: 600; }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 16px 20px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .stat-card {
            cursor: pointer;
            position: relative;
        }

        .stat-card.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .stat-card.active::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--primary);
            border-radius: var(--radius-md) var(--radius-md) 0 0;
        }

        .stat-card .filter-hint {
            position: absolute;
            top: 10px; right: 12px;
            font-size: 10px;
            color: var(--text-subtle);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .stat-card:hover .filter-hint { opacity: 1; }

        .stat-icon {
            width: 40px; height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .stat-icon.total { background: var(--primary-light); color: var(--primary); }
        .stat-icon.pending { background: var(--warning-bg); color: var(--warning); }
        .stat-icon.processing { background: var(--info-bg); color: var(--info); }
        .stat-icon.sent { background: var(--success-bg); color: var(--success); }
        .stat-icon.failed { background: var(--error-bg); color: var(--error); }

        .stat-body { flex-grow: 1; }
        .stat-body .stat-value { font-size: 22px; font-weight: 700; line-height: 1.2; }
        .stat-body .stat-label { font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 1px; text-transform: uppercase; letter-spacing: 0.03em; }

        .filter-section {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px 18px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-section .filter-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-section .filter-label i { font-size: 13px; }

        .filter-section input[type="date"] {
            padding: 7px 12px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--card-bg);
            color: var(--text-main);
            min-width: 140px;
        }

        .filter-section input[type="date"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .filter-separator {
            color: var(--text-subtle);
            font-size: 13px;
        }

        .btn-filter {
            padding: 7px 18px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-filter:hover { background: var(--primary-dark); }

        .btn-reset {
            padding: 7px 16px;
            background: transparent;
            color: var(--text-muted);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-reset:hover {
            background: var(--bg-main);
            border-color: var(--text-subtle);
            color: var(--text-main);
        }

        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-left i {
            color: var(--primary);
            font-size: 16px;
        }

        .card-header-left h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        .card-header .badge-count {
            padding: 3px 10px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
        }

        .table-wrap { overflow-x: auto; padding: 15px; }

        table { width: 100% !important; border-collapse: collapse; font-size: 13px; }

        thead { background: var(--bg-main); }

        th {
            padding: 14px 20px !important;
            text-align: left !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            color: var(--text-muted) !important;
            letter-spacing: 0.04em !important;
            white-space: nowrap !important;
            border-bottom: 1px solid var(--border) !important;
        }

        td {
            padding: 16px 20px !important;
            border-bottom: 1px solid var(--border-light) !important;
            vertical-align: middle !important;
        }

        tr:last-child td { border-bottom: none !important; }
        tr:hover td { background: rgba(5, 150, 105, 0.02); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-badge.pending { background: var(--warning-bg); color: var(--warning-text); }
        .status-badge.processing { background: var(--info-bg); color: var(--info-text); }
        .status-badge.sent { background: var(--success-bg); color: var(--success-text); }
        .status-badge.failed { background: var(--error-bg); color: var(--error-text); }

        .status-dot {
            width: 6px; height: 6px; border-radius: 50%; display: inline-block;
        }

        .status-dot.pending { background: var(--warning); }
        .status-dot.processing { background: var(--info); animation: pulse 1.5s infinite; }
        .status-dot.sent { background: var(--success); }
        .status-dot.failed { background: var(--error); }

        .status-dot.sent { background: var(--success); }

        .msg-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.5;
            color: var(--text-muted);
            font-size: 12px;
            word-break: break-word;
        }

        .msg-cell { max-width: 320px; }

        .msg-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 7px;
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            background: var(--primary-light);
            border: none;
            border-radius: 100px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-view:hover {
            background: var(--primary);
            color: #fff;
        }

        .file-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10.5px;
            color: var(--info-text);
            background: var(--info-bg);
            padding: 3px 9px;
            border-radius: 100px;
            font-weight: 600;
            white-space: nowrap;
        }

        .doc-cell {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .doc-cell i { color: var(--primary); font-size: 12px; }

        .telp-cell {
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
        }

        .telp-cell i { color: #25D366; font-size: 13px; }

        .error-msg {
            color: var(--error-text);
            font-size: 11px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: default;
        }

        .error-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 260px;
        }

        .btn-view-error {
            color: var(--error-text);
            background: var(--error-bg);
            flex-shrink: 0;
        }

        .btn-view-error:hover {
            background: var(--error);
            color: #fff;
        }

        .text-mono {
            font-family: 'SF Mono', 'Courier New', monospace;
            font-size: 12px;
            letter-spacing: 0.02em;
        }

        .text-muted { color: var(--text-muted); font-size: 12px; }
        .text-subtle { color: var(--text-subtle); font-size: 11px; }

        .time-cell {
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .time-cell .date { font-weight: 500; color: var(--text-main); }
        .time-cell .time { color: var(--text-subtle); font-size: 11px; }
        .time-cell .relative { font-size: 10px; color: var(--text-subtle); display: block; margin-top: 1px; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            margin-top: auto;
        }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px; width: 100%;
            background: #FEF2F2; color: #EF4444;
            border: 1px solid #FEE2E2; border-radius: var(--radius-md);
            font-size: 14px; font-weight: 600; cursor: pointer;
            transition: all 0.2s ease; font-family: inherit;
        }

        .btn-logout:hover { background: #FEE2E2; border-color: #FECACA; }

        .no-file { color: var(--text-subtle); font-size: 11px; }

        .file-indicator {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: var(--primary);
            font-size: 11px;
            font-weight: 500;
        }

        .file-indicator i { font-size: 12px; }

        div.dataTables_wrapper div.dataTables_filter input {
            padding: 7px 12px !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            font-size: 13px !important;
            font-family: inherit !important;
            outline: none !important;
            margin-left: 6px !important;
            width: 220px !important;
            transition: border-color 0.2s, box-shadow 0.2s !important;
        }

        div.dataTables_wrapper div.dataTables_filter input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-glow) !important;
        }

        div.dataTables_wrapper div.dataTables_length select {
            padding: 5px 10px !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            font-size: 13px !important;
            font-family: inherit !important;
            outline: none !important;
            margin: 0 4px !important;
            background: var(--card-bg) !important;
        }

        div.dataTables_wrapper div.dataTables_info {
            font-size: 12px !important;
            color: var(--text-muted) !important;
            padding: 14px 0 !important;
        }

        div.dataTables_wrapper div.dataTables_paginate {
            padding: 10px 0 !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button {
            padding: 6px 12px !important;
            border-radius: var(--radius-sm) !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: all 0.2s !important;
            color: var(--text-muted) !important;
            background: transparent !important;
            border: 1px solid transparent !important;
            margin: 0 1px !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button:hover {
            background: var(--bg-main) !important;
            border-color: var(--border) !important;
            color: var(--text-main) !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button.current {
            background: var(--primary) !important;
            color: white !important;
            border-color: var(--primary) !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button.disabled {
            color: var(--text-subtle) !important;
            cursor: not-allowed !important;
            background: transparent !important;
            border-color: transparent !important;
        }

        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 10px 18px;
            background: var(--text-main);
            color: white;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 999;
            pointer-events: none;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Message detail modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.show { display: flex; }

        .modal {
            width: 100%;
            max-width: 580px;
            max-height: 90vh;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .modal-header h3 { font-size: 15px; font-weight: 700; color: var(--text-main); }
        .modal-header p { font-size: 12px; color: var(--text-subtle); margin-top: 2px; }

        .modal-close {
            background: var(--bg-main);
            border: none;
            width: 32px; height: 32px;
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 14px;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .modal-close:hover { background: var(--border-light); color: var(--text-main); }

        .modal-body {
            padding: 22px;
            overflow-y: auto;
        }

        .chat-bubble {
            background: var(--primary-surface);
            border: 1px solid rgba(5, 150, 105, 0.15);
            border-radius: 14px 14px 14px 4px;
            padding: 14px 16px;
            margin-bottom: 18px;
        }

        .chat-bubble-header {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 10.5px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }

        .chat-bubble-text {
            font-size: 13.5px;
            line-height: 1.65;
            color: var(--text-main);
            white-space: pre-wrap;
            word-break: break-word;
        }

        .chat-bubble-meta {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: 11px;
            color: var(--text-subtle);
        }

        .chat-check { color: var(--success); }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 16px;
        }

        .info-item {
            background: var(--bg-main);
            border: 1px solid var(--border-light);
            border-radius: 10px;
            padding: 10px 14px;
            min-width: 0;
        }

        .info-item label {
            display: block;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-subtle);
            font-weight: 600;
            margin-bottom: 3px;
        }

        .info-item span {
            font-size: 13px;
            color: var(--text-main);
            font-weight: 500;
            word-break: break-word;
        }

        .info-item span a { color: var(--info); text-decoration: none; }
        .info-item span a:hover { text-decoration: underline; }

        .error-panel {
            margin-top: 14px;
            background: var(--error-bg);
            border: 1px solid #FEE2E2;
            color: var(--error-text);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12.5px;
            line-height: 1.5;
            word-break: break-word;
        }

        .error-panel h4 {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .retry-badge {
            display: inline-flex;
            align-items: center;
            background: var(--error-text);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 100px;
            text-transform: none;
            letter-spacing: 0;
            margin-left: 2px;
        }

        .btn-copy-msg {
            margin-top: 16px;
            width: 100%;
            padding: 11px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-copy-msg:hover { background: var(--primary-dark); }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 1024px) {
            :root { --sidebar-width: 280px; }
            .sidebar { transform: translateX(-100%); box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.1); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; max-width: 100vw; }
            .stats-row { grid-template-columns: repeat(3, 1fr); }
            .stat-card { padding: 14px 16px; }
            .stat-body .stat-value { font-size: 18px; }

            .mobile-header {
                display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px;
                background: rgba(255,255,255,0.98); backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--border); align-items: center;
                padding: 0 20px; z-index: 40; justify-content: space-between;
            }

            .hamburger {
                cursor: pointer; padding: 8px; border-radius: var(--radius-sm);
                background: var(--bg-main); border: none; color: var(--text-main);
            }

            .sidebar-overlay {
                display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); z-index: 45;
            }
            .sidebar-overlay.active { display: block; }
            header { flex-direction: column; align-items: flex-start; gap: 16px; }
            .user-profile { width: 100%; justify-content: center; }
            .filter-section { flex-direction: column; align-items: stretch; }
            .filter-section input[type="date"] { flex-grow: 1; }
        }

        @media (max-width: 640px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }

        @media (min-width: 1025px) { .mobile-header { display: none; } }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <header>
            <div class="header-title">
                <div class="breadcrumb">
                    <i class="fas fa-home"></i> Dashboard <i class="fas fa-chevron-right"></i>
                    <span>History WA</span>
                </div>
                <h1>History WhatsApp</h1>
                <p>Riwayat pengiriman pesan WhatsApp gateway.</p>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                <div class="user-info"><span>{{ Session::get('user_id') }}</span></div>
            </div>
        </header>

        <div class="stats-row" title="Klik kartu untuk memfilter data berdasarkan status">
            <div class="stat-card" data-status="">
                <div class="stat-icon total"><i class="fas fa-envelope"></i></div>
                <div class="stat-body">
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Pesan</div>
                </div>
                <span class="filter-hint"><i class="fas fa-filter"></i></span>
            </div>
            <div class="stat-card" data-status="pending">
                <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
                <div class="stat-body">
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <span class="filter-hint"><i class="fas fa-filter"></i></span>
            </div>
            <div class="stat-card" data-status="processing">
                <div class="stat-icon processing"><i class="fas fa-sync-alt fa-spin"></i></div>
                <div class="stat-body">
                    <div class="stat-value">{{ $stats['processing'] }}</div>
                    <div class="stat-label">Processing</div>
                </div>
                <span class="filter-hint"><i class="fas fa-filter"></i></span>
            </div>
            <div class="stat-card" data-status="sent">
                <div class="stat-icon sent"><i class="fas fa-check-circle"></i></div>
                <div class="stat-body">
                    <div class="stat-value">{{ $stats['sent'] }}</div>
                    <div class="stat-label">Terkirim</div>
                </div>
                <span class="filter-hint"><i class="fas fa-filter"></i></span>
            </div>
            <div class="stat-card" data-status="failed">
                <div class="stat-icon failed"><i class="fas fa-exclamation-circle"></i></div>
                <div class="stat-body">
                    <div class="stat-value">{{ $stats['failed'] }}</div>
                    <div class="stat-label">Gagal</div>
                </div>
                <span class="filter-hint"><i class="fas fa-filter"></i></span>
            </div>
        </div>

        <div class="date-summary">
            <i class="fas fa-calendar-alt"></i>
            Menampilkan data periode
            <strong id="range-label">{{ \Carbon\Carbon::parse($today)->isoFormat('D MMMM YYYY') }}</strong>
            <span id="range-to-label" style="display:none;"></span>
        </div>

        <div class="filter-section">
            <span class="filter-label"><i class="fas fa-filter"></i> Filter Tanggal</span>
            <input type="date" id="date_from" value="{{ $today }}">
            <span class="filter-separator">—</span>
            <input type="date" id="date_to" value="{{ $today }}">
            <button id="btn-filter" class="btn-filter"><i class="fas fa-search"></i> Tampilkan</button>
            <button id="btn-reset" class="btn-reset"><i class="fas fa-undo"></i> Hari Ini</button>
            <input type="hidden" id="active-status" value="">
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <i class="fas fa-list"></i>
                    <h3>Daftar Pengiriman Pesan</h3>
                </div>
                <span class="badge-count" id="row-count">0 data</span>
            </div>
            <div class="table-wrap">
                <table id="waHistoryTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>No. Surat</th>
                            <th>No. Telepon</th>
                            <th>Pesan</th>
                            <th>Status</th>
                            <th>Error</th>
                            <th>Waktu Kirim</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="msgModal">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="modal-header">
                <div>
                    <h3 id="modal-title">Detail Pesan WhatsApp</h3>
                    <p id="modal-id-sub">ID Antrean #0</p>
                </div>
                <button type="button" class="modal-close" id="modal-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="chat-bubble">
                    <div class="chat-bubble-header"><i class="fab fa-whatsapp"></i> Isi pesan terkirim</div>
                    <div class="chat-bubble-text" id="modal-pesan"></div>
                    <div class="chat-bubble-meta">
                        <span id="modal-bubble-time"></span>
                        <span class="chat-check" id="modal-check" style="display:none;"><i class="fas fa-check-double"></i></span>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label><i class="fas fa-file-alt"></i> No. Surat</label>
                        <span id="modal-no-surat">-</span>
                    </div>
                    <div class="info-item">
                        <label><i class="fab fa-whatsapp"></i> Penerima</label>
                        <span id="modal-telp">-</span>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-tasks"></i> Status</label>
                        <span id="modal-status">-</span>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-box"></i> Lampiran</label>
                        <span id="modal-file">Tidak ada lampiran</span>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-hourglass-half"></i> Dibuat</label>
                        <span id="modal-created">-</span>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-paper-plane"></i> Dikirim</label>
                        <span id="modal-sent">Belum dikirim</span>
                    </div>
                </div>

                <div class="error-panel" id="modal-error" style="display:none;">
                    <h4><i class="fas fa-exclamation-triangle"></i> Detail Error <span class="retry-badge" id="modal-error-retry" style="display:none;"></span></h4>
                    <div id="modal-error-text"></div>
                </div>

                <button type="button" class="btn-copy-msg" id="modal-copy"><i class="fas fa-copy"></i> Salin Isi Pesan</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        var waMessages = {};

        function getTableData() {
            return {
                url: '{{ route("wa-gateway.history-data") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                    d.status = $('#active-status').val() || '';
                },
                dataSrc: function (json) {
                    waMessages = {};
                    $.each(json.data, function (i, r) {
                        waMessages[r.row_id] = r;
                    });
                    return json.data;
                }
            };
        }

        function showToast(msg) {
            var t = $('#toast');
            t.text(msg).addClass('show');
            clearTimeout(t.data('timer'));
            t.data('timer', setTimeout(function () { t.removeClass('show'); }, 1500));
        }

        function statusBadgeHtml(s) {
            var labels = { pending: 'Pending', processing: 'Processing', sent: 'Terkirim', failed: 'Gagal' };
            return '<span class="status-badge ' + s + '"><span class="status-dot ' + s + '"></span> ' + (labels[s] || s) + '</span>';
        }

        function openMessageModal(id) {
            var r = waMessages[id];
            if (!r) return;

            $('#modal-id-sub').text('ID Antrean #' + r.row_id);
            $('#modal-pesan').text(r.pesan_full || '(Tidak ada teks, hanya lampiran)');
            $('#modal-no-surat').text(r.no_surat_raw || '-');
            $('#modal-telp').text(r.no_telp_disp || r.no_telp_raw || '-');
            $('#modal-status').html(statusBadgeHtml(r.status_raw));
            $('#modal-created').text(r.created_at_raw || '-');
            $('#modal-sent').text(r.sent_at_raw || 'Belum dikirim');
            $('#modal-bubble-time').text(r.sent_at_raw || r.created_at_raw || '');

            if (r.status_raw === 'sent') {
                $('#modal-check').show();
            } else {
                $('#modal-check').hide();
            }

            if (r.file_name) {
                if (r.file_path && /^https?:\/\//i.test(r.file_path)) {
                    $('#modal-file').html('<a href="' + r.file_path + '" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> ' + r.file_name + '</a>');
                } else {
                    $('#modal-file').html('<span class="file-tag"><i class="fas fa-paperclip"></i> ' + r.file_name + '</span>');
                }
            } else {
                $('#modal-file').text('Tidak ada lampiran');
            }

            if (r.error_full) {
                $('#modal-error-text').text(r.error_full);

                var retryMatch = r.error_full.match(/Retry (\d+)/);
                if (retryMatch) {
                    var maxRetry = r.error_full.indexOf('[Max RetriesReached]') !== -1;
                    $('#modal-error-retry').text('Percobaan ke-' + retryMatch[1] + (maxRetry ? ' · Berhenti (batas maksimum)' : ''));
                    $('#modal-error-retry').show();
                } else {
                    $('#modal-error-retry').hide();
                }

                $('#modal-error').show();
            } else {
                $('#modal-error').hide();
            }

            $('#msgModal').addClass('show');
        }

        function updateRangeLabel() {
            var from = $('#date_from').val();
            var to = $('#date_to').val();
            if (from === to) {
                var d = new Date(from + 'T00:00:00');
                var opts = { day: 'numeric', month: 'long', year: 'numeric' };
                $('#range-label').text(d.toLocaleDateString('id-ID', opts));
                $('#range-to-label').hide();
            } else {
                var d1 = new Date(from + 'T00:00:00');
                var d2 = new Date(to + 'T00:00:00');
                var opts = { day: 'numeric', month: 'long', year: 'numeric' };
                $('#range-label').text(d1.toLocaleDateString('id-ID', opts));
                $('#range-to-label').show().text(' — ' + d2.toLocaleDateString('id-ID', opts));
                $('#range-to-label').insertAfter('#range-label');
            }
        }

        $(document).ready(function () {
            var table = $('#waHistoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: getTableData(),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'no_surat', name: 'no_surat' },
                    { data: 'no_telp', name: 'no_telp' },
                    { data: 'pesan', name: 'pesan' },
                    { data: 'status', name: 'status' },
                    { data: 'error_message', name: 'error_message' },
                    { data: 'sent_at', name: 'sent_at' },
                    { data: 'created_at', name: 'created_at' },
                ],
                order: [[0, 'desc']],
                pageLength: 20,
                lengthMenu: [10, 20, 50, 100],
                drawCallback: function (settings) {
                    var info = this.api().page.info();
                    $('#row-count').text(info.recordsDisplay + ' data');
                },
                language: {
                    processing: '<div style="padding:24px;text-align:center;color:var(--text-muted);"><i class="fas fa-spinner fa-spin" style="margin-right:8px;"></i>Memuat data...</div>',
                    search: '<i class="fas fa-search" style="color:var(--text-subtle);margin-right:4px;"></i>',
                    searchPlaceholder: 'Cari no. surat, telepon, pesan...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    loadingRecords: 'Memuat...',
                    zeroRecords: '<div style="padding:40px;text-align:center;color:var(--text-muted);"><i class="fas fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;color:var(--text-subtle);"></i>Tidak ada data yang cocok</div>',
                    emptyTable: '<div style="padding:40px;text-align:center;color:var(--text-muted);"><i class="fas fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;color:var(--text-subtle);"></i>Belum ada data</div>',
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
            });

            $('#btn-filter').on('click', function () {
                table.ajax.reload();
                updateRangeLabel();
            });

            $('#date_from, #date_to').on('keypress', function (e) {
                if (e.which === 13) {
                    $('#btn-filter').click();
                }
            });

            $('#btn-reset').on('click', function () {
                var today = new Date().toISOString().split('T')[0];
                $('#date_from').val(today);
                $('#date_to').val(today);
                $('#active-status').val('');
                $('.stat-card').removeClass('active');
                updateRangeLabel();
                table.ajax.reload();
            });

            $('.stat-card').on('click', function () {
                var status = $(this).data('status') || '';
                $('.stat-card').removeClass('active');
                if (status) {
                    $(this).addClass('active');
                    $('#active-status').val(status);
                } else {
                    $('#active-status').val('');
                }
                table.ajax.reload();
            });

            $(document).on('click', '.btn-view', function () {
                openMessageModal($(this).data('id'));
                if ($(this).data('error')) {
                    var body = $('#msgModal .modal-body');
                    setTimeout(function () { body.animate({ scrollTop: body[0].scrollHeight }, 300); }, 50);
                }
            });

            $('#modal-close').on('click', function () {
                $('#msgModal').removeClass('show');
            });

            $('#msgModal').on('click', function (e) {
                if (e.target === this) {
                    $('#msgModal').removeClass('show');
                }
            });

            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    $('#msgModal').removeClass('show');
                }
            });

            $('#modal-copy').on('click', function () {
                var text = $('#modal-pesan').text();
                if (!text) return;
                navigator.clipboard.writeText(text).then(function () {
                    showToast('Isi pesan disalin ke clipboard');
                }).catch(function () {
                    showToast('Gagal menyalin pesan');
                });
            });

            updateRangeLabel();
        });
    </script>
</body>
</html>
