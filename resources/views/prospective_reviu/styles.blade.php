<style>
    :root {
        --sidebar-width: 280px;
        --primary: #10B981;
        --primary-dark: #059669;
        --primary-light: rgba(16, 185, 129, 0.1);
        --accent: #8B5CF6;
        --accent-light: rgba(139, 92, 246, 0.08);
        --accent-dark: #7C3AED;
        --bg-main: #F1F5F9;
        --card-bg: #FFFFFF;
        --text-main: #0F172A;
        --text-secondary: #334155;
        --text-muted: #64748B;
        --border: #E2E8F0;
        --border-light: #F1F5F9;
        --farmasi: #F97316;
        --farmasi-light: rgba(249, 115, 22, 0.1);
        --farmasi-dark: #EA580C;
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

    /* ─── Sidebar (shared) ─── */
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
        overflow-y: auto;
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

    .btn-logout:hover {
        background: #FEE2E2;
        border-color: #FECACA;
        transform: translateY(-1px);
    }

    .btn-logout svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    /* ─── Main Content ─── */
    .main-content {
        margin-left: var(--sidebar-width);
        flex-grow: 1;
        padding: 0;
        max-width: calc(100vw - var(--sidebar-width));
        overflow-x: hidden;
    }

    /* ─── Hero Header ─── */
    .page-hero {
        background: linear-gradient(135deg, #1a0a2e 0%, #2d1854 40%, #4c2885 100%);
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
        background: radial-gradient(circle, rgba(249, 115, 22, 0.15) 0%, transparent 70%);
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
        background: radial-gradient(circle, rgba(139, 92, 246, 0.12) 0%, transparent 70%);
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
        font-size: 22px;
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
        background: linear-gradient(135deg, var(--farmasi), var(--accent));
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

    /* ─── Forms ─── */
    .form-grid {
        display: grid;
        gap: 16px 20px;
        margin-bottom: 8px;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        background: #F8FAFC;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        color: var(--text-main);
        transition: all 0.2s;
        outline: none;
        font-weight: 500;
    }

    .form-control:focus {
        background: white;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .form-control[readonly] {
        background: #F1F5F9;
        color: var(--text-secondary);
        cursor: default;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .form-control.is-invalid {
        border-color: #EF4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }

    .invalid-feedback {
        display: block;
        color: #EF4444;
        font-size: 11px;
        margin-top: 4px;
        font-weight: 500;
    }

    .section-divider {
        margin: 24px 0;
        border: none;
        border-top: 1.5px dashed var(--border);
    }

    .section-title {
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .section-title svg {
        color: var(--accent);
    }

    .section-title.farmasi-accent {
        color: var(--farmasi);
    }

    .section-title.farmasi-accent svg {
        color: var(--farmasi);
    }

    /* ─── Search ─── */
    .search-container {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
    }

    /* ─── Buttons ─── */
    .btn {
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent), var(--accent-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-farmasi {
        background: linear-gradient(135deg, var(--farmasi), var(--farmasi-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2);
    }

    .btn-farmasi:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* ─── Table ─── */
    .history-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .history-table th {
        text-align: left;
        padding: 10px 16px;
        background: linear-gradient(to bottom, #FAFBFC, #F6F8FA);
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border);
    }

    .history-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13px;
    }

    .history-table tr:hover {
        background: rgba(139, 92, 246, 0.03);
    }

    .history-table tr {
        cursor: pointer;
        transition: background 0.15s;
    }

    /* ─── Layout Grid ─── */
    .layout-grid {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 24px;
        align-items: start;
    }

    .layout-grid>* {
        min-width: 0;
        height: 100%;
    }

    /* ─── Search Card ─── */
    .search-card {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
        margin-bottom: 0;
    }

    .search-card .search-container {
        flex-shrink: 0;
    }

    .search-card .section-title {
        flex-shrink: 0;
    }

    .search-card .table-scroll-wrapper {
        flex: 1;
        overflow: auto;
        min-height: 0;
    }

    /* ─── Badge ─── */
    .badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-blue {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .badge-orange {
        background: rgba(249, 115, 22, 0.1);
        color: #F97316;
    }

    .badge-green {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .badge-red {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .badge-purple {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
    }

    /* ─── Radio & Checkbox Groups ─── */
    .radio-group,
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 4px;
    }

    .radio-group label,
    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        cursor: pointer;
        text-transform: none;
        letter-spacing: 0;
        margin-bottom: 0;
    }

    .radio-group input[type="radio"],
    .checkbox-group input[type="checkbox"] {
        accent-color: var(--accent);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    /* ─── Appropriateness Table ─── */
    .appropriateness-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .appropriateness-table th {
        padding: 12px 16px;
        background: linear-gradient(to bottom, #FAFBFC, #F6F8FA);
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.04em;
        border: 1px solid var(--border);
        text-align: center;
    }

    .appropriateness-table td {
        padding: 12px 16px;
        border: 1px solid var(--border);
        vertical-align: middle;
        font-size: 13px;
        font-weight: 500;
    }

    .appropriateness-table td:first-child {
        font-weight: 600;
        color: var(--text-secondary);
        background: #FAFBFC;
    }

    .appropriateness-table .radio-group {
        gap: 20px;
        justify-content: center;
    }

    /* ─── Signature ─── */
    .signature-preview-box {
        width: 100%;
        height: 120px;
        background: #FAFBFC;
        border: 2px dashed var(--border);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }

    .signature-preview-box:hover {
        border-color: var(--accent);
        background: white;
    }

    .signature-preview-box img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .signature-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: var(--text-muted);
        font-size: 12px;
    }

    .signature-placeholder svg {
        opacity: 0.4;
    }

    .signature-pad {
        width: 100%;
        height: 300px;
        background: white;
        border-radius: 12px;
        touch-action: none;
        outline: none;
    }

    .signature-start-hint {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        color: var(--accent);
        opacity: 0.6;
        font-size: 11px;
        font-weight: 600;
        border-left: 2px dashed var(--accent);
        padding-left: 10px;
    }

    /* ─── TTD Grid ─── */
    .ttd-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .ttd-item {
        text-align: center;
    }

    .ttd-item label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* ─── Modal ─── */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: white;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.3);
        animation: modalSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalSlideUp {
        from {
            transform: translateY(30px) scale(0.97);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes modalBounce {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes pulseRing {
        0% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.2; }
        100% { transform: scale(1); opacity: 0.5; }
    }

    .modal-header {
        padding: 20px 28px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #1a0a2e, #2d1854);
    }

    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: white;
    }

    .modal-body {
        padding: 28px;
        overflow-y: auto;
        flex-grow: 1;
    }

    .modal-footer {
        padding: 16px 28px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: #FAFBFC;
    }

    .close-modal {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        cursor: pointer;
        color: rgba(255, 255, 255, 0.7);
        padding: 8px;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .close-modal:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* ─── Parameter Klinis Grid ─── */
    .param-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    .param-grid .form-group {
        margin-bottom: 0;
    }

    .param-grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 20px;
    }

    .param-grid-2col .form-group {
        margin-bottom: 0;
    }

    /* ─── Animate ─── */
    .animate-in {
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* ─── Responsive ─── */
    @media (max-width: 1024px) {
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
            padding: 80px 20px 52px;
        }

        .content-area {
            padding: 0 20px 40px;
        }

        .layout-grid {
            grid-template-columns: 1fr;
        }

        .param-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .ttd-grid {
            grid-template-columns: 1fr;
        }

        .hero-top {
            flex-direction: column;
            gap: 16px;
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
    }

    @media (max-width: 640px) {
        .param-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .appropriateness-table .radio-group {
            gap: 10px;
        }
    }
</style>
