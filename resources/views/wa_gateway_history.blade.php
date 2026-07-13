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

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #10B981;
            --primary-dark: #059669;
            --primary-light: rgba(16, 185, 129, 0.1);
            --primary-glow: rgba(16, 185, 129, 0.2);
            --bg-main: #F0FDF4;
            --card-bg: #FFFFFF;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --success: #10B981;
            --success-bg: #D1FAE5;
            --success-text: #065F46;
            --error: #EF4444;
            --error-bg: #FEE2E2;
            --error-text: #991B1B;
            --warning-bg: #FEF3C7;
            --warning-text: #92400E;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #F0FDF4 0%, #ECFDF5 50%, #F8FAFC 100%);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: rgba(255,255,255,0.95);
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

        .nav-item:hover { background: var(--bg-main); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }

        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 32px; }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .header-title h1 {
            font-size: 28px; font-weight: 800; letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--text-main), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-title p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }

        .user-profile {
            display: flex; align-items: center; gap: 12px;
            padding: 8px 16px; background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px); border: 1px solid var(--border);
            border-radius: 14px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .avatar {
            width: 32px; height: 32px; background: linear-gradient(135deg, #E2E8F0, #CBD5E1);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 12px; color: var(--text-muted);
        }

        .user-info span { font-size: 13px; font-weight: 600; display: block; }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            padding: 20px 24px;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .stat-card .stat-value { font-size: 28px; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 6px; font-weight: 500; }
        .stat-card.total .stat-value { color: var(--text-main); }
        .stat-card.pending .stat-value { color: #F59E0B; }
        .stat-card.processing .stat-value { color: #3B82F6; }
        .stat-card.sent .stat-value { color: var(--success); }
        .stat-card.failed .stat-value { color: var(--error); }

        .card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            padding: 20px;
        }

        .table-wrap { overflow-x: auto; }

        table { width: 100% !important; border-collapse: collapse; font-size: 13px; }

        thead { background: var(--bg-main); }

        th {
            padding: 14px 16px !important;
            text-align: left !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            color: var(--text-muted) !important;
            letter-spacing: 0.03em !important;
            white-space: nowrap !important;
            border-bottom: 1px solid var(--border) !important;
        }

        td {
            padding: 14px 16px !important;
            border-bottom: 1px solid var(--border) !important;
            vertical-align: middle !important;
        }

        tr:hover td { background: rgba(16, 185, 129, 0.02); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.pending { background: #FEF3C7; color: #92400E; }
        .status-badge.processing { background: #DBEAFE; color: #1E40AF; }
        .status-badge.sent { background: var(--success-bg); color: var(--success-text); }
        .status-badge.failed { background: var(--error-bg); color: var(--error-text); }

        .status-dot {
            width: 7px; height: 7px; border-radius: 50%; display: inline-block;
        }

        .status-dot.pending { background: #F59E0B; }
        .status-dot.processing { background: #3B82F6; animation: pulse 1.5s infinite; }
        .status-dot.sent { background: var(--success); }
        .status-dot.failed { background: var(--error); }

        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

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
        }

        .error-msg {
            color: var(--error);
            font-size: 11px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .text-mono { font-family: 'Courier New', monospace; font-size: 12px; }
        .text-muted { color: var(--text-muted); font-size: 12px; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            margin-top: auto;
        }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px; width: 100%;
            background: #FEF2F2; color: #EF4444;
            border: 1px solid #FEE2E2; border-radius: 12px;
            font-size: 14px; font-weight: 600; cursor: pointer;
            transition: all 0.2s ease; font-family: inherit;
        }

        .btn-logout:hover { background: #FEE2E2; border-color: #FECACA; transform: translateY(-1px); }
        .btn-logout svg { width: 18px; height: 18px; flex-shrink: 0; }

        div.dataTables_wrapper div.dataTables_filter input {
            padding: 8px 14px !important;
            border: 2px solid var(--border) !important;
            border-radius: 10px !important;
            font-size: 13px !important;
            font-family: inherit !important;
            outline: none !important;
            margin-left: 8px !important;
            width: 260px !important;
        }

        div.dataTables_wrapper div.dataTables_filter input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px var(--primary-glow) !important;
        }

        div.dataTables_wrapper div.dataTables_length select {
            padding: 6px 12px !important;
            border: 2px solid var(--border) !important;
            border-radius: 10px !important;
            font-size: 13px !important;
            font-family: inherit !important;
            outline: none !important;
            margin: 0 6px !important;
        }

        div.dataTables_wrapper div.dataTables_info {
            font-size: 13px !important;
            color: var(--text-muted) !important;
            padding: 16px 0 !important;
        }

        div.dataTables_wrapper div.dataTables_paginate {
            padding: 12px 0 !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button {
            padding: 8px 14px !important;
            border-radius: 10px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: all 0.2s !important;
            color: var(--text-main) !important;
            background: var(--bg-main) !important;
            border: 1px solid var(--border) !important;
            margin: 0 2px !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button:hover {
            background: var(--primary-light) !important;
            border-color: var(--primary) !important;
            color: var(--primary) !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button.current {
            background: var(--primary) !important;
            color: white !important;
            border: 1px solid var(--primary) !important;
        }

        div.dataTables_wrapper div.dataTables_paginate a.paginate_button.disabled {
            color: #CBD5E1 !important;
            cursor: not-allowed !important;
            background: transparent !important;
            border-color: transparent !important;
        }

        @media (max-width: 1024px) {
            :root { --sidebar-width: 280px; }
            .sidebar { transform: translateX(-100%); box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.1); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; }
            .stats-row { grid-template-columns: repeat(3, 1fr); }

            .mobile-header {
                display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px;
                background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--border); align-items: center;
                padding: 0 20px; z-index: 40; justify-content: space-between;
            }

            .hamburger {
                cursor: pointer; padding: 8px; border-radius: 8px;
                background: var(--bg-main); border: none; color: var(--text-main);
            }

            .sidebar-overlay {
                display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 45;
            }
            .sidebar-overlay.active { display: block; }
            header { flex-direction: column; align-items: flex-start; gap: 20px; }
            .user-profile { width: 100%; justify-content: center; }
        }

        @media (min-width: 1025px) { .mobile-header { display: none; } }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <header>
            <div class="header-title">
                <h1>History WA</h1>
                <p>Riwayat pengiriman pesan WhatsApp.</p>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                <div class="user-info"><span>{{ Session::get('user_id') }}</span></div>
            </div>
        </header>

        <div class="stats-row">
            <div class="stat-card total">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Pesan</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card processing">
                <div class="stat-value">{{ $stats['processing'] }}</div>
                <div class="stat-label">Processing</div>
            </div>
            <div class="stat-card sent">
                <div class="stat-value">{{ $stats['sent'] }}</div>
                <div class="stat-label">Terkirim</div>
            </div>
            <div class="stat-card failed">
                <div class="stat-value">{{ $stats['failed'] }}</div>
                <div class="stat-label">Gagal</div>
            </div>
        </div>

        <div class="card">
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#waHistoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("wa-gateway.history-data") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
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
                language: {
                    processing: 'Memuat data...',
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    loadingRecords: 'Memuat...',
                    zeroRecords: 'Tidak ada data yang cocok',
                    emptyTable: 'Tidak ada data',
                    paginate: {
                        first: 'Pertama',
                        previous: 'Sebelumnya',
                        next: 'Selanjutnya',
                        last: 'Terakhir'
                    }
                },
                responsive: true,
            });
        });
    </script>
</body>
</html>