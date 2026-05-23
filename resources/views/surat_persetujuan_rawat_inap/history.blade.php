<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat Surat Persetujuan Rawat Inap</title>

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

        /* Sidebar Styles */
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
            overflow-y: auto;
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

        .nav-item svg {
            width: 20px;
            height: 20px;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            margin-top: auto;
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
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
        }

        .sidebar-overlay.active {
            display: block;
        }

        @media (max-width: 1024px) {
            .mobile-header {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding-top: 64px;
            }
        }

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
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
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
            background: white;
            padding: 0;
            border-radius: 20px;
            border: 1px solid var(--border);
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }

        .filter-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        }

        .filter-header {
            padding: 18px 24px;
            background: linear-gradient(to right, #F8FAFC, #FAFBFC);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .filter-header-icon {
            width: 32px;
            height: 32px;
            background: var(--accent-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            flex-shrink: 0;
        }

        .filter-header-text {
            flex-grow: 1;
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .filter-badge {
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .filter-badge.today {
            background: #ECFDF5;
            color: #10B981;
            border: 1px solid #D1FAE5;
        }

        .filter-badge.active {
            background: var(--accent-light);
            color: var(--accent);
            border: 1px solid rgba(99, 102, 241, 0.2);
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 4px 12px rgba(0,0,0,0.03);
        }

        .table-top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px 14px;
            border-bottom: 1px solid var(--border-light);
            background: linear-gradient(to right, #FAFBFC, white);
        }

        .table-top-bar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-top-bar-left h2 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.2px;
        }

        .result-count-badge {
            background: var(--bg-main);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid var(--border);
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
        }

        .data-table thead th:last-child {
            text-align: center;
        }

        .data-table tbody tr {
            transition: all 0.15s ease;
            border-bottom: 1px solid #F1F5F9;
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

        /* Badges */
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
        }

        .badge-success {
            background: #ECFDF5;
            color: #10B981;
            border: 1px solid #A7F3D0;
        }

        .badge-secondary {
            background: #F1F5F9;
            color: #64748B;
            border: 1px solid #E2E8F0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border-radius: 6px;
            font-weight: 700;
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
        .hub-badge.diri { background: #ECFDF5; color: #065F46; }
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
            background: rgba(59, 130, 246, 0.08);
            color: var(--info);
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        .btn-wa:hover {
            background: var(--info);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        /* Empty State */
        .empty-state {
            padding: 72px 40px;
            text-align: center;
        }

        .empty-state .empty-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
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
        }

        .empty-state h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 13px;
            color: var(--text-muted);
            max-width: 340px;
            margin: 0 auto 20px;
            line-height: 1.6;
        }

        .empty-state .empty-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: var(--accent-light);
            color: var(--accent);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .empty-state .empty-action:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
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
            max-width: 720px;
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
            margin-top: 12px;
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
            gap: 16px 24px;
            margin-bottom: 24px;
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
            padding: 20px;
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
            max-height: 120px;
            max-width: 100%;
            object-fit: contain;
        }

        .sig-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .sig-placeholder svg {
            width: 28px;
            height: 28px;
            opacity: 0.5;
        }

        .btn-close-modal {
            padding: 10px 20px;
            background: #F1F5F9;
            color: var(--text-main);
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-close-modal:hover {
            background: #E2E8F0;
        }

        .modal-footer {
            padding: 20px 28px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            background: #FAFBFC;
        }
    </style>
</head>
<body>

    @include('partials.sidebar')

    <main class="main-content">
        <!-- Hero Header -->
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>/</span>
                <span class="current">Riwayat Surat Persetujuan Rawat Inap</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>RM 02</h1>
                    <p>Daftar Riwayat Surat Persetujuan & Penolakan Rawat Inap Pasien</p>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="stats-row animate-in">
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['total'] }}</span>
                    <span class="stat-label">Total Dokumen</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['today'] }}</span>
                    <span class="stat-label">Hari Ini</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['month'] }}</span>
                    <span class="stat-label font-semibold">Bulan Ini</span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            
            <!-- Toolbar -->
            <div class="toolbar animate-in">
                <div class="toolbar-left">
                    <div class="search-box">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="tableSearch" onkeyup="filterTable(this.value)" placeholder="Cari cepat di tabel ini...">
                    </div>
                </div>
                <a href="{{ route('surat-persetujuan-rawat-inap.index') }}" class="btn-create">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Buat Baru
                </a>
            </div>

            <!-- Advanced Filter Card -->
            <div class="filter-card animate-in">
                <div class="filter-header">
                    <div class="filter-header-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    </div>
                    <div class="filter-header-text">
                        <div class="filter-title">
                            Pencarian & Penyaringan Lanjutan
                            @if($activeFilters > 0)
                                <span class="filter-badge active">{{ $activeFilters }} Filter Aktif</span>
                            @else
                                <span class="filter-badge today">Hari Ini</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('surat-persetujuan-rawat-inap.history') }}" method="GET">
                    <div class="filter-body">
                        <div class="filter-form">
                            <!-- Search -->
                            <div class="filter-group">
                                <label>Pencarian</label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" name="search" value="{{ request('search') }}" class="filter-input" placeholder="Nama Pasien / No. RM / No. Surat">
                                </div>
                            </div>
                            <!-- No Rawat -->
                            <div class="filter-group">
                                <label>No. Rawat</label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <input type="text" name="no_rawat" value="{{ request('no_rawat') }}" class="filter-input" placeholder="Contoh: 2026/05/21/000001">
                                </div>
                            </div>
                            <!-- Penanggung Jawab -->
                            <div class="filter-group">
                                <label>Penanggung Jawab / Petugas</label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <input type="text" name="person" value="{{ request('person') }}" class="filter-input" placeholder="Nama PJ / Nama Petugas">
                                </div>
                            </div>
                            <!-- Tanggal Range -->
                            <div class="filter-group">
                                <label>Periode Tanggal</label>
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
                            <a href="{{ route('surat-persetujuan-rawat-inap.history') }}" class="btn-filter-reset">
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

            <!-- Tabel Data -->
            <div class="table-card animate-in">
                <div class="table-top-bar">
                    <div class="table-top-bar-left">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <h2>Daftar Dokumen @if($isDefaultToday)(Hari Ini, {{ now()->format('d M Y') }})@endif</h2>
                        <span class="result-count-badge">{{ $consents->total() }} dokumen</span>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table" id="historyTable">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Informasi Pasien</th>
                                <th>Tanggal</th>
                                <th>Penanggung Jawab</th>
                                <th>Ruang & Kelas</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consents as $consent)
                            <tr class="table-row">
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <div class="surat-badge" title="{{ $consent->no_surat }}">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            {{ $consent->no_surat }}
                                        </div>
                                        @if($consent->regPeriksa->signaturePasien)
                                            <span class="badge badge-success" style="width: fit-content; font-size: 9px; padding: 2px 6px;">
                                                <svg width="8" height="8" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                Tanda Tangan Ada
                                            </span>
                                        @else
                                            <span class="badge badge-secondary" style="width: fit-content; font-size: 9px; padding: 2px 6px;">
                                                Belum TTD
                                            </span>
                                        @endif
                                    </div>
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
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span style="font-weight: 700; color: var(--text-secondary);">{{ $consent->nama_pj }}</span>
                                        @php
                                            $hub = strtolower($consent->hubungan);
                                            $hubClass = 'default';
                                            if (str_contains($hub, 'suami')) $hubClass = 'suami';
                                            elseif (str_contains($hub, 'istri')) $hubClass = 'istri';
                                            elseif (str_contains($hub, 'diri') || str_contains($hub, 'saya')) $hubClass = 'diri';
                                        @endphp
                                        <span class="hub-badge {{ $hubClass }}" style="width: fit-content; font-size: 10px; padding: 2px 8px;">{{ $consent->hubungan }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <span style="font-weight: 600; color: var(--text-main); font-size: 13px;">{{ $consent->ruang ?? '-' }}</span>
                                        <span style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ $consent->kelas ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button
                                            class="btn-action btn-detail"
                                            onclick="openDetailModal({{ json_encode([
                                                'no_surat' => $consent->no_surat,
                                                'no_rawat' => $consent->no_rawat,
                                                'nm_pasien' => $consent->regPeriksa->pasien->nm_pasien ?? 'Unknown',
                                                'no_rm' => $consent->regPeriksa->no_rkm_medis ?? '-',
                                                'tgl_periksa' => $consent->tanggal,
                                                'nama_pj' => $consent->nama_pj,
                                                'hubungan' => $consent->hubungan,
                                                'ktp_pj' => $consent->no_ktppj,
                                                'telp_pj' => $consent->no_telppj,
                                                'alamat_pj' => $consent->alamatpj,
                                                'pendidikan_pj' => $consent->pendidikan_pj,
                                                'hak_kelas' => $consent->hak_kelas,
                                                'ruang_diinginkan' => $consent->ruang,
                                                'kelas' => $consent->kelas,
                                                'bayar_secara' => $consent->bayar_secara,
                                                'nama_alamat_keluarga_terdekat' => $consent->nama_alamat_keluarga_terdekat,
                                                'petugas' => ($consent->pegawai->nama ?? '-') . ' (' . ($consent->nip ?? '-') . ')',
                                                'signature' => $consent->regPeriksa->signaturePasien ? asset('storage/' . $consent->regPeriksa->signaturePasien->signature_path) : null
                                            ]) }})"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </button>
                                        <button
                                            class="btn-action btn-pdf"
                                            onclick="downloadPDF('{{ route('surat-persetujuan-rawat-inap.download', $consent->no_surat) }}')"
                                            title="Unduh PDF"
                                        >
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            PDF
                                        </button>
                                        <button
                                            class="btn-action btn-wa"
                                            onclick="sendWA('{{ $consent->no_surat }}', this, event)"
                                            title="Kirim WhatsApp"
                                        >
                                            <svg fill="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
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
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        @if($isDefaultToday)
                                            <h3>Belum Ada Dokumen Hari Ini</h3>
                                            <p>Tidak ada surat persetujuan rawat inap yang dibuat pada {{ now()->format('d M Y') }}. Gunakan Pencarian Lanjutan di atas untuk mencari data dari tanggal lain.</p>
                                            <a href="{{ route('surat-persetujuan-rawat-inap.history') }}?start_date=&end_date=" class="empty-action">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                Lihat Semua Riwayat
                                            </a>
                                        @else
                                            <h3>Tidak Ada Hasil Ditemukan</h3>
                                            <p>Tidak ada dokumen yang cocok dengan filter pencarian Anda. Coba ubah atau reset filter di atas.</p>
                                            <a href="{{ route('surat-persetujuan-rawat-inap.history') }}" class="empty-action">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Reset Pencarian
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper animate-in">
                {{ $consents->links() }}
            </div>
        </div>
    </main>

    <!-- Detail Modal -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-panel animate-in">
            <div class="modal-top">
                <div>
                    <h3>Detail Surat Persetujuan Rawat Inap</h3>
                    <p>Informasi lengkap dokumen persetujuan pasien</p>
                </div>
                <button class="modal-close" onclick="closeDetailModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
                        <label>No. Rawat</label>
                        <span id="modalNoRawat" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Nama Pasien</label>
                        <span id="modalNamaPasien" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>No. Rekam Medis</label>
                        <span id="modalNoRM" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Tanggal Dokumen</label>
                        <span id="modalTglPeriksa" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Petugas Entry</label>
                        <span id="modalPetugas" class="info-value">-</span>
                    </div>
                </div>

                <!-- Penanggung Jawab -->
                <div class="modal-section-title">Penanggung Jawab (Pihak Pertama)</div>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nama Lengkap</label>
                        <span id="modalNamaPJ" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>NIK / KTP Penanggung Jawab</label>
                        <span id="modalKTPPJ" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Hubungan dengan Pasien</label>
                        <span id="modalHubungan" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Pendidikan</label>
                        <span id="modalPendidikanPJ" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>No. Telepon / HP</label>
                        <span id="modalTelpPJ" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Penanggung Jawab Biaya</label>
                        <span id="modalBayarSecara" class="info-value">-</span>
                    </div>
                    <div class="info-item full-width">
                        <label>Alamat Lengkap PJ</label>
                        <span id="modalAlamatPJ" class="info-value">-</span>
                    </div>
                    <div class="info-item full-width">
                        <label>Keluarga Terdekat (Darurat)</label>
                        <span id="modalKeluargaTerdekat" class="info-value">-</span>
                    </div>
                </div>

                <!-- Hak & Kelas Perawatan -->
                <div class="modal-section-title">Pilihan Kamar & Perawatan</div>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Hak Kelas Perawatan</label>
                        <span id="modalHakKelas" class="info-value">-</span>
                    </div>
                    <div class="info-item">
                        <label>Kamar/Ruang yang Diinginkan</label>
                        <span id="modalKamarDiinginkan" class="info-value">-</span>
                    </div>
                </div>

                <!-- Signature -->
                <div class="modal-section-title">Tanda Tangan Penanggung Jawab</div>
                <div class="sig-box">
                    <img id="modalSignature" src="" alt="Tanda Tangan" style="display:none;">
                    <div id="modalNoSignature" class="sig-placeholder">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Tanda tangan belum diunggah</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="display: flex; gap: 10px; align-items: center;">
                <button class="btn-action btn-pdf" id="modalBtnPdf" onclick="" style="padding: 10px 20px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh PDF
                </button>
                <button class="btn-action btn-wa" id="modalBtnWa" onclick="" style="padding: 10px 20px;">
                    <svg fill="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Kirim WA
                </button>
                <button class="btn-close-modal" onclick="closeDetailModal()" style="margin-left: auto;">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Open Detail Modal
        function openDetailModal(data) {
            document.getElementById('modalNoSurat').textContent = data.no_surat;
            document.getElementById('modalNoRawat').textContent = data.no_rawat;
            document.getElementById('modalNamaPasien').textContent = data.nm_pasien;
            document.getElementById('modalNoRM').textContent = data.no_rm;
            document.getElementById('modalTglPeriksa').textContent = data.tgl_periksa;
            document.getElementById('modalNamaPJ').textContent = data.nama_pj;
            document.getElementById('modalKTPPJ').textContent = data.ktp_pj;
            document.getElementById('modalHubungan').textContent = data.hubungan;
            document.getElementById('modalPendidikanPJ').textContent = data.pendidikan_pj || '-';
            document.getElementById('modalTelpPJ').textContent = data.telp_pj ? '+' + data.telp_pj : '-';
            document.getElementById('modalAlamatPJ').textContent = data.alamat_pj;
            document.getElementById('modalBayarSecara').textContent = data.bayar_secara;
            document.getElementById('modalKeluargaTerdekat').textContent = data.nama_alamat_keluarga_terdekat || '-';
            document.getElementById('modalHakKelas').textContent = data.hak_kelas;
            document.getElementById('modalKamarDiinginkan').textContent = data.ruang_diinginkan + ' (' + data.kelas + ')';
            document.getElementById('modalPetugas').textContent = data.petugas;

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

            // Bind action buttons in the detail modal dynamically
            document.getElementById('modalBtnPdf').onclick = function() {
                downloadPDF('/surat-persetujuan-rawat-inap/download/' + encodeURIComponent(data.no_surat));
            };
            document.getElementById('modalBtnWa').onclick = function(e) {
                sendWA(data.no_surat, this, e);
            };

            document.getElementById('detailModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close Detail Modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDetailModal();
        });

        // Close modal on click overlay
        document.getElementById('detailModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeDetailModal();
        });

        // Client-side quick filter
        function filterTable(query) {
            const rows = document.querySelectorAll('#historyTable tbody .table-row');
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

        // ─── Kirim WhatsApp with Template Prompt ───
        async function sendWA(noSurat, btn, event) {
            if (event) event.preventDefault();
            console.log('sendWA triggered for:', noSurat);
            
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span style="font-size:10px;">Loading...</span>';
            
            try {
                // 1. Fetch template
                const templateResponse = await fetch(`/surat-persetujuan-rawat-inap/wa-template/${encodeURIComponent(noSurat)}`);
                if (!templateResponse.ok) throw new Error('Gagal mengambil template');
                const { template } = await templateResponse.json();
                
                // 2. Prompt user
                const message = prompt('Edit pesan WhatsApp (klik OK untuk kirim, Cancel untuk batal):', template);
                if (message === null) return; // User cancelled
                
                btn.innerHTML = '<span style="font-size:10px;">Sending...</span>';
                
                // 3. Send POST
                const response = await fetch(`/surat-persetujuan-rawat-inap/send-wa`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ no_surat: noSurat, message })
                });
                
                const result = await response.json();
                if (response.ok && result.success) {
                    showWaSuccessModal();
                } else {
                    alert(result.error || 'Gagal mengirim WhatsApp');
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan: ' + err);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }

        // ─── Beautiful success notification modal handlers ───
        function showWaSuccessModal() {
            const modal = document.getElementById('wa-success-modal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeWaSuccessModal() {
            const modal = document.getElementById('wa-success-modal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    </script>

    <!-- Beautiful WhatsApp Success Notification Modal -->
    <div id="wa-success-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 20px; transition: all 0.3s ease;">
        <div style="background: white; border-radius: 24px; padding: 32px; width: 100%; max-width: 440px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div style="width: 72px; height: 72px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 2px solid rgba(16, 185, 129, 0.2);">
                <svg style="width: 36px; height: 36px; color: #10B981;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin-bottom: 10px;">Antrean Terkirim!</h3>
            <p style="font-size: 14px; color: #64748B; line-height: 1.6; margin-bottom: 24px;">Pesan WhatsApp beserta lampiran dokumen digital telah berhasil masuk ke antrean untuk dikirimkan ke nomor WhatsApp pasien.</p>
            <button onclick="closeWaSuccessModal()" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); outline: none;">
                Selesai
            </button>
        </div>
    </div>

    <style>
        @keyframes scaleUp {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</body>
</html>
