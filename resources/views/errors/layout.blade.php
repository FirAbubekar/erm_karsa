<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code ?? 'Error' }} - {{ $title ?? 'Terjadi Kesalahan' }} | Karsa ERM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body {
            background-color: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .error-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 24px;
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.06);
        }
        .error-code {
            font-size: 80px;
            font-weight: 800;
            line-height: 1;
            color: #0F172A;
            letter-spacing: -2px;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #1E293B 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .error-title {
            font-size: 20px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 10px;
        }
        .error-desc {
            color: #64748B;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #10B981;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-home:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F1F5F9;
            color: #334155;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-back:hover {
            background: #E2E8F0;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">{{ $code ?? '500' }}</div>
        <div class="error-title">{{ $title ?? 'Terjadi Kesalahan' }}</div>
        <div class="error-desc">{{ $desc ?? 'Terjadi kesalahan dalam memproses permintaan Anda.' }}</div>
        <div class="btn-group">
            <button onclick="window.history.back()" class="btn-back">
                &larr; Kembali
            </button>
            <a href="{{ url('/') }}" class="btn-home">
                Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>
