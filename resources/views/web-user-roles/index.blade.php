<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | User Roles</title>

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

        /* ─── Main Content ─── */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 0;
            max-width: calc(100vw - var(--sidebar-width));
        }

        /* ─── Hero Header ─── */
        .page-hero {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            padding: 32px 40px 52px;
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
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%);
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
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
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
            font-size: 26px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.75px;
            margin-bottom: 6px;
        }

        .hero-title p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
        }

        .hero-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            position: relative;
            z-index: 1;
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

        /* ─── Content Area ─── */
        .content-area {
            padding: 0 40px 40px;
            margin-top: -28px;
            position: relative;
            z-index: 2;
        }

        /* ─── Glass Card ─── */
        .glass-card {
            background: white;
            padding: 28px;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 10px 15px -3px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 12px;
            display: inline-flex;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 16px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
            font-size: 14px;
        }

        th {
            font-weight: 600;
            color: var(--text-muted);
            background: var(--bg-main);
        }

        td {
            color: var(--text-main);
        }

        .alert-success {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-error {
            background-color: #FEE2E2;
            color: #991B1B;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            padding: 32px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
        }

        .modal-close {
            background: var(--bg-main);
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: #E2E8F0;
            color: var(--text-main);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.2s;
            background: var(--bg-main);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        /* Select2 override for form control */
        .select2-container--default .select2-selection--single {
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-main);
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-main);
            line-height: normal;
            font-size: 14px;
            padding-left: 16px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .select2-container--open .select2-selection--single {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-light);
            background: white !important;
        }

        .select2-dropdown {
            border-color: var(--border);
            border-radius: 10px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .select2-results__option {
            font-size: 14px;
            padding: 10px 16px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 32px;
        }

        .btn-secondary {
            background: white;
            color: var(--text-main);
            border: 1px solid var(--border);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: var(--bg-main);
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
        }

        .pagination-wrapper nav {
            display: flex;
            align-items: center;
            gap: 4px;
            width: 100%;
            justify-content: space-between;
        }

        .pagination-wrapper nav>div:first-child {
            display: none;
        }

        .pagination-wrapper nav>div:last-child {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-wrapper nav span[aria-current="page"] span,
        .pagination-wrapper nav a,
        .pagination-wrapper nav span[aria-disabled="true"] span {
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
            margin: 0 2px;
        }

        .pagination-wrapper nav span[aria-current="page"] span {
            background: var(--accent);
            color: white;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
            border: 1px solid var(--accent);
        }

        .pagination-wrapper nav a {
            background: white;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .pagination-wrapper nav span[aria-disabled="true"] span {
            background: var(--bg-main);
            color: var(--text-muted);
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

        /* Responsiveness & Layout Fixes */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 280px;
            }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.1);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                max-width: 100vw;
            }

            .page-hero {
                padding: 100px 20px 40px;
            }

            .content-area {
                padding: 0 20px 20px;
            }

            .mobile-header {
                display: flex;
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

            .hamburger {
                cursor: pointer;
                padding: 8px;
                border-radius: 8px;
                background: var(--bg-main);
                border: none;
                color: var(--text-main);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 45;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        @media (min-width: 1025px) {
            .mobile-header {
                display: none;
            }

            .sidebar-overlay {
                display: none;
            }
        }
    </style>
</head>

<body>
    @include('partials.sidebar')

    <div class="main-content">
        <!-- Hero Header -->
        <div class="page-hero">
            <div class="hero-top">
                <div>
                    <div class="hero-breadcrumb">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        <span class="current">User Roles</span>
                    </div>
                    <div class="hero-title">
                        <h1>👥 User Roles</h1>
                        <p>Atur pemetaan akses / jabatan untuk pegawai atau dokter.</p>
                    </div>
                </div>

                <div class="user-chip">
                    <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                    <span>{{ Session::get('user_id') }}</span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if (session('success'))
                <div class="alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <ul style="margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="glass-card">
                <div class="card-header">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 700;">Data User Roles</h2>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <form method="GET" action="{{ route('web-user-roles.index') }}"
                            style="display: flex; gap: 8px;">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama..."
                                style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; width: 220px;">
                            <button type="submit" class="btn-secondary"
                                style="padding: 8px 12px; display: flex; align-items: center; gap: 4px;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16"
                                    height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </button>
                            @if (request('search'))
                                <a href="{{ route('web-user-roles.index') }}"
                                    style="display: flex; align-items: center; padding: 8px 12px; background-color: var(--border); color: var(--text-secondary); border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500;">Clear</a>
                            @endif
                        </form>
                        <button onclick="openModal()" class="btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Set Role User
                        </button>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP/Kode Dokter</th>
                                <th>Nama Pegawai/Dokter</th>
                                <th>Role / Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($userRoles as $index => $ur)
                                <tr>
                                    <td>{{ $userRoles->firstItem() + $index }}</td>
                                    <td>
                                        <span
                                            style="background: var(--bg-main); padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 13px; border: 1px solid var(--border);">
                                            {{ $ur->nip }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 500;">{{ $ur->user_name }}</td>
                                    <td>
                                        <span
                                            style="background: var(--primary-light); color: var(--primary-dark); padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 600;">
                                            {{ $ur->jabatan ? $ur->jabatan->nm_jbtn : '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('web-user-roles.destroy', $ur->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus role user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Cabut Akses</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        style="text-align: center; color: var(--text-muted); padding: 32px 0;">Belum ada
                                        data mapping user role.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $userRoles->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="roleModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2>Set Role User</h2>
                <button onclick="closeModal()" class="modal-close" type="button">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('web-user-roles.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Pegawai / Dokter <span style="color: var(--danger);">*</span></label>
                    <!-- Select2 requires width: 100% explicitly in some cases -->
                    <select name="nip" id="nip" class="form-control" style="width: 100%;" required>
                        <!-- Options loaded via AJAX -->
                    </select>
                </div>

                <div class="form-group">
                    <label>Role / Jabatan <span style="color: var(--danger);">*</span></label>
                    <select name="role_id" id="role_id" class="form-control select2-static" style="width: 100%;"
                        required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($jabatans as $jbtn)
                            <option value="{{ $jbtn->kd_jbtn }}">{{ $jbtn->nm_jbtn }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeModal()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const modal = document.getElementById('roleModal');

        function openModal() {
            modal.classList.add('active');

            // Re-initialize select2 specifically when modal opens
            // if needed to avoid overlay issues, setting dropdownParent to modal
            $('#nip').select2({
                dropdownParent: $('#roleModal'),
                placeholder: 'Cari nama atau NIK pegawai/dokter...',
                allowClear: true,
                ajax: {
                    url: '{{ route('search-users') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $('#role_id').select2({
                dropdownParent: $('#roleModal'),
                placeholder: '-- Pilih Role --'
            });
        }

        function closeModal() {
            modal.classList.remove('active');
            // reset form if needed
            $('#nip').val(null).trigger('change');
            $('#role_id').val('').trigger('change');
        }

        // Close modal on outside click
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>
