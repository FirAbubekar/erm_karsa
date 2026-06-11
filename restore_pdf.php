<?php
$bladeFile = 'resources/views/edukasi_pasien/pdf.blade.php';

$html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 45mm 10mm 15mm 10mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ─── Fixed Header ─── */
        header {
            position: fixed;
            top: -40mm;
            left: 0;
            right: 0;
            height: 35mm;
        }
        
        .footer-device { 
            position: fixed; 
            bottom: -10mm; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 7pt; 
            color: #666; 
            border-top: 1px dashed #999; 
            padding-top: 2px;
        }

        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; padding: 0; }
        .header-logo { width: 55px; text-align: center; }
        .header-logo img { width: 50px; height: auto; }
        .header-center { text-align: center; padding: 0 5px; }
        .header-center .gov { font-size: 10pt; font-weight: bold; }
        .header-center .hospital { font-size: 12pt; font-weight: bold; }
        .header-center .accreditation { font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px; }
        .header-center .address { font-size: 8pt; }
        .header-center .email { font-size: 8pt; }
        .header-right-logo { width: 55px; text-align: center; }
        .header-right-logo img { width: 45px; height: auto; }
        
        .header-line { border: none; border-top: 2.5px solid #000; margin: 3px 0 1px 0; }
        .header-line-thin { border: none; border-top: 0.5px solid #000; margin: 0 0 0 0; }

        /* ─── Title + Patient Info ─── */
        .form-title-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 2px; }
        .form-title-table td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        .form-title-cell { text-align: center; font-weight: bold; font-size: 10pt; width: 50%; vertical-align: middle !important; }
        .patient-info-cell { font-size: 8pt; width: 50%; padding: 0 !important;}
        .patient-info-cell table { border: none; border-collapse: collapse; width: 100%; height: 100%; margin: 0; }
        .patient-info-cell table td { border: none; padding: 2px 4px; font-size: 8pt; }
        .patient-info-cell .label { width: 60px; }
        .patient-info-cell .colon { width: 5px; }
        
        .rm-box-container { display: flex; align-items: center; }
        .rm-box { display: inline-block; border: 1px solid #000; padding: 0px 4px; text-align: center; font-weight: bold; font-size: 8pt; margin-right: 2px;}

        /* ─── Main Content Tables ─── */
        .main-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 5px; }
        .main-table th, .main-table td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        
        .section-title { font-weight: bold; background-color: #f0f0f0; }
        .checkbox { display: inline-block; width: 8px; height: 8px; border: 1px solid #000; margin-right: 4px; vertical-align: middle; text-align: center; line-height: 8px; font-size: 8pt; }
        
        /* ─── Edukasi Table ─── */
        .edukasi-table { width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 7.5pt; text-align: left; }
        .edukasi-table th, .edukasi-table td { border: 1px solid #000; padding: 3px; vertical-align: top; }
        .edukasi-table th { text-align: center; font-weight: bold; background-color: #e0e0e0; vertical-align: middle; }
        
        .list-no-margin { margin: 0; padding-left: 12px; }
        
        .sig-box-sm { height: 35px; width: 100%; background-position: center; background-repeat: no-repeat; background-size: contain; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
    
        .main-table tr, .edukasi-table tr { page-break-inside: avoid; }
    </style>
</head>
<body>
    <header>
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if(file_exists(public_path('images/logo-jatim.png')))
                        <img src="{{ public_path('images/logo-jatim.png') }}" alt="Logo Jatim">
                    @else
                        <div style="width:50px; height:50px; border:1px solid #ccc; text-align:center; line-height:50px; font-size:7pt; color:#999;">Logo</div>
                    @endif
                </td>
                <td class="header-center">
                    <div class="gov">PEMERINTAH PROVINSI JAWA TIMUR</div>
                    <div class="hospital">RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU</div>
                    <div class="accreditation">Terakreditasi Paripurna Versi Starkes</div>
                    <div class="address">Jl. A. Yani 10 – 13 Telp. (0341) 596898 – 591076 -591036 Fax. 596901 – 591076</div>
                    <div class="email">Email : rsukhbatu@jatimprov.go.id</div>
                </td>
                <td class="header-right-logo">
                    @if(file_exists(public_path('images/logo-rs.png')))
                        <img src="{{ public_path('images/logo-rs.png') }}" alt="Logo RS">
                    @else
                        <div style="width:45px; height:45px; border:1px solid #ccc; text-align:center; line-height:45px; font-size:7pt; color:#999;">Logo</div>
                    @endif
                </td>
            </tr>
        </table>
        <hr class="header-line">
        <hr class="header-line-thin">
    </header>

    <div class="footer-device">
        Dicetak pada {{ $deviceInfo['downloaded_at'] ?? now()->format('d/m/Y H:i:s') }} | IP: {{ $deviceInfo['ip'] ?? '-' }}
    </div>

    <main>
        <!-- Title and Patient Info -->
        <table class="form-title-table">
            <tr>
                <td class="form-title-cell">
                    FORMULIR PENGKAJIAN<br>
                    KEBUTUHAN INFORMASI, EDUKASI,<br>
                    PRIVASI PASIEN DAN KELUARGA
                </td>
                <td class="patient-info-cell">
                    <table>
                        <tr>
                            <td class="label">No. RM</td>
                            <td class="colon">:</td>
                            <td>
                                @php
                                    $rm = $assessment->regPeriksa->pasien->no_rkm_medis ?? '-';
                                    $parts = str_split(str_pad($rm, 6, '0', STR_PAD_LEFT), 2);
                                @endphp
                                <div class="rm-box-container">
                                    @foreach($parts as $p)
                                        <span class="rm-box">{{ $p }}</span> -
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Nama Pasien</td>
                            <td class="colon">:</td>
                            <td>{{ $assessment->regPeriksa->pasien->nm_pasien ?? '-' }} &nbsp;&nbsp;&nbsp; {{ ($assessment->regPeriksa->pasien->jk ?? '') == 'L' ? 'L' : 'P' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tgl. Lahir</td>
                            <td class="colon">:</td>
                            <td>{{ isset($assessment->regPeriksa->pasien->tgl_lahir) ? date('d-m-Y', strtotime($assessment->regPeriksa->pasien->tgl_lahir)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">NIK</td>
                            <td class="colon">:</td>
                            <td>{{ $assessment->regPeriksa->pasien->no_ktp ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 2px 4px; background-color: #ddd; font-weight: bold; text-align: center;">
                    <span class="checkbox">{!! str_contains($assessment->regPeriksa->status_lanjut ?? '', 'Jalan') ? 'V' : '&nbsp;' !!}</span> RAWAT JALAN &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! str_contains($assessment->regPeriksa->status_lanjut ?? '', 'Inap') ? 'V' : '&nbsp;' !!}</span> RAWAT INAP &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! str_contains($assessment->regPeriksa->status_lanjut ?? '', 'IGD') ? 'V' : '&nbsp;' !!}</span> IGD
                </td>
            </tr>
        </table>

        <table class="main-table">
            <tr>
                <td colspan="2" style="border-bottom:none;">
                    <table style="width:100%; font-size:8pt; border-collapse:collapse;">
                        <tr><td style="width: 140px;">Nama Penerima Informasi</td><td>: {{ $assessment->nama_penerima_info }}</td></tr>
                        <tr><td>Hubungan dengan Pasien</td><td>: {{ $assessment->hubungan_dgn_pasien }}</td></tr>
                    </table>
                </td>
            </tr>
            <tr class="section-title">
                <td colspan="2">A. ASESMEN KEBUTUHAN PENDIDIKAN</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 4px 8px;">
                    <div style="margin-bottom: 4px;"><strong>DATA PASIEN</strong> (Beri tanda <span class="checkbox">V</span> pada kotak yang sesuai)</div>
                    
                    @php
                        $bahasa = json_decode($assessment->bahasa, true) ?? [];
                        $hambatan = json_decode($assessment->hambatan_edukasi, true) ?? [];
                    @endphp
                    <table style="width: 100%; border-collapse: collapse; font-size: 8pt;">
                        <tr>
                            <td style="width: 120px;">Bahasa</td>
                            <td>: 
                                <span class="checkbox">{!! in_array('Indonesia', $bahasa) ? 'V' : '&nbsp;' !!}</span> Indonesia
                                &nbsp;
                                <span class="checkbox">{!! in_array('Inggris', $bahasa) ? 'V' : '&nbsp;' !!}</span> Inggris
                                &nbsp;
                                <span class="checkbox">{!! in_array('Daerah', $bahasa) ? 'V' : '&nbsp;' !!}</span> Daerah: {{ in_array('Daerah', $bahasa) ? $assessment->bahasa_lainnya : '..........' }}
                                &nbsp;
                                <span class="checkbox">{!! in_array('Lain-lain', $bahasa) ? 'V' : '&nbsp;' !!}</span> Lain-lain: {{ in_array('Lain-lain', $bahasa) ? $assessment->bahasa_lainnya : '..........' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Perlu Penerjemah</td>
                            <td>: 
                                <span class="checkbox">{!! $assessment->perlu_penerjemah == 'Ya' ? 'V' : '&nbsp;' !!}</span> Ya
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->perlu_penerjemah == 'Tidak' ? 'V' : '&nbsp;' !!}</span> Tidak
                            </td>
                        </tr>
                        <tr>
                            <td>Pendidikan Pasien</td>
                            <td>:
                                <span class="checkbox">{!! $assessment->pendidikan == 'SD' ? 'V' : '&nbsp;' !!}</span> SD
                                &nbsp;
                                <span class="checkbox">{!! $assessment->pendidikan == 'SLTP' ? 'V' : '&nbsp;' !!}</span> SLTP
                                &nbsp;
                                <span class="checkbox">{!! $assessment->pendidikan == 'SLTA' ? 'V' : '&nbsp;' !!}</span> SLTA
                                &nbsp;
                                <span class="checkbox">{!! $assessment->pendidikan == 'Lain-lain' ? 'V' : '&nbsp;' !!}</span> Lain-lain: {{ $assessment->pendidikan == 'Lain-lain' ? $assessment->pendidikan_lainnya : '..........' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Baca dan Tulis</td>
                            <td>:
                                <span class="checkbox">{!! $assessment->baca_dan_tulis == 'Baik' ? 'V' : '&nbsp;' !!}</span> Baik
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->baca_dan_tulis == 'Kurang' ? 'V' : '&nbsp;' !!}</span> Kurang
                            </td>
                        </tr>
                        <tr>
                            <td>Nilai Budaya</td>
                            <td>:
                                <span class="checkbox">{!! $assessment->nilai_budaya == 'Modern' ? 'V' : '&nbsp;' !!}</span> Modern
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->nilai_budaya == 'Moderat' ? 'V' : '&nbsp;' !!}</span> Moderat
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->nilai_budaya == 'Tradisional' ? 'V' : '&nbsp;' !!}</span> Tradisional
                            </td>
                        </tr>
                        <tr>
                            <td>Gaya Pembelajaran</td>
                            <td>:
                                <span class="checkbox">{!! $assessment->gaya_pembelajaran == 'Visual' ? 'V' : '&nbsp;' !!}</span> Visual
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->gaya_pembelajaran == 'Audio' ? 'V' : '&nbsp;' !!}</span> Audio
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->gaya_pembelajaran == 'Kinestetik' ? 'V' : '&nbsp;' !!}</span> Kinestetik
                            </td>
                        </tr>
                        <tr>
                            <td>Literasi Kesehatan</td>
                            <td>:
                                <span class="checkbox">{!! $assessment->literasi_kesehatan == 'Baik' ? 'V' : '&nbsp;' !!}</span> Baik
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->literasi_kesehatan == 'Cukup' ? 'V' : '&nbsp;' !!}</span> Cukup
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->literasi_kesehatan == 'Kurang' ? 'V' : '&nbsp;' !!}</span> Kurang
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Hambatan Edukasi</td>
                            <td>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td>: <span class="checkbox">{!! in_array('Tidak Ada', $hambatan) ? 'V' : '&nbsp;' !!}</span> Tidak Ada</td>
                                        <td><span class="checkbox">{!! in_array('Penglihatan', $hambatan) ? 'V' : '&nbsp;' !!}</span> Penglihatan</td>
                                        <td><span class="checkbox">{!! in_array('Bahasa', $hambatan) ? 'V' : '&nbsp;' !!}</span> Bahasa</td>
                                        <td><span class="checkbox">{!! in_array('Kognitif Terbatas', $hambatan) ? 'V' : '&nbsp;' !!}</span> Kognitif Terbatas</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp; <span class="checkbox">{!! in_array('Emosional terganggu', $hambatan) ? 'V' : '&nbsp;' !!}</span> Emosional terganggu</td>
                                        <td><span class="checkbox">{!! in_array('Budaya/Agama', $hambatan) ? 'V' : '&nbsp;' !!}</span> Budaya/Agama</td>
                                        <td><span class="checkbox">{!! in_array('Motivasi Kurang', $hambatan) ? 'V' : '&nbsp;' !!}</span> Motivasi Kurang</td>
                                        <td><span class="checkbox">{!! in_array('Pendengaran Terganggu', $hambatan) ? 'V' : '&nbsp;' !!}</span> Pendengaran Terganggu</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp; <span class="checkbox">{!! in_array('Gangguan Bicara', $hambatan) ? 'V' : '&nbsp;' !!}</span> Gangguan Bicara</td>
                                        <td><span class="checkbox">{!! in_array('Fisik Lemah', $hambatan) ? 'V' : '&nbsp;' !!}</span> Fisik Lemah</td>
                                        <td colspan="2"><span class="checkbox">{!! in_array('Lain-lain', $hambatan) ? 'V' : '&nbsp;' !!}</span> Lain-lain: {{ in_array('Lain-lain', $hambatan) ? $assessment->hambatan_lainnya : '..........' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Kesediaan Menerima</td>
                            <td>:
                                <span class="checkbox">{!! $assessment->kesediaan_menerima == 'Ya' ? 'V' : '&nbsp;' !!}</span> Ya
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->kesediaan_menerima == 'Tidak' ? 'V' : '&nbsp;' !!}</span> Tidak
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="section-title">
                <td colspan="2">B. RENCANA KEBUTUHAN EDUKASI</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 4px;">
                    @php
                        $rencana = json_decode($assessment->rencana_kebutuhan, true) ?? [];
                    @endphp
                    <table style="width: 100%; border-collapse: collapse; font-size: 8pt;">
                        <tr>
                            <td style="width: 33%;"><span class="checkbox">{!! in_array('B_HAK_PARTISIPASI', $rencana) ? 'V' : '&nbsp;' !!}</span> Hak Berpartisipasi Pelayanan</td>
                            <td style="width: 33%;"><span class="checkbox">{!! in_array('B_PROSEDUR_PENUNJANG', $rencana) ? 'V' : '&nbsp;' !!}</span> Prosedur Pemeriksaan Penunjang</td>
                            <td style="width: 33%;"><span class="checkbox">{!! in_array('B_DIAGNOSIS', $rencana) ? 'V' : '&nbsp;' !!}</span> Kondisi Kesehatan, Diagnosis</td>
                        </tr>
                        <tr>
                            <td><span class="checkbox">{!! in_array('B_INFORMED_CONSENT', $rencana) ? 'V' : '&nbsp;' !!}</span> Proses Informed Consent</td>
                            <td><span class="checkbox">{!! in_array('B_DIET_NUTRISI', $rencana) ? 'V' : '&nbsp;' !!}</span> Diet dan Nutrisi</td>
                            <td><span class="checkbox">{!! in_array('B_OBAT', $rencana) ? 'V' : '&nbsp;' !!}</span> Penggunaan Obat secara Efektif</td>
                        </tr>
                        <tr>
                            <td><span class="checkbox">{!! in_array('B_ALAT_MEDIS', $rencana) ? 'V' : '&nbsp;' !!}</span> Penggunaan Alat Medis</td>
                            <td><span class="checkbox">{!! in_array('B_MANAJEMEN_NYERI', $rencana) ? 'V' : '&nbsp;' !!}</span> Manajemen Nyeri</td>
                            <td rowspan="2"><span class="checkbox">{!! in_array('B_REHABILITASI', $rencana) ? 'V' : '&nbsp;' !!}</span> Teknik Rehabilitasi</td>
                        </tr>
                        <tr>
                            <td><span class="checkbox">{!! in_array('B_CUCI_TANGAN', $rencana) ? 'V' : '&nbsp;' !!}</span> Cuci Tangan yang Benar</td>
                            <td><span class="checkbox">{!! in_array('B_BAHAYA_ROKOK', $rencana) ? 'V' : '&nbsp;' !!}</span> Bahaya Rokok</td>
                        </tr>
                        <tr>
                            <td><span class="checkbox">{!! in_array('B_RUJUKAN', $rencana) ? 'V' : '&nbsp;' !!}</span> Rujukan Edukasi</td>
                            <td><span class="checkbox">{!! in_array('B_EDUKASI_PULANG', $rencana) ? 'V' : '&nbsp;' !!}</span> Edukasi Pasien Pulang</td>
                            <td><span class="checkbox">{!! in_array('B_LAINNYA', $rencana) ? 'V' : '&nbsp;' !!}</span> Lain-lain: {{ $assessment->rencana_lainnya }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-top: none;">
                    <div style="float: left; width: 50%;">
                        Tanggal Edukasi: {{ $assessment->tgl_edukasi ? date('d-m-Y', strtotime($assessment->tgl_edukasi)) : '' }}<br>
                        Nama: {{ $assessment->nama_penerima_info }}
                    </div>
                    <div style="float: right; width: 40%; text-align: center;">
                        Tanda Tangan:<br>
                        @if($assessment->ttd_penerima)
                            <img src="{{ public_path('storage/' . $assessment->ttd_penerima) }}" class="signature-image" style="max-height: 40px; margin-top:5px;">
                        @else
                            <div class="sig-space" style="height: 40px;"></div>
                        @endif
                    </div>
                    <div style="clear: both;"></div>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 0;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 7.5pt;">
                        <tr>
                            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 2px 4px; color: blue;" class="text-center"><a href="#" style="text-decoration: underline; color: blue;">Tujuan Edukasi :</a></td>
                            <td style="border-bottom: 1px solid #000; padding: 2px 4px; color: blue;" class="text-center"><a href="#" style="text-decoration: underline; color: blue;">Metode Edukasi :</a></td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000; padding: 2px 4px; color: blue; vertical-align: top;">
                                <ul class="list-no-margin" style="list-style-type: none; padding-left: 0;">
                                    <li>1. Mampu Mengetahui</li>
                                    <li>2. Mau Berpartisipasi</li>
                                    <li>3. Mengambil Keputusan</li>
                                    <li>4. Mendemonstrasikan</li>
                                </ul>
                            </td>
                            <td style="padding: 2px 4px; color: blue; vertical-align: top;">
                                <ul class="list-no-margin" style="list-style-type: none; padding-left: 0;">
                                    <li>1. Penjelasan</li>
                                    <li>2. Diskusi</li>
                                    <li>3. Ceramah</li>
                                    <li>4. Demonstrasi</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; padding: 0;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 7.5pt;">
                        <tr>
                            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 2px 4px; color: blue;" class="text-center"><a href="#" style="text-decoration: underline; color: blue;">Materi Edukasi :</a></td>
                            <td style="border-bottom: 1px solid #000; padding: 2px 4px; color: blue;" class="text-center"><a href="#" style="text-decoration: underline; color: blue;">Evaluasi :</a></td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000; padding: 2px 4px; color: blue; vertical-align: top;">
                                <ul class="list-no-margin" style="list-style-type: none; padding-left: 0;">
                                    <li>1. Leaflet</li>
                                    <li>2. Lembar Balik</li>
                                    <li>3. Alat Peraga</li>
                                </ul>
                            </td>
                            <td style="padding: 2px 4px; color: blue; vertical-align: top;">
                                <ul class="list-no-margin" style="list-style-type: none; padding-left: 0;">
                                    <li>1. Sudah Mengerti</li>
                                    <li>2. Mampu Menjelaskan</li>
                                    <li>3. Mampu Mendemonstrasikan</li>
                                    <li>4. Perlu Pengulangan</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Table Implementation (Edukasi Table) -->
        <table class="edukasi-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 10%;">Poli/ Unit</th>
                    <th rowspan="2" style="width: 25%;">KEBUTUHAN EDUKASI : TOPIK</th>
                    <th rowspan="2" style="width: 10%;">Tgl/Jam Edukasi</th>
                    <th rowspan="2" style="width: 20%;">Verifikasi</th>
                    <th colspan="2">Tanda Tangan</th>
                    <th rowspan="2" style="width: 10%;">Tgl Reedukasi</th>
                </tr>
                <tr>
                    <th style="width: 12.5%;">Pasien/ Keluarga</th>
                    <th style="width: 12.5%;">Edukator</th>
                </tr>
            </thead>
            <tbody>
                @foreach($implementations as $impl)
                @php
                    $verifikasi = is_string($impl->verifikasi) ? json_decode($impl->verifikasi, true) : $impl->verifikasi;
                    if (!is_array($verifikasi)) $verifikasi = [];
                @endphp
                <tr>
                    <td class="text-center">{{ $impl->poli_unit }}</td>
                    <td>{!! nl2br(e($impl->nama_topik)) !!}</td>
                    <td class="text-center">{{ $impl->created_at ? \Carbon\Carbon::parse($impl->created_at)->format('d/m/y H:i') : '' }}</td>
                    <td style="color: blue;">
                        <div style="margin-bottom:2px;">Tujuan Edukasi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="checkbox" style="color:#000;">{!! in_array('Tujuan', $verifikasi) ? 'V' : '&nbsp;' !!}</span></div>
                        <div style="margin-bottom:2px;">Metode Edukasi &nbsp;&nbsp;&nbsp;<span class="checkbox" style="color:#000;">{!! in_array('Metode', $verifikasi) ? 'V' : '&nbsp;' !!}</span></div>
                        <div style="margin-bottom:2px;">Material Edukasi <span class="checkbox" style="color:#000;">{!! in_array('Materi', $verifikasi) ? 'V' : '&nbsp;' !!}</span></div>
                        <div>Evaluasi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="checkbox" style="color:#000;">{!! in_array('Evaluasi', $verifikasi) ? 'V' : '&nbsp;' !!}</span></div>
                    </td>
                    <td class="text-center">
                        @if($impl->ttd_pasien)
                            <div class="sig-box-sm" style="background-image: url('{{ public_path('storage/' . $impl->ttd_pasien) }}');"></div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($impl->ttd_edukator)
                            <div class="sig-box-sm" style="background-image: url('{{ public_path('storage/' . $impl->ttd_edukator) }}');"></div>
                        @endif
                    </td>
                    <td class="text-center">{{ $impl->tgl_reedukasi ? date('d/m/y', strtotime($impl->tgl_reedukasi)) : '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>
HTML;

file_put_contents($bladeFile, $html);
echo "pdf.blade.php restored completely.\n";
