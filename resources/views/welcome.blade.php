<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Login</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #10B981;
            --primary-hover: #059669;
            --bg-dark: #0B0F19;
            --card-bg: rgba(21, 27, 40, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-main: #FFFFFF;
            --text-muted: #9CA3AF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(6, 182, 212, 0.15) 0px, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            margin: 0 auto 16px auto;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .logo-icon svg {
            width: 24px;
            height: 24px;
        }

        .logo {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(to right, #10B981, #06B6D4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 4px;
            display: inline-block;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 8px;
            margin-left: 4px;
        }

        input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-login {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 10px;
        }

        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-links {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Micro-interactions */
        .form-group:focus-within label {
            color: var(--primary);
        }

        .glass-dot {
            position: absolute;
            width: 150px;
            height: 150px;
            background: var(--primary);
            filter: blur(80px);
            opacity: 0.2;
            z-index: -1;
            border-radius: 100%;
        }

        .dot-1 { top: 10%; left: 20%; }
        .dot-2 { bottom: 10%; right: 20%; background: #06B6D4; }
    </style>
</head>
<body>
    <div class="glass-dot dot-1"></div>
    <div class="glass-dot dot-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="header">
                <div class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="logo">KARSA ERM</div>
                <div class="subtitle">Electronic Records Management System</div>
            </div>

            @if ($errors->has('login_error'))
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #FCA5A5; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; text-align: center;">
                    {{ $errors->first('login_error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Username atau Email</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username anda" value="{{ old('username') }}" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="off" required>
                </div>

                <button type="submit" class="btn-login">Masuk ke Sistem</button>
            </form>

            <div class="footer-links">
                <p>Belum punya akun? <a href="#">Hubungi Admin</a></p>
            </div>
        </div>
    </div>
</body>
</html>
