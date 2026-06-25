<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Tutorial Hasil Lab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #10B981;
            --primary-light: rgba(16, 185, 129, 0.1);
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; }

        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; max-width: calc(100vw - var(--sidebar-width)); min-height: 100vh; display: flex; flex-direction: column; }
        .page-hero { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 40px; color: white; }
        .hero-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 12px; color: rgba(255,255,255,0.5); }
        .hero-title h1 { font-size: 26px; font-weight: 800; }
        
        .content-area { padding: 40px; margin-top: -28px; z-index: 2; flex: 1; }
        .card { background: white; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); margin-bottom: 24px; padding: 32px; }
        .card h2 { font-size: 20px; font-weight: 700; margin-bottom: 16px; color: var(--text-main); }
        .card p { color: var(--text-muted); font-size: 14px; line-height: 1.6; margin-bottom: 20px; }
        
        .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 16px; background: #000; border: 1px solid var(--border); margin-bottom: 24px; }
        .video-placeholder { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: rgba(255,255,255,0.7); }
        .video-placeholder svg { width: 64px; height: 64px; fill: currentColor; margin-bottom: 12px; transition: transform 0.2s; cursor: pointer; }
        .video-placeholder svg:hover { transform: scale(1.1); color: var(--primary); }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}" style="color: inherit; text-decoration: none;">Dashboard</a>
                <span>/</span>
                <span>Tutorial</span>
                <span>/</span>
                <span style="color: var(--primary); font-weight: 600;">Hasil Lab</span>
            </div>
            <div class="hero-title">
                <h1>📖 Tutorial Hasil Lab (Laboratorium)</h1>
            </div>
        </div>

        <div class="content-area">
            <div class="card">
                <h2>Video Panduan Penggunaan</h2>
                <div class="video-container">
                    <div class="video-placeholder">
                        <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        <p style="color: white; font-weight: 600;">Klik untuk memutar video tutorial</p>
                    </div>
                </div>
                <h2>Petunjuk Penggunaan:</h2>
                <p>1. Masuk ke menu Laboratorium.<br>2. Masukkan kata kunci pencarian (Nama Pasien / PID / ONO).<br>3. Klik tombol **Lihat Detail** untuk melihat rincian hasil parameter lab.<br>4. Untuk membagikan dokumen: klik **Print Preview** (PDF) atau kirim file hasil tes ke WhatsApp pasien/petugas dengan memilih nomor tujuan dari dropdown Select2 yang tersedia.</p>
            </div>
        </div>
    </div>
</body>
</html>
