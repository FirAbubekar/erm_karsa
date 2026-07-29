<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Riwayat Edukasi Pasien</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

        .nav-item svg {
            width: 20px;
            height: 20px;
        }

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
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
            font-weight: 400;
        }

        .hero-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
        }

        .hero-breadcrumb a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: color 0.2s;
        }

        .hero-breadcrumb a:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-breadcrumb .current {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px 8px 8px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            color: rgba(255, 255, 255, 0.85);
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 10px 15px -3px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
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
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .filter-form {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .filter-form {
                grid-template-columns: 1fr;
            }
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03), 0 4px 12px rgba(0, 0, 0, 0.03);
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

        .hub-badge.suami {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .hub-badge.istri {
            background: #FCE7F3;
            color: #9D174D;
        }

        .hub-badge.default {
            background: #F1F5F9;
            color: #475569;
        }

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
            letter-spacing: -0.2px;
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

        .pagination-wrapper nav>div:first-child {
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

        .pagination-wrapper nav svg {
            width: 16px;
            height: 16px;
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
            from {
                transform: translateY(30px) scale(0.97);
                opacity: 0;
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
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
            color: rgba(255, 255, 255, 0.5);
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.7);
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
            background: rgba(255, 255, 255, 0.2);
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
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease-out both;
        }

        .animate-delay-1 {
            animation-delay: 0.05s;
        }

        .animate-delay-2 {
            animation-delay: 0.1s;
        }

        .animate-delay-3 {
            animation-delay: 0.15s;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .mobile-header {
                display: flex;
            }

            .page-hero {
                padding: 80px 20px 50px;
            }

            .stats-row {
                grid-template-columns: 1fr;
                margin: -28px 20px 0;
            }

            .content-area {
                padding: 24px 20px;
            }

            .toolbar {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .search-box input {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }
        }

        /* ─── Select2 Custom Styling ─── */
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background-color: #F8FAFC;
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155;
            padding-left: 36px; /* Space for the icon */
            padding-right: 12px;
            line-height: normal;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 4px;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--primary);
            background-color: #fff;
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .select2-dropdown {
            border-color: #E2E8F0;
            border-radius: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-size: 13px;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 13px;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary);
            color: white;
        }
        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            color: #334155;
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
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
                <span class="current">Riwayat Edukasi Pasien</span>
            </div>
            <div class="hero-top">
                <div class="hero-title">
                    <h1>📋 Riwayat Edukasi Pasien</h1>
                    <p>Kelola data asesmen umum pasien yang telah tersimpan.</p>
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ number_format($stats['total']) }}</span>
                    <span class="stat-label">Total Asesmen</span>
                </div>
            </div>
            <div class="stat-card animate-in animate-delay-2">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ number_format($stats['today']) }}</span>
                    <span class="stat-label">Hari Ini</span>
                </div>
            </div>
            <div class="stat-card animate-in animate-delay-3">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ number_format($stats['month']) }}</span>
                    <span class="stat-label">Bulan Ini</span>
                </div>
            </div>
        </div>

        <!-- ─── Content Area ─── -->
        <div class="content-area">
            @if (session('error'))
                <div class="animate-in"
                    style="background: #FFF1F2; color: #E11D48; padding: 14px 20px; border-radius: 16px; margin-bottom: 24px; border: 1px solid #FFE4E6; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.05);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Pencarian Lanjutan -->
            <div class="filter-card animate-in">
                <div class="filter-header">
                    <div class="filter-header-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div class="filter-header-text">
                        <div class="filter-title">
                            Pencarian Lanjutan
                            @php
                                $isDefaultToday =
                                    !request('search') &&
                                    !request('no_rawat') &&
                                    !request('person') &&
                                    !request('bangsal') &&
                                    request('start_date') === now()->toDateString() &&
                                    request('end_date') === now()->toDateString();
                                $activeFilters = count(
                                    array_filter(
                                        request()->only(['search', 'no_rawat', 'person', 'bangsal', 'start_date', 'end_date']),
                                    ),
                                );
                            @endphp
                            @if ($isDefaultToday)
                                <span class="filter-badge today">Default: Hari Ini</span>
                            @elseif($activeFilters > 0)
                                <span class="filter-badge active">{{ $activeFilters }} Filter Aktif</span>
                            @endif
                        </div>
                        <div style="font-size: 12px; color: #94A3B8; margin-top: 1px;">
                            @if ($isDefaultToday)
                                Menampilkan data hari ini. Isi form di bawah untuk mencari data lainnya.
                            @else
                                Menampilkan hasil pencarian dengan filter yang diterapkan.
                            @endif
                        </div>
                    </div>
                </div>

                <form id="filterForm" action="{{ route('edukasi-pasien.history') }}" method="GET">
                    <div class="filter-body">
                        <div class="filter-form">
                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Identitas Pasien
                                </label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="filter-input" placeholder="Nama, No.RM, atau No.Surat">
                                </div>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Registrasi (No. Rawat)
                                </label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.53 0 1.04.21 1.41.59L17 7l-3.59 3.59A2 2 0 0112 11H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    </svg>
                                    <input type="text" name="no_rawat" value="{{ request('no_rawat') }}"
                                        class="filter-input" placeholder="Format: YYYY/MM/DD/xxxxxx">
                                </div>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Penanggung Jawab / Staf
                                </label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <input type="text" name="person" value="{{ request('person') }}"
                                        class="filter-input" placeholder="Cari nama PJ atau Petugas">
                                </div>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Kamar / Bangsal
                                </label>
                                <div class="filter-input-wrapper">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; color: #94A3B8; z-index: 1;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <select name="bangsal" class="filter-input select2-bangsal" style="width: 100%;">
                                        <option value="">Semua Kamar / Bangsal</option>
                                        <option value="IGD" {{ request('bangsal') === 'IGD' ? 'selected' : '' }}>IGD (Instalasi Gawat Darurat)</option>
                                        @foreach($bangsals as $b)
                                            <option value="{{ $b->kd_bangsal }}" {{ request('bangsal') == $b->kd_bangsal ? 'selected' : '' }}>
                                                {{ $b->nm_bangsal }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:14px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                    </svg>
                                    Periode Pemeriksaan
                                </label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <div class="filter-input-wrapper" style="flex: 1;">
                                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                                            class="filter-input" style="padding-left: 14px;">
                                    </div>
                                    <span style="color: #94A3B8; font-size: 11px; font-weight: 700;">s/d</span>
                                    <div class="filter-input-wrapper" style="flex: 1;">
                                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                                            class="filter-input" style="padding-left: 14px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="filter-actions">
                        @if ($activeFilters > 0)
                            <a href="{{ route('edukasi-pasien.history') }}" class="btn-filter-reset">
                                <svg width="18" height="18" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset Pencarian
                            </a>
                        @endif
                        <button type="submit" class="btn-filter-submit">
                            <svg width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>


            <!-- Tabel Data -->
            <div class="table-card animate-in">
                <!-- Table Header Bar -->
                <div class="table-top-bar">
                    <div class="table-top-bar-left">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h2>Daftar Dokumen @if ($isDefaultToday)
                                (Hari Ini, {{ now()->format('d M Y') }})
                            @endif
                        </h2>
                        <span class="result-count-badge">{{ $assessments->total() }} dokumen</span>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table" id="consentTable">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Informasi Pasien</th>
                                <th>Tgl. Periksa</th>
                                <th>Penanggung Jawab</th>
                                <th>Hubungan</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assessments as $assessment)
                                <tr class="table-row">
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            <div class="surat-badge" title="{{ $assessment->no_rawat }}">
                                                <svg width="12" height="12" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ $assessment->no_rawat }}
                                            </div>
                                            @if ($assessment->regPeriksa?->signaturePasien)
                                                <span class="badge badge-success"
                                                    style="width: fit-content; font-size: 9px; padding: 2px 6px;">
                                                    <svg width="8" height="8" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                                                    </svg>
                                                    Tanda Tangan Lengkap
                                                </span>
                                            @else
                                                <span class="badge badge-secondary"
                                                    style="width: fit-content; font-size: 9px; padding: 2px 6px;">
                                                    Belum TTD
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="patient-info">
                                            <div class="patient-avatar">
                                                {{ strtoupper(substr($assessment->regPeriksa->pasien->nm_pasien ?? '?', 0, 1)) }}
                                            </div>
                                            <div class="patient-details">
                                                <span
                                                    class="patient-name">{{ $assessment->regPeriksa->pasien->nm_pasien ?? 'Unknown' }}</span>
                                                <span
                                                    class="patient-rm">{{ $assessment->regPeriksa->no_rkm_medis ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <span
                                                class="date-value">{{ \Carbon\Carbon::parse($assessment->tanggal_edukasi)->format('d M Y') }}</span>
                                            <span
                                                class="date-relative">{{ \Carbon\Carbon::parse($assessment->tanggal_edukasi)->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            style="font-weight: 600; color: var(--text-secondary);">{{ $assessment->nama_penerima_info }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $hub = strtolower($assessment->hubungan_dgn_pasien);
                                            $hubClass = 'default';
                                            if (str_contains($hub, 'suami')) {
                                                $hubClass = 'suami';
                                            } elseif (str_contains($hub, 'istri')) {
                                                $hubClass = 'istri';
                                            }
                                        @endphp
                                        <span
                                            class="hub-badge {{ $hubClass }}">{{ $assessment->hubungan_dgn_pasien }}</span>
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <button class="btn-action btn-detail"
                                                onclick="openDetailModal({{ json_encode([
                                                    'no_rawat' => $assessment->no_rawat,
                                                    'nm_pasien' => $assessment->regPeriksa->pasien->nm_pasien ?? 'Unknown',
                                                    'no_rm' => $assessment->regPeriksa->no_rkm_medis ?? '-',
                                                    'tgl_periksa' => $assessment->tanggal_edukasi,
                                                    'nama_pj' => $assessment->nama_penerima_info,
                                                    'hubungan' => $assessment->hubungan_dgn_pasien,
                                                    'signature' => $assessment->ttd_pasien_wali ? route('edukasi-pasien.signature', ['filename' => $assessment->ttd_pasien_wali]) : null,
                                                ]) }})">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Detail
                                            </button>
                                            <button class="btn-action btn-pdf"
                                                onclick="downloadPDF('{{ route('edukasi-pasien.download-pdf', $assessment->id_uuid) }}')">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                PDF
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            @if ($isDefaultToday)
                                                <h3>Belum Ada Dokumen Hari Ini</h3>
                                                <p>Tidak ada edukasi pasien yang dibuat pada
                                                    {{ now()->format('d M Y') }}. Gunakan Pencarian Lanjutan di atas
                                                    untuk mencari data dari tanggal lain.</p>
                                                <a href="{{ route('edukasi-pasien.history') }}?start_date=&end_date="
                                                    class="empty-action">
                                                    <svg width="14" height="14" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    Lihat Semua Riwayat
                                                </a>
                                            @else
                                                <h3>Tidak Ada Hasil Ditemukan</h3>
                                                <p>Tidak ada dokumen yang cocok dengan filter pencarian Anda. Coba ubah
                                                    atau reset filter di atas.</p>
                                                <a href="{{ route('edukasi-pasien.history') }}" class="empty-action">
                                                    <svg width="14" height="14" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
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
            <div class="pagination-wrapper">
                {{ $assessments->links() }}
            </div>
        </div>
    </div>

    <!-- ─── Detail Modal ─── -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-panel">
            <div class="modal-top">
                <div>
                    <h3>Detail Edukasi Pasien</h3>
                    <p>Informasi lengkap asesmen pasien</p>
                </div>
                <button class="modal-close" onclick="closeDetailModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
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



                <!-- Signature -->
                <div class="modal-section-title" style="margin-top:28px;">Tanda Tangan Digital</div>
                <div class="sig-box">
                    <img id="modalSignature" src="" alt="Tanda Tangan" style="display:none;">
                    <div id="modalNoSignature" class="sig-placeholder">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span>Tanda tangan tidak tersedia</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    @include('edukasi_pasien.modals')

    <script>
        // ─── Filter Form Validation ───
        function showError(msg) {
            document.getElementById('error-modal-message').innerHTML = msg;
            document.getElementById('error-modal').style.display = 'flex';
        }

        function hideError() {
            document.getElementById('error-modal').style.display = 'none';
        }

        document.getElementById('close-error-modal').addEventListener('click', hideError);
        document.getElementById('btn-close-error').addEventListener('click', hideError);

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            const search = this.search.value.trim();
            const noRawat = this.no_rawat.value.trim();
            const person = this.person.value.trim();
            const bangsal = this.bangsal ? this.bangsal.value.trim() : '';
            const startDate = this.start_date.value.trim();
            const endDate = this.end_date.value.trim();

            if (!search && !noRawat && !person && !bangsal && !startDate && !endDate) {
                e.preventDefault();
                showError('Silakan isi minimal salah satu filter (Identitas, No. Rawat, Petugas, Bangsal, atau Periode) sebelum menekan tombol Terapkan Filter.');
            }
        });

        // ─── Detail Modal ───
        function openDetailModal(data) {
            document.getElementById('modalNoSurat').textContent = data.no_rawat;
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
            // Buka tab baru SECARA LANGSUNG saat di-klik agar tidak diblokir oleh browser (Popup Blocker)
            const pdfWindow = window.open('about:blank', '_blank');

            // Helper fungsion untuk isi tab
            const proceedDownload = (lat, lng) => {
                const query = (lat && lng) ? '?lat=' + lat + '&lng=' + lng : '';
                pdfWindow.location.href = baseUrl + query;
            };

            // Helper fetch dengan timeout 1.5 detik agar tab tidak blank terlalu lama jika internet lambat/ip-api diblokir
            const fetchIp = () => {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 1000);

                fetch('http://ip-api.com/json/', {
                        signal: controller.signal
                    })
                    .then(res => res.json())
                    .then(data => {
                        clearTimeout(timeoutId);
                        proceedDownload(data.lat, data.lon);
                    })
                    .catch(() => {
                        clearTimeout(timeoutId);
                        proceedDownload('-', '-');
                    });
            };

            // Jika di HTTP (bukan localhost), browser block HTML5 Geolocation. Kita pakai IP-API JS fallback.
            if (navigator.geolocation && (window.location.protocol === 'https:' || window.location.hostname ===
                    'localhost' || window.location.hostname === '127.0.0.1')) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        proceedDownload(position.coords.latitude.toFixed(6), position.coords.longitude.toFixed(6));
                    },
                    function(error) {
                        // Fallback ke IP Geoloc jika ditolak
                        fetchIp();
                    }, {
                        timeout: 3000
                    } // kurangi timeout jadi 3 detik agar lebih responsif
                );
            } else {
                // Gunakan IP Geolocation langsung jika HTTP network (seperti 192.168.x.x)
                fetchIp();
            }
        }
    </script>

    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-bangsal').select2({
                placeholder: "Semua Kamar / Bangsal",
                allowClear: true,
                width: '100%'
            });
            
            // Fix search focus on Select2 dropdown
            $('.select2-bangsal').on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field').focus();
                }, 10);
            });
        });
    </script>
</body>

</html>
