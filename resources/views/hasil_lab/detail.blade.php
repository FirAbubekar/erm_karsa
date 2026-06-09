<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Detail Hasil Lab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; }

        .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; }
        .mobile-header, .sidebar-overlay { display: none; }

        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; max-width: calc(100vw - var(--sidebar-width)); min-height: 100vh; display: flex; flex-direction: column; }

        .page-hero {
            background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
            padding: 40px 40px 60px;
            position: relative;
            overflow: hidden;
            color: white;
        }
        .hero-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 12px; color: rgba(255,255,255,0.5); position: relative; z-index: 1; }
        .hero-breadcrumb a { color: rgba(255,255,255,0.5); text-decoration: none; }
        .hero-title h1 { font-size: 26px; font-weight: 800; letter-spacing: -0.75px; margin-bottom: 6px; position: relative; z-index: 1; }
        .hero-title p { color: rgba(255,255,255,0.6); font-size: 14px; position: relative; z-index: 1; }

        .content-area { padding: 0 40px 40px; margin-top: -28px; position: relative; z-index: 2; flex: 1; }

        .card { background: white; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); margin-bottom: 24px; overflow: hidden; }
        .card-header { display: flex; align-items: center; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); background: #FAFBFF; }
        .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .card-icon-blue { background: #EFF6FF; color: #3B82F6; }
        .card-icon-purple { background: var(--accent-light); color: var(--accent); }
        .card-icon-green { background: var(--primary-light); color: var(--primary); }
        .card-title { font-size: 15px; font-weight: 700; color: var(--text-main); }

        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); padding: 20px 24px; }
        .info-item { padding: 12px; }
        .info-item label { display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .info-value { font-size: 14px; font-weight: 600; color: var(--text-main); }
        .info-value-mono { font-family: monospace; font-size: 13px; font-weight: 600; }

        .table-wrap { overflow-x: auto; }
        .lab-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .lab-table th { background: #F8FAFC; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .lab-table td { padding: 14px 20px; border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        
        .val-high { color: #EF4444; font-weight: 700; }
        .val-low { color: #F59E0B; font-weight: 700; }
        .val-normal { color: var(--primary); font-weight: 700; }
        
        .badge-ket { display: inline-flex; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .badge-ket-H { background: #FEF2F2; color: #EF4444; }
        .badge-ket-L { background: #FFFBEB; color: #F59E0B; }
        .badge-ket-N { background: #F0FDF4; color: var(--primary); }

        .saran-section { padding: 20px 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .saran-box label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px; }
        .saran-text { background: #F8FAFC; border: 1.5px solid var(--border); border-radius: 12px; padding: 16px; font-size: 13px; color: var(--text-secondary); line-height: 1.6; min-height: 80px; white-space: pre-wrap; }

        .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: white; color: var(--text-secondary); border: 1.5px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 600; text-decoration: none; margin-bottom: 20px; transition: all 0.2s; }
        .btn-back:hover { border-color: var(--accent); color: var(--accent); }

        .badge { display: inline-flex; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 600; }
        .badge-ralan { background: #EFF6FF; color: #2563EB; }
        .badge-ranap { background: #FFF7ED; color: #EA580C; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; max-width: 100vw; }
        }
        @media (max-width: 768px) { .info-grid, .saran-section { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-hero">
            <div class="hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span style="opacity: 0.5;">/</span>
                <a href="{{ route('hasil-lab.index') }}">Hasil Lab</a>
                <span style="opacity: 0.5;">/</span>
                <span style="color: white; font-weight: 600;">Detail</span>
            </div>
            <div class="hero-title">
                <h1>🔬 Detail Pemeriksaan Lab</h1>
                <p>{{ $periksaLab->nm_pasien }} — {{ $periksaLab->nm_perawatan }}</p>
            </div>
        </div>

        <div class="content-area">
            <a href="{{ route('hasil-lab.index') }}" class="btn-back">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>

            <!-- Info Pasien -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon card-icon-blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="card-title">Informasi Pasien</div>
                </div>
                <div class="info-grid">
                    <div class="info-item"><label>Nama Pasien</label><div class="info-value">{{ $periksaLab->PNAME ?? '-' }}</div></div>
                    <div class="info-item"><label>No. Rekam Medis (PID)</label><div class="info-value-mono">{{ $periksaLab->PID ?? '-' }}</div></div>
                    <div class="info-item"><label>Jenis Kelamin</label><div class="info-value">{{ $periksaLab->SEX === 'L' ? 'Laki-laki' : 'Perempuan' }}</div></div>
                    <div class="info-item"><label>Order No (ONO)</label><div class="info-value-mono">{{ $periksaLab->ONO }}</div></div>
                    <div class="info-item"><label>Visit No</label><div class="info-value-mono">{{ $periksaLab->VISITNO ?? '-' }}</div></div>
                    <div class="info-item"><label>Alamat</label><div class="info-value" style="font-size: 12px; line-height: 1.4;">{{ $periksaLab->ADDRESS }}, {{ $periksaLab->KELURAHAN }}, {{ $periksaLab->KECAMATAN }}, {{ $periksaLab->KABUPATEN }}</div></div>
                </div>
            </div>

            <!-- Info Pemeriksaan -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon card-icon-purple">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="card-title">Informasi Pemeriksaan</div>
                </div>
                <div class="info-grid">
                    <div class="info-item"><label>Jenis Pemeriksaan</label><div class="info-value">{{ $periksaLab->nm_perawatan }}</div></div>
                    <div class="info-item"><label>Tanggal Order</label><div class="info-value">{{ \Carbon\Carbon::parse($periksaLab->REQUEST_DT)->format('d M Y') }}</div></div>
                    <div class="info-item"><label>Jam Order</label><div class="info-value">{{ \Carbon\Carbon::parse($periksaLab->REQUEST_DT)->format('H:i') }} WIB</div></div>
                    <div class="info-item"><label>Dokter Pengirim</label><div class="info-value" style="color: var(--text-secondary);">{{ $periksaLab->CLINICIAN_NM ?? '-' }}</div></div>
                    <div class="info-item"><label>Ruangan</label><div class="info-value">{{ $periksaLab->ROOM }}</div></div>
                </div>
            </div>

            <!-- Hasil Lab -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon card-icon-green">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div class="card-title">Parameter & Hasil Pemeriksaan</div>
                </div>
                <div class="table-wrap">
                    <table class="lab-table">
                        <thead>
                            <tr>
                                <th>Parameter Pemeriksaan</th>
                                <th>Hasil</th>
                                <th>Satuan</th>
                                <th>Nilai Rujukan</th>
                                <th>Keterangan (Flag)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailLab as $item)
                            @php
                                $k = strtoupper(trim($item->keterangan ?? ''));
                                $valClass = $k == 'H' ? 'val-high' : ($k == 'L' ? 'val-low' : ($k == 'N' ? 'val-normal' : ''));
                                $ketBadge = in_array($k, ['H','L','N']) ? 'badge-ket-'.$k : '';
                                $ketLabel = $k == 'H' ? 'Tinggi' : ($k == 'L' ? 'Rendah' : ($k == 'N' ? 'Normal' : $item->keterangan));
                            @endphp
                            <tr>
                                <td style="font-weight: 600;">{{ $item->Pemeriksaan }}</td>
                                <td><span class="{{ $valClass }}">{{ $item->nilai ?? '-' }}</span></td>
                                <td style="color: var(--text-muted);">{{ $item->satuan ?? '-' }}</td>
                                <td style="font-size: 12px; color: var(--text-secondary);">{{ $item->nilai_rujukan ?? '-' }}</td>
                                <td>
                                    @if($k)
                                        <span class="badge-ket {{ $ketBadge }}">{{ $k }}</span>
                                    @else
                                        <span style="color: var(--text-muted);">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Saran -->
            @if($saranKesan)
            <div class="card">
                <div class="card-header">
                    <div class="card-icon card-icon-blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div class="card-title">Saran & Kesan Analis/Dokter</div>
                </div>
                <div class="saran-section">
                    <div class="saran-box">
                        <label>Kesan</label>
                        <div class="saran-text">{{ $saranKesan->kesan ?? '-' }}</div>
                    </div>
                    <div class="saran-box">
                        <label>Saran</label>
                        <div class="saran-text">{{ $saranKesan->saran ?? '-' }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
