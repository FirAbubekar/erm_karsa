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
            font-family: 'Arial','Times New Roman', Times, serif;
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
        .header-logo { width: 90px; text-align: center; }
        .header-logo img { width: 50px; height: auto; }
        .header-center { text-align: center; padding: 0 5px; }
        .header-center .gov { font-size: 10pt; font-weight: bold; }
        .header-center .hospital { font-size: 12pt; font-weight: bold; }
        .header-center .accreditation { font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px; }
        .header-center .address { font-size: 8pt; }
        .header-center .email { font-size: 8pt; }
        .header-right-logo { width: 90px; text-align: center; }
        .header-right-logo img { width: 45px; height: auto; }
        
        
        
        .header-line { border: none; border-top: 2.5px solid #000; margin: 3px 0 1px 0; }
        .header-line-thin { border: none; border-top: 0.5px solid #000; margin: 0 0 0 0; }

        /* ─── Title + Patient Info ─── */
        .form-title-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 2px; }
        .form-title-table td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        .form-title-cell { text-align: center; font-weight: bold; font-size: 10pt; width: 50%; vertical-align: middle !important; height: 30mm; }
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
        .checkbox { display: inline-block; width: 11px; height: 11px; border: 1px solid #000; margin-right: 4px; vertical-align: middle; text-align: center; line-height: 11px; font-size: 8pt; padding-bottom: 1px; }
        
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
                    <div style="height: 20px;"></div>
                    @if(file_exists(public_path('images/logo-rs.png')))
                        <img src="{{ public_path('images/logo-rs.png') }}" alt="Logo RS">
                    @else
                        <div style="width:45px; height:45px; border:1px solid #ccc; text-align:center; line-height:45px; font-size:7pt; color:#999; margin: 0 auto;">Logo</div>
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
        <table class="form-title-table" style="margin-top:-60px !important">
            <tr>
                <td style="width: 50%; font-size:14pt; font-weight:bold" class="text-center">FORMULIR PENGKAJIAN KEBUTUHAN INFORMASI, EDUKASI, PRIVASI PASIEN DAN KELUARGA</td>
                <td style="width: 50%;">
                    <table>
                        <tr>
                            <td class="label" style="border: none;">No. RM</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">{{ $assessment->regPeriksa->pasien->no_rkm_medis ?? '-'}}</td>

                        </tr>
                        <tr>
                            <td class="label" style="border: none;">Nama Pasien</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">{{ $assessment->regPeriksa->pasien->nm_pasien ?? '-' }} / &nbsp;&nbsp;&nbsp; {{ ($assessment->regPeriksa->pasien->jk ?? '') == 'L' ? 'L' : 'P' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="border: none;">Tgl. Lahir</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">{{ isset($assessment->regPeriksa->pasien->tgl_lahir) ? date('d-m-Y', strtotime($assessment->regPeriksa->pasien->tgl_lahir)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="border: none;">NIK</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">{{ $assessment->regPeriksa->pasien->no_ktp ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 2px 4px; background-color: #ddd; font-weight: bold; text-align: center;">
                    <span class="checkbox">{!! str_contains($assessment->regPeriksa->status_lanjut ?? '', 'Ralan') && $assessment->regPeriksa->kd_poli != 'IGDK' ? 'V' : '&nbsp;' !!}</span> RAWAT JALAN &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! str_contains($assessment->regPeriksa->status_lanjut ?? '', 'Ranap') ? 'V' : '&nbsp;' !!}</span> RAWAT INAP &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! str_contains($assessment->regPeriksa->status_lanjut ?? '', 'Ralan') && $assessment->regPeriksa->kd_poli == 'IGDK' ? 'V' : '&nbsp;' !!}</span> IGD
                </td>
            </tr>
        </table>

        <table class="main-table">
            <tr>
                <td colspan="2" style="border-bottom:none;">
                    <table style="width:100%; font-size:8pt; border-collapse:collapse;">
                        <tr><td style="width: 140px; border: none;">Nama Penerima Informasi</td><td style="border: none;">: {{ $assessment->nama_penerima_info }}</td></tr>
                        <tr><td style="border: none;">Hubungan dengan Pasien</td><td style="border: none;">: {{ $assessment->hubungan_dgn_pasien }}</td></tr>
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
                            <td style="width: 120px; border: none;">Bahasa</td>
                            <td style="border: none;">: 
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
                            <td style="border: none;">Perlu Penerjemah</td>
                            <td style="border: none;">: 
                                <span class="checkbox">{!! $assessment->perlu_penerjemah == 'Ya' ? 'V' : '&nbsp;' !!}</span> Ya
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->perlu_penerjemah == 'Tidak' ? 'V' : '&nbsp;' !!}</span> Tidak
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">Pendidikan Pasien</td>
                            <td style="border: none;">
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
                            <td style="border: none;">Baca dan Tulis</td>
                            <td style="border: none;">
                                <span class="checkbox">{!! $assessment->baca_dan_tulis == 'Baik' ? 'V' : '&nbsp;' !!}</span> Baik
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->baca_dan_tulis == 'Kurang' ? 'V' : '&nbsp;' !!}</span> Kurang
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">Nilai Budaya</td>
                            <td style="border: none;">
                                <span class="checkbox">{!! $assessment->nilai_budaya == 'Modern' ? 'V' : '&nbsp;' !!}</span> Modern
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->nilai_budaya == 'Moderat' ? 'V' : '&nbsp;' !!}</span> Moderat
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->nilai_budaya == 'Tradisional' ? 'V' : '&nbsp;' !!}</span> Tradisional
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">Gaya Pembelajaran</td>
                            <td style="border: none;">
                                <span class="checkbox">{!! $assessment->gaya_pembelajaran == 'Visual' ? 'V' : '&nbsp;' !!}</span> Visual
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->gaya_pembelajaran == 'Audio' ? 'V' : '&nbsp;' !!}</span> Audio
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->gaya_pembelajaran == 'Kinestetik' ? 'V' : '&nbsp;' !!}</span> Kinestetik
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">Literasi Kesehatan</td>
                            <td style="border: none;">
                                <span class="checkbox">{!! $assessment->literasi_kesehatan == 'Baik' ? 'V' : '&nbsp;' !!}</span> Baik
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->literasi_kesehatan == 'Cukup' ? 'V' : '&nbsp;' !!}</span> Cukup
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="checkbox">{!! $assessment->literasi_kesehatan == 'Kurang' ? 'V' : '&nbsp;' !!}</span> Kurang
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; vertical-align: top;">Hambatan Edukasi</td>
                            <td style="border: none;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="border: none;">: <span class="checkbox">{!! in_array('Tidak Ada', $hambatan) ? 'V' : '&nbsp;' !!}</span> Tidak Ada</td>
                                        <td style="border: none;"><span class="checkbox">{!! in_array('Penglihatan', $hambatan) ? 'V' : '&nbsp;' !!}</span> Penglihatan</td>
                                        <td style="border: none;"><span class="checkbox">{!! in_array('Bahasa', $hambatan) ? 'V' : '&nbsp;' !!}</span> Bahasa</td>
                                        <td style="border: none;"><span class="checkbox">{!! in_array('Kognitif Terbatas', $hambatan) ? 'V' : '&nbsp;' !!}</span> Kognitif Terbatas</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">&nbsp; <span class="checkbox">{!! in_array('Emosional terganggu', $hambatan) ? 'V' : '&nbsp;' !!}</span> Emosional terganggu</td>
                                        <td style="border: none;"><span class="checkbox">{!! in_array('Budaya/Agama', $hambatan) ? 'V' : '&nbsp;' !!}</span> Budaya/Agama</td>
                                        <td style="border: none;"><span class="checkbox">{!! in_array('Motivasi Kurang', $hambatan) ? 'V' : '&nbsp;' !!}</span> Motivasi Kurang</td>
                                        <td style="border: none;"><span class="checkbox">{!! in_array('Pendengaran Terganggu', $hambatan) ? 'V' : '&nbsp;' !!}</span> Pendengaran Terganggu</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">&nbsp; <span class="checkbox">{!! in_array('Gangguan Bicara', $hambatan) ? 'V' : '&nbsp;' !!}</span> Gangguan Bicara</td>
                                        <td style="border: none;"><span class="checkbox">{!! in_array('Fisik Lemah', $hambatan) ? 'V' : '&nbsp;' !!}</span> Fisik Lemah</td>
                                        <td style="border: none;" colspan="2"><span class="checkbox">{!! in_array('Lain-lain', $hambatan) ? 'V' : '&nbsp;' !!}</span> Lain-lain: {{ in_array('Lain-lain', $hambatan) ? $assessment->hambatan_lainnya : '..........' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">Kesediaan Menerima</td>
                            <td style="border: none;">
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
                        <tr style="border: none;">
                            <td style="width: 33%; border: none;"><span class="checkbox">{!! in_array('B_HAK_PARTISIPASI', $rencana) ? 'V' : '&nbsp;' !!}</span> Hak untuk Berpartisipasi pada Proses Pelayanan </td>
                            <td style="width: 33%; border: none;"><span class="checkbox">{!! in_array('B_PROSEDUR_PENUNJANG', $rencana) ? 'V' : '&nbsp;' !!}</span> Prosedur Pemeriksaan Penunjang</td>
                            <td style="width: 33%; border: none;"><span class="checkbox">{!! in_array('B_DIAGNOSIS', $rencana) ? 'V' : '&nbsp;' !!}</span> Kondisi Kesehatan, Diagnosis pasti, dan penatalaksanaannya</td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_INFORMED_CONSENT', $rencana) ? 'V' : '&nbsp;' !!}</span> Proses Pemberian Informed Consent</td>
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_DIET_NUTRISI', $rencana) ? 'V' : '&nbsp;' !!}</span> Diet dan Nutrisi</td>
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_OBAT', $rencana) ? 'V' : '&nbsp;' !!}</span> Penggunaan Obat secara Efektif dan Aman, Efek Samping serta Interaksinya </td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_ALAT_MEDIS', $rencana) ? 'V' : '&nbsp;' !!}</span> Penggunaan Alat Medis yang Aman</td>
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_MANAJEMEN_NYERI', $rencana) ? 'V' : '&nbsp;' !!}</span> Manajemen Nyeri</td>
                            <td style="border: none;" rowspan="2"><span class="checkbox">{!! in_array('B_REHABILITASI', $rencana) ? 'V' : '&nbsp;' !!}</span> Teknik Rehabilitasi</td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_CUCI_TANGAN', $rencana) ? 'V' : '&nbsp;' !!}</span> Cuci Tangan yang Benar</td>
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_BAHAYA_ROKOK', $rencana) ? 'V' : '&nbsp;' !!}</span> Bahaya Rokok</td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_RUJUKAN', $rencana) ? 'V' : '&nbsp;' !!}</span> Rujukan Edukasi</td>
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_EDUKASI_PULANG', $rencana) ? 'V' : '&nbsp;' !!}</span> Edukasi Pasien Pulang</td>
                            <td style="border: none;"><span class="checkbox">{!! in_array('B_LAINNYA', $rencana) ? 'V' : '&nbsp;' !!}</span> Lain-lain: {{ $assessment->rencana_lainnya }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-top: none;">
                    <div style="float: left; width: 50%;">
                        Tanggal Edukasi: {{ $assessment->updated_at ? date('d-m-Y', strtotime($assessment->updated_at)) : '' }}<br>
                        Nama: {{ $assessment->nama_penerima_info }}
                    </div>
                    <div style="float: right; width: 40%; text-align: center;">
                        Tanda Tangan:<br>
                        @if($assessment->ttd_pasien_wali)
                            <img src="{{ public_path('storage/' . $assessment->ttd_pasien_wali) }}" alt="ttd" class="signature-image" style="max-height: 40px; margin-top:5px;">
                            <div style="font-size: 8pt; margin-top: 2px;">({{ $assessment->nama_penerima_info ?? $assessment->nama_pasien_wali_ttd }})</div>
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
                            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 2px 4px; " class="text-center">Tujuan Edukasi :</td>
                            <td style="border-bottom: 1px solid #000; padding: 2px 4px; " class="text-center">Metode Edukasi :</td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000; padding: 2px 4px;  vertical-align: top;">
                                <ul class="list-no-margin" style="list-style-type: none; padding-left: 0;">
                                    <li>1. Mampu Mengetahui</li>
                                    <li>2. Mau Berpartisipasi</li>
                                    <li>3. Mengambil Keputusan</li>
                                    <li>4. Mendemonstrasikan</li>
                                </ul>
                            </td>
                            <td style="padding: 2px 4px;  vertical-align: top;">
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
                            <td style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 2px 4px; " class="text-center">Materi Edukasi :</td>
                            <td style="border-bottom: 1px solid #000; padding: 2px 4px; " class="text-center">Evaluasi :</td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000; padding: 2px 4px;  vertical-align: top;">
                                <ul class="list-no-margin" style="list-style-type: none; padding-left: 0;">
                                    <li>1. Leaflet</li>
                                    <li>2. Lembar Balik</li>
                                    <li>3. Alat Peraga</li>
                                </ul>
                            </td>
                            <td style="padding: 2px 4px;  vertical-align: top;">
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
                    <td class="text-center">
                        {{ $impl->created_at ? \Carbon\Carbon::parse($impl->created_at)->format('d/m/y H:i') : '' }}
                        @if($impl->tgl_akhir_edukasi)
                            <br>s.d.<br>
                            {{ \Carbon\Carbon::parse($impl->tgl_akhir_edukasi)->format('d/m/y H:i') }}
                        @endif
                    </td>
                    <td style="">
                        <table>
                            @php
                                $valTujuan = '&nbsp;';
                                if (isset($verifikasi['Tujuan'])) $valTujuan = $verifikasi['Tujuan'];
                                elseif (in_array('Tujuan', $verifikasi)) $valTujuan = 'V';

                                $valMetode = '&nbsp;';
                                if (isset($verifikasi['Metode'])) $valMetode = $verifikasi['Metode'];
                                elseif (in_array('Metode', $verifikasi)) $valMetode = 'V';

                                $valMateri = '&nbsp;';
                                if (isset($verifikasi['Materi'])) $valMateri = $verifikasi['Materi'];
                                elseif (in_array('Materi', $verifikasi)) $valMateri = 'V';

                                $valEvaluasi = '&nbsp;';
                                if (isset($verifikasi['Evaluasi'])) $valEvaluasi = $verifikasi['Evaluasi'];
                                elseif (in_array('Evaluasi', $verifikasi)) $valEvaluasi = 'V';
                            @endphp
                            <tr>
                                <td style="border:none;">Tujuan Edukasi</td>
                                <td class="text-center" style="border:none;"><span class="checkbox" style="color:#000;">{!! $valTujuan !!}</span></td>
                                </tr>
                            <tr>
                                <td style="border:none;">Metode Edukasi</td>
                                <td class="text-center" style="border:none;"><span class="checkbox" style="color:#000;">{!! $valMetode !!}</span></td>
                            </tr>
                            <tr>
                                <td style="border:none;">Material Edukasi</td>
                                <td class="text-center" style="border:none;"><span class="checkbox" style="color:#000;">{!! $valMateri !!}</span></td>
                            </tr>
                            <tr>
                                <td style="border:none;">Evaluasi</td>
                                <td class="text-center" style="border:none;"><span class="checkbox" style="color:#000;">{!! $valEvaluasi !!}</span></td>
                            </tr>
                        </table>
                        
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if($impl->ttd_pasien)
                            <img src="{{ public_path('storage/' . $impl->ttd_pasien) }}" style="max-height: 40px; max-width: 100%; object-fit: contain;">
                            <div style="font-size: 6pt; margin-top: 2px;">({{ $impl->nama_penerima_info ?? $assessment->nama_penerima_info ?? $assessment->nama_pasien_wali_ttd }})</div>
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if($impl->ttd_edukator)
                            @php
                                $namaEdukator = '';
                                if (!str_contains($impl->ttd_edukator, 'education/')) {
                                    $pegawai = \App\Models\Pegawai::where('nik', $impl->ttd_edukator)->first();
                                    $namaEdukator = $pegawai ? $pegawai->nama : $impl->ttd_edukator;
                                }
                            @endphp
                            @if(str_contains($impl->ttd_edukator, 'education/'))
                                <img src="{{ public_path('storage/' . $impl->ttd_edukator) }}" style="max-height: 40px; max-width: 100%; object-fit: contain;">
                            @else
                                @php
                                    $ttdBase64 = $pegawai ? $pegawai->signature_base64 : null;
                                @endphp
                                @if($ttdBase64)
                                    <img src="{{ $ttdBase64 }}" style="max-height: 40px; max-width: 100%; object-fit: contain;">
                                @endif
                            @endif
                            @if($namaEdukator)
                                <div style="font-size: 6pt; margin-top: 2px;">({{ $namaEdukator }})</div>
                            @endif
                        @endif
                    </td>
                    <td class="text-center">{{ $impl->tgl_reedukasi ? date('d/m/y', strtotime($impl->tgl_reedukasi)) : '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <script type="text/php">
        if (isset($pdf)) {
            $text = "RM. 010A/{PAGE_NUM}-{PAGE_COUNT}/Rev 1";
            $dummy_text = "RM. 010A/9-9/Rev 1"; // Used for width calculation to avoid {PAGE_NUM} string length inflation
            $font = $fontMetrics->get_font("Arial", "bold");
            $size = 8;
            $width = $fontMetrics->get_text_width($dummy_text, $font, $size);
            
            // X: Align to the right margin
            // Page width: 595.28pt. Margin right: 10mm (28.35pt)
            $x = $pdf->get_width() - 28.35 - $width;
            
            // Y: At the very top of the header area
            // Header top is 14.17pt
            $y = 16;
            
            $pdf->page_text($x, $y, $text, $font, $size, [0,0,0]);
        }
    </script>
</body>
</html>