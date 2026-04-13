<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 12mm 12mm 12mm 12mm;
            size: A4;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9.5pt;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ─── Header ─── */
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; padding: 0; }
        .header-logo { width: 55px; text-align: center; }
        .header-logo img { width: 50px; height: auto; }
        .header-center { text-align: center; padding: 0 5px; }
        .header-center .gov { font-size: 10pt; font-weight: bold; }
        .header-center .hospital { font-size: 12pt; font-weight: bold; }
        .header-center .accreditation { font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px; }
        .header-center .stars { color: #DAA520; font-size: 12pt; letter-spacing: 2px; }
        .header-center .address { font-size: 8pt; }
        .header-center .email { font-size: 8pt; }
        .header-right-logo { width: 55px; text-align: center; }
        .header-right-logo img { width: 45px; height: auto; }
        .doc-code { text-align: right; font-size: 9pt; font-weight: bold; margin-bottom: 2px; }
        .header-line { border: none; border-top: 2.5px solid #000; margin: 3px 0 1px 0; }
        .header-line-thin { border: none; border-top: 0.5px solid #000; margin: 0 0 6px 0; }

        /* ─── Title + Patient Info ─── */
        .form-title-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 6px; }
        .form-title-table td { border: 1px solid #000; padding: 4px 8px; vertical-align: middle; }
        .form-title-cell { text-align: center; font-weight: bold; font-size: 10pt; width: 42%; }
        .patient-info-cell { font-size: 9pt; }
        .patient-info-cell table { border: none; border-collapse: collapse; width: 100%; }
        .patient-info-cell table td { border: none; padding: 1px 3px; font-size: 9pt; }
        .patient-info-cell .label { width: 70px; }
        .patient-info-cell .colon { width: 8px; }
        .rm-box { display: inline-block; border: 1px solid #000; padding: 0px 4px; min-width: 16px; text-align: center; font-weight: bold; font-size: 9pt; }

        /* ─── Content ─── */
        .content { font-size: 9.5pt; text-align: justify; }
        .content ol { padding-left: 16px; margin: 0; }
        .content ol > li { margin-bottom: 3px; text-align: justify; }
        .sub-list { padding-left: 16px; margin-top: 2px; }
        .sub-list li { margin-bottom: 2px; }

        /* ─── Auth Table ─── */
        .auth-table { width: 100%; border: none; border-collapse: collapse; margin-top: 2px; }
        .auth-table td { border: none; padding: 1px 0; font-size: 9.5pt; }

        /* ─── Signature ─── */
        .closing-text { margin-top: 6px; font-size: 9.5pt; text-align: justify; }
        .signature-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .signature-table td { vertical-align: top; padding: 2px 5px; text-align: center; font-size: 9.5pt; width: 50%; }
        .sig-space { height: 55px; }
        .signature-image { max-height: 60px; max-width: 150px; }
        .underline-text { text-decoration: underline; }
    </style>
</head>
<body>
    <!-- Document Code -->
    <div class="doc-code">RM. 001C/2-2/ Rev. 1</div>

    <!-- Hospital Header -->
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
                <!-- <div class="stars">★ ★ ★ ★ ★</div> -->
                <div class="address">Jl. A. Yani 10 – 13 Telp. (0341) 596898 – 591076 -591036 Fax. 596901 – 591076</div>
                <div class="email">Email : <a href="mailto:rsukhbatu@jatimprov.go.id" style="color:#000;">rsukhbatu@jatimprov.go.id</a></div>
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

    <!-- Form Title + Patient Info -->
    <table class="form-title-table">
        <tr>
            <td class="form-title-cell">
                FORMULIR PEMBERIAN INFORMASI<br>
                DAN PERSETUJUAN UMUM<br>
                <em>(GENERAL CONSENT)</em>
            </td>
            <td class="patient-info-cell">
                <table>
                    <!-- @php
                        $noRM = $consent->regPeriksa->pasien->no_rkm_medis ?? '-';
                        $rmParts = str_split(str_pad($noRM, 8, '0', STR_PAD_LEFT), 2);
                    @endphp -->
                    <tr>
                        <td class="label">No. RM</td>
                        <td class="colon">:</td>
                        <td>
                           {{ $consent->regPeriksa->pasien->no_rkm_medis ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Nama Pasien</td>
                        <td class="colon">:</td>
                        <td>{{ $consent->regPeriksa->pasien->nm_pasien ?? '-' }} &nbsp; {{ $consent->regPeriksa->pasien->jk == 'L' ? 'L' : 'P' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tgl. Lahir</td>
                        <td class="colon">:</td>
                        <td>{{ date('d m Y', strtotime($consent->regPeriksa->pasien->tgl_lahir)) ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="colon">:</td>
                        <td>{{ $consent->regPeriksa->pasien->no_ktp ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Content: 11 Numbered Sections -->
    <div class="content">
        <ol>
            <li><strong>HAK DAN KEWAJIBAN SEBAGAI PASIEN :</strong> Dengan menandatangani dokumen ini saya mengakui bahwa pada proses pendaftaran untuk mendapatkan perawatan di RSUD Karsa Husada Batu telah mendapatkan informasi tentang hak-hak dan kewajiban saya sebagai pasien;</li>

            <li><strong>PERSETUJUAN PELAYANAN KESEHATAN :</strong> Saya mengetahui bahwa saya memiliki kondisi yang membutuhkan perawatan medis, saya mengizinkan dokter dan profesional kesehatan lainnya untuk melakukan prosedur diagnostik dan untuk memberikan pengobatan medis seperti yang diperlukan dalam penilaian profesional mereka. Hal ini mencakup seluruh pemeriksaan dan prosedur diagnostik (kecuali yang membutuhkan persetujuan khusus/tertulis)</li>

            <li><strong>AKSES INFORMASI KESEHATAN :</strong> Saya <span class="underline-text">memberi kuasa kepada setiap</span> dan seluruh orang yang merawat saya untuk memeriksa dan atau memberitahukan informasi kesehatan saya kepada <span class="underline-text">pemberi kesehatan</span> lain yang turut merawat saya selama di rumah sakit ini.</li>

            <li><strong>RAHASIA KEDOKTERAN :</strong> Saya <span class="underline-text">setuju</span> RSUD Karsa Husada Batu untuk menjaga privasi dan <span class="underline-text">kerahasiaan informasi medis saya baik untuk kepentingan perawatan dan pengobatan, pendidikan maupun penelitian.</span></li>

            <li><strong>PELEPASAN INFORMASI :</strong> Saya setuju untuk membuka rahasia kedokteran terkait dengan kondisi kesehatan, asuhan, dan pengobatan yang saya terima kepada :
                <ol style="list-style-type: lower-alpha;" class="sub-list">
                    <li>Perusahaan asuransi kesehatan / perusahaan lainnya atau pihak lain yang menjamin pembiayaan saya;</li>
                    <li>Anggota keluarga saya / pihak yang berwenang :
                        <table class="auth-table">
                            <tr>
                                <td style="width:50%;">1) {{ $pelepasanInformasi[0]->nama ?? '..............................' }} ( No. HP : {{ $pelepasanInformasi[0]->no_telp ?? '............' }} )</td>
                                <td style="width:50%;">3) {{ $pelepasanInformasi[2]->nama ?? '..............................' }} ( No. HP : {{ $pelepasanInformasi[2]->no_telp ?? '............' }} )</td>
                            </tr>
                            <tr>
                                <td>2) {{ $pelepasanInformasi[1]->nama ?? '..............................' }} ( No. HP : {{ $pelepasanInformasi[1]->no_telp ?? '............' }} )</td>
                                <td>4) {{ $pelepasanInformasi[3]->nama ?? '..............................' }} ( No. HP : {{ $pelepasanInformasi[3]->no_telp ?? '............' }} )</td>
                            </tr>
                        </table>
                    </li>
                </ol>
            </li>

            <li><strong>BARANG PRIBADI :</strong> Saya setuju untuk tidak membawa barang–barang berharga yang tidak diperlukan (seperti perhiasan, elektronik, dll) selama dalam perawatan di RSUD Karsa Husada Batu, dan saya menyetujui jika membawanya maka RSUD Karsa Husada Batu tidak bertanggung jawab terhadap kehilangan, kerusakan, atau pencurian.</li>

            <li><strong>PENGAJUAN KELUHAN :</strong> Saya menyatakan bahwa saya telah menerima informasi tentang adanya tata cara mengajukan dan mengatasi keluhan terkait pelayanan medik yang diberikan terhadap diri saya. Saya Setuju untuk mengikuti tata cara mengajukan keluhan sesuai dengan prosedur yang ada.</li>

            <li><strong>KEWAJIBAN PEMBAYARAN :</strong> Saya memahami tentang informasi biaya pengobatan atau biaya tindakan yang telah dijelaskan oleh Petugas RSUD Karsa Husada Batu. Saya bersedia membayar selisih biaya akibat kenaikan kelas diatas hak kepesertaan JKN, atas permintaan sendiri dan atau bersedia membayar seluruh biaya yang timbul akibat perawatan yang dianggap tidak layak klaim oleh JKN/asuransi kesehatan lainnya.</li>

            <li><strong>RUMAH SAKIT PENDIDIKAN :</strong> Saya mengetahui bahwa RSUD Karsa Husada Batu merupakan rumah sakit pendidikan yang menjadi tempat praktik klinik bagi mahasiswa kedokteran dan profesi-profesi kesehatan lainnya, karena itu mereka mungkin berpartisipasi dan atau terlibat dalam perawatan saya dan saya menyetujui bahwa mereka berpartisipasi dalam perawatan saya sepanjang di bawah supervisi dokter penanggung jawab pasien.</li>

            <li><strong>TATA TERTIB :</strong> Selama dalam perawatan saya dan keluarga akan mematuhi ketentuan yang telah dibuat oleh RSUD Karsa Husada Batu untuk selalu menjaga kebersihan dan ketertiban, mematuhi waktu jam berkunjung serta larangan merokok dan larangan mengambil dan menyimpan gambar/video dokumen dan aktivitas pelayanan selama di RSUD Karsa Husada Batu.</li>

            <li>Melalui dokumen ini, saya menegaskan kembali bahwa saya mempercayakan kepada semua tenaga kesehatan rumah sakit untuk memberikan perawatan, diagnostik dan terapi kepada saya sebagai pasien rawat inap atau rawat jalan atau Instalasi gawat darurat (IGD), termasuk semua pemeriksaan penunjang, yang dibutuhkan untuk pengobatan dan tindakan yang diperlukan.</li>
        </ol>
    </div>

    <!-- Closing Statement -->
    <div class="closing-text">
        Saya menyetujui setiap pernyataan yang terdapat pada formulir ini dan mendatangani tanpa paksaan dan dengan kesadaran penuh.
    </div>

    <!-- Signature Section -->
    <table class="signature-table">
        <tr>
            <td>Mengetahui :<br>Petugas Rumah Sakit</td>
            <td>Batu, {{ date('d m Y', strtotime($consent->tanggal)) }}<br>Pasien/Wali</td>
        </tr>
        <tr>
            <td><div class="sig-space"></div></td>
            <td>
                @if($consent->regPeriksa->signaturePasien && $consent->regPeriksa->signaturePasien->signature_path)
                    <img src="{{ public_path('storage/' . $consent->regPeriksa->signaturePasien->signature_path) }}" class="signature-image">
                @else
                    <div class="sig-space"></div>
                @endif
            </td>
        </tr>
        <tr>
            <td>({{ $consent->pegawai->nama ?? '........................................' }})<br><span style="font-size:8pt;">Nama dan tanda tangan</span></td>
            <td>({{ $consent->nama_pj }})<br><span style="font-size:8pt;">Nama dan tanda tangan</span></td>
        </tr>
    </table>

    <!-- Device Info Footer -->
    <div style="margin-top: 15px; border-top: 1px dashed #999; padding-top: 6px; font-size: 7.5pt; color: #666; text-align: center; line-height: 1.4;">
        Dicetak pada {{ $deviceInfo['downloaded_at'] ?? now()->format('d/m/Y H:i:s') }} |
        IP: {{ $deviceInfo['ip'] ?? '-' }} |
        Koordinat: {{ $deviceInfo['lat'] ?? '-' }}, {{ $deviceInfo['lng'] ?? '-' }}
    </div>
</body>
</html>
