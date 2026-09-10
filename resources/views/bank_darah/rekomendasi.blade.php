<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karsa ERM | Rekomendasi Dokter</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #DC2626;
            --primary-dark: #B91C1C;
            --primary-light: rgba(220, 38, 38, 0.08);
            --primary-glow: rgba(220, 38, 38, 0.15);
            --primary-surface: #FEF2F2;
            --bg-main: #F1F5F9;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-subtle: #94A3B8;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --success: #10B981;
            --error: #EF4444;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.03);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg-main);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(255,255,255,0.98);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; height: 100vh; z-index: 50;
            transition: transform 0.3s ease;
        }
        .logo-section { padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .logo-box {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 16px;
            box-shadow: 0 4px 8px var(--primary-glow);
        }
        .logo-text { font-weight: 700; color: var(--text-main); font-size: 18px; letter-spacing: -0.5px; }
        .nav-section { padding: 24px 16px; flex-grow: 1; }
        .nav-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 16px; margin-left: 8px; letter-spacing: 0.05em; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
        .nav-item:hover { background: var(--primary-surface); color: var(--text-main); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item svg { width: 20px; height: 20px; }
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 28px 32px; max-width: calc(100vw - var(--sidebar-width)); }

        header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .header-title h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.3px; color: var(--text-main); }
        .header-title p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-subtle); margin-bottom: 6px; }
        .breadcrumb i { font-size: 10px; }
        .breadcrumb span { color: var(--text-muted); }

        .user-profile { display: flex; align-items: center; gap: 10px; padding: 6px 14px 6px 6px; background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
        .avatar { width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: white; }
        .user-info span { font-size: 13px; font-weight: 500; display: block; }

        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; gap: 10px; }
        .card-header i { color: var(--primary); font-size: 16px; }
        .card-header h3 { font-size: 14px; font-weight: 600; color: var(--text-main); }

        .card-body { padding: 24px; }

        .form-section {
            margin-bottom: 22px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 20px 22px;
            box-shadow: var(--shadow-sm);
        }
        .form-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-light);
        }
        .step-badge {
            width: 30px; height: 30px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800; flex-shrink: 0;
            box-shadow: 0 2px 6px var(--primary-glow);
        }
        .step-title {
            font-size: 14px; font-weight: 700; color: var(--primary);
            text-transform: uppercase; letter-spacing: 0.04em;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid .full-width { grid-column: 1 / -1; }

        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-group label { font-size: 12px; font-weight: 600; color: var(--text-muted); }
        .form-group input, .form-group select, .form-group textarea {
            padding: 9px 12px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-family: inherit; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--card-bg); color: var(--text-main);
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .form-group textarea { resize: vertical; min-height: 60px; }

        .search-box { margin-bottom: 4px; }
        .search-input-group { display: flex; gap: 8px; align-items: flex-end; }
        .search-input-group .form-group { margin-bottom: 0; }
        .btn-search {
            padding: 9px 20px; background: var(--primary); color: white; border: none;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; font-family: inherit;
            cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px;
            white-space: nowrap; height: 39px;
        }
        .btn-search:hover { background: var(--primary-dark); }
        .search-results {
            margin-top: 8px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            max-height: 220px; overflow-y: auto; background: var(--card-bg);
        }
        .search-results table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .search-results th { background: var(--primary-surface); color: var(--primary); font-weight: 600; padding: 8px 10px; text-align: left; border-bottom: 1px solid var(--border); position: sticky; top: 0; }
        .search-results td { padding: 7px 10px; border-bottom: 1px solid var(--border-light); cursor: pointer; }
        .search-results tr:hover td { background: var(--primary-surface); }
        .search-results tr.selected td { background: #FECACA; }

        .statement-box {
            background: var(--primary-surface);
            border: 1px solid #FECACA;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .statement-box p { font-size: 13px; line-height: 1.6; color: var(--text-main); text-align: justify; }

        .action-bar {
            display: flex; gap: 12px; justify-content: flex-end;
            padding-top: 20px; border-top: 1px solid var(--border-light); margin-top: 20px;
        }
        .btn-primary {
            padding: 10px 24px; background: var(--primary); color: white; border: none;
            border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; font-family: inherit;
            cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-secondary {
            padding: 10px 24px; background: transparent; color: var(--text-muted);
            border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500; font-family: inherit; cursor: pointer; transition: all 0.2s;
        }
        .btn-secondary:hover { background: var(--bg-main); }

        .toast {
            position: fixed; bottom: 24px; right: 24px; padding: 10px 18px;
            background: var(--text-main); color: white; border-radius: var(--radius-sm);
            font-size: 12px; font-weight: 500; opacity: 0; transform: translateY(10px);
            transition: all 0.3s ease; z-index: 999; pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateY(0); }
        .toast.success { background: var(--success); }
        .toast.error { background: var(--error); }

        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); margin-top: auto; }
        .btn-logout { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; width: 100%; background: #FEF2F2; color: #EF4444; border: 1px solid #FEE2E2; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; font-family: inherit; }
        .btn-logout:hover { background: #FEE2E2; border-color: #FECACA; }

        @media (max-width: 1024px) {
            :root { --sidebar-width: 280px; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; max-width: 100vw; }
            .form-grid { grid-template-columns: 1fr; }
            .mobile-header { display: flex; position: fixed; top: 0; left: 0; right: 0; height: 64px; background: rgba(255,255,255,0.98); border-bottom: 1px solid var(--border); align-items: center; padding: 0 20px; z-index: 40; justify-content: space-between; }
            .hamburger { cursor: pointer; padding: 8px; border-radius: var(--radius-sm); background: var(--bg-main); border: none; color: var(--text-main); }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index: 45; }
            .sidebar-overlay.active { display: block; }
            header { flex-direction: column; align-items: flex-start; gap: 16px; }
            .user-profile { width: 100%; justify-content: center; }
        }
        @media (min-width: 1025px) { .mobile-header { display: none; } }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        <header>
            <div class="header-title">
                <div class="breadcrumb">
                    <i class="fas fa-home"></i> Dashboard <i class="fas fa-chevron-right"></i>
                    <span>Bank Darah</span> <i class="fas fa-chevron-right"></i>
                    <span>Rekomendasi Dokter</span>
                </div>
                <h1>Rekomendasi Dokter</h1>
                <p>Formulir rekomendasi permintaan darah untuk transfusi.</p>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ substr(Session::get('user_id'), 0, 1) }}</div>
                <div class="user-info"><span>{{ Session::get('user_id') }}</span></div>
            </div>
        </header>

        <form id="form-rekomendasi" method="POST">
            @csrf

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-file-medical"></i>
                    <h3>Formulir Rekomendasi Dokter</h3>
                </div>
                <div class="card-body">
                    <div class="statement-box">
                        <p>
                            Berdasarkan hasil pemeriksaan uji silang serasi yang dikirimkan
                            <strong>BDRS Karsa Husada Batu</strong> pada tanggal
                        </p>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <span class="step-badge">1</span>
                            <span class="step-title">Data Uji & Darah</span>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="tgl_uji">Tanggal Uji Silang Serasi</label>
                                <input type="date" id="tgl_uji" name="tgl_uji" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label for="bentuk_darah">Bentuk / Komponen Darah</label>
                                <select id="bentuk_darah" name="bentuk_darah" required>
                                    <option value="">Pilih Komponen Darah...</option>
                                    <option value="Whole Blood">Whole Blood (WB)</option>
                                    <option value="Packed Red Cells">Packed Red Cells (PRC)</option>
                                    <option value="Trombocyte Concentrate">Trombocyte Concentrate (TC)</option>
                                    <option value="Fresh Frozen Plasma">Fresh Frozen Plasma (FFP)</option>
                                    <option value="Cryoprecipitate">Cryoprecipitate</option>
                                    <option value="Washed Red Cells">Washed Red Cells (WRC)</option>
                                    <option value="Leukocyte Reduced">Leukocyte Reduced</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gol_darah_permintaan">Golongan Darah</label>
                                <select id="gol_darah_permintaan" name="gol_darah_permintaan" required>
                                    <option value="">Pilih Golongan Darah...</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <span class="step-badge">2</span>
                            <span class="step-title">Data Pasien</span>
                        </div>
                        <div class="search-box">
                            <div class="search-input-group">
                                <div class="form-group" style="flex:1">
                                    <label for="no_rkm_medis">No. Rekam Medis</label>
                                    <input type="text" id="no_rkm_medis" name="no_rkm_medis" placeholder="Ketik no RM / nama / no rawat">
                                </div>
                                <button type="button" id="btn-cari" class="btn-search">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                            <div id="search-results" class="search-results" style="display:none"></div>
                        </div>
                        <div class="form-grid" style="margin-top:16px">
                            <div class="form-group full-width">
                                <label for="nama_pasien">Nama Pasien</label>
                                <input type="text" id="nama_pasien" name="nama_pasien" required placeholder="Nama lengkap pasien">
                            </div>
                            <div class="form-group">
                                <label for="no_rawat">No. Rawat</label>
                                <input type="text" id="no_rawat" name="no_rawat" placeholder="Nomor rawat">
                            </div>
                            <div class="form-group">
                                <label for="umur_pasien">Umur</label>
                                <input type="text" id="umur_pasien" name="umur_pasien" placeholder="Tahun / Tanggal lahir">
                            </div>
                            <div class="form-group">
                                <label for="gol_darah_pasien">Golongan Darah Pasien</label>
                                <select id="gol_darah_pasien" name="gol_darah_pasien">
                                    <option value="">Pilih...</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="alamat_pasien">Alamat</label>
                                <textarea id="alamat_pasien" name="alamat_pasien" placeholder="Alamat lengkap pasien"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="rumah_sakit">Rumah Sakit</label>
                                <input type="text" id="rumah_sakit" name="rumah_sakit" value="RSUD Karsa Husada Batu">
                            </div>
                            <div class="form-group">
                                <label for="ruang_bagian">Ruang / Bagian</label>
                                <select id="ruang_bagian" name="ruang_bagian" style="width:100%;">
                                    <option value="">Pilih Ruang / Bagian...</option>
                                    @foreach($ruangList as $r)
                                        <option value="{{ $r }}">{{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="no_reg_rm">No. Reg / RM</label>
                                <input type="text" id="no_reg_rm" name="no_reg_rm" placeholder="Nomor registrasi / rekam medis">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <span class="step-badge">3</span>
                            <span class="step-title">Dokter & Tanggal</span>
                        </div>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="nama_dokter">Dokter yang Merawat</label>
                                <select id="nama_dokter" name="nama_dokter" required style="width:100%;">
                                    <option value="">Pilih Dokter...</option>
                                    @foreach($dokterList as $d)
                                        <option value="{{ $d->nm_dokter }}" data-kd="{{ $d->kd_dokter }}">{{ $d->nm_dokter }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="kd_dokter" name="kd_dokter">
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="action-bar">
                        <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('bank-darah.history') }}'">
                            <i class="fas fa-history"></i> Riwayat
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Simpan & Cetak
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="toast" id="toast"></div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function showToast(msg, type) {
            var t = $('#toast');
            t.text(msg).removeClass('success error show');
            if (type) t.addClass(type);
            t.addClass('show');
            clearTimeout(t.data('timer'));
            t.data('timer', setTimeout(function () { t.removeClass('show'); }, 3000));
        }

        function fillPatient(row) {
            $('#no_rkm_medis').val(row.no_rkm_medis);
            $('#no_rawat').val(row.no_rawat);
            $('#nama_pasien').val(row.nm_pasien);
            $('#umur_pasien').val(row.umur);
            $('#alamat_pasien').val(row.alamat);
            if (row.gol_darah && row.gol_darah !== '-') {
                $('#gol_darah_pasien').val(row.gol_darah).trigger('change');
            }
            if (row.ruang) {
                var ruangName = row.ruang.split(' - ')[0];
                var ruangOpt = $('#ruang_bagian').find('option[value="' + ruangName.replace(/'/g, "\\'") + '"]');
                if (ruangOpt.length) {
                    $('#ruang_bagian').val(ruangName).trigger('change');
                } else {
                    var newOpt = new Option(ruangName, ruangName, true, true);
                    $('#ruang_bagian').append(newOpt).trigger('change');
                }
            }
            $('#no_reg_rm').val(row.no_rawat);
            if (row.kd_dokter && row.nm_dokter && row.nm_dokter !== '-') {
                var exists = $('#nama_dokter').find('option[value="' + row.nm_dokter.replace(/'/g, "\\'") + '"]').length > 0;
                if (!exists) {
                    var opt = new Option(row.nm_dokter, row.nm_dokter, true, true);
                    opt.dataset.kd = row.kd_dokter;
                    $('#nama_dokter').append(opt);
                }
                $('#nama_dokter').val(row.nm_dokter).trigger('change');
                $('#kd_dokter').val(row.kd_dokter);
            }
            $('#search-results').hide().html('');
        }

        $(document).ready(function () {
            $('#bentuk_darah, #gol_darah_permintaan, #gol_darah_pasien, #nama_dokter, #ruang_bagian').select2({
                width: '100%',
                placeholder: function() { return $(this).find('option:first').text(); }
            });

            $('#nama_dokter').on('change', function () {
                var selected = $(this).find(':selected');
                $('#kd_dokter').val(selected.data('kd') || '');
            });

            $('#btn-cari').on('click', function () {
                var keyword = $('#no_rkm_medis').val().trim();
                if (keyword.length < 2) {
                    showToast('Masukkan minimal 2 karakter untuk mencari', 'error');
                    return;
                }

                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mencari...');

                $.ajax({
                    url: '{{ route("bank-darah.cari-pasien") }}',
                    method: 'GET',
                    data: { keyword: keyword },
                    success: function (res) {
                        btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari');
                        if (!res.success || !res.data.length) {
                            $('#search-results').html('<div style="padding:12px;text-align:center;color:var(--text-muted);font-size:13px">Data pasien tidak ditemukan</div>').show();
                            return;
                        }
                        var html = '<table><thead><tr><th>No. RM</th><th>Nama Pasien</th><th>No. Rawat</th><th>Ruang</th><th>Dokter</th></tr></thead><tbody>';
                        $.each(res.data, function (i, r) {
                            html += '<tr data-index="' + i + '">';
                            html += '<td>' + r.no_rkm_medis + '</td>';
                            html += '<td><strong>' + r.nm_pasien + '</strong></td>';
                            html += '<td>' + (r.no_rawat || '-') + '</td>';
                            html += '<td>' + (r.ruang || '-') + '</td>';
                            html += '<td>' + (r.nm_dokter && r.nm_dokter !== '-' ? r.nm_dokter : '-') + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        var $results = $('#search-results').html(html).show();
                        $results.find('tr[data-index]').on('click', function () {
                            var idx = $(this).data('index');
                            fillPatient(res.data[idx]);
                            showToast('Data pasien berhasil dipilih', 'success');
                        });
                    },
                    error: function () {
                        btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari');
                        showToast('Gagal mencari data pasien', 'error');
                    }
                });
            });

            $('#no_rkm_medis').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#btn-cari').click();
                }
            });

            $('#form-rekomendasi').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: '{{ route("bank-darah.store") }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function (res) {
                        showToast(res.message, 'success');
                        setTimeout(function () {
                            window.location.href = '{{ route("bank-darah.download", ":id") }}'.replace(':id', res.id);
                        }, 1000);
                    },
                    error: function (xhr) {
                        var msg = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showToast(msg, 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan & Cetak');
                    }
                });
            });
        });
    </script>
</body>
</html>
