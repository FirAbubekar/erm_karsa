<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Laporan RM 10</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #10B981;
            --accent: #6366F1;
            --bg-main: #F1F5F9;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; width: calc(100% - var(--sidebar-width)); min-height: 100vh; }

        .page-hero {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            padding: 40px;
            color: white;
        }

        .page-hero h1 { font-size: 28px; font-weight: 800; margin-bottom: 6px; }
        .page-hero p { color: rgba(255,255,255,0.5); font-size: 14px; }

        .content-area { padding: 40px; }

        .card {
            background: white;
            padding: 48px;
            border-radius: 20px;
            border: 1px solid var(--border);
            text-align: center;
        }

        .card svg { width: 64px; height: 64px; color: var(--accent); margin-bottom: 16px; }
        .card h3 { font-size: 20px; font-weight: 700; margin-bottom: 8px; }
        .card p { color: var(--text-muted); font-size: 14px; }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-hero">
            <h1>Laporan RM 10</h1>
            <p>Menu Laporan RM 10 (Edukasi Pasien)</p>
        </div>

        <div class="content-area">
            <div class="card">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3>Modul Laporan RM 10 Edukasi Pasien</h3>
                <p>Fitur laporan dan grafik untuk RM 10 Edukasi Pasien akan disajikan di halaman ini.</p>
            </div>
        </div>
    </div>
</body>
</html>
