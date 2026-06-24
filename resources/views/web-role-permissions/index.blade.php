<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Role Permissions</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); display: flex; min-height: 100vh; }

        /* ─── Sidebar Styles ─── */
        .sidebar { width: var(--sidebar-width); background: white; border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 50; transition: transform 0.3s ease; }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; }
        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
        .nav-item:hover { background: var(--bg-main); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-item svg { width: 20px; height: 20px; }
        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); margin-top: auto; }
        .btn-logout { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; width: 100%; background: #FEF2F2; color: #EF4444; border: 1px solid #FEE2E2; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }

        /* ─── Main Content ─── */
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; max-width: calc(100vw - var(--sidebar-width)); }

        /* ─── Hero Header ─── */
        .page-hero { background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%); padding: 32px 40px 52px; position: relative; overflow: hidden; }
        .page-hero::before { content: ''; position: absolute; top: -50%; right: -20%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .page-hero::after { content: ''; position: absolute; bottom: -60%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .hero-top { display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 1; }
        .hero-title h1 { font-size: 26px; font-weight: 800; color: white; letter-spacing: -0.75px; margin-bottom: 6px; }
        .hero-title p { color: rgba(255,255,255,0.5); font-size: 14px; }
        .hero-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 12px; color: rgba(255,255,255,0.4); position: relative; z-index: 1; }
        .hero-breadcrumb a { color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
        .hero-breadcrumb a:hover { color: rgba(255,255,255,0.8); }
        .hero-breadcrumb .current { color: rgba(255,255,255,0.7); font-weight: 600; }
        .user-chip { display: flex; align-items: center; gap: 10px; padding: 8px 16px 8px 8px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); border-radius: 50px; backdrop-filter: blur(10px); }
        .user-chip .avatar { width: 32px; height: 32px; background: linear-gradient(135deg, var(--accent), var(--primary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; color: white; }
        .user-chip span { color: rgba(255,255,255,0.85); font-size: 13px; font-weight: 600; }

        /* ─── Content Area ─── */
        .content-area { padding: 0 40px 40px; margin-top: -28px; position: relative; z-index: 2; }

        /* ─── Glass Card ─── */
        .glass-card { background: white; padding: 28px; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 10px 15px -3px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        
        .btn-primary { background: var(--primary); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { background: white; color: var(--text-main); border: 1px solid var(--border); padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; display: inline-flex; cursor: pointer; transition: all 0.2s; }
        .btn-secondary:hover { background: var(--bg-main); }
        
        .alert-success { background-color: #D1FAE5; color: #065F46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 12px; }
        .alert-error { background-color: #FEE2E2; color: #991B1B; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 500; }

        /* Form Group & Select2 */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; }
        
        .select2-container--default .select2-selection--single { height: 44px; border: 1px solid var(--border); border-radius: 10px; background: var(--bg-main); display: flex; align-items: center; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { color: var(--text-main); line-height: normal; font-size: 14px; padding-left: 16px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px; }
        .select2-container--open .select2-selection--single { border-color: var(--primary) !important; box-shadow: 0 0 0 3px var(--primary-light); background: white !important; }
        
        /* Permissions Grid */
        .permission-groups {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .perm-group-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }
        
        .perm-group-header {
            background: var(--bg-main);
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .perm-group-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .perm-list {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .perm-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .perm-item input[type="checkbox"] {
            margin-top: 3px;
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }
        
        .perm-item label {
            cursor: pointer;
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
            line-height: 1.4;
        }
        
        .perm-slug {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
            font-family: monospace;
            margin-top: 2px;
        }

        /* Responsiveness & Layout Fixes */
        @media (max-width: 1024px) {
            :root { --sidebar-width: 280px; }
            .sidebar { transform: translateX(-100%); box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.1); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; max-width: 100vw; }
            .page-hero { padding: 100px 20px 40px; }
            .content-area { padding: 0 20px 20px; }
            .mobile-header { display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: white; border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
            .hamburger { cursor: pointer; padding: 8px; border-radius: 8px; background: var(--bg-main); border: none; color: var(--text-main); }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 45; }
            .sidebar-overlay.active { display: block; }
        }

        @media (min-width: 1025px) {
            .mobile-header { display: none; }
            .sidebar-overlay { display: none; }
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
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="current">Role Permissions</span>
                    </div>
                    <div class="hero-title">
                        <h1>🛡️ Role Permissions</h1>
                        <p>Atur hak akses atau permission untuk setiap role / jabatan.</p>
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
            @if(session('success'))
                <div class="alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                <form action="{{ route('web-role-permissions.index') }}" method="GET" id="roleSelectForm">
                    <div class="form-group">
                        <label>Pilih Role / Jabatan</label>
                        <select name="role_id" id="role_id" class="form-control" style="width: 100%;" onchange="document.getElementById('roleSelectForm').submit();">
                            <option value="">-- Pilih Role --</option>
                            @foreach($jabatans as $jbtn)
                                <option value="{{ $jbtn->kd_jbtn }}" {{ $selectedRole == $jbtn->kd_jbtn ? 'selected' : '' }}>
                                    {{ $jbtn->nm_jbtn }} ({{ $jbtn->kd_jbtn }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            @if($selectedRole)
            <div class="glass-card" style="padding: 0; overflow: hidden;">
                <form action="{{ route('web-role-permissions.sync') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $selectedRole }}">
                    
                    <div class="card-header" style="padding: 24px; margin-bottom: 0; border-bottom: 1px solid var(--border); background: #FAFAFA;">
                        <div>
                            <h2 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Daftar Permission</h2>
                            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Centang hak akses yang diizinkan untuk role ini.</p>
                        </div>
                        <button type="submit" class="btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                    
                    <div style="padding: 24px;">
                        <div class="permission-groups">
                            @forelse($permissionsByGroup as $group => $permissions)
                                <div class="perm-group-card">
                                    <div class="perm-group-header">
                                        <div class="perm-group-title">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            {{ $group ?: 'Lainnya' }}
                                        </div>
                                        <div>
                                            <button type="button" class="btn-secondary toggle-group" data-group="{{ Str::slug($group) }}" style="padding: 4px 8px; font-size: 11px;">Select All</button>
                                        </div>
                                    </div>
                                    <div class="perm-list" id="group-{{ Str::slug($group) }}">
                                        @foreach($permissions as $perm)
                                            <div class="perm-item">
                                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm-{{ $perm->id }}" {{ in_array($perm->id, $activePermissions) ? 'checked' : '' }}>
                                                <label for="perm-{{ $perm->id }}">
                                                    {{ $perm->name }}
                                                    <span class="perm-slug">{{ $perm->slug }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px 0;">
                                    Belum ada data Master Permission di database.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </form>
            </div>
            @else
            <div class="glass-card" style="text-align: center; padding: 60px 20px;">
                <svg fill="none" stroke="#CBD5E1" viewBox="0 0 24 24" width="64" height="64" style="margin: 0 auto 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <h3 style="font-size: 18px; color: var(--text-secondary); margin-bottom: 8px;">Pilih Role Terlebih Dahulu</h3>
                <p style="color: var(--text-muted); font-size: 14px;">Silakan pilih Role / Jabatan pada dropdown di atas untuk melihat dan mengatur hak aksesnya.</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#role_id').select2({
                placeholder: '-- Pilih Role / Jabatan --'
            });

            // Handle Select All / Deselect All
            $('.toggle-group').click(function() {
                var groupSlug = $(this).data('group');
                var container = $('#group-' + groupSlug);
                var checkboxes = container.find('input[type="checkbox"]');
                
                // Check if all are currently checked
                var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
                
                if (allChecked) {
                    checkboxes.prop('checked', false);
                    $(this).text('Select All');
                } else {
                    checkboxes.prop('checked', true);
                    $(this).text('Deselect All');
                }
            });
            
            // Initial label setup for toggle buttons
            $('.toggle-group').each(function() {
                var groupSlug = $(this).data('group');
                var container = $('#group-' + groupSlug);
                var checkboxes = container.find('input[type="checkbox"]');
                var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
                
                if (allChecked && checkboxes.length > 0) {
                    $(this).text('Deselect All');
                }
            });
        });
    </script>
</body>
</html>
