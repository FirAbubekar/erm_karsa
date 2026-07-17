<script>
    // ══════════ Sync Search Card Height to Data Pasien ══════════
    function syncSearchCardHeight() {
        const dataPasienCard = document.getElementById('card-data-pasien');
        const searchCard = document.getElementById('card-search');
        if (dataPasienCard && searchCard) {
            searchCard.style.maxHeight = 'none'; // reset
            const targetH = dataPasienCard.offsetHeight;
            searchCard.style.maxHeight = targetH + 'px';
        }
    }
    // Run after DOM paint
    setTimeout(syncSearchCardHeight, 100);
    window.addEventListener('resize', syncSearchCardHeight);

    // ══════════ Utility Modals ══════════
    function showError(msg) {
        document.getElementById('error-modal-message').textContent = msg;
        document.getElementById('error-modal').style.display = 'flex'
    }

    function hideError() {
        document.getElementById('error-modal').style.display = 'none'
    }

    function showSuccess(msg) {
        document.getElementById('success-modal-message').textContent = msg;
        document.getElementById('success-modal').style.display = 'flex'
    }

    function hideSuccess() {
        document.getElementById('success-modal').style.display = 'none'
    }
    document.getElementById('close-error-modal').addEventListener('click', hideError);
    document.getElementById('btn-close-error').addEventListener('click', hideError);
    document.getElementById('close-success-modal').addEventListener('click', hideSuccess);
    document.getElementById('btn-close-success').addEventListener('click', hideSuccess);

    // ══════════ Patient Search ══════════
    const btnSearch = document.getElementById('btn-search');
    const inputNoRm = document.getElementById('search-no-rm');
    const historyTableBody = document.getElementById('history-table-body');
    let currentAssessmentId = null; // Track existing assessment

    inputNoRm.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchPatient()
        }
    });

    async function searchPatient() {
        const noRm = inputNoRm.value;
        if (!noRm) return showError('Silakan masukkan No. RM');
        btnSearch.disabled = true;
        btnSearch.innerHTML = 'Mencari...';
        try {
            const res = await fetch(`/pasien/search?no_rm=${noRm}`);
            const result = await res.json();
            if (res.ok) {
                const {
                    pasien,
                    history
                } = result;
                document.getElementById('field-no-rm').value = pasien.no_rkm_medis;
                document.getElementById('field-nm-pasien').value = pasien.nm_pasien;
                document.getElementById('field-tgl-lahir').value = pasien.tgl_lahir;
                document.getElementById('field-jk').value = pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan';
                historyTableBody.innerHTML = '';
                if (history.length > 0) {
                    history.forEach((reg, i) => {
                        const row = document.createElement('tr');
                        row.innerHTML =
                            `<td>${i+1}</td><td>${reg.tgl_registrasi}</td><td>${reg.no_rawat}</td><td><span class="badge badge-blue" style="cursor:pointer">Pilih</span></td>`;
                        row.addEventListener('click', () => {
                            document.getElementById('field-no-rawat').value = reg.no_rawat;
                            document.getElementById('field-tgl-registrasi').value = reg
                                .tgl_registrasi;
                            Array.from(historyTableBody.children).forEach(r => r.style.background =
                                '');
                            row.style.background = 'var(--primary-light)';
                            // Load existing assessment for this no_rawat
                            loadAssessment(reg.no_rawat);
                        });
                        historyTableBody.appendChild(row);
                    });
                    document.getElementById('field-no-rawat').value = history[0].no_rawat;
                    document.getElementById('field-tgl-registrasi').value = history[0].tgl_registrasi;
                    historyTableBody.children[0].style.background = 'var(--primary-light)';
                    // Auto-load assessment for first row
                    loadAssessment(history[0].no_rawat);
                } else {
                    historyTableBody.innerHTML =
                        '<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-muted);font-size:12px">Tidak ada riwayat rawat.</td></tr>';
                    resetAssessmentForm();
                }
            } else {
                showError(result.error || 'Terjadi kesalahan')
            }
        } catch (e) {
            console.error(e);
            showError('Gagal menghubungi server')
        } finally {
            btnSearch.disabled = false;
            btnSearch.innerHTML = 'Cari'
        }
    }
    btnSearch.addEventListener('click', searchPatient);

    // ══════════ Load Existing Assessment ══════════
    async function loadAssessment(noRawat) {
        try {
            const res = await fetch(`/edukasi-pasien/get-assessment?no_rawat=${encodeURIComponent(noRawat)}`);
            const result = await res.json();
            if (result.exists) {
                currentAssessmentId = result.data.id_uuid;
                resetAssessmentForm();
                populateAssessmentForm(result.data);
            } else {
                currentAssessmentId = null;
                resetAssessmentForm();
            }
        } catch (e) {
            console.error('Failed to load assessment:', e);
            currentAssessmentId = null;
        }
    }

    function populateAssessmentForm(data) {
        // Text fields
        if (data.nama_penerima_info) document.getElementById('field-nama-penerima').value = data.nama_penerima_info;
        if (data.hubungan_dgn_pasien) document.getElementById('field-hubungan-pasien').value = data.hubungan_dgn_pasien;
        if (data.tanggal_edukasi) document.getElementById('field-tgl-edukasi').value = data.tanggal_edukasi;
        if (data.nama_pasien_wali_ttd) document.getElementById('field-nama-wali-ttd').value = data.nama_pasien_wali_ttd;

        // Checkboxes helper
        const setCheckboxes = (name, values) => {
            document.querySelectorAll(`input[name="${name}"]`).forEach(cb => {
                cb.checked = values && values.includes(cb.value);
            });
        };
        // Radio helper
        const setRadio = (name, value) => {
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.checked = r.value === String(value);
            });
        };

        // Section A
        setCheckboxes('bahasa[]', data.bahasa);
        if (data.bahasa_lainnya) {
            document.getElementById('bahasa-lainnya').value = data.bahasa_lainnya;
            document.getElementById('bahasa-lainnya').style.display = 'block';
        }
        setRadio('perlu_penerjemah', data.perlu_penerjemah);
        setRadio('baca_dan_tulis', data.baca_dan_tulis);
        setRadio('pendidikan', data.pendidikan);
        if (data.pendidikan_lainnya) {
            document.getElementById('pendidikan-lainnya').value = data.pendidikan_lainnya;
            document.getElementById('pendidikan-lainnya').style.display = 'block';
        }
        setRadio('nilai_budaya', data.nilai_budaya);
        setRadio('gaya_pembelajaran', data.gaya_pembelajaran);
        setRadio('literasi_kesehatan', data.literasi_kesehatan);
        setCheckboxes('hambatan_edukasi[]', data.hambatan_edukasi);
        if (data.hambatan_lainnya) {
            document.getElementById('hambatan-lainnya').value = data.hambatan_lainnya;
            document.getElementById('hambatan-lainnya').style.display = 'block';
        }
        setRadio('kesediaan_menerima', data.kesediaan_menerima);

        // Section B
        setCheckboxes('rencana_kebutuhan[]', data.rencana_kebutuhan);
        if (data.rencana_lainnya) {
            document.getElementById('rencana-lainnya').value = data.rencana_lainnya;
            document.getElementById('rencana-lainnya').style.display = 'block';
        }

        // Signature preview
        if (data.ttd_pasien_wali) {
            const sigBox = document.getElementById('konfirmasi-signature-box');
            if (sigBox) {
                let img = sigBox.querySelector('img');
                const placeholder = sigBox.querySelector('.signature-placeholder');
                if (!img) {
                    img = document.createElement('img');
                    img.style.cssText = 'max-width:100%;max-height:100%;object-fit:contain';
                    sigBox.appendChild(img);
                }
                img.src = '/storage/' + data.ttd_pasien_wali;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
                sigBox.style.borderStyle = 'solid';
                sigBox.style.borderColor = 'var(--primary)';
                sigBox.dataset.signatureData = 'existing'; // Mark as already has signature
            }
        }

        // Section C - Populate implementations
        if (data.implementations && data.implementations.length > 0) {
            populateImplementations(data.implementations);
        }
    }

    function populateImplementations(implementations) {
        const rows = document.querySelectorAll('#topik-table-body tr');
        implementations.forEach(impl => {
            // Find matching row by kode_topik
            let row = null;
            rows.forEach(r => {
                if (r.dataset.kode === impl.kode_topik) row = r;
            });

            // Recreate custom row if not found and is_custom
            if (!row && impl.is_custom) {
                row = document.createElement('tr');
                row.dataset.kode = impl.kode_topik;
                row.dataset.isCustom = 'true';
                row.innerHTML = `
                <td><input type="text" class="poli-input" placeholder="Poli/Unit" value="${impl.poli_unit || ''}"></td>
                <td class="topik-cell">
                    <input type="text" class="form-control topik-input" style="font-size:12px;padding:6px 8px" placeholder="Tulis topik edukasi..." value="">
                    <textarea class="topik-sebutkan-input form-control" style="font-size:11px;padding:4px 6px;margin-top:4px;width:100%;border-radius:4px;resize:vertical;min-height:50px;" placeholder="Sebutkan detail..."></textarea>
                </td>
                <td>
                    <div style="margin-bottom:6px">
                        <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:2px">Mulai:</label>
                        <input type="datetime-local" class="form-control input-start" style="font-size:11px;padding:4px 6px" value="${getNowDatetime()}">
                    </div>
                    <div>
                        <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:2px">Selesai:</label>
                        <input type="datetime-local" class="form-control input-end" style="font-size:11px;padding:4px 6px">
                    </div>
                </td>
                <td class="verif-cell">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                        <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Tujuan</label>
                        <input type="text" maxlength="1" data-verif="Tujuan" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                        <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Metode</label>
                        <input type="text" maxlength="1" data-verif="Metode" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                        <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Materi</label>
                        <input type="text" maxlength="1" data-verif="Materi" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                        <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Evaluasi</label>
                        <input type="text" maxlength="1" data-verif="Evaluasi" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
                    </div>
                </td>
                <td class="ttd-cell">
                    <div class="ttd-mini" id="ttd-pasien-${impl.kode_topik}" onclick="openSignatureModal('ttd-pasien-${impl.kode_topik}')"><span class="ttd-placeholder" style="font-size:9px;color:var(--text-muted)">TTD</span></div>
                    <div style="margin-top:4px; text-align:center;display:none">
                        <label style="font-size:9px;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;">
                            <input type="checkbox" class="copy-ttd-cb" data-target="ttd-pasien-${impl.kode_topik}"> Salin TTD B
                        </label>
                    </div>
                </td>
                <td><input type="date" class="form-control" style="font-size:11px;padding:6px 8px"></td>
                <td style="text-align:center;white-space:nowrap">
                    <button class="btn-icon btn-icon-save" title="Simpan baris" style="margin-right:4px">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    </button>
                    <button class="btn-icon btn-icon-delete" onclick="this.closest('tr').remove()" title="Hapus baris">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;
                topikTableBody.appendChild(row);

                // Sync customRowCounter
                const match = impl.kode_topik.match(/^CUSTOM_(\d+)$/);
                if (match) {
                    const num = parseInt(match[1], 10);
                    if (num > customRowCounter) customRowCounter = num;
                }
            }

            if (!row) return;

            // Populate Poli/Unit if it's an input field
            const poliInput = row.querySelector('.poli-input');
            if (poliInput && impl.poli_unit) {
                poliInput.value = impl.poli_unit;
            }

            // Populate (sebutkan) input if it exists
            const sebutkanInput = row.querySelector('.topik-sebutkan-input');
            if (impl.is_custom) {
                const topikInput = row.querySelector('.topik-input');
                if (topikInput && sebutkanInput && impl.nama_topik) {
                    let full = impl.nama_topik;
                    if (full.endsWith(')')) {
                        const lastOpen = full.lastIndexOf('(');
                        if (lastOpen !== -1) {
                            topikInput.value = full.substring(0, lastOpen).trim();
                            sebutkanInput.value = full.substring(lastOpen + 1, full.length - 1).trim();
                        } else {
                            topikInput.value = full;
                        }
                    } else {
                        topikInput.value = full;
                    }
                }
            } else {
                if (sebutkanInput && impl.nama_topik) {
                    const topikCell = row.querySelector('.topik-cell');
                    const originalTopik = topikCell ? (topikCell.dataset.originalTopik || '') : '';
                    const prefix = originalTopik.replace(/\([sS]ebutkan\)/, '').trim();
                    if (prefix && impl.nama_topik.startsWith(prefix)) {
                        let detail = impl.nama_topik.substring(prefix.length).trim();
                        if (detail.startsWith('(') && detail.endsWith(')')) {
                            detail = detail.substring(1, detail.length - 1).trim();
                        }
                        sebutkanInput.value = detail;
                    }
                }
            }

            // Verifikasi inputs
            if (impl.verifikasi) {
                const verifCell = row.querySelector('.verif-cell');
                if (verifCell) {
                    if (Array.isArray(impl.verifikasi)) {
                        // Backward compatibility for old checkbox data
                        impl.verifikasi.forEach(val => {
                            const input = verifCell.querySelector(`input[data-verif="${val}"]`);
                            if (input) input.value = "1";
                        });
                    } else if (typeof impl.verifikasi === 'object') {
                        Object.keys(impl.verifikasi).forEach(key => {
                            const input = verifCell.querySelector(`input[data-verif="${key}"]`);
                            if (input) input.value = impl.verifikasi[key];
                        });
                    }
                }
            }

            // TTD Pasien
            if (impl.ttd_pasien) {
                const ttdBox = row.querySelector('[id^="ttd-pasien-"]');
                if (ttdBox) {
                    let img = ttdBox.querySelector('img');
                    const placeholder = ttdBox.querySelector('.ttd-placeholder');
                    if (!img) {
                        img = document.createElement('img');
                        img.style.cssText = 'max-width:100%;max-height:100%;object-fit:contain';
                        ttdBox.appendChild(img);
                    }
                    img.src = '/storage/' + impl.ttd_pasien;
                    img.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                    ttdBox.dataset.signatureData = 'existing';
                }
            }

            if (impl.nama_penerima_info) {
                const namaInput = row.querySelector('.nama-penerima-input');
                if (namaInput) namaInput.value = impl.nama_penerima_info;
            }

            // Tgl Reedukasi
            if (impl.tgl_reedukasi) {
                const dateInput = row.querySelector('input[type="date"]');
                if (dateInput) dateInput.value = impl.tgl_reedukasi;
            }

            // Tgl/Jam Edukasi (datetime-local)
            if (impl.created_at) {
                const dtStart = row.querySelector('.input-start');
                if (dtStart) {
                    let dtStr = String(impl.created_at).replace(' ', 'T');
                    dtStart.value = dtStr.substring(0, 16);
                }
            }
            if (impl.tgl_akhir_edukasi) {
                const dtInputEnd = row.querySelector('.input-end');
                if (dtInputEnd) {
                    let dtStr = String(impl.tgl_akhir_edukasi).replace(' ', 'T');
                    dtInputEnd.value = dtStr.substring(0, 16);
                }
            }
        });
    }

    function resetAssessmentForm() {
        currentAssessmentId = null;
        // Text fields
        document.getElementById('field-nama-penerima').value = '';
        document.getElementById('field-hubungan-pasien').value = '';
        document.getElementById('field-nama-wali-ttd').value = '';

        // Uncheck all checkboxes & radios in sections A & B
        document.querySelectorAll(
            'input[name="bahasa[]"], input[name="hambatan_edukasi[]"], input[name="rencana_kebutuhan[]"]').forEach(
            cb => cb.checked = false);
        ['perlu_penerjemah', 'baca_dan_tulis', 'pendidikan', 'nilai_budaya', 'gaya_pembelajaran', 'literasi_kesehatan',
            'kesediaan_menerima'
        ].forEach(name => {
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = false);
        });

        // Hide & clear "lainnya" fields
        ['bahasa-lainnya', 'pendidikan-lainnya', 'hambatan-lainnya', 'rencana-lainnya'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.value = '';
                el.style.display = 'none';
            }
        });

        // Reset signature
        const sigBox = document.getElementById('konfirmasi-signature-box');
        if (sigBox) {
            const img = sigBox.querySelector('img');
            const placeholder = sigBox.querySelector('.signature-placeholder');
            if (img) img.style.display = 'none';
            if (placeholder) placeholder.style.display = 'flex';
            sigBox.style.borderStyle = '';
            sigBox.style.borderColor = '';
            delete sigBox.dataset.signatureData;
        }

        // --- Reset Section C (Implementation Rows) ---
        // Remove custom rows
        document.querySelectorAll('#topik-table-body tr[data-is-custom="true"]').forEach(row => row.remove());

        // Reset default rows
        const defaultRows = document.querySelectorAll('#topik-table-body tr');
        const nowDt = getNowDatetime();
        defaultRows.forEach(row => {
            // Reset verifikasi inputs
            row.querySelectorAll('.verif-cell input[type="text"]').forEach(input => input.value = '');

            // Reset datetime-local value to today's date-time
            const dtStart = row.querySelector('.input-start');
            if (dtStart) dtStart.value = nowDt;
            const dtInputEnd = row.querySelector('.input-end');
            if (dtInputEnd) dtInputEnd.value = '';

            // Reset ttd-pasien
            const ttdPasienBox = row.querySelector('[id^="ttd-pasien-"]');
            if (ttdPasienBox) {
                const img = ttdPasienBox.querySelector('img');
                const placeholder = ttdPasienBox.querySelector('.ttd-placeholder');
                if (img) img.style.display = 'none';
                if (placeholder) placeholder.style.display = 'flex';
                delete ttdPasienBox.dataset.signatureData;
            }

            // Reset nama penerima per baris
            const namaInput = row.querySelector('.nama-penerima-input');
            if (namaInput) namaInput.value = '';

            // Reset tgl reedukasi date input
            const dateInput = row.querySelector('input[type="date"]');
            if (dateInput) dateInput.value = '';

            // Reset (sebutkan) input if exists
            const sebutkanInput = row.querySelector('.topik-sebutkan-input');
            if (sebutkanInput) sebutkanInput.value = '';
        });
    }

    // ══════════ Lain-lain toggles ══════════
    document.querySelectorAll('[data-toggle-other]').forEach(cb => {
        const target = document.getElementById(cb.dataset.toggleOther);
        if (target) {
            cb.addEventListener('change', () => {
                target.style.display = cb.checked ? 'block' : 'none'
            });
            target.style.display = cb.checked ? 'block' : 'none';
        }
    });
    document.querySelectorAll('[data-toggle-other-radio]').forEach(radio => {
        const groupName = radio.name;
        const target = document.getElementById(radio.dataset.toggleOtherRadio);
        if (target) {
            document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                r.addEventListener('change', () => {
                    target.style.display = radio.checked ? 'block' : 'none'
                });
            });
        }
    });

    // ══════════ Signature Pad ══════════
    let activeSignatureTarget = null;
    const sigModal = document.getElementById('signature-modal');
    const sigCanvas = document.getElementById('signature-pad');
    const sigPad = new SignaturePad(sigCanvas, {
        backgroundColor: 'rgba(255,255,255,0)',
        penColor: '#1E293B'
    });

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
            sigCanvas.focus()
        }, 50);
    }

    sigPad.onBegin = () => {
        document.getElementById('signature-start-hint').style.display = 'none'
    };

    document.getElementById('close-signature-modal').addEventListener('click', () => {
        sigModal.style.display = 'none';
        document.body.style.overflow = ''
    });
    document.getElementById('clear-signature').addEventListener('click', () => {
        sigPad.clear();
        document.getElementById('signature-start-hint').style.display = 'flex'
    });

    document.getElementById('save-signature-modal').addEventListener('click', () => {
        if (sigPad.isEmpty()) return showError('Silakan tanda tangan terlebih dahulu.');
        const dataURL = sigPad.toDataURL('image/png');
        if (activeSignatureTarget) {
            const container = document.getElementById(activeSignatureTarget);
            if (container) {
                let img = container.querySelector('img');
                let placeholder = container.querySelector('.signature-placeholder,.ttd-placeholder');
                if (!img) {
                    img = document.createElement('img');
                    img.style.cssText = 'max-width:100%;max-height:100%;object-fit:contain';
                    container.appendChild(img)
                }
                img.src = dataURL;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
                container.style.borderStyle = 'solid';
                container.style.borderColor = 'var(--primary)';
                container.dataset.signatureData = dataURL;

                // Uncheck copy-ttd-cb if manually signed
                const cb = document.querySelector(`.copy-ttd-cb[data-target="${activeSignatureTarget}"]`);
                if (cb) cb.checked = false;
            }
        }
        sigModal.style.display = 'none';
        document.body.style.overflow = '';
    });

    // Konfirmasi signature click
    const konfSigBox = document.getElementById('konfirmasi-signature-box');
    if (konfSigBox) konfSigBox.addEventListener('click', () => openSignatureModal('konfirmasi-signature-box'));

    // ══════════ Dynamic Topik Rows ══════════
    let customRowCounter = 0;
    const topikTableBody = document.getElementById('topik-table-body');

    function getNowDatetime() {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        return `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
    }

    document.getElementById('btn-add-topik').addEventListener('click', () => {
        customRowCounter++;
        const noUrut = topikTableBody.children.length + 1;
        const kode = 'CUSTOM_' + customRowCounter;
        const nowDt = getNowDatetime();
        const row = document.createElement('tr');
        row.dataset.kode = kode;
        row.dataset.isCustom = 'true';
        row.innerHTML = `
        <td><input type="text" class="poli-input" placeholder="Poli/Unit" value=""></td>
        <td class="topik-cell">
            <input type="text" class="form-control topik-input" style="font-size:12px;padding:6px 8px" placeholder="Tulis topik edukasi..." value="">
            <textarea class="topik-sebutkan-input form-control" style="font-size:11px;padding:4px 6px;margin-top:4px;width:100%;border-radius:4px;resize:vertical;min-height:50px;" placeholder="Sebutkan detail..."></textarea>
        </td>
        <td>
            <div style="margin-bottom:6px">
                <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:2px">Mulai:</label>
                <input type="datetime-local" class="form-control input-start"
                    style="font-size:11px;padding:4px 6px" value="${nowDt}">
            </div>
            <div>
                <label style="font-size:10px;color:var(--text-muted);display:block;margin-bottom:2px">Selesai:</label>
                <input type="datetime-local" class="form-control input-end"
                    style="font-size:11px;padding:4px 6px">
            </div>
        </td>
        <td class="verif-cell">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Tujuan</label>
                <input type="text" maxlength="1" data-verif="Tujuan" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Metode</label>
                <input type="text" maxlength="1" data-verif="Metode" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Materi</label>
                <input type="text" maxlength="1" data-verif="Materi" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <label style="font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text-secondary);margin:0;">Evaluasi</label>
                <input type="text" maxlength="1" data-verif="Evaluasi" oninput="this.value=this.value.replace(/[^1-4]/g,'')" style="width:24px;height:20px;text-align:center;font-size:11px;border:1px solid #94a3b8;border-radius:4px;outline:none;background-color:#ffffff;" placeholder="">
            </div>
        </td>
        <td class="ttd-cell"><div class="ttd-mini" id="ttd-pasien-${kode}" onclick="openSignatureModal('ttd-pasien-${kode}')"><span class="ttd-placeholder" style="font-size:9px;color:var(--text-muted)">TTD</span></div></td>
        <td><input type="date" class="form-control" style="font-size:11px;padding:6px 8px"></td>
        <td style="text-align:center;white-space:nowrap">
            <button class="btn-icon btn-icon-save" title="Simpan baris" style="margin-right:4px">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            </button>
            <button class="btn-icon btn-icon-delete" onclick="this.closest('tr').remove()" title="Hapus baris">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    `;
        topikTableBody.appendChild(row);
        row.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    });

    // ══════════ Simpan Data Asesmen Edukasi ══════════
    document.getElementById('btn-save-edukasi').addEventListener('click', async () => {
        const noRawat = document.getElementById('field-no-rawat').value;
        if (!noRawat) return showError('Silakan pilih No. Rawat terlebih dahulu.');

        const namaPenerima = document.getElementById('field-nama-penerima').value;
        if (!namaPenerima) return showError('Nama penerima informasi harus diisi.');

        // Signature
        const sigBox = document.getElementById('konfirmasi-signature-box');
        const signatureData = sigBox ? sigBox.dataset.signatureData : null;
        if (!signatureData) return showError('Tanda tangan pasien/wali harus diisi.');

        // Collect checkboxes helper
        const getChecked = name => Array.from(document.querySelectorAll(`input[name="${name}"]:checked`))
            .map(cb => cb.value);
        // Collect radio helper
        const getRadio = name => {
            const r = document.querySelector(`input[name="${name}"]:checked`);
            return r ? r.value : null;
        };

        const payload = {
            no_rawat: noRawat,
            nama_penerima_info: namaPenerima,
            hubungan_dgn_pasien: document.getElementById('field-hubungan-pasien').value || null,
            // Section A
            bahasa: getChecked('bahasa[]'),
            bahasa_lainnya: document.getElementById('bahasa-lainnya').value || null,
            perlu_penerjemah: getRadio('perlu_penerjemah'),
            baca_dan_tulis: getRadio('baca_dan_tulis'),
            pendidikan: getRadio('pendidikan'),
            pendidikan_lainnya: document.getElementById('pendidikan-lainnya').value || null,
            nilai_budaya: getRadio('nilai_budaya'),
            gaya_pembelajaran: getRadio('gaya_pembelajaran'),
            literasi_kesehatan: getRadio('literasi_kesehatan'),
            hambatan_edukasi: getChecked('hambatan_edukasi[]'),
            hambatan_lainnya: document.getElementById('hambatan-lainnya').value || null,
            kesediaan_menerima: getRadio('kesediaan_menerima'),
            // Section B
            rencana_kebutuhan: getChecked('rencana_kebutuhan[]'),
            rencana_lainnya: document.getElementById('rencana-lainnya').value || null,
            // Konfirmasi
            tanggal_edukasi: document.getElementById('field-tgl-edukasi').value || null,
            nama_pasien_wali_ttd: document.getElementById('field-nama-wali-ttd').value || null,
            signature: signatureData === 'existing' ? null : signatureData,
        };

        const btn = document.getElementById('btn-save-edukasi');
        btn.disabled = true;
        btn.innerHTML =
            '<svg class="animate-spin" style="width:18px;height:18px" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity=".25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" opacity=".75"></path></svg> Menyimpan...';

        try {
            const res = await fetch('/edukasi-pasien/save-assessment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });
            const result = await res.json();
            if (res.ok && result.success) {
                showSuccess(result.message);
            } else {
                showError(result.error || 'Terjadi kesalahan saat menyimpan.');
            }
        } catch (e) {
            console.error(e);
            showError('Gagal menghubungi server.');
        } finally {
            btn.disabled = false;
            btn.innerHTML =
                '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data Edukasi';
        }
    });

    // ══════════ Simpan Per-Baris Pelaksanaan Edukasi (Section C) ══════════
    async function saveImplementationRow(btn) {
        const row = btn.closest('tr');
        if (!row) return;

        const noRawat = document.getElementById('field-no-rawat').value;
        if (!noRawat) return showError('Pilih No. Rawat terlebih dahulu.');

        const kode = row.dataset.kode;
        const isCustom = row.dataset.isCustom === 'true';

        // Poli/Unit: badge text or input
        const badgeEl = row.querySelector('.badge');
        const poliInput = row.querySelector('.poli-input');
        const poliUnit = badgeEl ? badgeEl.textContent.trim() : (poliInput ? poliInput.value : '');

        // Topik: text content or input (custom rows have input)
        const topikCell = row.querySelector('.topik-cell');
        let namaTopik = '';
        if (isCustom) {
            const topikInput = topikCell ? topikCell.querySelector('.topik-input') : null;
            const sebutkanInput = topikCell ? topikCell.querySelector('.topik-sebutkan-input') : null;
            const t = topikInput ? topikInput.value.trim() : '';
            const d = sebutkanInput ? sebutkanInput.value.trim() : '';
            if (t && d) {
                namaTopik = `${t} (${d})`;
            } else if (t) {
                namaTopik = t;
            }
        } else {
            const originalTopik = topikCell ? (topikCell.dataset.originalTopik || '') : '';
            const sebutkanInput = topikCell ? topikCell.querySelector('.topik-sebutkan-input') : null;
            const cleanOriginal = originalTopik.replace(/\([sS]ebutkan\)/, '').trim();
            if (sebutkanInput) {
                const detailVal = sebutkanInput.value.trim();
                if (detailVal) {
                    namaTopik = `${cleanOriginal} (${detailVal})`;
                } else {
                    namaTopik = cleanOriginal;
                }
            } else {
                namaTopik = cleanOriginal;
            }
        }
        if (!namaTopik) return showError('Topik edukasi tidak boleh kosong.');

        // Datetime
        const dtInputStart = row.querySelector('.input-start');
        const dtInputEnd = row.querySelector('.input-end');
        const tglMulai = dtInputStart ? dtInputStart.value : null;
        const tglSelesai = dtInputEnd ? dtInputEnd.value : null;

        // Verifikasi inputs
        const verifCell = row.querySelector('.verif-cell');
        let verifikasi = null;
        if (verifCell) {
            const obj = {};
            verifCell.querySelectorAll('input[type="text"]').forEach(input => {
                if (input.value.trim() !== '') {
                    obj[input.dataset.verif] = input.value.trim();
                }
            });
            if (Object.keys(obj).length > 0) verifikasi = obj;
        }

        // TTD Pasien & Nama
        const ttdPasienBox = row.querySelector('[id^="ttd-pasien-"]');
        const ttdPasienData = ttdPasienBox && ttdPasienBox.dataset.signatureData ? ttdPasienBox.dataset
            .signatureData : null;
            
        const namaInput = row.querySelector('.nama-penerima-input');
        const namaPenerimaInfo = namaInput ? namaInput.value.trim() : null;

        // Tgl Reedukasi
        const reEdukInput = row.querySelector('input[type="date"]');
        const tglReedukasi = reEdukInput ? reEdukInput.value : null;

        // Row index
        const noUrut = Array.from(topikTableBody.children).indexOf(row) + 1;

        const payload = {
            no_rawat: noRawat,
            kode_topik: kode,
            nama_topik: namaTopik,
            poli_unit: poliUnit || null,
            no_urut: noUrut,
            is_custom: isCustom,
            verifikasi: verifikasi,
            ttd_pasien: (ttdPasienData && ttdPasienData !== 'existing') ? ttdPasienData : null,
            nama_penerima_info: namaPenerimaInfo || null,
            tgl_reedukasi: tglReedukasi || null,
            tgl_edukasi: tglMulai || null,
            tgl_akhir_edukasi: tglSelesai || null,
        };

        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML =
            '<svg class="animate-spin" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity=".25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" opacity=".75"></path></svg>';

        try {
            const res = await fetch('/edukasi-pasien/save-implementation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });
            const result = await res.json();
            if (res.ok && result.success) {
                showSuccess(result.message);
                // Brief green flash on saved row
                row.style.background = 'rgba(34,197,94,0.1)';
                setTimeout(() => row.style.background = '', 1500);
            } else {
                showError(result.error || 'Gagal menyimpan baris.');
            }
        } catch (e) {
            console.error(e);
            showError('Gagal menghubungi server.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    }

    // Event delegation for save buttons (works for static & dynamic rows)
    document.getElementById('topik-table-body').addEventListener('click', (e) => {
        const saveBtn = e.target.closest('.btn-icon-save');
        if (saveBtn) {
            e.preventDefault();
            saveImplementationRow(saveBtn);
        }
    });

    // Event delegation for Copy TTD B checkbox
    document.getElementById('topik-table-body').addEventListener('change', (e) => {
        if (e.target.classList.contains('copy-ttd-cb')) {
            const targetId = e.target.dataset.target;
            const container = document.getElementById(targetId);
            if (!container) return;

            if (e.target.checked) {
                const sigBoxB = document.getElementById('konfirmasi-signature-box');
                let dataToCopy = null;
                let imgB = null;

                if (sigBoxB) {
                    if (sigBoxB.dataset.signatureData && sigBoxB.dataset.signatureData !== 'existing') {
                        dataToCopy = sigBoxB.dataset.signatureData;
                    }
                    imgB = sigBoxB.querySelector('img');
                }

                if (!imgB || !imgB.src) {
                    e.target.checked = false;
                    showError('Belum ada tanda tangan di Bagian B untuk disalin.');
                    return;
                }

                let finalData = dataToCopy;
                if (!finalData) {
                    if (imgB.src.startsWith('data:image')) {
                        finalData = imgB.src;
                    } else {
                        try {
                            finalData = new URL(imgB.src).pathname;
                        } catch (e) {
                            finalData = imgB.src;
                        }
                    }
                }

                let img = container.querySelector('img');
                let placeholder = container.querySelector('.ttd-placeholder');
                if (!img) {
                    img = document.createElement('img');
                    img.style.cssText = 'max-width:100%;max-height:100%;object-fit:contain';
                    container.appendChild(img)
                }
                img.src = imgB.src;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
                container.style.borderStyle = 'solid';
                container.style.borderColor = 'var(--primary)';
                container.dataset.signatureData = finalData;
            } else {
                const img = container.querySelector('img');
                const placeholder = container.querySelector('.ttd-placeholder');
                if (img) img.style.display = 'none';
                if (placeholder) placeholder.style.display = 'flex';
                container.style.borderStyle = '';
                container.style.borderColor = '';
                delete container.dataset.signatureData;
            }
        }
    });
</script>
