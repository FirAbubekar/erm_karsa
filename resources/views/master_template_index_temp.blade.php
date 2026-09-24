<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Master Permission</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

        .nav-item svg { width: 20px; height: 20px; }

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
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 0; max-width: calc(100vw - var(--sidebar-width)); }

        /* ─── Hero Header ─── */
        .page-hero {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            padding: 32px 40px 52px;
            position: relative;
            overflow: hidden;
        }
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
        .glass-card {
            background: white;
            padding: 28px;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 10px 15px -3px rgba(0,0,0,0.04);
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
        
        .btn-warning {
            background: var(--warning);
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

        th, td {
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
            top: 0; left: 0; right: 0; bottom: 0;
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
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
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
        
        /* ─── Pagination ─── */
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

        /* Sembunyikan view mobile bawaan jika tidak perlu */
        .pagination-wrapper nav > div:first-child {
            display: none;
        }

        .pagination-wrapper nav > div:last-child {
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

        /* ─── Responsiveness & Layout Fixes ─── */
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
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="current">Master Permission</span>
                    </div>
                    <div class="hero-title">
                        <h1>🔐 Master Permission</h1>
                        <p>Kelola data permission untuk setiap role dan pengguna dalam sistem.</p>
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
                <div class="card-header">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 700;">Data Permission</h2>
                    </div>
                    <button onclick="openModal('create')" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Permission
                    </button>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Group</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $index => $permission)
                                <tr>
                                    <td>{{ $permissions->firstItem() + $index }}</td>
                                    <td>{{ $permission->group }}</td>
                                    <td style="font-weight: 500;">{{ $permission->name }}</td>
                                    <td>
                                        <span style="background: var(--bg-main); padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 13px; border: 1px solid var(--border);">
                                            {{ $permission->slug }}
                                        </span>
                                    </td>
                                    <td>{{ $permission->deskripsi ?? '-' }}</td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <button onclick="openModal('edit', {{ json_encode($permission) }})" class="btn-warning">Edit</button>
                                            <form action="{{ route('web-permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permission ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px 0;">Belum ada data permission.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-wrapper">
                    {{ $permissions->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div id="permissionModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">Tambah Permission</h2>
                <button onclick="closeModal()" class="modal-close" type="button">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="permissionForm" action="{{ route('web-permissions.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" id="permissionId">
                
                <div class="form-group">
                    <label for="group">Group <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="group" id="group" class="form-control" required placeholder="Contoh: General Consent">
                </div>
                
                <div class="form-group">
                    <label for="name">Name <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="Contoh: Lihat General Consent">
                </div>

                <div class="form-group">
                    <label for="slug">Slug <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="slug" id="slug" class="form-control" required placeholder="Contoh: gc.view">
                    <span style="font-size: 11px; color: var(--text-muted); margin-top: 6px; display: block;">Gunakan format unik (lowercase tanpa spasi).</span>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi opsional"></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeModal()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const modal = document.getElementById('permissionModal');
        const form = document.getElementById('permissionForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');
        const btnSubmit = document.getElementById('btnSubmit');
        
        function openModal(type, data = null) {
            modal.classList.add('active');
            
            if (type === 'create') {
                modalTitle.innerText = 'Tambah Permission';
                form.action = "{{ route('web-permissions.store') }}";
                formMethod.value = 'POST';
                btnSubmit.innerText = 'Simpan Permission';
                
                form.reset();
            } else if (type === 'edit') {
                modalTitle.innerText = 'Edit Permission';
                form.action = `/web-permissions/${data.id}`;
                formMethod.value = 'PUT';
                btnSubmit.innerText = 'Update Permission';
                
                document.getElementById('group').value = data.group;
                document.getElementById('name').value = data.name;
                document.getElementById('slug').value = data.slug;
                document.getElementById('deskripsi').value = data.deskripsi || '';
            }
        }
        
        function closeModal() {
            modal.classList.remove('active');
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
