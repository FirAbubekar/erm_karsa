<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat General Consent</title>

    <!-- Google Fonts: Inter -->
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
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-secondary: #334155;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #3B82F6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* ─── Sidebar Styles ─── */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
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
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
        }

        .logo-text {
            font-weight: 700;
            color: var(--text-main);
            font-size: 18px;
            letter-spacing: -0.5px;
        }

        .nav-section {
            padding: 24px 16px;
            flex-grow: 1;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 16px;
            margin-left: 8px;
            letter-spacing: 0.05em;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .nav-item:hover {
            background: var(--bg-main);
            color: var(--text-main);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-item svg { width: 20px; height: 20px; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            margin-top: auto;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            width: 100%;
            background: #FEF2F2;
            color: #EF4444;
            border: 1px solid #FEE2E2;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: #FEE2E2;
            border-color: #FECACA;
            transform: translateY(-1px);
        }

        .btn-logout svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: white;
            border-bottom: 1px solid var(--border);
            align-items: center;
            padding: 0 20px;
            z-index: 40;
            justify-content: space-between;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 45;
        }
        .sidebar-overlay.active { display: block; }

        /* ─── Main Content ─── */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 0;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        /* ─── Hero Header ─── */
        .page-hero {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            padding: 40px 40px 60px;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .page-hero::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        .hero-title h1 {
            font-size: 28px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.75px;
            margin-bottom: 6px;
        }

        .hero-title p {
            color: rgba(255,255,255,0.5);
            font-size: 14px;
            font-weight: 400;
        }

        .hero-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 12px;
            color: rgba(255,255,255,0.4);
        }

        .hero-breadcrumb a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }

        .hero-breadcrumb a:hover {
            color: rgba(255,255,255,0.8);
        }

        .hero-breadcrumb .current {
            color: rgba(255,255,255,0.7);
            font-weight: 600;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px 8px 8px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .user-chip .avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            color: white;
        }

        .user-chip span {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            font-weight: 600;
        }

        /* ─── Stats Cards ─── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: -36px 40px 0;
            position: relative;
            z-index: 2;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04), 0 10px 15px -3px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg {
            width: 24px;
            height: 24px;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.2));
            color: var(--primary);
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.2));
            color: var(--info);
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(99, 102, 241, 0.18));
            color: var(--accent);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 4px;
        }

        /* ─── Content Area ─── */
        .content-area {
            padding: 32px 40px 40px;
        }

        /* ─── Toolbar ─── */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 16px 10px 40px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-main);
            width: 280px;
            outline: none;
            transition: all 0.2s;
        }

        .search-box input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-light);
        }

        .search-box input::placeholder {
            color: #94A3B8;
        }

        .search-box svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #94A3B8;
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            margin-bottom: 28px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .filter-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .filter-header {
            padding: 16px 24px;
            background: linear-gradient(to right, #F8FAFC, #FFFFFF);
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
        }

        .filter-badge {
            background: var(--accent-light);
            color: var(--accent);
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .filter-body {
            padding: 24px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 11px;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-left: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-input-wrapper {
            position: relative;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .filter-input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #94A3B8;
            z-index: 10;
        }

        .filter-input {
            width: 100%;
            padding: 11px 16px 11px 42px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .filter-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-light);
            background: white;
        }

        .filter-input::placeholder {
            color: #CBD5E1;
        }

        .filter-actions {
            display: flex;
            gap: 12px;
            padding: 20px 24px 24px;
            justify-content: flex-end;
            background: #FAFBFC;
            border-top: 1px solid #F1F5F9;
        }

        .btn-filter-submit {
            padding: 11px 28px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
        }

        .btn-filter-submit:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-filter-reset {
            padding: 11px 22px;
            background: white;
            color: #64748B;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-filter-reset:hover {
            background: #F1F5F9;
            color: #1E293B;
            border-color: #CBD5E1;
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
        }

        .btn-create:active {
            transform: translateY(0);
        }

        .btn-create svg {
            width: 18px;
            height: 18px;
        }

        /* ─── Table ─── */
        .table-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            padding: 14px 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            background: linear-gradient(to bottom, #FAFBFC, #F6F8FA);
            border-bottom: 1px solid var(--border);
            text-align: left;
            white-space: nowrap;
            position: sticky;
            top: 0;
        }

        .data-table thead th:last-child {
            text-align: center;
        }

        .data-table tbody tr {
            transition: all 0.15s ease;
            border-bottom: 1px solid #F1F5F9;
        }

        .data-table tbody tr:last-child {
            border-bottom: none;
        }

        .data-table tbody tr:hover {
            background: linear-gradient(to right, rgba(99, 102, 241, 0.02), rgba(16, 185, 129, 0.02));
        }

        .data-table td {
            padding: 16px 20px;
            font-size: 13px;
            color: var(--text-secondary);
            vertical-align: middle;
        }

        /* No. Surat Badge */
        .surat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            background: var(--accent-light);
            border: 1px solid rgba(99, 102, 241, 0.12);
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            color: var(--accent);
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            letter-spacing: -0.3px;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Patient Info Cell */
        .patient-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .patient-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #E0E7FF, #C7D2FE);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .patient-details {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .patient-name {
            font-weight: 700;
            color: var(--text-main);
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .patient-rm {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 2px;
        }

        /* Date Cell */
        .date-cell {
            display: flex;
            flex-direction: column;
        }

        .date-value {
            font-weight: 600;
            color: var(--text-main);
            font-size: 13px;
        }

        .date-relative {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Hubungan Badge */
        .hub-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .hub-badge.suami { background: #DBEAFE; color: #1E40AF; }
        .hub-badge.istri { background: #FCE7F3; color: #9D174D; }
        .hub-badge.default { background: #F1F5F9; color: #475569; }

        /* Action Buttons */
        .action-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-detail {
            background: var(--accent-light);
            color: var(--accent);
            border: 1px solid rgba(99, 102, 241, 0.12);
        }

        .btn-detail:hover {
            background: var(--accent);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .btn-pdf {
            background: rgba(16, 185, 129, 0.08);
            color: var(--primary);
            border: 1px solid rgba(16, 185, 129, 0.15);
        }

        .btn-pdf:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-wa {
            background: rgba(37, 211, 102, 0.08);
            color: #25D366;
            border: 1px solid rgba(37, 211, 102, 0.15);
        }

        .btn-wa:hover {
            background: #25D366;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
        }

        .btn-action svg {
            width: 14px;
            height: 14px;
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state .empty-icon {
            width: 64px;
            height: 64px;
            background: var(--accent-light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--accent);
        }

        .empty-state .empty-icon svg {
            width: 28px;
            height: 28px;
            opacity: 0.6;
        }

        .empty-state h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .empty-state p {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* ─── Pagination ─── */
        .pagination-wrapper {
            margin-top: 24px;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-wrapper nav > div:first-child {
            display: none;
        }

        .pagination-wrapper nav span[aria-current="page"] span,
        .pagination-wrapper nav a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
        }

        .pagination-wrapper nav span[aria-current="page"] span {
            background: var(--accent);
            color: white;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
        }

        .pagination-wrapper nav a {
            background: white;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .pagination-wrapper nav a:hover {
            background: var(--accent-light);
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ─── Modal ─── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-panel {
            background: white;
            width: 100%;
            max-width: 640px;
            max-height: 90vh;
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.3);
            animation: modalIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalIn {
            from { transform: translateY(30px) scale(0.97); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .modal-top {
            padding: 24px 28px;
            background: linear-gradient(135deg, #0F172A, #1E293B);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .modal-top h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .modal-top p {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
        }

        .modal-close {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7);
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .modal-close svg {
            width: 18px;
            height: 18px;
        }

        .modal-body {
            padding: 28px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .modal-section-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--accent);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-section-title::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: var(--border);
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }

        .info-item label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .info-item .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        .info-item .info-value.mono {
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 12px;
            background: var(--accent-light);
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            color: var(--accent);
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .sig-box {
            background: #FAFBFC;
            border: 2px dashed var(--border);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 140px;
            transition: all 0.2s;
        }

        .sig-box:hover {
            border-color: var(--accent);
            background: white;
        }

        .sig-box img {
            max-height: 100px;
            max-width: 100%;
            object-fit: contain;
        }

        .sig-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
        }

        .sig-placeholder svg {
            width: 28px;
            height: 28px;
            opacity: 0.3;
        }

        .sig-placeholder span {
            font-size: 11px;
            font-weight: 600;
        }

        .modal-footer {
            padding: 16px 28px;
            background: #FAFBFC;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
        }

        .btn-close-modal {
            padding: 10px 28px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .btn-close-modal:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.3);
        }

        /* ─── Animations ─── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease-out both;
        }

        .animate-delay-1 { animation-delay: 0.05s; }
        .animate-delay-2 { animation-delay: 0.1s; }
        .animate-delay-3 { animation-delay: 0.15s; }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .mobile-header { display: flex; }
            .page-hero { padding: 80px 20px 50px; }
            .stats-row { grid-template-columns: 1fr; margin: -28px 20px 0; }
            .content-area { padding: 24px 20px; }
            .toolbar { flex-direction: column; gap: 12px; align-items: stretch; }
            .search-box input { width: 100%; }
        }

        @media (max-width: 768px) {
            .info-grid { grid-template-columns: 1fr; }
            .stats-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <!-- ─── Hero Header ─── -->
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                <span class="current">Riwayat General Consent</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>📋 Riwayat General Consent</h1>
                    <p>Kelola data persetujuan umum pasien yang telah tersimpan.</p>
                </div>
                <div class="user-chip">
                    <div class="avatar">{{ strtoupper(substr(Session::get('user_id', '?'), 0, 1)) }}</div>
                    <span>{{ Session::get('user_id') }}</span>
                </div>
            </div>
        </div>

        <!-- ─── Stats Cards ─── -->
        <div class="stats-row">
            <div class="stat-card animate-in animate-delay-1">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $consents->total() }}</span>
                    <span class="stat-label">Total Persetujuan</span>
                </div>
            </div>
            <div class="stat-card animate-in animate-delay-2">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $consents->where('tanggal', '>=', now()->startOfMonth()->toDateString())->count() }}</span>
                    <span class="stat-label">Bulan Ini</span>
                </div>
            </div>
            <div class="stat-card animate-in animate-delay-3">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.53 0 1.04.21 1.41.59L17 7l-3.59 3.59A2 2 0 0112 11H7a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $consents->count() }}</span>
                    <span class="stat-label">Halaman Ini</span>
                </div>
            </div>
        </div>

        <!-- ─── Content Area ─── -->
        <div class="content-area">
            @if(session('error'))
                <div class="animate-in" style="background: #FFF1F2; color: #E11D48; padding: 14px 20px; border-radius: 16px; margin-bottom: 24px; border: 1px solid #FFE4E6; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.05);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Advanced Filter Card (Premium Design) -->
            <div class="filter-card animate-in">
                <div class="filter-header">
                    <div class="filter-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Pencarian Lanjutan Dokumen
                        @php $activeFilters = count(array_filter(request()->only(['search', 'no_rawat', 'person', 'start_date', 'end_date']))); @endphp
                        @if($activeFilters > 0)
                            <span class="filter-badge">{{ $activeFilters }} Filter Aktif</span>
                        @endif
                    </div>
                    <div style="font-size: 11px; color: #94A3B8;">Gunakan filter untuk mempersempit pencarian data lama</div>
                </div>
                
                <form action="{{ route('general-consent.index') }}" method="GET">
                    <div class="filter-body">
                        <div class="filter-form">
                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Identitas Pasien
                                </label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" name="search" value="{{ request('search') }}" class="filter-input" placeholder="Nama, No.RM, atau No.Surat">
                                </div>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Registrasi (No. Rawat)
                                </label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.53 0 1.04.21 1.41.59L17 7l-3.59 3.59A2 2 0 0112 11H7a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                                    <input type="text" name="no_rawat" value="{{ request('no_rawat') }}" class="filter-input" placeholder="Format: YYYY/MM/DD/xxxxxx">
                                </div>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Penanggung Jawab / Staf
                                </label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <input type="text" name="person" value="{{ request('person') }}" class="filter-input" placeholder="Cari nama PJ atau Petugas">
                                </div>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                                    Periode Pemeriksaan
                                </label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <div class="filter-input-wrapper" style="flex: 1;">
                                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="filter-input" style="padding-left: 14px;">
                                    </div>
                                    <span style="color: #94A3B8; font-size: 11px; font-weight: 700;">s/d</span>
                                    <div class="filter-input-wrapper" style="flex: 1;">
                                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="filter-input" style="padding-left: 14px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="filter-actions">
                        @if($activeFilters > 0)
                            <a href="{{ route('general-consent.index') }}" class="btn-filter-reset">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset Pencarian
                            </a>
                        @endif
                        <button type="submit" class="btn-filter-submit">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Toolbar (Action Only) -->
            <div class="toolbar" style="margin-bottom: 16px;">
                <div class="toolbar-left">
                    <h2 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Daftar Dokumen Terpilih
                    </h2>
                </div>
                <a href="{{ route('general-consent') }}" class="btn-create">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Baru
                </a>
            </div>

            <!-- Table -->
            <div class="table-card animate-in">
                <div class="table-wrapper">
                    <table class="data-table" id="consentTable">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Pasien</th>
                                <th>Tgl. Periksa</th>
                                <th>Penanggung Jawab</th>
                                <th>Hubungan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consents as $consent)
                            <tr class="table-row">
                                <td>
                                    <span class="surat-badge" title="{{ $consent->no_surat }}">
                                        @if($consent->regPeriksa->signaturePasien)
                                            <span class="badge badge-success">Ada TTD</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak Ada TTD</span>
                                        @endif
                                        {{ $consent->no_surat }}
                                    </span>
                                </td>
                                <td>
                                    <div class="patient-info">
                                        <div class="patient-avatar">
                                            {{ strtoupper(substr($consent->regPeriksa->pasien->nm_pasien ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="patient-details">
                                            <span class="patient-name">{{ $consent->regPeriksa->pasien->nm_pasien ?? 'Unknown' }}</span>
                                            <span class="patient-rm">{{ $consent->regPeriksa->no_rkm_medis ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <span class="date-value">{{ \Carbon\Carbon::parse($consent->tanggal)->format('d M Y') }}</span>
                                        <span class="date-relative">{{ \Carbon\Carbon::parse($consent->tanggal)->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--text-secondary);">{{ $consent->nama_pj }}</span>
                                </td>
                                <td>
                                    @php
                                        $hub = strtolower($consent->bertindak_atas);
                                        $hubClass = 'default';
                                        if (str_contains($hub, 'suami')) $hubClass = 'suami';
                                        elseif (str_contains($hub, 'istri')) $hubClass = 'istri';
                                    @endphp
                                    <span class="hub-badge {{ $hubClass }}">{{ $consent->bertindak_atas }}</span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button
                                            class="btn-action btn-detail"
                                            onclick="openDetailModal({{ json_encode([
                                                'no_surat' => $consent->no_surat,
                                                'nm_pasien' => $consent->regPeriksa->pasien->nm_pasien ?? 'Unknown',
                                                'no_rm' => $consent->regPeriksa->no_rkm_medis ?? '-',
                                                'tgl_periksa' => $consent->tanggal,
                                                'nama_pj' => $consent->nama_pj,
                                                'hubungan' => $consent->bertindak_atas,
                                                'ktp_pj' => $consent->no_ktppj,
                                                'telp_pj' => $consent->no_telp,
                                                'kepercayaan' => $consent->nilai_kepercayaan,
                                                'signature' => $consent->signatures_path ? asset('storage/' . $consent->signatures_path) : null
                                            ]) }})"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </button>
                                        <button
                                            class="btn-action btn-pdf"
                                            onclick="downloadPDF('{{ route('general-consent.download', $consent->no_surat) }}')"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            PDF
                                        </button>
                                        <button
                                            class="btn-action btn-wa"
                                            onclick="sendWA('{{ $consent->no_surat }}', this, event)"
                                        >
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                            WA
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <h3>Belum Ada Riwayat</h3>
                                        <p>Data general consent yang tersimpan akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $consents->links() }}
            </div>
        </div>
    </div>

    <!-- ─── Detail Modal ─── -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-panel">
            <div class="modal-top">
                <div>
                    <h3>Detail General Consent</h3>
                    <p>Informasi lengkap persetujuan pasien</p>
                </div>
                <button class="modal-close" onclick="closeDetailModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="modal-body">
                <!-- Pasien Info -->
                <div class="modal-section-title">Informasi Pasien</div>
                <div class="info-grid">
                    <div class="info-item">
                        <label>No. Surat</label>
                        <span id="modalNoSurat" class="info-value mono">-</span>
                    </div>
                    <div class="info-item">
                        <label>Tgl. Periksa</label>
                        <span id="modalTglPeriksa" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Nama Pasien</label>
                        <span id="modalNamaPasien" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>No. Rekam Medis</label>
                        <span id="modalNoRM" class="info-value">-</span>
                    </div>
                </div>

                <!-- Penanggung Jawab -->
                <div class="modal-section-title">Penanggung Jawab</div>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nama Lengkap</label>
                        <span id="modalNamaPJ" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Hubungan</label>
                        <span id="modalHubungan" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>No. Telepon</label>
                        <span id="modalTelpPJ" class="info-value">-</span>
                    </div>
                    <div class="info-item" style="display:none;">
                        <label>No. KTP / NIK</label>
                        <span id="modalKTPPJ" class="info-value">-</span>
                    </div>
                </div>

                <!-- Pelepasan Informasi -->
                <div class="modal-section-title">Pelepasan Informasi (Pihak Berwenang)</div>
                <div id="modalPelepasanContainer">
                    <div id="modalPelepasanLoading" style="text-align:center; padding:16px; color:#64748B; font-size:13px;">
                        <em>Memuat data...</em>
                    </div>
                    <table id="modalPelepasanTable" style="width:100%; border-collapse:collapse; display:none;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:10px 14px; background:linear-gradient(to bottom,#FAFBFC,#F6F8FA); color:#64748B; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E2E8F0;">No.</th>
                                <th style="text-align:left; padding:10px 14px; background:linear-gradient(to bottom,#FAFBFC,#F6F8FA); color:#64748B; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E2E8F0;">Nama</th>
                                <th style="text-align:left; padding:10px 14px; background:linear-gradient(to bottom,#FAFBFC,#F6F8FA); color:#64748B; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #E2E8F0;">No. Telp</th>
                            </tr>
                        </thead>
                        <tbody id="modalPelepasanBody"></tbody>
                    </table>
                    <div id="modalPelepasanEmpty" style="display:none; text-align:center; padding:20px; color:#94A3B8; font-size:13px;">
                        <em>Tidak ada data pihak berwenang.</em>
                    </div>
                </div>

                <!-- Signature -->
                <div class="modal-section-title" style="margin-top:28px;">Tanda Tangan Digital</div>
                <div class="sig-box">
                    <img id="modalSignature" src="" alt="Tanda Tangan" style="display:none;">
                    <div id="modalNoSignature" class="sig-placeholder">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Tanda tangan tidak tersedia</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // ─── Detail Modal ───
        function openDetailModal(data) {
            document.getElementById('modalNoSurat').textContent = data.no_surat;
            document.getElementById('modalNamaPasien').textContent = data.nm_pasien;
            document.getElementById('modalNoRM').textContent = data.no_rm;
            document.getElementById('modalTglPeriksa').textContent = data.tgl_periksa;
            document.getElementById('modalNamaPJ').textContent = data.nama_pj;
            document.getElementById('modalHubungan').textContent = data.hubungan;
            document.getElementById('modalKTPPJ').textContent = data.ktp_pj;
            document.getElementById('modalTelpPJ').textContent = data.telp_pj;

            const sigImg = document.getElementById('modalSignature');
            const noSig = document.getElementById('modalNoSignature');

            if (data.signature) {
                sigImg.src = data.signature;
                sigImg.style.display = 'block';
                noSig.style.display = 'none';
            } else {
                sigImg.style.display = 'none';
                noSig.style.display = 'flex';
            }

            // ─── Fetch Pelepasan Informasi ───
            const tableEl = document.getElementById('modalPelepasanTable');
            const bodyEl = document.getElementById('modalPelepasanBody');
            const loadingEl = document.getElementById('modalPelepasanLoading');
            const emptyEl = document.getElementById('modalPelepasanEmpty');

            // Reset state
            tableEl.style.display = 'none';
            emptyEl.style.display = 'none';
            loadingEl.style.display = 'block';
            bodyEl.innerHTML = '';

            if (data.no_surat) {
                fetch(`/pelepasan-informasi/${data.no_surat}`)
                    .then(res => res.json())
                    .then(items => {
                        loadingEl.style.display = 'none';
                        if (items.length > 0) {
                            items.forEach((item, idx) => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td style="padding:10px 14px; border-bottom:1px solid #F1F5F9; font-size:13px; color:#64748B; font-weight:600;">${idx + 1}</td>
                                    <td style="padding:10px 14px; border-bottom:1px solid #F1F5F9; font-size:13px; font-weight:600; color:#0F172A;">${item.nama}</td>
                                    <td style="padding:10px 14px; border-bottom:1px solid #F1F5F9; font-size:13px; color:#334155;">${item.no_telp || '-'}</td>
                                `;
                                bodyEl.appendChild(tr);
                            });
                            tableEl.style.display = 'table';
                        } else {
                            emptyEl.style.display = 'block';
                        }
                    })
                    .catch(() => {
                        loadingEl.style.display = 'none';
                        emptyEl.style.display = 'block';
                    });
            } else {
                loadingEl.style.display = 'none';
                emptyEl.style.display = 'block';
            }

            document.getElementById('detailModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close on overlay click
        document.getElementById('detailModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeDetailModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDetailModal();
        });

        // ─── Client-side table search ───
        function filterTable(query) {
            const rows = document.querySelectorAll('#consentTable tbody .table-row');
            const q = query.toLowerCase().trim();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        }
        // ─── PDF Download with Geolocation ───
        function downloadPDF(baseUrl) {
            // Helper fungsion untuk buka tab
            const proceedDownload = (lat, lng) => {
                const query = (lat && lng) ? '?lat=' + lat + '&lng=' + lng : '';
                window.open(baseUrl + query, '_blank');
            };

            // Jika di HTTP (bukan localhost), browser block HTML5 Geolocation. Kita pakai IP-API JS fallback.
            if (navigator.geolocation && (window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        proceedDownload(position.coords.latitude.toFixed(6), position.coords.longitude.toFixed(6));
                    },
                    function(error) {
                        // Fallback ke IP Geoloc jika ditolak
                        fetch('http://ip-api.com/json/')
                            .then(res => res.json())
                            .then(data => proceedDownload(data.lat, data.lon))
                            .catch(() => proceedDownload('-', '-'));
                    },
                    { timeout: 5000 } // 5 detik
                );
            } else {
                // Gunakan IP Geolocation langsung jika HTTP network (seperti 192.168.x.x)
                fetch('http://ip-api.com/json/')
                    .then(res => res.json())
                    .then(data => proceedDownload(data.lat, data.lon))
                    .catch(() => proceedDownload('-', '-'));
            }
        }

        async function sendWA(noSurat, btn, event) {
            if (event) event.preventDefault();
            console.log('sendWA triggered for:', noSurat);
            
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span style="font-size:10px;">Loading...</span>';
            
            try {
                // 1. Fetch template
                const templateResponse = await fetch(`/general-consent/wa-template/${encodeURIComponent(noSurat)}`);
                if (!templateResponse.ok) throw new Error('Gagal mengambil template');
                const { template } = await templateResponse.json();
                
                // 2. Prompt user
                const message = prompt('Edit pesan WhatsApp (klik OK untuk kirim, Cancel untuk batal):', template);
                if (message === null) return; // User cancelled
                
                btn.innerHTML = '<span style="font-size:10px;">Sending...</span>';
                
                // 3. Send POST
                const response = await fetch(`/general-consent/send-wa/${encodeURIComponent(noSurat)}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });
                // const result = await response.json();
                // const text = await response.text();
               
                console.log('sendWA triggered for:', response);
                // if (response.ok) {
                //     alert(result.message);
                // } else {
                //     alert(result.error || 'Gagal mengirim WhatsApp');
                // }
            } catch (err) {
                // console.log(noSurat);
                alert('Terjadi kesalahan jaringan' + err);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }
    </script>
</body>
</html>
