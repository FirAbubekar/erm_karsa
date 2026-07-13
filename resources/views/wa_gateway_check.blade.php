<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Cek Koneksi WA Gateway</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #F0FDF4 0%, #ECFDF5 50%, #F8FAFC 100%);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
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
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 16px;
            box-shadow: 0 4px 8px var(--primary-glow);
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
            font-weight: 600;
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 32px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--text-main), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-title p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #E2E8F0, #CBD5E1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            color: var(--text-muted);
        }

        .user-info span {
            font-size: 13px;
            font-weight: 600;
            display: block;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            padding: 32px;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 20px 40px -5px rgba(0, 0, 0, 0.03);
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 24px;
            color: var(--text-main);
        }

        .card-title svg {
            width: 22px;
            height: 22px;
            color: var(--primary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            animation: fadeIn 0.5s ease;
        }

        .status-badge.connected {
            background: var(--success-bg);
            color: var(--success-text);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.15);
        }

        .status-badge.error {
            background: var(--error-bg);
            color: var(--error-text);
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.15);
        }

        .status-badge.unknown {
            background: var(--warning-bg);
            color: var(--warning-text);
        }

        .status-icon {
            width: 20px;
            height: 20px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-dot.connected {
            background: var(--success);
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
            animation: pulse 2s infinite;
        }

        .status-dot.error {
            background: var(--error);
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.5);
        }

        .status-dot.unknown {
            background: #F59E0B;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .info-grid {
            display: grid;
            gap: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            background: var(--bg-main);
            border-radius: 14px;
            font-size: 13px;
            transition: all 0.2s;
        }

        .info-row:hover {
            background: #E8F5E9;
        }

        .info-label {
            color: var(--text-muted);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label svg {
            width: 16px;
            height: 16px;
            color: var(--primary);
        }

        .info-value {
            font-weight: 600;
            word-break: break-all;
            text-align: right;
            max-width: 60%;
            font-size: 13px;
        }

        .info-value.mono {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: #E2E8F0;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .btn-center {
            text-align: center;
            margin-top: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
            background: var(--bg-main);
            outline: none;
            color: var(--text-main);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            background: white;
        }

        .form-input::placeholder {
            color: #94A3B8;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 100px;
        }

        .form-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .test-result {
            margin-top: 20px;
            padding: 16px 20px;
            border-radius: 14px;
            font-size: 13px;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .test-result.show {
            display: block;
        }

        .test-result.success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid #A7F3D0;
        }

        .test-result.error {
            background: var(--error-bg);
            color: var(--error-text);
            border: 1px solid #FECACA;
        }

        .test-result .result-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .test-result .result-body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-word;
            line-height: 1.5;
        }

        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
            font-family: inherit;
        }

        .btn-logout:hover {
            background: #FEE2E2;
            border-color: #FECACA;
            transform: translateY(-1px);
        }

        .btn-logout svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 24px 0;
        }

        @media (max-width: 1024px) {
            :root { --sidebar-width: 280px; }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.1);
            }

            .sidebar.active { transform: translateX(0); }

            .main-content {
                margin-left: 0;
                padding: 20px;
                padding-top: 80px;
            }

            .grid-2 { grid-template-columns: 1fr; }

            .mobile-header {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 64px;
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(10px);
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
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 45;
            }

            .sidebar-overlay.active { display: block; }

            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .user-profile { width: 100%; justify-content: center; }
        }

        @media (min-width: 1025px) {
            .mobile-header { display: none; }
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <header>
            <div class="header-title">
                <h1>WhatsApp Gateway</h1>
                <p>Diagnostik koneksi dan pengujian pengiriman pesan.</p>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                <div class="user-info"><span>{{ Session::get('user_id') }}</span></div>
            </div>
        </header>

        <div class="grid-2">
            {{-- STATUS KONEKSI --}}
            <div class="card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Status Koneksi
                </div>

                @php $status = $result['status'] ?? 'unknown'; @endphp

                <div class="status-badge {{ $status }}">
                    <span class="status-dot {{ $status }}"></span>
                    @if($status === 'connected')
                        Terhubung
                    @elseif($status === 'error')
                        Gagal Terhubung
                    @else
                        Tidak Diketahui
                    @endif
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                            URL Gateway
                        </span>
                        <span class="info-value mono">{{ $result['url'] ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            Device ID
                        </span>
                        <span class="info-value mono" style="font-size:11px">{{ $result['device_id'] ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Response
                        </span>
                        <span class="info-value">{{ $result['message'] ?? '-' }}</span>
                    </div>
                    @if(isset($result['response_time']))
                    <div class="info-row">
                        <span class="info-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Response Time
                        </span>
                        <span class="info-value">{{ $result['response_time'] }}ms</span>
                    </div>
                    @endif
                </div>

                <div class="btn-center">
                    <a href="{{ route('wa-gateway.check') }}" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Cek Ulang Koneksi
                    </a>
                </div>
            </div>

            {{-- TEST KIRIM PESAN --}}
            <div class="card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Test Kirim Pesan
                </div>

                <form id="testWaForm">
                    <div class="form-group">
                        <label class="form-label" for="phone">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" class="form-input" placeholder="Contoh: 081234567890" required>
                        <div class="form-hint">Masukkan nomor tujuan dengan atau tanpa kode negara.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="message">Pesan</label>
                        <textarea id="message" name="message" class="form-input" placeholder="Tulis pesan yang akan dikirim..." required>Test pesan dari Karsa ERM - WhatsApp Gateway.</textarea>
                    </div>

                    <button type="submit" id="sendBtn" class="btn btn-primary" style="width:100%">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Pesan Test
                    </button>
                </form>

                <div class="test-result" id="testResult">
                    <div class="result-title" id="resultTitle"></div>
                    <div class="result-body" id="resultBody"></div>
                </div>

                <div class="divider"></div>

                <div style="display:flex;gap:12px;flex-wrap:wrap">
                    <button type="button" class="btn btn-outline" style="flex:1" onclick="setMessage('Test pesan dari Karsa ERM - WhatsApp Gateway.')">
                        Pesan Default
                    </button>
                    <button type="button" class="btn btn-outline" style="flex:1" onclick="setMessage('Halo, ini adalah pesan test dari sistem Karsa ERM untuk memverifikasi fungsi WhatsApp Gateway.')">
                        Pesan Panjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setMessage(text) {
            document.getElementById('message').value = text;
        }

        document.getElementById('testWaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('sendBtn');
            const result = document.getElementById('testResult');
            const resultTitle = document.getElementById('resultTitle');
            const resultBody = document.getElementById('resultBody');

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Mengirim...';
            result.classList.remove('show', 'success', 'error');

            const formData = new FormData();
            formData.append('phone', document.getElementById('phone').value);
            formData.append('message', document.getElementById('message').value);

            try {
                const res = await fetch('{{ route("wa-gateway.test-send") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await res.json();

                result.classList.add('show');
                if (data.success) {
                    result.classList.add('success');
                    resultTitle.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Pesan berhasil dikirim!';
                    resultBody.textContent = data.message || 'Pesan berhasil dikirim ke nomor tujuan.';
                } else {
                    result.classList.add('error');
                    resultTitle.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Gagal mengirim pesan';
                    resultBody.textContent = data.message || 'Terjadi kesalahan saat mengirim pesan.';
                }
            } catch (err) {
                result.classList.add('show', 'error');
                resultTitle.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Error Koneksi';
                resultBody.textContent = 'Tidak dapat terhubung ke server. ' + err.message;
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg> Kirim Pesan Test';
            }
        });
    </script>
</body>
</html>