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

    /* ─── Sidebar (shared from RM01) ─── */
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
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
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
        background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
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
        background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
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
        background: linear-gradient(135deg, var(--accent), var(--primary));
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
        height: 40px;
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
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
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
        background: rgba(99, 102, 241, 0.03);
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

    /* ─── Search Card (match Data Pasien height, scrollable) ─── */
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

    .badge-red {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .badge-green {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .badge-orange {
        background: rgba(249, 115, 22, 0.1);
        color: #F97316;
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

    /* ─── Rencana Kebutuhan Grid ─── */
    .rencana-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 24px;
    }

    .rencana-grid label {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        cursor: pointer;
        text-transform: none;
        letter-spacing: 0;
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .rencana-grid input[type="checkbox"] {
        accent-color: var(--accent);
        width: 16px;
        height: 16px;
        margin-top: 2px;
        cursor: pointer;
        flex-shrink: 0;
    }

    /* ─── Info Boxes (Section B right panel) ─── */
    .info-box {
        background: #F8FAFC;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .info-box-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 8px;
    }

    .info-box ol {
        padding-left: 20px;
    }

    .info-box ol li {
        font-size: 12px;
        color: var(--text-secondary);
    }

    /* ─── Signature ─── */
    .signature-preview-box {
        width: 100%;
        height: 160px;
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
        gap: 8px;
        color: var(--text-muted);
        font-size: 13px;
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

    .modal-header {
        padding: 20px 28px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #0F172A, #1E293B);
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

    /* ─── Edukasi Table (Section C) ─── */
    .edukasi-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .edukasi-table th {
        padding: 10px 12px;
        background: linear-gradient(to bottom, #FAFBFC, #F6F8FA);
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.04em;
        border: 1px solid var(--border);
        text-align: center;
        white-space: nowrap;
    }

    .edukasi-table td {
        padding: 10px 12px;
        border: 1px solid var(--border);
        vertical-align: top;
        font-size: 12px;
    }

    .edukasi-table .topik-cell {
        white-space: pre-line;
        line-height: 1.5;
        min-width: 200px;
    }

    .edukasi-table .verif-cell {
        min-width: 100px;
    }

    .edukasi-table .ttd-cell {
        min-width: 80px;
        text-align: center;
    }

    .edukasi-table .ttd-mini {
        width: 70px;
        height: 50px;
        border: 1.5px dashed var(--border);
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FAFBFC;
        transition: all 0.2s;
        overflow: hidden;
        margin: 0 auto;
    }

    .edukasi-table .ttd-mini:hover {
        border-color: var(--accent);
        background: white;
    }

    .edukasi-table .ttd-mini img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .edukasi-table .poli-input {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 12px;
        background: #F8FAFC;
        outline: none;
    }

    .edukasi-table .poli-input:focus {
        border-color: var(--accent);
        background: white;
    }

    /* ─── Action Buttons ─── */
    .btn-add-row {
        margin-top: 10px;
        display: inline-flex;
        align-items: right;
        gap: 6px;
        padding: 8px 16px;
        background: var(--accent-light);
        color: var(--accent);
        border: 1.5px dashed var(--accent);
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-add-row:hover {
        background: rgba(99, 102, 241, 0.15);
        transform: translateY(-1px);
    }

    .btn-icon {
        padding: 6px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-icon-save {
        background: var(--primary-light);
        color: var(--primary);
    }

    .btn-icon-save:hover {
        background: var(--primary);
        color: white;
    }

    .btn-icon-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .btn-icon-delete:hover {
        background: #EF4444;
        color: white;
    }

    /* ─── Konfirmasi Grid ─── */
    .konfirmasi-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    /* ─── Animations ─── */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-in {
        animation: fadeInUp 0.4s ease-out both;
    }

    @keyframes modalBounce {
        0% {
            transform: scale(0.9);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes pulseRing {
        0% {
            transform: scale(0.8);
            opacity: 0.5;
        }

        80%,
        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    /* ─── Mobile ─── */
    @media (max-width: 1024px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            max-width: 100%;
        }

        .page-hero {
            padding: 80px 20px 50px;
        }

        .content-area {
            padding: 0 20px 20px;
        }

        .form-grid {
            grid-template-columns: 1fr !important;
        }

        .layout-grid {
            grid-template-columns: 1fr;
        }

        .konfirmasi-grid {
            grid-template-columns: 1fr;
        }

        .rencana-grid {
            grid-template-columns: 1fr;
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
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
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
</style>
