<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekomendasi Dokter - {{ $data->no_rekomendasi }}</title>
    <style>
        @page { margin: 10mm 12mm; size: A4; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            margin: 0; padding: 0;
        }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .header-table td { vertical-align: middle; padding: 0; text-align: center; }
        .header-logo { width: 55px; }
        .header-logo img { width: 48px; height: auto; }
        .header-center .gov { font-size: 10pt; font-weight: bold; }
        .header-center .hospital { font-size: 12pt; font-weight: bold; }
        .header-center .address { font-size: 7.5pt; }
        .header-right-logo { width: 55px; }
        .header-right-logo img { width: 45px; height: auto; }
        .header-line { border: none; border-top: 2.5px solid #000; margin: 3px 0 1px 0; }
        .header-line-thin { border: none; border-top: 0.8px solid #000; margin: 0 0 8px 0; }

        .doc-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 16px;
        }

        .content { text-align: justify; line-height: 1.5; }
        .content p { margin-bottom: 10px; }

        .field-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding: 0 4px;
            font-weight: bold;
        }

        .patient-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .patient-table td { padding: 2px 4px; vertical-align: top; font-size: 11pt; }
        .patient-table .label { width: 130px; font-weight: bold; }
        .patient-table .colon { width: 15px; text-align: center; }
        .patient-table .value { border-bottom: 1px solid #000; padding-left: 4px; font-weight: bold; }

        .signature-section { margin-top: 30px; text-align: right; }
        .signature-place { margin-top: 10px; }
        .signature-line { border-bottom: 1px solid #000; width: 250px; display: inline-block; }
        .signature-label { font-size: 9pt; margin-top: 2px; }

        .footer-text { font-size: 7.5pt; color: #666; text-align: center; margin-top: 20px; padding-top: 6px; border-top: 1px dashed #999; }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if(file_exists(public_path('images/logo-jatim.png')))
                    <img src="{{ public_path('images/logo-jatim.png') }}" alt="Logo Jatim">
                @endif
            </td>
            <td class="header-center">
                <div class="gov">PEMERINTAH PROVINSI JAWA TIMUR</div>
                <div class="hospital">RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU</div>
                <div class="address">
                    JL. A. Yani 10 – 13 Telp. (0341) 596898 – 591076 -591036 Fax. 596901 – 591076<br>
                    Email : rsukhbatu@jatimprov.go.id
                </div>
            </td>
            <td class="header-right-logo">
                @if(file_exists(public_path('images/logo-rs.png')))
                    <img src="{{ public_path('images/logo-rs.png') }}" alt="Logo RS">
                @endif
            </td>
        </tr>
    </table>
    <hr class="header-line">
    <hr class="header-line-thin">

    <div class="doc-title">REKOMENDASI DOKTER</div>
    <div style="text-align: right; font-size: 9pt; margin-bottom: 12px;">
        No. Rekomendasi: <strong>{{ $data->no_rekomendasi }}</strong>
    </div>

    <div class="content">
        <p>
            Berdasarkan hasil pemeriksaan uji silang serasi yang dikirimkan
            <strong>Bank Darah RSUD Karsa Husada Batu</strong> pada tanggal
            <span class="field-line">{{ \Carbon\Carbon::parse($data->tgl_uji)->isoFormat('D MMMM YYYY') }}</span>
            , Kami merekomendasikan permintaan darah tersebut dapat dikeluarkan
            dan ditransfusikan dalam bentuk
            <span class="field-line">{{ $data->bentuk_darah }}</span>
            Dengan Golongan Darah
            <span class="field-line">{{ $data->gol_darah_permintaan }}</span>.
        </p>

        <p style="font-weight: bold;">Kepada pasien:</p>

        <table class="patient-table">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->nama_pasien }}</td>
            </tr>
            <tr>
                <td class="label">Umur</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->umur_pasien ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->alamat_pasien ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Rumah Sakit</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->rumah_sakit ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Ruang / Bagian</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->ruang_bagian ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. RM</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->no_rkm_medis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Golongan Darah</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->gol_darah_pasien ?? '-' }}</td>
            </tr>
        </table>

        <p>
            Kami akan bertanggung jawab sepenuhnya terhadap resiko reaksi transfusi
            pada pasien tersebut di atas.
        </p>

        <p>Demikian kami ucapkan terimakasih atas kerjasamanya.</p>
    </div>

    <div class="signature-section">
        <div>Batu, {{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('D MMMM YYYY') }}</div>
        <div style="margin-top: 4px; font-weight: bold;">Dokter yang merawat</div>
        <div class="signature-place">
            @if($sigBase64)
                <img src="{{ $sigBase64 }}" style="width:80px; height:auto; margin-bottom:4px;">
            @else
                <div class="signature-line"></div>
            @endif
        </div>
        <div class="signature-label">( {{ $data->nama_dokter }} )</div>
    </div>

    <div class="footer-text">
        Dicetak pada {{ $deviceInfo['downloaded_at'] ?? now()->format('d/m/Y H:i:s') }} |
        IP: {{ $deviceInfo['ip'] ?? '-' }} |
        No. Rekom: {{ $data->no_rekomendasi }}
    </div>
</body>
</html>
