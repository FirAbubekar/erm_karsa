<!-- Signature Modal -->
<div class="modal-overlay" id="signature-modal">
    <div class="modal-content" style="max-width:600px">
        <div class="modal-header">
            <div class="modal-title">Tanda Tangan</div>
            <button class="close-modal" id="close-signature-modal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="modal-body" style="padding:24px">
            <div style="border:2px solid var(--border);border-radius:14px;background:#fff;position:relative;overflow:hidden">
                <div class="signature-start-hint" id="signature-start-hint">
                    <span>START</span><span>HERE</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                </div>
                <canvas id="signature-pad" class="signature-pad" tabindex="0"></canvas>
            </div>
            <p style="margin-top:12px;font-size:13px;color:var(--text-muted);text-align:center">Silakan goreskan tanda tangan Anda mulai dari sisi kiri kotak.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" id="clear-signature" style="background:#F1F5F9;color:var(--text-main)">Hapus</button>
            <button type="button" class="btn btn-primary" id="save-signature-modal">Simpan Tanda Tangan</button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal-overlay" id="error-modal" style="z-index:1050;padding:20px">
    <div class="modal-content" style="max-width:380px;text-align:center;padding:40px 32px 32px;border-radius:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,.1);border:1px solid rgba(239,68,68,.1);animation:modalBounce .4s cubic-bezier(.175,.885,.32,1.275);background:#fff;margin:auto">
        <button id="close-error-modal" style="position:absolute;top:16px;right:16px;background:none;border:none;color:#94A3B8;cursor:pointer;padding:8px;border-radius:50%;display:flex;align-items:center;justify-content:center">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div style="width:72px;height:72px;background:#FEF2F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;color:#EF4444;position:relative">
            <div style="position:absolute;inset:-4px;border-radius:50%;border:2px solid #FCA5A5;opacity:.5;animation:pulseRing 2s cubic-bezier(.215,.61,.355,1) infinite"></div>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:36px;height:36px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 style="font-size:20px;font-weight:700;color:#1E293B;margin:0 0 12px">Aksi Diperlukan</h3>
        <p id="error-modal-message" style="font-size:14px;font-weight:500;color:#64748B;margin:0 0 28px;line-height:1.6"></p>
        <button id="btn-close-error" style="width:100%;padding:14px 24px;background:#EF4444;color:#fff;border:none;border-radius:12px;font-weight:600;font-size:15px;cursor:pointer;transition:all .2s;box-shadow:0 4px 6px -1px rgba(239,68,68,.2)">Saya Mengerti</button>
    </div>
</div>

<!-- Success Modal -->
<div class="modal-overlay" id="success-modal" style="z-index:1050;padding:20px">
    <div class="modal-content" style="max-width:380px;text-align:center;padding:40px 32px 32px;border-radius:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,.1);border:1px solid rgba(16,185,129,.1);animation:modalBounce .4s cubic-bezier(.175,.885,.32,1.275);background:#fff;margin:auto">
        <button id="close-success-modal" style="position:absolute;top:16px;right:16px;background:none;border:none;color:#94A3B8;cursor:pointer;padding:8px;border-radius:50%;display:flex;align-items:center;justify-content:center">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div style="width:72px;height:72px;background:#ECFDF5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;color:#10B981;position:relative">
            <div style="position:absolute;inset:-4px;border-radius:50%;border:2px solid #6EE7B7;opacity:.5;animation:pulseRing 2s cubic-bezier(.215,.61,.355,1) infinite"></div>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:36px;height:36px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h3 style="font-size:20px;font-weight:700;color:#1E293B;margin:0 0 12px">Berhasil!</h3>
        <p id="success-modal-message" style="font-size:14px;font-weight:500;color:#64748B;margin:0 0 28px;line-height:1.6"></p>
        <button id="btn-close-success" style="width:100%;padding:14px 24px;background:#10B981;color:#fff;border:none;border-radius:12px;font-weight:600;font-size:15px;cursor:pointer;transition:all .2s;box-shadow:0 4px 6px -1px rgba(16,185,129,.2)">Selesai</button>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal-overlay" id="confirm-modal" style="z-index:1050;padding:20px;display:none;">
    <div class="modal-content" style="max-width:380px;text-align:center;padding:40px 32px 32px;border-radius:24px;box-shadow:0 20px 25px -5px rgba(0,0,0,.1);border:1px solid rgba(245,158,11,.1);animation:modalBounce .4s cubic-bezier(.175,.885,.32,1.275);background:#fff;margin:auto">
        <button id="close-confirm-modal" style="position:absolute;top:16px;right:16px;background:none;border:none;color:#94A3B8;cursor:pointer;padding:8px;border-radius:50%;display:flex;align-items:center;justify-content:center">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div style="width:72px;height:72px;background:#FFFBEB;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;color:#F59E0B;position:relative">
            <div style="position:absolute;inset:-4px;border-radius:50%;border:2px solid #FCD34D;opacity:.5;animation:pulseRing 2s cubic-bezier(.215,.61,.355,1) infinite"></div>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:36px;height:36px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 style="font-size:20px;font-weight:700;color:#1E293B;margin:0 0 12px">Konfirmasi</h3>
        <p id="confirm-modal-message" style="font-size:14px;font-weight:500;color:#64748B;margin:0 0 28px;line-height:1.6"></p>
        <div style="display:flex;gap:12px;width:100%;">
            <button id="btn-cancel-confirm" style="flex:1;padding:12px;background:#F1F5F9;color:#475569;border:none;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer;transition:all .2s;">Batal</button>
            <button id="btn-ok-confirm" style="flex:1;padding:12px;background:#EF4444;color:#fff;border:none;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer;transition:all .2s;box-shadow:0 4px 6px -1px rgba(239,68,68,.2)">Ya, Lanjutkan</button>
        </div>
    </div>
</div>
