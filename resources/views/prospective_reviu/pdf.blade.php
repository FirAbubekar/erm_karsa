<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Prospective Reviu</title>
    <style>
        @page {
            margin: 45mm 15mm 15mm 15mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
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

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
            font-family: 'Arial', 'Times New Roman', Times, serif;
        }

        .header-logo {
            width: 55px;
            text-align: center;
        }

        .header-logo img {
            width: 50px;
            height: auto;
        }

        .header-center {
            text-align: center;
            padding: 0 5px;
        }

        .header-center .gov {
            font-size: 10pt;
            font-weight: bold;
        }

        .header-center .hospital {
            font-size: 12pt;
            font-weight: bold;
        }

        .header-center .accreditation {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-center .address {
            font-size: 8pt;
        }

        .header-center .email {
            font-size: 8pt;
        }

        .header-right-logo {
            width: 55px;
            text-align: center;
        }

        .header-right-logo img {
            width: 45px;
            height: auto;
        }

        .header-line {
            border: none;
            border-top: 2.5px solid #000;
            margin: 3px 0 1px 0;
        }

        .header-line-thin {
            border: none;
            border-top: 0.5px solid #000;
            margin: 0 0 0 0;
        }

        h2 {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .main-table tr {
            page-break-inside: avoid;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 3px;
            vertical-align: middle;
            text-align: center;
            line-height: 12px;
            font-size: 9pt;
            font-family: sans-serif;
            position: relative;
            top: -1px;
        }

        .ttd-box {
            text-align: center;
            padding: 10px;
        }

        .ttd-img {
            max-width: 80px;
            max-height: 80px;
            margin: 5px auto;
            display: block;
        }

        .signature-name {
            font-size: 9pt;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if (file_exists(public_path('images/logo-jatim.png')))
                        <img src="{{ public_path('images/logo-jatim.png') }}" alt="Logo Jatim">
                    @else
                        <div
                            style="width:50px; height:50px; border:1px solid #ccc; text-align:center; line-height:50px; font-size:7pt; color:#999;">
                            Logo</div>
                    @endif
                </td>
                <td class="header-center">
                    <div class="gov">PEMERINTAH PROVINSI JAWA TIMUR</div>
                    <div class="hospital">RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU</div>
                    <div class="accreditation">Terakreditasi Paripurna Versi Starkes</div>
                    <div class="address">Jl. A. Yani 10 – 13 Telp. (0341) 596898 – 591076 -591036 Fax. 596901 – 591076
                    </div>
                    <div class="email">Email : rsukhbatu@jatimprov.go.id</div>
                </td>
                <td class="header-right-logo">
                    @if (file_exists(public_path('images/logo-rs.png')))
                        <img src="{{ public_path('images/logo-rs.png') }}" alt="Logo RS">
                    @else
                        <div
                            style="width:45px; height:45px; border:1px solid #ccc; text-align:center; line-height:45px; font-size:7pt; color:#999;">
                            Logo</div>
                    @endif
                </td>
            </tr>
        </table>
        <hr class="header-line">
        <hr class="header-line-thin">
    </header>

    <div class="footer-device">
        Dicetak pada {{ $deviceInfo['downloaded_at'] ?? now()->format('d/m/Y H:i:s') }} | IP:
        {{ $deviceInfo['ip'] ?? '-' }}
    </div>

    <main>
        <h2 style="margin-top:-50px !important;">FORM PROSPECTIVE REVIU ANTIBIOTIK PASIEN</h2>

        <table class="main-table">
            <!-- IDENTITAS PASIEN -->
            <tr>
                <td colspan="2" class="section-title">IDENTITAS PASIEN</td>
            </tr>
            <tr>
                <td style="width: 40%;">No. RM / Nama</td>
                <td style="width: 60%;">{{ $review->no_rm }} / {{ $review->nama_pasien }}</td>
            </tr>
            <tr>
                <td>Tanggal Reviu</td>
                <td>{{ date('d-m-Y', strtotime($review->tanggal_reviu)) }}</td>
            </tr>
            <tr>
                <td>Diagnosis</td>
                <td>{{ $review->diagnosis }}</td>
            </tr>

            <!-- REGIMEN SAAT INI -->
            <tr>
                <td colspan="2" class="section-title">REGIMEN SAAT INI</td>
            </tr>
            <tr>
                <td>
                    Antibiotik
                    &nbsp;&nbsp;<span class="checkbox">{!! $review->tipe_antibiotik == 'Empiris' ? 'V' : '&nbsp;' !!}</span> Empiris
                    &nbsp;&nbsp;<span class="checkbox">{!! $review->tipe_antibiotik == 'Definitif' ? 'V' : '&nbsp;' !!}</span> Definitif
                    &nbsp;&nbsp;<span class="checkbox">{!! $review->tipe_antibiotik == 'Profilaksis' ? 'V' : '&nbsp;' !!}</span> Profilaksis
                </td>
                <td>{{ $review->antibiotik_direview }}</td>
            </tr>
            <tr>
                <td>Hari ke-</td>
                <td>{{ $review->hari_ke }}</td>
            </tr>

            <!-- PARAMETER KLINIS -->
            <tr>
                <td colspan="2" class="section-title">PARAMETER KLINIS</td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="width: 10%; border-top: none; border-left: none; border-bottom: none;">TD
                            </td>
                            <td style="width: 10%; border-top: none; border-bottom: none;">Suhu
                            </td>
                            <td style="width: 10%; border-top: none; border-bottom: none;">RR
                            </td>
                            <td style="width: 10%; border-top: none; border-bottom: none;">SpO2
                            </td>
                            <td style="width: 10%; border-top: none; border-bottom: none;">GCS
                            </td>

                            <td style="width: 10%; border-top: none; border-left: none; border-bottom: none;">
                                {{ $review->klinis_td }}</td>
                            <td style="width: 10%; border-top: none; border-bottom: none;">
                                {{ $review->klinis_suhu }} &deg;C</td>
                            <td style="width: 10%; border-top: none; border-bottom: none;">{{ $review->klinis_rr }}
                            </td>
                            <td style="width: 10%; border-top: none; border-bottom: none;">
                                {{ $review->klinis_spo2 }} %</td>
                            <td style="width: 10%; border-top: none; border-right: none; border-bottom: none;">
                                {{ $review->klinis_gcs }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%">Demam</td>
                <td style="width: 50%">
                    <span class="checkbox">{!! $review->klinis_demam == 1 ? 'V' : '&nbsp;' !!}</span> Ya
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! $review->klinis_demam == 0 ? 'V' : '&nbsp;' !!}</span> Tidak
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="width: 25%; border-top: none; border-left: none;">Leukosit
                            </td>
                            <td style="width: 25%; border-top: none;">% Neutrofil
                            </td>
                            <td style="width: 25%; border-top: none; border-left: none;">
                                {{ $review->lab_leukosit }} /&micro;L</td>
                            <td style="width: 25%; border-top: none;">
                                {{ $review->lab_neutrofil_persen }} %</td>
                        </tr>
                        <tr>
                            <td style="width: 25%; border-top: none; border-left: none; border-bottom: none;">Kreatinin
                            </td>
                            <td style="width: 25%; border-top: none; border-bottom: none;">Ureum
                            </td>
                            <td style="width: 25%; border-top: none; border-left: none; border-bottom: none;">
                                {{ $review->lab_kreatinin }} </td>
                            <td style="width: 25%; border-top: none; border-right: none; border-bottom: none;">
                                {{ $review->lab_ureum }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- KULTUR -->
            <tr>
                <td colspan="2" class="section-title">KULTUR</td>
            </tr>
            <tr>
                <td>Hasil Kultur</td>
                <td>
                    <span class="checkbox">{!! $review->kultur_status == 'Positif' ? 'V' : '&nbsp;' !!}</span> Positif:
                    {{ $review->kultur_status == 'Positif' ? $review->kultur_hasil_positif : '....................' }}
                    &nbsp;&nbsp;
                    <span class="checkbox">{!! $review->kultur_status == 'Negatif' ? 'V' : '&nbsp;' !!}</span> Negatif
                    &nbsp;&nbsp;
                    <span class="checkbox">{!! $review->kultur_status == 'Menunggu Hasil' ? 'V' : '&nbsp;' !!}</span> Menunggu Hasil
                    &nbsp;&nbsp;
                    <span class="checkbox">{!! $review->kultur_status == 'Belum' ? 'V' : '&nbsp;' !!}</span> Belum
                </td>
            </tr>
            <tr>
                <td>Rekomendasi Antibiotik</td>
                <td>{{ $review->kultur_rekomendasi_antibiotik }}</td>
            </tr>

            <!-- PENILAIAN APPROPRIATENESS -->
            <tr>
                <td colspan="2" class="section-title">PENILAIAN APPROPRIATENESS</td>
            </tr>
            <tr>
                <td>Indikasi</td>
                <td>
                    <span class="checkbox">{!! $review->is_indikasi_tepat == 1 ? 'V' : '&nbsp;' !!}</span> Tepat
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! $review->is_indikasi_tepat == 0 && $review->is_indikasi_tepat !== null ? 'V' : '&nbsp;' !!}</span> Tidak tepat
                </td>
            </tr>
            <tr>
                <td>Jenis antibiotik</td>
                <td>
                    <span class="checkbox">{!! $review->is_jenis_tepat == 1 ? 'V' : '&nbsp;' !!}</span> Tepat
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! $review->is_jenis_tepat == 0 && $review->is_jenis_tepat !== null ? 'V' : '&nbsp;' !!}</span> Tidak tepat
                </td>
            </tr>
            <tr>
                <td>Dosis</td>
                <td>
                    <span class="checkbox">{!! $review->is_dosis_tepat == 1 ? 'V' : '&nbsp;' !!}</span> Tepat
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! $review->is_dosis_tepat == 0 && $review->is_dosis_tepat !== null ? 'V' : '&nbsp;' !!}</span> Tidak Tepat
                </td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>
                    <span class="checkbox">{!! $review->is_durasi_sesuai == 1 ? 'V' : '&nbsp;' !!}</span> Sesuai
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! $review->is_durasi_sesuai == 0 && $review->is_durasi_sesuai !== null ? 'V' : '&nbsp;' !!}</span> Tidak Sesuai
                </td>
            </tr>

            <!-- REKOMENDASI TIM PGA -->
            <tr>
                <td colspan="2" class="section-title">REKOMENDASI TIM PGA</td>
            </tr>
            <tr>
                <td colspan="2">
                    @php
                        $rekomendasi = is_array($review->rekomendasi_pga)
                            ? $review->rekomendasi_pga
                            : json_decode($review->rekomendasi_pga, true) ?? [];
                    @endphp
                    <span class="checkbox">{!! in_array('Continue', $rekomendasi) ? 'V' : '&nbsp;' !!}</span> Continue
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! in_array('Escalation', $rekomendasi) ? 'V' : '&nbsp;' !!}</span> Escalation
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! in_array('De-escalation', $rekomendasi) ? 'V' : '&nbsp;' !!}</span> De-escalation
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! in_array('Switch IV to PO', $rekomendasi) ? 'V' : '&nbsp;' !!}</span> Switch IV to PO
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! in_array('Stop', $rekomendasi) ? 'V' : '&nbsp;' !!}</span> Stop
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! in_array('Dose adjustment', $rekomendasi) ? 'V' : '&nbsp;' !!}</span> Dose adjustment
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox">{!! in_array('Lainnya', $rekomendasi) ? 'V' : '&nbsp;' !!}</span> Lainnya:
                    {{ in_array('Lainnya', $rekomendasi) ? $review->rekomendasi_pga_lainnya : '....................' }}
                </td>
            </tr>

            <!-- RESPON DPJP & CATATAN -->
            <tr>
                <td class="section-title" style="width: 50%;">RESPON DPJP</td>
                <td class="section-title" style="width: 50%;">CATATAN</td>
            </tr>
            <tr>
                <td style="padding: 0; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td
                                style="border-top: none; border-left: none; border-right: none; padding: 8px; border-bottom: 1px solid #000;">
                                <span class="checkbox">{!! $review->respon_dpjp == 'Menerima' ? 'V' : '&nbsp;' !!}</span> Menerima Rekomendasi Tim PGA
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom: none; border-left: none; border-right: none; padding: 8px;">
                                <span class="checkbox">{!! $review->respon_dpjp !== 'Menerima' ? 'V' : '&nbsp;' !!}</span> Menolak Rekomendasi Tim PGA
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    {{ $review->respon_catatan }}
                </td>
            </tr>

            <!-- SIGNATURES -->
            <tr>
                <td style="padding: 0;">
                    <div style="padding: 5px;">TTD Apoteker Klinis:</div>
                    <div class="ttd-box">
                        @if (isset($qrApoteker))
                            <img src="{{ $qrApoteker }}" class="ttd-img" />
                        @endif
                        <div class="signature-name">{{ $review->ttd_apoteker_klinis }}</div>
                    </div>
                </td>
                <td style="padding: 0;">
                    <div style="padding: 5px;">TTD Perawat:</div>
                    <div class="ttd-box">
                        @if (isset($qrPerawat))
                            <img src="{{ $qrPerawat }}" class="ttd-img" />
                        @endif
                        <div class="signature-name">{{ $review->nama_perawat }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding: 0;">
                    <div style="padding: 5px;">TTD DPJP:</div>
                    <div class="ttd-box">
                        @if (isset($qrDpjp))
                            <img src="{{ $qrDpjp }}" class="ttd-img" />
                        @endif
                        <div class="signature-name">{{ $review->nama_dpjp }}</div>
                    </div>
                </td>
                <td style="padding: 0;">
                    <div style="padding: 5px;">TTD KPRA:</div>
                    <div class="ttd-box">
                        @if (isset($qrKpra))
                            <img src="{{ $qrKpra }}" class="ttd-img" />
                        @endif
                        <div class="signature-name">dr. RESTI ENGGAR PAWENINGGALIH, M.Ked,.Klin, Sp.MK</div>
                    </div>
                </td>
            </tr>
        </table>
    </main>
</body>

</html>
