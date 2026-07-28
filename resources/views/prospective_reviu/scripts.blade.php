<script>
let currentEditId = null;
// ══════════ Sync Search Card Height ══════════
function syncSearchCardHeight() {
    const dataPasienCard = document.getElementById('card-data-pasien');
    const searchCard = document.getElementById('card-search');
    if (dataPasienCard && searchCard) {
        searchCard.style.maxHeight = 'none';
        const targetH = dataPasienCard.offsetHeight;
        searchCard.style.maxHeight = targetH + 'px';
    }
}
setTimeout(syncSearchCardHeight, 100);
window.addEventListener('resize', syncSearchCardHeight);

// ══════════ Initialize Select2 ══════════
$(document).ready(function() {
    $('#ttd-perawat-input').select2({
        placeholder: 'Ketik minimal 3 huruf...',
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: '/prospective-reviu/search-pegawai',
            dataType: 'json',
            delay: 500,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });

    $('#ttd-dpjp-input').select2({
        placeholder: 'Ketik minimal 3 huruf...',
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: '/prospective-reviu/search-dokter',
            dataType: 'json',
            delay: 500,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
});

// ══════════ Utility Modals ══════════
function showError(msg){document.getElementById('error-modal-message').innerHTML=msg;document.getElementById('error-modal').style.display='flex'}
function hideError(){document.getElementById('error-modal').style.display='none'}
function showSuccess(msg){document.getElementById('success-modal-message').textContent=msg;document.getElementById('success-modal').style.display='flex'}
function hideSuccess(){document.getElementById('success-modal').style.display='none'}
document.getElementById('close-error-modal').addEventListener('click',hideError);
document.getElementById('btn-close-error').addEventListener('click',hideError);
document.getElementById('close-success-modal').addEventListener('click',hideSuccess);
document.getElementById('btn-close-success').addEventListener('click',hideSuccess);

// ══════════ Patient Search ══════════
const btnSearch = document.getElementById('btn-search');
const inputNoRm = document.getElementById('search-no-rm');
const historyTableBody = document.getElementById('history-table-body');

inputNoRm.addEventListener('keydown', e => {
    if(e.key === 'Enter') { e.preventDefault(); searchPatient(); }
});

async function searchPatient() {
    const noRm = inputNoRm.value;
    if(!noRm) return showError('Silakan masukkan No. RM');
    if(noRm.length < 5) return showError('No. RM harus minimal 5 karakter');
    if(!/^\d+$/.test(noRm)) return showError('No. RM harus berupa angka');
    btnSearch.disabled = true;
    btnSearch.innerHTML = 'Mencari...';
    try {
        const res = await fetch(`/pasien/search?no_rm=${noRm}`);
        const result = await res.json();
        if(res.ok) {
            const { pasien, history } = result;
            document.getElementById('field-no-rm').value = pasien.no_rkm_medis;
            document.getElementById('field-nm-pasien').value = pasien.nm_pasien;
            document.getElementById('field-tgl-lahir').value = pasien.tgl_lahir;
            document.getElementById('field-jk').value = pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan';
            historyTableBody.innerHTML = '';
            if(history.length > 0) {
                history.forEach((reg, i) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td>${i+1}</td><td>${reg.tgl_registrasi}</td><td>${reg.no_rawat}</td><td><span class="badge badge-purple" style="cursor:pointer">Pilih</span></td>`;
                    row.addEventListener('click', () => {
                        document.getElementById('field-no-rawat').value = reg.no_rawat;
                        document.getElementById('field-tgl-registrasi').value = reg.tgl_registrasi;
                        Array.from(historyTableBody.children).forEach(r => r.style.background = '');
                        row.style.background = 'rgba(139, 92, 246, 0.08)';
                        resetForm();
                        loadHistory(reg.no_rawat);
                    });
                    historyTableBody.appendChild(row);
                });
                document.getElementById('field-no-rawat').value = history[0].no_rawat;
                document.getElementById('field-tgl-registrasi').value = history[0].tgl_registrasi;
                historyTableBody.children[0].style.background = 'rgba(139, 92, 246, 0.08)';
                resetForm();
                loadHistory(history[0].no_rawat);
            } else {
                historyTableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-muted);font-size:12px">Tidak ada riwayat rawat.</td></tr>';
                resetForm();
            }
        } else {
            showError(result.error || 'Terjadi kesalahan');
        }
    } catch(e) {
        console.error(e);
        showError('Gagal menghubungi server');
    } finally {
        btnSearch.disabled = false;
        btnSearch.innerHTML = 'Cari';
    }
}
btnSearch.addEventListener('click', searchPatient);

@if(isset($no_rm) && $no_rm)
    window.addEventListener('DOMContentLoaded', () => {
        const inputRm = document.getElementById('search-no-rm');
        if(inputRm) {
            inputRm.value = '{{ $no_rm }}';
            searchPatient().then(() => {
                const targetRawat = '{{ $no_rawat ?? "" }}';
                if(targetRawat) {
                    const rows = document.querySelectorAll('#history-table-body tr');
                    rows.forEach(r => {
                        if(r.cells[2] && r.cells[2].textContent.includes(targetRawat)) {
                            r.click();
                        }
                    });
                }
                
                @if(isset($id_uuid) && $id_uuid)
                    setTimeout(() => {
                        const btn = document.querySelector('button[data-id="{{ $id_uuid }}"]');
                        if (btn) btn.click();
                    }, 1000); // Give time for loadHistory to finish fetching from DB
                @endif
            });
        }
    });
@endif

function resetForm() {
    currentEditId = null;
    // Reset Regimen
    document.querySelectorAll('input[name="antibiotik_jenis"]').forEach(r => r.checked = false);
    const hariKe = document.getElementById('field-hari-ke');
    if(hariKe) hariKe.value = '';
    const antiReview = document.getElementById('field-antibiotik-direview');
    if (antiReview) antiReview.value = '';

    // Reset Parameter Klinis
    ['field-td', 'field-suhu', 'field-rr', 'field-spo2', 'field-gcs', 'field-suhu-celcius', 'field-spo2-persen'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.value = '';
    });
    document.querySelectorAll('input[name="demam"]').forEach(r => r.checked = false);
    ['field-leukosit', 'field-neutrofil-persen', 'field-leukosit-value', 'field-neutrofil-value', 'field-kreatinin', 'field-ureum'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.value = '';
    });

    // Reset Kultur
    document.querySelectorAll('input[name="hasil_kultur"]').forEach(r => r.checked = false);
    const kulturHasil = document.getElementById('field-kultur-hasil-positif');
    if(kulturHasil) kulturHasil.value = '';
    const rekAntibiotik = document.getElementById('field-rekomendasi-antibiotik');
    if(rekAntibiotik) rekAntibiotik.value = '';

    // Reset Penilaian Appropriateness
    ['indikasi', 'jenis_antibiotik', 'dosis_appropriateness', 'durasi'].forEach(name => {
        document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = false);
    });

    // Reset Rekomendasi PGA
    document.querySelectorAll('input[name="rekomendasi_pga[]"]').forEach(cb => cb.checked = false);
    const pgaLainnya = document.getElementById('field-pga-lainnya');
    if(pgaLainnya) { pgaLainnya.value = ''; pgaLainnya.style.display = 'none'; }

    // Reset Respon DPJP
    document.querySelectorAll('input[name="respon_dpjp"]').forEach(r => r.checked = false);
    const catatan = document.getElementById('field-catatan');
    if(catatan) catatan.value = '';

    // Reset Diagnosis & Tanggal Reviu
    const diagnosis = document.getElementById('field-diagnosis');
    if(diagnosis) diagnosis.value = '';
    const tglReviu = document.getElementById('field-tgl-reviu');
    if(tglReviu) tglReviu.value = new Date().toISOString().split('T')[0];

    // Reset TTD boxes
    const ttdPerawat = $('#ttd-perawat-input');
    if(ttdPerawat.length) ttdPerawat.val(null).trigger('change');
    const ttdDpjp = $('#ttd-dpjp-input');
    if(ttdDpjp.length) ttdDpjp.val(null).trigger('change');
}

// ══════════ Lain-lain toggle (PGA) ══════════
const pgaLainnyaCb = document.querySelector('input[name="rekomendasi_pga[]"][value="Lainnya"]');
const pgaLainnyaInput = document.getElementById('field-pga-lainnya');
if(pgaLainnyaCb && pgaLainnyaInput) {
    pgaLainnyaCb.addEventListener('change', () => {
        pgaLainnyaInput.style.display = pgaLainnyaCb.checked ? 'block' : 'none';
    });
}

// ══════════ Signature Pad ══════════
let activeSignatureTarget = null;
const sigModal = document.getElementById('signature-modal');
const sigCanvas = document.getElementById('signature-pad');
const sigPad = new SignaturePad(sigCanvas, { backgroundColor: 'rgba(255,255,255,0)', penColor: '#1E293B' });

function resizeSigCanvas() {
    const r = Math.max(window.devicePixelRatio || 1, 1);
    sigCanvas.width = sigCanvas.offsetWidth * r;
    sigCanvas.height = sigCanvas.offsetHeight * r;
    sigCanvas.getContext('2d').scale(r, r);
    sigPad.clear();
}

function openSignatureModal(targetId) {
    activeSignatureTarget = targetId;
    sigModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        resizeSigCanvas();
        sigPad.clear();
        document.getElementById('signature-start-hint').style.display = 'flex';
        sigCanvas.focus();
    }, 50);
}

sigPad.onBegin = () => { document.getElementById('signature-start-hint').style.display = 'none'; };

document.getElementById('close-signature-modal').addEventListener('click', () => {
    sigModal.style.display = 'none';
    document.body.style.overflow = '';
});

document.getElementById('clear-signature').addEventListener('click', () => {
    sigPad.clear();
    document.getElementById('signature-start-hint').style.display = 'flex';
});

document.getElementById('save-signature-modal').addEventListener('click', () => {
    if(sigPad.isEmpty()) return showError('Silakan tanda tangan terlebih dahulu.');
    const dataURL = sigPad.toDataURL('image/png');
    if(activeSignatureTarget) {
        const container = document.getElementById(activeSignatureTarget);
        if(container) {
            let img = container.querySelector('img');
            let placeholder = container.querySelector('.signature-placeholder');
            if(!img) {
                img = document.createElement('img');
                img.style.cssText = 'max-width:100%;max-height:100%;object-fit:contain';
                container.appendChild(img);
            }
            img.src = dataURL;
            img.style.display = 'block';
            if(placeholder) placeholder.style.display = 'none';
            container.style.borderStyle = 'solid';
            container.style.borderColor = 'var(--accent)';
            container.dataset.signatureData = dataURL;
        }
    }
    sigModal.style.display = 'none';
    document.body.style.overflow = '';
});

// TTD Click handlers
['ttd-apoteker', 'ttd-perawat', 'ttd-dpjp', 'ttd-kpra'].forEach(id => {
    const el = document.getElementById(id);
    if(el) el.addEventListener('click', () => openSignatureModal(id));
});

// ══════════ Save Prospective Reviu ══════════
document.getElementById('btn-save-reviu').addEventListener('click', async () => {
    const noRawat = document.getElementById('field-no-rawat').value;
    if(!noRawat) return showError('Silakan pilih No. Rawat terlebih dahulu.');

    // Collect radio helper
    const getRadio = name => {
        const r = document.querySelector(`input[name="${name}"]:checked`);
        return r ? r.value : null;
    };

    // Collect checkboxes helper
    const getChecked = name => Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(cb => cb.value);

    // Collect input value helper safely
    const getVal = id => {
        const el = document.getElementById(id);
        return el ? (el.value || null) : null;
    };

    const payload = {
        id_uuid: currentEditId,
        no_rawat: noRawat,
        tanggal_reviu: getVal('field-tgl-reviu'),
        diagnosis: getVal('field-diagnosis'),
        // Regimen
        tipe_antibiotik: getRadio('antibiotik_jenis'),
        antibiotik_direview: getVal('field-antibiotik-direview'),
        hari_ke: getVal('field-hari-ke'),
        // Parameter Klinis
        klinis_td: getVal('field-td'),
        klinis_suhu: getVal('field-suhu-celcius') || getVal('field-suhu'),
        klinis_rr: getVal('field-rr'),
        klinis_spo2: getVal('field-spo2-persen') || getVal('field-spo2'),
        klinis_gcs: getVal('field-gcs'),
        is_demam: getRadio('demam'),
        lab_leukosit: getVal('field-leukosit-value') || getVal('field-leukosit'),
        lab_neutrofil_persen: getVal('field-neutrofil-persen'),
        lab_kreatinin: getVal('field-kreatinin'),
        lab_ureum: getVal('field-ureum'),
        // Kultur
        kultur_status: getRadio('hasil_kultur'),
        kultur_hasil_positif: getVal('field-kultur-hasil-positif'),
        kultur_rekomendasi_antibiotik: getVal('field-rekomendasi-antibiotik'),
        // Penilaian Appropriateness
        is_indikasi_tepat: getRadio('indikasi'),
        is_jenis_tepat: getRadio('jenis_antibiotik'),
        is_dosis_tepat: getRadio('dosis_appropriateness'),
        is_durasi_sesuai: getRadio('durasi'),
        // Rekomendasi PGA
        rekomendasi_pga: getChecked('rekomendasi_pga[]'),
        rekomendasi_pga_lainnya: document.getElementById('field-pga-lainnya').value || null,
        // Respon DPJP
        respon_dpjp: getRadio('respon_dpjp'),
        respon_catatan: document.getElementById('field-catatan').value || null,
        // TTD
        ttd_perawat: document.getElementById('ttd-perawat-input') ? document.getElementById('ttd-perawat-input').value : null,
        ttd_dpjp: document.getElementById('ttd-dpjp-input') ? document.getElementById('ttd-dpjp-input').value : null,
    };

    const btn = document.getElementById('btn-save-reviu');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity=".25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" opacity=".75"></path></svg> Menyimpan...';

    try {
        const res = await fetch('/prospective-reviu/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload),
        });
        const result = await res.json();

        // Clear existing errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        if(res.ok && result.success) {
            showSuccess(result.message);
            resetForm();
            loadHistory(noRawat);
        } else {
            if (res.status === 422 && result.errors) {
                let firstErrorEl = null;
                const fieldMap = {
                    'no_rawat': 'field-no-rawat',
                    'tanggal_reviu': 'field-tgl-reviu',
                    'diagnosis': 'field-diagnosis',
                    'tipe_antibiotik': 'antibiotik_jenis',
                    'antibiotik_direview': 'field-antibiotik-direview',
                    'hari_ke': 'field-hari-ke',
                    'klinis_td': 'field-td',
                    'klinis_suhu': 'field-suhu',
                    'klinis_rr': 'field-rr',
                    'klinis_spo2': 'field-spo2',
                    'klinis_gcs': 'field-gcs',
                    'is_demam': 'demam',
                    'lab_leukosit': 'field-leukosit',
                    'lab_neutrofil_persen': 'field-neutrofil-persen',
                    'lab_kreatinin': 'field-kreatinin',
                    'lab_ureum': 'field-ureum',
                    'kultur_status': 'hasil_kultur',
                    'kultur_hasil_positif': 'field-kultur-hasil-positif',
                    'kultur_rekomendasi_antibiotik': 'field-rekomendasi-antibiotik',
                    'is_indikasi_tepat': 'indikasi',
                    'is_jenis_tepat': 'jenis_antibiotik',
                    'is_dosis_tepat': 'dosis_appropriateness',
                    'is_durasi_sesuai': 'durasi',
                    'rekomendasi_pga': 'rekomendasi_pga[]',
                    'respon_dpjp': 'respon_dpjp'
                };

                for (const key in result.errors) {
                    const errorMsg = result.errors[key][0];
                    const targetName = fieldMap[key] || key;
                    
                    let el = document.getElementById(targetName);
                    if (!el) {
                        el = document.querySelector(`input[name="${targetName}"]`);
                        if (el) el = el.closest('.form-group') || el.closest('.radio-group') || el.closest('.checkbox-group');
                    }

                    if (el) {
                        el.classList.add('is-invalid');
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.innerText = errorMsg;
                        
                        if (el.classList.contains('form-group') || el.classList.contains('radio-group') || el.classList.contains('checkbox-group')) {
                            el.appendChild(feedback);
                        } else {
                            el.parentNode.insertBefore(feedback, el.nextSibling);
                        }

                        if (!firstErrorEl) firstErrorEl = el;
                    } else {
                        // Fallback
                        showError(errorMsg);
                    }
                }

                if (firstErrorEl) {
                    firstErrorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                showError(result.error || 'Terjadi kesalahan saat menyimpan.');
            }
        }
    } catch(e) {
        console.error(e);
        showError('Gagal menghubungi server.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Prospective Reviu';
    }
});

async function loadHistory(noRawat) {
    try {
        const res = await fetch(`/prospective-reviu/history?no_rawat=${encodeURIComponent(noRawat)}`);
        if (!res.ok) throw new Error('Network error');
        const data = await res.json();
        
        const tbody = document.getElementById('history-tbody');
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:var(--text-muted);font-size:13px;">Belum ada riwayat reviu untuk pasien ini.</td></tr>';
            document.getElementById('card-history').style.display = 'block';
            return;
        }

        data.forEach(rev => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="padding: 12px; border-bottom: 1px solid var(--border-color);">${rev.tanggal_reviu || '-'}</td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border-color);">${rev.hari_ke || '-'}</td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border-color);">${rev.tipe_antibiotik || '-'}</td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border-color);">${rev.nama_apoteker_klinis || rev.ttd_apoteker_klinis || '-'}</td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border-color);">${rev.nama_dpjp || rev.ttd_dpjp || '-'}</td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-outline" data-id="${rev.id_uuid}" style="padding:6px 12px;font-size:12px;">Edit</button>
                </td>
            `;
            const btn = tr.querySelector('button');
            btn.addEventListener('click', () => {
                populateForm(rev);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            tbody.appendChild(tr);
        });

        document.getElementById('card-history').style.display = 'block';
    } catch (err) {
        console.error(err);
    }
}

function populateForm(data) {
    resetForm();
    currentEditId = data.id_uuid;
    
    const setVal = (id, val) => {
        const el = document.getElementById(id);
        if(el && val) el.value = val;
    };
    const setRadio = (name, val) => {
        if(val === null || val === undefined || val === '') return;
        const r = document.querySelector(`input[name="${name}"][value="${val}"]`);
        if(r) r.checked = true;
    };
    const setChecked = (name, vals) => {
        if(!vals || !Array.isArray(vals)) return;
        vals.forEach(v => {
            const cb = document.querySelector(`input[name="${name}"][value="${v}"]`);
            if(cb) cb.checked = true;
        });
    };

    setVal('field-tgl-reviu', data.tanggal_reviu);
    setVal('field-diagnosis', data.diagnosis);
    setRadio('antibiotik_jenis', data.tipe_antibiotik);
    setVal('field-antibiotik-direview', data.antibiotik_direview);
    setVal('field-hari-ke', data.hari_ke);
    setVal('field-td', data.klinis_td);
    setVal('field-suhu', data.klinis_suhu);
    setVal('field-rr', data.klinis_rr);
    setVal('field-spo2', data.klinis_spo2);
    setVal('field-gcs', data.klinis_gcs);
    setVal('field-suhu-celcius', data.klinis_suhu);
    setVal('field-spo2-persen', data.klinis_spo2);
    setRadio('demam', data.is_demam);
    setVal('field-leukosit', data.lab_leukosit);
    setVal('field-neutrofil-persen', data.lab_neutrofil_persen);
    setVal('field-leukosit-value', data.lab_leukosit);
    setVal('field-kreatinin', data.lab_kreatinin);
    setVal('field-ureum', data.lab_ureum);
    setRadio('hasil_kultur', data.kultur_status);
    setVal('field-kultur-hasil-positif', data.kultur_hasil_positif);
    setVal('field-rekomendasi-antibiotik', data.kultur_rekomendasi_antibiotik);
    setRadio('indikasi', data.is_indikasi_tepat);
    setRadio('jenis_antibiotik', data.is_jenis_tepat);
    setRadio('dosis_appropriateness', data.is_dosis_tepat);
    setRadio('durasi', data.is_durasi_sesuai);
    
    setChecked('rekomendasi_pga[]', data.rekomendasi_pga);
    if(data.rekomendasi_pga_lainnya) {
        setVal('field-pga-lainnya', data.rekomendasi_pga_lainnya);
        document.getElementById('field-pga-lainnya').style.display = 'block';
    }
    
    setRadio('respon_dpjp', data.respon_dpjp);
    setVal('field-catatan', data.respon_catatan);

    // populate select2
    if(data.ttd_perawat && data.nama_perawat) {
        const perawatOption = new Option(data.nama_perawat, data.ttd_perawat, true, true);
        $('#ttd-perawat-input').append(perawatOption).trigger('change');
    }
    if(data.ttd_dpjp && data.nama_dpjp) {
        const dpjpOption = new Option(data.nama_dpjp, data.ttd_dpjp, true, true);
        $('#ttd-dpjp-input').append(dpjpOption).trigger('change');
    }
}
</script>
