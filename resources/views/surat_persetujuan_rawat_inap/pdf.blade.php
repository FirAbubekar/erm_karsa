<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Persetujuan Rawat Inap (RM 02)</title>
    <style>
        @page {
            margin: 8mm 10mm 8mm 10mm;
            size: A4;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.2pt;
            line-height: 1.25;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ─── Header ─── */
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; padding: 0; }
        .header-logo { width: 60px; text-align: left; }
        .header-logo img { width: 55px; height: auto; }
        .header-center { text-align: center; padding: 0 5px; }
        .header-center .gov { font-size: 10.5pt; font-weight: bold; }
        .header-center .hospital { font-size: 12.5pt; font-weight: bold; margin-top: 1px; }
        .header-center .accreditation { font-size: 8.5pt; font-weight: bold; margin-top: 1px; text-transform: uppercase; letter-spacing: 0.5px; }
        .header-center .stars { color: #FFD700; font-size: 10pt; letter-spacing: 1px; line-height: 1; margin: 1px 0; }
        .header-center .address { font-size: 7.5pt; margin-top: 1px; }
        .header-center .email { font-size: 7.5pt; }
        .header-right-logo { width: 60px; text-align: right; }
        .header-right-logo img { width: 50px; height: auto; }
        
        .doc-code { text-align: right; font-size: 9pt; font-weight: bold; margin-bottom: 2px; }
        
        /* Premium Double Border for Kop Surat */
        .header-line-container {
            border-top: 2.5px solid #000;
            border-bottom: 0.8px solid #000;
            height: 1.5px;
            margin-top: 3px;
            margin-bottom: 5px;
        }

        /* ─── Outer Container ─── */
        .form-outer-box {
            border: 1.5px solid #000;
            width: 100%;
        }

        /* ─── Inner Sections ─── */
        .section-divider {
            border-bottom: 1.2px solid #000;
        }

        /* Title Box */
        .title-box {
            background-color: #dfdfdf;
            text-align: center;
            padding: 5px 0;
        }
        .title-box .main-title {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .title-box .sub-title {
            font-size: 8pt;
            font-style: italic;
        }

        /* Form Details */
        .padded-section {
            padding: 5px 10px;
        }
        .section-header {
            font-size: 8.5pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
        }
        .form-table td {
            padding: 2.5px 2px;
            vertical-align: middle;
            font-size: 8.2pt;
        }
        .form-table .label {
            width: 110px;
        }
        .form-table .colon {
            width: 12px;
            text-align: center;
        }

        /* Underlined style */
        .solid-underline {
            border-bottom: 1px solid #000;
            font-weight: bold;
            padding-bottom: 0.5px;
        }

        /* Robust Premium HTML Checkbox */
        .checkbox-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            text-align: center;
            line-height: 10px;
            font-size: 8px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            vertical-align: middle;
            margin-right: 4px;
            background-color: #fff;
        }

        /* List styling */
        .statement-list {
            margin: 2px 0 2px 15px;
            padding: 0;
        }
        .statement-list li {
            text-align: justify;
            margin-bottom: 2.5px;
            font-size: 8.2pt;
            line-height: 1.25;
        }

        /* Highlight box */
        .highlight-box {
            background-color: #dfdfdf;
            text-align: center;
            font-weight: bold;
            font-style: italic;
            padding: 5px 10px;
            font-size: 8.2pt;
            text-decoration: underline;
        }

        /* RM Char Boxes */
        .rm-char-box {
            display: inline-block;
            border: 1px solid #000;
            width: 13px;
            height: 17px;
            line-height: 17px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            font-weight: bold;
            margin-right: 1px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    @php
        // Clean & Parse Penanggung Jawab Address
        $alamat = $consent->alamatpj ?? '';
        $rt = ''; $rw = ''; $kel = ''; $kec = ''; $kota = '';
        
        if (preg_match('/RT\s+(\d+)/i', $alamat, $matches)) {
            $rt = $matches[1];
        }
        if (preg_match('/RW\s+(\d+)/i', $alamat, $matches)) {
            $rw = $matches[1];
        }
        if (preg_match('/Kel\.\s+([^,]+)/i', $alamat, $matches)) {
            $kel = trim($matches[1]);
        }
        if (preg_match('/Kec\.\s+([^,]+)/i', $alamat, $matches)) {
            $kec = trim($matches[1]);
        }
        
        $parts = explode(',', $alamat);
        if (count($parts) >= 3) {
            $kota = trim(end($parts));
        } else {
            $kota = $alamat;
        }
        
        $jalan = '';
        if (preg_match('/^(.*?)(?=\bRT\b)/i', $alamat, $matches)) {
            $jalan = trim($matches[1]);
        } else {
            $jalan = $alamat;
        }

        // Clean & Parse Patient Address
        $patientRt = $consent->regPeriksa->pasien->rt ?? '';
        $patientRw = $consent->regPeriksa->pasien->rw ?? '';
        $patientKel = $consent->regPeriksa->pasien->nm_kel ?? $consent->regPeriksa->pasien->kelurahan->nm_kel ?? '-';
        $patientKec = $consent->regPeriksa->pasien->nm_kec ?? $consent->regPeriksa->pasien->kecamatan->nm_kec ?? '-';
        $patientKota = $consent->regPeriksa->pasien->nm_kab ?? $consent->regPeriksa->pasien->kabupaten->nm_kab ?? '-';
        $patientJalan = $consent->regPeriksa->pasien->alamat ?? '-';

        // Construct Patient Full Address in one clean line
        $alamatPasien = '';
        $pasien = $consent->regPeriksa->pasien ?? null;
        if ($pasien) {
            $alamatPasien = $pasien->alamat ?? '';
            if (!empty($patientRt) && $patientRt !== '-') {
                $alamatPasien .= ' RT ' . $patientRt;
            }
            if (!empty($patientRw) && $patientRw !== '-') {
                $alamatPasien .= ' RW ' . $patientRw;
            }
            if (!empty($patientKel) && $patientKel !== '-') {
                $alamatPasien .= ', Kel. ' . $patientKel;
            }
            if (!empty($patientKec) && $patientKec !== '-') {
                $alamatPasien .= ', Kec. ' . $patientKec;
            }
            if (!empty($patientKota) && $patientKota !== '-') {
                $alamatPasien .= ', ' . $patientKota;
            }
        }
        $alamatPasien = trim($alamatPasien) ?: '-';

        // Clean & Parse Penanggung Jawab Name, Age, and Gender if stored in Name(Age)(Gender) format
        $pjNamaJelas = $consent->nama_pj;
        $pjUmurParsed = '';
        $pjJkParsed = '';
        
        if (preg_match('/^([^\(]+)\((\d+)\)\(([LP])\)$/i', $consent->nama_pj, $matches)) {
            $pjNamaJelas = trim($matches[1]);
            $pjUmurParsed = $matches[2];
            $pjJkParsed = $matches[3];
        }

        // Parse Penanggung Jawab Hubungan
        $isDiri = false; $isIstri = false; $isAnak = false; $isWali = false; $isSuami = false; $isOrangTua = false;
        $waliValue = '';
        
        $hubLower = strtolower($consent->hubungan ?? '');
        if ($hubLower === 'diri saya' || $hubLower === 'diri sendiri') {
            $isDiri = true;
        } elseif ($hubLower === 'istri') {
            $isIstri = true;
        } elseif ($hubLower === 'anak') {
            $isAnak = true;
        } elseif ($hubLower === 'suami') {
            $isSuami = true;
        } elseif ($hubLower === 'ayah' || $hubLower === 'ibu' || $hubLower === 'orang tua') {
            $isOrangTua = true;
        } else {
            $isWali = true;
            $waliValue = $consent->hubungan;
        }

        // Deduce Penanggung Jawab Gender
        $pj_jk = '';
        if (str_contains($hubLower, 'suami') || str_contains($hubLower, 'ayah') || str_contains($hubLower, 'bapak')) {
            $pj_jk = 'L';
        } elseif (str_contains($hubLower, 'istri') || str_contains($hubLower, 'ibu')) {
            $pj_jk = 'P';
        }
        if (str_contains($hubLower, 'diri') || str_contains($hubLower, 'saya')) {
            $pj_jk = $consent->regPeriksa->pasien->jk ?? '';
        }

        // Clean & Parse Patient RM
        $noRM = $consent->regPeriksa->pasien->no_rkm_medis ?? '';
        $noRMClean = str_replace(['-', ' '], '', $noRM);
        $noRMClean = str_pad($noRMClean, 6, '0', STR_PAD_LEFT);
        $rmChars = str_split($noRMClean);

        // Parse Patient Age
        $ageYears = '-';
        $ageMonths = '-';
        if (isset($consent->regPeriksa->pasien->tgl_lahir)) {
            $birthDate = new \DateTime($consent->regPeriksa->pasien->tgl_lahir);
            $today = new \DateTime($consent->tanggal);
            $diff = $today->diff($birthDate);
            $ageYears = $diff->y;
            $ageMonths = $diff->m;
        }

        // Parse Hak Kelas Checkbox
        $isKelasI = false; $isKelasII = false; $isKelasIII = false;
        $hakLower = strtolower($consent->hak_kelas ?? '');
        if (str_contains($hakLower, '1') || str_contains($hakLower, 'i') && !str_contains($hakLower, 'ii')) {
            $isKelasI = true;
        }
        if (str_contains($hakLower, '2') || str_contains($hakLower, 'ii') && !str_contains($hakLower, 'iii')) {
            $isKelasII = true;
        }
        if (str_contains($hakLower, '3') || str_contains($hakLower, 'iii')) {
            $isKelasIII = true;
        }

        // Parse Penanggung Jawab Biaya
        $isUmum = false; $isJkn = false; $isAsuransi = false;
        $asuransiValue = '';
        
        $bayarLower = strtolower($consent->bayar_secara ?? '');
        if (str_contains($bayarLower, 'sendiri') || str_contains($bayarLower, 'umum')) {
            $isUmum = true;
        } elseif (str_contains($bayarLower, 'bpjs') || str_contains($bayarLower, 'jkn') || str_contains($bayarLower, 'kis')) {
            $isJkn = true;
        } else {
            $isAsuransi = true;
            $asuransiValue = $consent->bayar_secara;
        }
    @endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Document Header Code -->
    <div class="doc-code">RM. 001B/1-1/ Rev. 1</div>
    
    <!-- Hospital Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if(file_exists(public_path('images/logo-jatim.png')))
                    <img src="{{ public_path('images/logo-jatim.png') }}" alt="Logo Jatim">
                @else
                    <div style="width:55px; height:55px; border:1px solid #ccc; text-align:center; line-height:55px; font-size:7pt; color:#999;">Logo</div>
                @endif
            </td>
            <td class="header-center">
                <div class="gov">PEMERINTAH PROVINSI JAWA TIMUR</div>
                <div class="hospital">RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU</div>
                <div class="accreditation">TERAKREDITASI PARIPURNA VERSI STARKES</div>
                <div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                <div class="address">JL. A. Yani 10 – 13 Telp. (0341) 596898 – 591076 -591036 Fax. 596901 – 591076</div>
                <div class="email">Email : <a href="mailto:rsukhbatu@jatimprov.go.id" style="color:#000; text-decoration: none;">rsukhbatu@jatimprov.go.id</a></div>
            </td>
            <td class="header-right-logo">
                @if(file_exists(public_path('images/logo-rs.png')))
                    <img src="{{ public_path('images/logo-rs.png') }}" alt="Logo RS">
                @else
                    <div style="width:50px; height:50px; border:1px solid #ccc; text-align:center; line-height:50px; font-size:7pt; color:#999;">Logo</div>
                @endif
            </td>
        </tr>
    </table>
    
    <!-- Kop double borders -->
    <div class="header-line-container"></div>

    <!-- Outer Border Box -->
    <div class="form-outer-box">
        <!-- Title Box Section -->
        <div class="title-box section-divider">
            <span class="main-title">SURAT PERSETUJUAN RAWAT INAP</span><br>
            <span class="sub-title">(diisi oleh pasien / wali, beri tanda <span style="font-family: DejaVu Sans, sans-serif;">☑</span> pada kolom yang sesuai)</span>
        </div>

        <!-- First Section: Yang bertanda tangan dibawah ini -->
        <div class="padded-section section-divider">
            <div class="section-header">Yang bertanda tangan dibawah ini :</div>
            <table class="form-table">
                <tr>
                    <td class="label">Nama</td>
                    <td class="colon">:</td>
                    <td><span class="solid-underline" style="display: inline-block; width: 98%;">{{ $pjNamaJelas }}</span></td>
                </tr>
                <tr>
                    <td class="label">Umur</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 80px; text-align: left; padding-left: 5px;">{{ $pjUmurParsed ?: ($consent->pj_umur ?? '-') }}</span> tahun
                    </td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">
                            {{ $pjJkParsed === 'L' ? 'Laki – Laki' : ($pjJkParsed === 'P' ? 'Perempuan' : ($pj_jk === 'L' ? 'Laki – Laki' : ($pj_jk === 'P' ? 'Perempuan' : '-'))) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">{{ $consent->alamatpj }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Hubungan</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">
                            @if($isWali)
                                Wali / Kurator ({{ $waliValue }})
                            @else
                                {{ $consent->hubungan ?? '-' }}
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Second Section: Pernyataan Persetujuan -->
        <div class="padded-section section-divider">
            <div style="text-align: justify; line-height: 1.35; margin-bottom: 4px;">
                Dengan ini menyatakan dengan sesungguhnya memberikan <strong>(PERSETUJUAN / PENOLAKAN)</strong> dilakukan rawat inap di RSUD Karsa Husada Batu serta setuju untuk :
            </div>
            <ol class="statement-list">
                <li>Memberikan keterangan tentang riwayat penyakit dan kesehatannya;</li>
                <li>Menjalani pemeriksaan fisik;</li>
                <li>Menjalani pemeriksaan penunjang;</li>
                <li>Mendapat tindakan medis non operatif serta tindakan perawatan yang dibutuhkan terkait dengan perawatan pasien;</li>
                <li>Memenuhi semua persyaratan administrasi yang diperlukan;</li>
                <li>Mentaati seluruh peraturan yang ditetapkan RSUD Karsa Husada Batu;</li>
                <li>Bersedia mengganti peralatan yang hilang (peralatan makan, minum, sprei, selimut, dan lain-lain yang terdaftar dalam ruang rawat inap pasien) akibat kelalaian pasien dan keluarga.</li>
            </ol>
        </div>

        <!-- Third Section: Pasien Info Details -->
        <div class="padded-section section-divider">
            <table class="form-table">
                <tr>
                    <td class="label">No. Rekam Medis</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">
                            {{ $consent->regPeriksa->pasien->no_rkm_medis ?? $consent->regPeriksa->no_rkm_medis ?? '' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Nama Pasien</td>
                    <td class="colon">:</td>
                    <td><span class="solid-underline" style="display: inline-block; width: 98%;">{{ $consent->regPeriksa->pasien->nm_pasien ?? '-' }}</span></td>
                </tr>
                <tr>
                    <td class="label">Tanggal Lahir / Umur</td>
                    <td class="colon">:</td>
                    <td>
                        @if(isset($consent->regPeriksa->pasien->tgl_lahir))
                            @php
                                $dobTime = strtotime($consent->regPeriksa->pasien->tgl_lahir);
                                $dobDay = date('d', $dobTime);
                                $dobMonth = date('m', $dobTime);
                                $dobYear = date('Y', $dobTime);
                            @endphp
                            <span class="solid-underline" style="display: inline-block; width: 25px; text-align: center;">{{ $dobDay }}</span>&nbsp;-&nbsp;<span class="solid-underline" style="display: inline-block; width: 25px; text-align: center;">{{ $dobMonth }}</span>&nbsp;-&nbsp;<span class="solid-underline" style="display: inline-block; width: 45px; text-align: center;">{{ $dobYear }}</span>
                        @else
                            <span class="solid-underline" style="display: inline-block; width: 25px; text-align: center;">-</span>&nbsp;-&nbsp;<span class="solid-underline" style="display: inline-block; width: 25px; text-align: center;">-</span>&nbsp;-&nbsp;<span class="solid-underline" style="display: inline-block; width: 45px; text-align: center;">-</span>
                        @endif
                        &nbsp;/&nbsp;<span class="solid-underline" style="display: inline-block; width: 180px; text-align: left; padding-left: 5px;">{{ $ageYears }} Tahun / {{ $ageMonths }} Bulan</span>&nbsp;&nbsp;Tahun / Bulan
                    </td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">
                            {{ ($consent->regPeriksa->pasien->jk ?? '') === 'L' ? 'Laki – Laki' : ((($consent->regPeriksa->pasien->jk ?? '') === 'P') ? 'Perempuan' : '-') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">{{ $alamatPasien }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Hak Kelas Perawatan</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">
                            {{ $consent->hak_kelas ?? '-' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Kelas Perawatan yang diinginkan</td>
                    <td class="colon">:</td>
                    <td>
                        Ruang <span class="solid-underline" style="display: inline-block; width: 260px; text-align: left; padding-left: 5px;">{{ $consent->ruang }} ({{ $consent->kelas }})</span>&nbsp;/ Utama / I / II *)
                    </td>
                </tr>
                <tr>
                    <td class="label">Penanggung Jawab Biaya</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="solid-underline" style="display: inline-block; width: 98%;">
                            {{ $consent->bayar_secara ?? '-' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Highlight Box Section -->
        <div class="highlight-box section-divider">
            <u>Bersedia menanggung biaya perawatan apabila naik kelas atau menjadi pasien umum atau apabila syarat dan ketentuan tidak sesuai dengan ketentuan penjamin *)</u>
        </div>

        <!-- Fourth Section: Penutup & TTD -->
        <div class="padded-section" style="padding-bottom: 15px;">
            <div style="font-size: 9pt; text-align: justify; margin-bottom: 12px;">
                Demikian pernyataan ini dibuat dengan sesungguhnya tanpa ada paksaan dari pihak manapun juga.
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; text-align: center; vertical-align: top; font-size: 9pt; padding: 0;">
                        Petugas Pendaftaran<br>
                        RSUD Karsa Husada Batu
                        <div style="height: 50px; margin: 4px 0; position: relative;">
                            @if($consent->pegawai && $consent->pegawai->nik !== '-' && $consent->pegawai->signature_base64)
                                <img src="{{ $consent->pegawai->signature_base64 }}" style="max-height: 48px; max-width: 140px;">
                            @else
                                <div style="height: 48px;"></div>
                            @endif
                        </div>
                        <strong><u>( {{ ($consent->pegawai && $consent->pegawai->nama !== '-') ? $consent->pegawai->nama : 'Nama Jelas dan Tanda tangan' }} )</u></strong>
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: top; font-size: 9pt; padding: 0;">
                        Batu, <span class="solid-underline" style="display: inline-block; width: 120px; text-align: left;">{{ date('d-m-Y', strtotime($consent->tanggal)) }}</span><br>
                        Pemberi persetujuan Pasien/Wali
                        <div style="height: 50px; margin: 4px 0; position: relative;">
                            @if($consent->regPeriksa->signaturePasien && $consent->regPeriksa->signaturePasien->signature_path)
                                <img src="{{ public_path('storage/' . $consent->regPeriksa->signaturePasien->signature_path) }}" style="max-height: 48px; max-width: 140px;">
                            @else
                                <div style="height: 48px;"></div>
                            @endif
                        </div>
                        <strong><u>( {{ $pjNamaJelas }} )</u></strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Device Info Footer -->
    <div style="margin-top: 10px; border-top: 1px dashed #999; padding-top: 4px; font-size: 7pt; color: #666; text-align: center; line-height: 1.4;">
        Dicetak pada {{ $deviceInfo['downloaded_at'] ?? now()->format('d/m/Y H:i:s') }} |
        IP: {{ $deviceInfo['ip'] ?? '-' }} |
        Koordinat: {{ $deviceInfo['lat'] ?? '-' }}, {{ $deviceInfo['lng'] ?? '-' }} |
        Nomor Dokumen: {{ $consent->no_surat }}
    </div>
</body>
</html>
