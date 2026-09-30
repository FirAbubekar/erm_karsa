<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 45mm 10mm 15mm 10mm;
            size: A4 portrait;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
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

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .header-logo {
            width: 90px;
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
            width: 90px;
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

        /* ─── Title + Patient Info ─── */
        .form-title-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 2px;
        }

        .form-title-table td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .form-title-cell {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            width: 50%;
            vertical-align: middle !important;
            height: 30mm;
        }

        .patient-info-cell {
            font-size: 8pt;
            width: 50%;
            padding: 0 !important;
        }

        .patient-info-cell table {
            border: none;
            border-collapse: collapse;
            width: 100%;
            height: 100%;
            margin: 0;
        }

        .patient-info-cell table td {
            border: none;
            padding: 2px 4px;
            font-size: 8pt;
        }

        .patient-info-cell .label {
            width: 60px;
        }

        .patient-info-cell .colon {
            width: 5px;
        }

        .rm-box-container {
            display: flex;
            align-items: center;
        }

        .rm-box {
            display: inline-block;
            border: 1px solid #000;
            padding: 0px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            margin-right: 2px;
        }

        /* ─── Main Content Tables ─── */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 5px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .checkbox {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            margin-right: 4px;
            vertical-align: middle;
            text-align: center;
            line-height: 11px;
            font-size: 8pt;
            padding-bottom: 1px;
        }

        /* ─── Edukasi Table ─── */
        .edukasi-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 7.5pt;
            text-align: left;
        }

        .edukasi-table th,
        .edukasi-table td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: top;
        }

        .edukasi-table th {
            text-align: center;
            font-weight: bold;
            background-color: #e0e0e0;
            vertical-align: middle;
        }

        .list-no-margin {
            margin: 0;
            padding-left: 12px;
        }

        .sig-box-sm {
            height: 35px;
            width: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .main-table tr,
        .edukasi-table tr {
            page-break-inside: avoid;
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
                    <div style="height: 20px;"></div>
                    @if (file_exists(public_path('images/logo-rs.png')))
                        <img src="{{ public_path('images/logo-rs.png') }}" alt="Logo RS">
                    @else
                        <div
                            style="width:45px; height:45px; border:1px solid #ccc; text-align:center; line-height:45px; font-size:7pt; color:#999; margin: 0 auto;">
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

        <!-- Title and Patient Info -->
        <table class="form-title-table" style="margin-top:-60px !important">
            <tr>
                <td style="width: 50%; font-size:14pt; font-weight:bold" class="text-center">
                    FORMULIR PERSETUJUAN/<br>PENOLAKAN TINDAKAN
                </td>
                <td style="width: 50%;">
                    <table>
                        <tr>
                            <td class="label" style="border: none;">No. RM</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">
                                @php
                                    $rm = $pernyataan->regPeriksa->pasien->no_rkm_medis ?? '-';
                                    $rm_chars = str_split(str_pad($rm, 6, '0', STR_PAD_LEFT));
                                @endphp
                                @foreach ($rm_chars as $c)
                                    <div class="rm-box">{{ $c }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="label" style="border: none;">Nama Pasien</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">{{ $pernyataan->regPeriksa->pasien->nm_pasien ?? '-' }} /
                                &nbsp;&nbsp;&nbsp; {{ ($pernyataan->regPeriksa->pasien->jk ?? '') == 'L' ? 'L' : 'P' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label" style="border: none;">Tgl. Lahir</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">
                                {{ isset($pernyataan->regPeriksa->pasien->tgl_lahir) ? date('d-m-Y', strtotime($pernyataan->regPeriksa->pasien->tgl_lahir)) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label" style="border: none;">NIK</td>
                            <td class="colon" style="border: none;">:</td>
                            <td style="border: none;">{{ $pernyataan->regPeriksa->pasien->no_ktp ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Header Tindakan -->
        <div
            style="text-align: center; font-weight: bold; font-size: 11pt; background-color: #ddd; border: 1px solid #000; border-top: none; padding: 4px; margin-bottom: 0px;">
            PEMBERIAN INFORMASI "{{ strtoupper($pernyataan->template->judul_formulir ?? '-') }}"
        </div>

        <table class="main-table" style="margin-top: 0; border-top: none;">
            <tr>
                <td style="width: 40%; border-top: none;">Dokter Penanggung Jawab Pelayanan</td>
                <td style="width: 60%; border-top: none;">{{ $pernyataan->dokter->nm_dokter ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pemberi Informasi</td>
                <td>{{ $pernyataan->pemberiInformasi->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Penerima Informasi / Pemberi Persetujuan *)</td>
                <td>{{ $pernyataan->nama_penerima_informasi ?? '-' }}</td>
            </tr>
        </table>

        <!-- Table Details -->
        <table class="main-table" style="margin-top: 0; border-top: none;">
            <thead>
                <tr style="background-color: #eee;">
                    <th style="width: 5%; text-align: center;">NO.</th>
                    <th style="width: 30%; text-align: center;">JENIS INFORMASI</th>
                    <th style="width: 55%; text-align: center;">ISI INFORMASI</th>
                    <th style="width: 10%; text-align: center;">TANDA (v)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pernyataan->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ $detail->jenis_informasi }}</td>
                        <td>{{ $detail->isi_informasi }}</td>
                        <td class="text-center">
                            @if ($detail->is_checked)
                                <div style="font-family: DejaVu Sans, sans-serif;">&#10004;</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Bottom Signatures Page 1 -->
        <table class="main-table" style="margin-top: 0; border-top: none;">
            <tr>
                <td style="width: 80%; border-right: none; vertical-align: middle;">
                    Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan
                    memberikan kesempatan untuk bertanya dan / atau berdiskusi.
                </td>
                <td
                    style="width: 20%; border-left: 1px solid #000; text-align: center; vertical-align: bottom; height: 70px;">
                    <div style="margin-bottom: 30px;">Dokter</div>
                    @php
                        $pegawaiDokter = \App\Models\Pegawai::where('nik', $pernyataan->kd_dokter)->first();
                    @endphp
                    @if ($pegawaiDokter && $pegawaiDokter->signature_base64)
                        <img src="{{ $pegawaiDokter->signature_base64 }}"
                            style="max-height: 40px; margin-bottom: 5px;">
                    @else
                        <div style="height: 40px;"></div>
                    @endif
                    <div style="font-size: 7pt;">
                        ( {{ $pernyataan->dokter->nm_dokter ?? '..........................' }} )<br>
                        Nama dan TTD
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 80%; border-right: none; vertical-align: middle;">
                    Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri tanda /
                    paraf di kolom kanannya, dan telah memahaminya.
                </td>
                <td
                    style="width: 20%; border-left: 1px solid #000; text-align: center; vertical-align: bottom; height: 70px;">
                    <div style="margin-bottom: 30px;">Pasien/Keluarga</div>
                    @if ($pernyataan->path_ttd_penerima_informasi)
                        <img src="{{ storage_path('app/private/' . $pernyataan->path_ttd_penerima_informasi) }}"
                            style="max-height: 40px; margin-bottom: 5px;">
                    @else
                        <div style="height: 40px;"></div>
                    @endif
                    <div style="font-size: 7pt;">
                        ( {{ $pernyataan->nama_penerima_informasi ?? '..........................' }} )<br>
                        Nama dan TTD
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-style: italic; font-size: 8pt;">
                    Bila pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah wali
                    atau keluarga terdekat.
                    <br>
                    <strong>CATATAN :</strong> Kolom kanan diisi oleh pasien/keluarga (di centang perawat)<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    *) Coret yang tidak perlu
                </td>
            </tr>
        </table>

        <div style="page-break-after: always;"></div>

        <!-- PAGE 2: PERSETUJUAN / PENOLAKAN -->
        <div
            style="text-align: center; font-weight: bold; font-size: 11pt; background-color: #ddd; border: 1px solid #000; padding: 4px; margin-top: 0px; margin-bottom: 0px;">
            {{ strtoupper($pernyataan->keputusan) == 'SETUJU' ? 'PERSETUJUAN' : 'PENOLAKAN' }} TINDAKAN
        </div>
        <table class="main-table" style="margin-top: 0; border-top: none;">
            <tr>
                <td style="padding: 10px;">
                    <div style="margin-bottom: 5px;">Yang bertandatangan di bawah ini saya :</div>
                    <table style="width: 100%; border: none; margin-bottom: 10px;">
                        <tr>
                            <td style="width: 15%; border: none;">Nama</td>
                            <td style="width: 2%; border: none;">:</td>
                            <td style="border: none; border-bottom: 1px dotted #000;">
                                {{ $pernyataan->nama_yang_menyatakan }}</td>
                            <td style="width: 15%; border: none; text-align: right;">
                                @php
                                    $jkYangMenyatakan = null;
                                    $hub = strtolower($pernyataan->hubungan_yang_menyatakan ?? '');
                                    if (
                                        $hub == 'diri sendiri' ||
                                        $pernyataan->nama_yang_menyatakan ==
                                            ($pernyataan->regPeriksa->pasien->nm_pasien ?? '')
                                    ) {
                                        $jkYangMenyatakan = $pernyataan->regPeriksa->pasien->jk ?? null;
                                    } elseif (
                                        in_array($hub, ['suami', 'ayah', 'bapak', 'laki-laki', 'kakek', 'paman'])
                                    ) {
                                        $jkYangMenyatakan = 'L';
                                    } elseif (in_array($hub, ['istri', 'ibu', 'perempuan', 'nenek', 'bibi'])) {
                                        $jkYangMenyatakan = 'P';
                                    }
                                @endphp
                                @if ($jkYangMenyatakan == 'L')
                                    L / <s>P</s> *)
                                @elseif($jkYangMenyatakan == 'P')
                                    <s>L</s> / P *)
                                @else
                                    L / P *)
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">Alamat</td>
                            <td style="border: none;">:</td>
                            <td colspan="2" style="border: none; border-bottom: 1px dotted #000;">
                                {{ $pernyataan->regPeriksa->pasien->alamat ?? '-' }}</td>
                        </tr>
                    </table>

                    <div style="margin-bottom: 5px; text-align: justify;">
                        Dengan ini menyatakan
                        @if (strtolower($pernyataan->keputusan) == 'setuju')
                            <strong>Persetujuan</strong>
                        @elseif(strtolower($pernyataan->keputusan) == 'tolak')
                            <strong>Penolakan</strong>
                        @endif
                        untuk
                        dilakukannya Tindakan medis berupa
                        <strong>{{ strtoupper($pernyataan->template->judul_formulir ?? '-') }}</strong>
                        @if (strtolower($pernyataan->keputusan) == 'tolak')
                            <br>Dengan alasan: <strong>{{ $pernyataan->alasan_penolakan ?? '-' }}</strong><br>
                        @endif
                        @php
                            $hubText = strtolower($pernyataan->hubungan_yang_menyatakan ?? '');
                            $jkPasien = $pernyataan->regPeriksa->pasien->jk ?? 'L';
                            $inverseHub = $pernyataan->hubungan_yang_menyatakan; // default fallback

                            if ($hubText == 'suami') {
                                $inverseHub = 'Istri';
                            } elseif ($hubText == 'istri') {
                                $inverseHub = 'Suami';
                            } elseif ($hubText == 'anak') {
                                $inverseHub = $jkPasien == 'L' ? 'Bapak' : 'Ibu';
                            } elseif (in_array($hubText, ['ayah', 'ibu', 'bapak'])) {
                                $inverseHub = 'Anak';
                            } elseif (in_array($hubText, ['kakak', 'adik', 'saudara'])) {
                                $inverseHub = 'Saudara';
                            } elseif ($hubText == 'keponakan') {
                                $inverseHub = $jkPasien == 'L' ? 'Paman' : 'Bibi';
                            } elseif ($hubText == 'cucu') {
                                $inverseHub = $jkPasien == 'L' ? 'Kakek' : 'Nenek';
                            } elseif (in_array($hubText, ['kakek', 'nenek'])) {
                                $inverseHub = 'Cucu';
                            }
                        @endphp
                        @if (strtolower($pernyataan->hubungan_yang_menyatakan) == 'diri sendiri' ||
                                $pernyataan->nama_yang_menyatakan == ($pernyataan->regPeriksa->pasien->nm_pasien ?? ''))
                            Terhadap saya / <s>.............................. saya</s> **)
                        @else
                            Terhadap <s>saya</s> / {{ $inverseHub }} saya **)
                        @endif
                    </div>

                    <table style="width: 100%; border: none; margin-bottom: 10px;">
                        <tr>
                            <td style="width: 15%; border: none;">No. RM</td>
                            <td style="width: 2%; border: none;">:</td>
                            <td style="border: none;">
                                @php
                                    $rm = $pernyataan->regPeriksa->pasien->no_rkm_medis ?? '-';
                                    $rm_chars = str_split(str_pad($rm, 6, '0', STR_PAD_LEFT));
                                @endphp
                                @foreach ($rm_chars as $c)
                                    <div class="rm-box">{{ $c }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">Nama</td>
                            <td style="border: none;">:</td>
                            <td style="border: none; border-bottom: 1px dotted #000;">
                                {{ $pernyataan->regPeriksa->pasien->nm_pasien ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;">Tgl Lahir</td>
                            <td style="border: none;">:</td>
                            <td style="border: none; border-bottom: 1px dotted #000;">
                                {{ isset($pernyataan->regPeriksa->pasien->tgl_lahir) ? date('d-m-Y', strtotime($pernyataan->regPeriksa->pasien->tgl_lahir)) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;">NIK</td>
                            <td style="border: none;">:</td>
                            <td style="border: none; border-bottom: 1px dotted #000;">
                                {{ $pernyataan->regPeriksa->pasien->no_ktp ?? '-' }}</td>
                        </tr>
                    </table>

                    <div style="text-align: justify; margin-bottom: 20px;">
                        Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di
                        atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul.<br>
                        @if (strtolower($pernyataan->keputusan) == 'setuju')
                            Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan
                            tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang
                            Maha Esa.
                        @else
                            Saya bertanggung jawab secara penuh atas segala akibat yang mungkin timbul sebagai akibat
                            tidak dilakukannya tindakan medis tersebut.
                        @endif
                    </div>

                    <div style="text-align: center; margin-bottom: 30px;">
                        Batu, Tanggal {{ date('d-m-Y', strtotime($pernyataan->waktu_persetujuan)) }} Pukul
                        {{ date('H:i', strtotime($pernyataan->waktu_persetujuan)) }} WIB
                    </div>

                    <table style="width: 100%; border: none; text-align: center;">
                        <tr>
                            <td style="width: 33%; border: none;">
                                Yang Menyatakan,
                                <br><br><br>
                                @if ($pernyataan->path_ttd_yang_menyatakan)
                                    <img src="{{ storage_path('app/private/' . $pernyataan->path_ttd_yang_menyatakan) }}"
                                        style="max-height: 50px;">
                                @else
                                    <div style="height: 50px;"></div>
                                @endif
                                <br>
                                ( {{ $pernyataan->nama_yang_menyatakan }} )<br>
                                Tanda tangan dan Nama
                            </td>
                            <td style="width: 33%; border: none;">
                                Saksi I<br>(Pasien/Keluarga)
                                <br><br><br>
                                @if ($pernyataan->path_ttd_saksi_keluarga)
                                    <img src="{{ storage_path('app/private/' . $pernyataan->path_ttd_saksi_keluarga) }}"
                                        style="max-height: 50px;">
                                @else
                                    <div style="height: 50px;"></div>
                                @endif
                                <br>
                                ( {{ $pernyataan->nama_saksi_keluarga ?? '..........................' }} )<br>
                                Tanda tangan dan Nama
                            </td>
                            <td style="width: 33%; border: none;">
                                Saksi II<br>(Rumah Sakit)
                                <br><br><br>
                                @php
                                    $saksiRS = \App\Models\Pegawai::where('nik', $pernyataan->saksi_rs)->first();
                                @endphp
                                @if ($saksiRS && $saksiRS->signature_base64)
                                    <img src="{{ $saksiRS->signature_base64 }}" style="max-height: 50px;">
                                @else
                                    <div style="height: 50px;"></div>
                                @endif
                                <br>
                                ( {{ $saksiRS->nama ?? '..........................' }} )<br>
                                Tanda tangan dan Nama
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </main>
    <script type="text/php">
        if (isset($pdf)) {
            $kode_dokumen = "{{ $pernyataan->template->kode_dokumen ?? 'RM. 007' }}";
            $text = "$kode_dokumen/{PAGE_NUM}-{PAGE_COUNT}/Rev 1";
            $dummy_text = "$kode_dokumen/9-9/Rev 1"; // Used for width calculation to avoid {PAGE_NUM} string length inflation
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
