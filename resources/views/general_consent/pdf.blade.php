<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 10mm 12mm 10mm 12mm;
            size: A4;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.15;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
        }

        /* ─── Header ─── */
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; padding: 0; }
        .header-logo { width: 55px; text-align: center; }
        .header-logo img { width: 50px; height: auto; }
        .header-center { text-align: center; padding: 0 5px; }
        .header-center .gov { font-size: 10.5pt; font-weight: bold; }
        .header-center .dinas { font-size: 10.5pt; font-weight: bold; }
        .header-center .hospital { font-size: 12.5pt; font-weight: bold; color: #333; }
        .header-center .address { font-size: 8.5pt; }
        .header-center .contact { font-size: 8.5pt; }
        .header-center .email { font-size: 8.5pt; }
        .header-right-logo { width: 55px; text-align: center; }
        .doc-code { text-align: right; font-size: 9.5pt; font-weight: bold; margin-bottom: 2px; }
        .header-line { border: none; border-top: 2.5px solid #000; margin: 3px 0 1px 0; }
        .header-line-thin { border: none; border-top: 0.5px solid #000; margin: 0 0 4px 0; }

        /* ─── Title + Patient Info ─── */
        .form-title-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 4px; }
        .form-title-table td { border: 1px solid #000; padding: 3px 6px; vertical-align: middle; }
        .form-title-cell { text-align: center; font-weight: bold; font-size: 10.5pt; width: 48%; }
        .patient-info-cell { font-size: 9.5pt; width: 52%; }
        .patient-info-cell table { border: none; border-collapse: collapse; width: 100%; }
        .patient-info-cell table td { border: none; padding: 1px 2px; font-size: 9.5pt; }
        .patient-info-cell .label { width: 75px; }
        .patient-info-cell .colon { width: 8px; }
        .rm-box { display: inline-block; border: 1px solid #000; padding: 0px 3px; min-width: 10px; text-align: center; font-weight: bold; font-size: 9.5pt; margin: 0 1px; }

        /* ─── Info Box Tables (Page 1) ─── */
        .info-box-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 4px;
        }
        .info-box-header {
            background-color: #d9d9d9;
            border-bottom: 1px solid #000;
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
            padding: 2.5px 5px;
            text-transform: uppercase;
        }
        .info-box-body {
            padding: 4px 6px;
            font-size: 9.5pt;
            text-align: justify;
            line-height: 1.15;
        }
        .info-box-body ol {
            padding-left: 14px;
            margin: 2px 0 0 0;
        }
        .info-box-body ol > li {
            margin-bottom: 2px;
        }

        /* ─── Content (Page 2) ─── */
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
        .sig-space { height: 50px; }
        .signature-image { max-height: 55px; max-width: 140px; }
        .underline-text { text-decoration: underline; }
    </style>
</head>
<body>
    @php
        $noRM = $consent->regPeriksa->pasien->no_rkm_medis ?? '';
        $rawRm = preg_replace('/[^0-9]/', '', $noRM);
        $paddedRm = str_pad($rawRm, 6, '0', STR_PAD_LEFT);
        $rmDigits = str_split($paddedRm);
    @endphp

    <!-- ════════════════════════════════════════════════════════════════ -->
    <!-- HALAMAN 1 : HAK, KEWAJIBAN, TATA TERTIB, BARANG PRIBADI, KELUHAN -->
    <!-- ════════════════════════════════════════════════════════════════ -->

    <!-- Document Code -->
    <div class="doc-code">RM. 001C/1-2/ Rev. 2</div>

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
                <div class="dinas">DINAS KESEHATAN</div>
                <div class="hospital">RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU</div>
                <div class="address">Jalan A. Yani Nomor 10 – 13, Ngaglik, Batu, Kota Batu, Jawa Timur 65311</div>
                <div class="contact">Telepon (0341)596898, Laman rsukarsahusadabatu.jatimprov.go.id</div>
                <div class="email">Email : <a href="mailto:rsukhbatu@jatimprov.go.id" style="color:#000; text-decoration:underline;">rsukhbatu@jatimprov.go.id</a></div>
            </td>
            <td class="header-right-logo">
                <div style="width:55px;"></div>
            </td>
        </tr>
    </table>
    <hr class="header-line">
    <hr class="header-line-thin">

    <!-- Form Title + Patient Info -->
    <table class="form-title-table">
        <tr>
            <td class="form-title-cell">
                FORMULIR INFORMASI UMUM<br>
                <em>(GENERAL INFORMATION)</em>
            </td>
            <td class="patient-info-cell">
                <table>
                    <tr>
                        <td class="label">No. RM</td>
                        <td class="colon">:</td>
                        <td>{{ $consent->regPeriksa->pasien->no_rkm_medis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nama Pasien</td>
                        <td class="colon">:</td>
                        <td>
                            @php $jk = strtoupper($consent->regPeriksa->pasien->jk ?? ''); @endphp
                            {{ $consent->regPeriksa->pasien->nm_pasien ?? '-' }} ({{ $jk == 'L' || $jk == 'LAKI-LAKI' ? 'L' : ($jk == 'P' || $jk == 'PEREMPUAN' ? 'P' : '-') }})
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Tgl. Lahir</td>
                        <td class="colon">:</td>
                        <td>{{ isset($consent->regPeriksa->pasien->tgl_lahir) ? date('d-m-Y', strtotime($consent->regPeriksa->pasien->tgl_lahir)) : '-' }}</td>
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

    <!-- 1. HAK PASIEN -->
    <table class="info-box-table">
        <tr>
            <th class="info-box-header">HAK – HAK PASIEN DAN KELUARGA (UU NO. 17 PASAL 276 TAHUN 2023)</th>
        </tr>
        <tr>
            <td class="info-box-body">
                RSUD Karsa Husada Batu menjamin hak-hak pasien meliputi:
                <ol>
                    <li>Mendapatkan informasi yang jelas dan akurat mengenai kesehatan dirinya.</li>
                    <li>Mendapatkan penjelasan yang memadai mengenai pelayanan kesehatan dan tindakan medis yang diterimanya.</li>
                    <li>Mendapatkan pelayanan kesehatan bermutu, aman, dan sesuai kebutuhan medis serta standar profesi.</li>
                    <li>Menolak atau menyetujui tindakan medis (kecuali pencegahan penyakit menular dan penanggulangan KLB/wabah).</li>
                    <li>Mendapatkan akses terhadap informasi yang terdapat di dalam rekam medis.</li>
                    <li>Meminta pendapat (second opinion) dari tenaga medis atau tenaga kesehatan lain.</li>
                    <li>Mendapatkan hak-hak lain sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</li>
                </ol>
            </td>
        </tr>
    </table>

    <!-- 2. KEWAJIBAN PASIEN -->
    <table class="info-box-table">
        <tr>
            <th class="info-box-header">KEWAJIBAN PASIEN DAN KELUARGA (UU NO. 17 PASAL 277 TAHUN 2023)</th>
        </tr>
        <tr>
            <td class="info-box-body">
                Dalam menerima pelayanan kesehatan, pasien dan keluarga memiliki kewajiban:
                <ol>
                    <li>Memberikan informasi yang lengkap, jujur, dan akurat tentang masalah kesehatan dan riwayat medisnya.</li>
                    <li>Mematuhi nasihat, petunjuk, dan rencana terapi yang diberikan oleh tenaga medis dan tenaga kesehatan.</li>
                    <li>Mematuhi seluruh tata tertib dan ketentuan yang berlaku di RSUD Karsa Husada Batu.</li>
                    <li>Memberikan imbalan jasa / menyelesaikan kewajiban pembiayaan atas pelayanan yang diterima.</li>
                </ol>
            </td>
        </tr>
    </table>

    <!-- 3. TATA TERTIB -->
    <table class="info-box-table">
        <tr>
            <th class="info-box-header">TATA TERTIB RAWAT INAP / RAWAT JALAN</th>
        </tr>
        <tr>
            <td class="info-box-body">
                <ol>
                    <li>Jam Besuk / Kunjungan Pasien<br>
                        Pagi : Pukul 10.00 WIB – 12.00 WIB. Sore : Pukul 16.00 WIB – 18.00 WIB<br>
                        Anak berusia di bawah 12 tahun TIDAK DIPERKENANKAN masuk ke ruang perawatan demi menjaga kesehatan anak.</li>
                    <li>Seluruh pasien, penunggu, dan pengunjung wajib menjaga kebersihan, ketenangan, dan ketertiban di lingkungan RSUD Karsa Husada Batu.</li>
                    <li>DILARANG MEROKOK di seluruh area Rumah Sakit (Kawasan Tanpa Rokok).</li>
                    <li>DILARANG mengambil foto, merekam video, atau audio dokumen dan aktivitas pelayanan kesehatan tanpa izin tertulis dari manajemen Rumah Sakit.</li>
                    <li>Pasien wajib mematuhi ketentuan diit yang telah ditetapkan oleh tim Gizi RSUD Karsa Husada Batu. Apabila terpaksa membawa makanan dari luar, HARUS sepengetahuan dan seizin ahli gizi yang bertugas</li>
                </ol>
            </td>
        </tr>
    </table>

    <!-- 4. BARANG PRIBADI -->
    <table class="info-box-table">
        <tr>
            <th class="info-box-header">BARANG PRIBADI</th>
        </tr>
        <tr>
            <td class="info-box-body">
                RSUD Karsa Husada Batu tidak menganjurkan membawa barang berharga selama menjalani perawatan. Apabila karena keadaan tertentu pasien diharuskan membawa barang berharga, pasien dan/atau keluarga disarankan untuk menitipkannya kepada rumah sakit sesuai dengan prosedur penitipan barang berharga yang berlaku. Rumah sakit akan memberikan perlindungan terhadap barang yang dititipkan sesuai dengan ketentuan dan prosedur yang berlaku.
            </td>
        </tr>
    </table>

    <!-- 5. PENGAJUAN KELUHAN -->
    <table class="info-box-table">
        <tr>
            <th class="info-box-header">PENGAJUAN KELUHAN</th>
        </tr>
        <tr>
            <td class="info-box-body">
                Pasien dan keluarga berhak menyampaikan aspirasi/keluhan terkait pelayanan medik melalui unit Pengaduan/Customer Service resmi Rumah Sakit sesuai dengan tata cara dan prosedur yang berlaku.
            </td>
        </tr>
    </table>

    <!-- ════════════════════════════════════════════════════════════════ -->
    <!-- HALAMAN 2 : PERNYATAAN GENERAL CONSENT, PELEPASAN INFO & TDTG    -->
    <!-- ════════════════════════════════════════════════════════════════ -->
    <div class="page-break"></div>

    <!-- Document Code Page 2 -->
    <div class="doc-code">RM. 001C/2-2/ Rev. 2</div>

    <!-- Hospital Header Page 2 -->
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
                <div class="dinas">DINAS KESEHATAN</div>
                <div class="hospital">RUMAH SAKIT UMUM DAERAH KARSA HUSADA BATU</div>
                <div class="address">Jalan A. Yani Nomor 10 – 13, Ngaglik, Batu, Kota Batu, Jawa Timur 65311</div>
                <div class="contact">Telepon (0341)596898, Laman rsukarsahusadabatu.jatimprov.go.id</div>
                <div class="email">Email : <a href="mailto:rsukhbatu@jatimprov.go.id" style="color:#000; text-decoration:underline;">rsukhbatu@jatimprov.go.id</a></div>
            </td>
            <td class="header-right-logo">
                <div style="width:55px;"></div>
            </td>
        </tr>
    </table>
    <hr class="header-line">
    <hr class="header-line-thin">

    <!-- Form Section Header Page 2 -->
    <div style="text-align: center; width: 100%; font-weight: bold; font-size: 10.5pt; margin: 4px 0 2px 0;">
        FORMULIR PERSETUJUAN UMUM PERAWATAN (<em>GENERAL CONSENT FOR TREATMENT</em>)
    </div>

    <div style="font-size: 9.5pt; margin: 2px 0;">
        Yang bertanda tangan dibawah ini saya
    </div>

    <!-- Table Penanggung Jawab -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 2px; font-size: 9.5pt;">
        <tr>
            <td style="width: 12%; padding: 1px 0;">Nama</td>
            <td style="width: 2%; padding: 1px 0;">:</td>
            <td style="width: 46%; padding: 1px 0;">{{ $consent->nama_pj ?? '-' }}</td>
            <td style="width: 14%; padding: 1px 0;">No Telepon</td>
            <td style="width: 2%; padding: 1px 0;">:</td>
            <td style="width: 24%; padding: 1px 0;">{{ $consent->no_telp ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 1px 0;">Umur</td>
            <td style="padding: 1px 0;">:</td>
            <td style="padding: 1px 0;">{{ $consent->umur_pj ? $consent->umur_pj . ' Th' : '- Th' }}</td>
            <td style="padding: 1px 0;">Jenis Kelamin</td>
            <td style="padding: 1px 0;">:</td>
            <td style="padding: 1px 0;">
                @php $jkpj = strtoupper($consent->jkpj ?? ''); @endphp
                {{ $jkpj == 'L' || $jkpj == 'LAKI-LAKI' ? 'Laki-laki' : ($jkpj == 'P' || $jkpj == 'PEREMPUAN' ? 'Perempuan' : '-') }}
            </td>
        </tr>
        <tr>
            <td style="padding: 1px 0;">Alamat</td>
            <td style="padding: 1px 0;">:</td>
            <td colspan="4" style="padding: 1px 0;">{{ $consent->regPeriksa->pasien->alamatpj ?? $consent->regPeriksa->pasien->alamat ?? '-' }}</td>
        </tr>
    </table>

    <div style="font-size: 9.5pt; margin: 3px 0 2px 0;">
        Selaku penanggung Jawab terhadap diri sendiri/ Istri/ Suami/ Anak/ Ayah/ Ibu/ Lainnya<u></u>
    </div>

    <!-- Table Pasien -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 3px; font-size: 9.5pt;">
        <tr>
            <td style="width: 12%; padding: 1px 0;">Nama</td>
            <td style="width: 2%; padding: 1px 0;">:</td>
            <td style="width: 46%; padding: 1px 0;">
                @php $jkPasien = strtoupper($consent->regPeriksa->pasien->jk ?? ''); @endphp
                {{ $consent->regPeriksa->pasien->nm_pasien ?? '-' }} ({{ $jkPasien == 'L' || $jkPasien == 'LAKI-LAKI' ? 'L' : ($jkPasien == 'P' || $jkPasien == 'PEREMPUAN' ? 'P' : '-') }})
            </td>
            <td style="width: 14%; padding: 1px 0;">NO RM</td>
            <td style="width: 2%; padding: 1px 0;">:</td>
            <td style="width: 24%; padding: 1px 0;">{{ $consent->regPeriksa->pasien->no_rkm_medis ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 1px 0;">Umur</td>
            <td style="padding: 1px 0;">:</td>
            <td style="padding: 1px 0;">{{ isset($consent->regPeriksa->pasien->tgl_lahir) ? \Carbon\Carbon::parse($consent->regPeriksa->pasien->tgl_lahir)->age . ' Th' : '-' }}</td>
            <td style="padding: 1px 0;">NIK</td>
            <td style="padding: 1px 0;">:</td>
            <td style="padding: 1px 0;">{{ $consent->regPeriksa->pasien->no_ktp ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 1px 0;">Alamat</td>
            <td style="padding: 1px 0;">:</td>
            <td colspan="4" style="padding: 1px 0;">{{ $consent->regPeriksa->pasien->alamat ?? '-' }}</td>
        </tr>
    </table>

    <div style="font-size: 9.5pt; margin: 3px 0 2px 0;">
        Untuk memberikan persetujuan tentang:
    </div>

    <!-- Content: Numbered Sections -->
    <div class="content" style="font-size: 9.5pt; line-height: 1.15;">
        <ol>
            <li><strong>HAK PASIEN TERLIBAT ASUHAN :</strong> Saya memahami bahwa saya dan atau keluarga yang diberi kuasa akan dilibatkan dalam proses asuhan dan rencana pengobatan, serta memiliki hak untuk memberikan persetujuan, atau penolakan prosedur/terapi dengan memahami konsekuensi medis atas keputusan tersebut dan hal tersebut adalah tanggung jawab saya.</li>

            <li><strong>PERSETUJUAN PELAYANAN KESEHATAN :</strong> Saya mengetahui bahwa saya memiliki kondisi yang membutuhkan perawatan medis, saya mengizinkan dokter dan profesional kesehatan lainnya untuk melakukan prosedur diagnostik dan untuk memberikan pengobatan medis seperti yang diperlukan dalam penilaian profesional mereka. Hal ini mencakup seluruh pemeriksaan dan prosedur diagnostik (kecuali yang membutuhkan persetujuan khusus/tertulis), prosedur diagnostik, pemeriksaan fisik, laboratorium, radiologi, serta tindakan medis rutin (pemasangan infus, kateter urine, pipa lambung, penyuntikan, pemberian obat, vaksinasi, dan bantuan hidup dasar emergency) yang diperlukan sesuai penilaian profesional mereka.</li>

            <li><strong>AKSES INFORMASI KESEHATAN :</strong> Saya memberi kuasa kepada setiap dan seluruh orang yang merawat saya untuk memeriksa dan atau memberitahukan informasi kesehatan saya kepada pemberi kesehatan lain yang turut merawat saya selama di rumah sakit ini.</li>

            <li><strong>RAHASIA KEDOKTERAN :</strong> Saya menyetujui bahwa RSUD menjaga privasi dan kerahasiaan seluruh informasi medis saya sesuai dengan ketentuan yang berlaku, baik untuk kepentingan perawatan dan pengobatan, pendidikan, maupun penelitian. Saya juga memahami dan menyetujui bahwa, apabila diperlukan untuk kepentingan evaluasi, pemantauan, dan dokumentasi kondisi medis saya, petugas kesehatan dapat melakukan pengambilan foto pada bagian atau area tubuh tertentu yang relevan dengan kondisi medis saya, dengan tetap memperhatikan privasi, kerahasiaan, dan kepentingan terbaik saya.</li>

            <li><strong>PELEPASAN INFORMASI :</strong> Saya setuju untuk membuka rahasia kedokteran terkait dengan kondisi kesehatan, asuhan, dan pengobatan yang saya terima kepada :
                <ol style="list-style-type: lower-alpha;" class="sub-list">
                    <li>Perusahaan asuransi kesehatan / perusahaan lainnya atau pihak lain yang menjamin pembiayaan saya;</li>
                    <li>Saya memberi wewenang kepada RSUD Karsa Husada Batu untuk membuka informasi rekam medis/rahasia kedokteran saya kepada penjamin pembayaran (BPJS Kesehatan, Asuransi Swasta, Jamkesda, Perusahaan) dan Platform SATUSEHAT Kementerian Kesehatan RI (berdasarkan Permenkes No. 24 Tahun 2022);</li>
                    <li>Anggota keluarga saya / pihak yang berwenang :
                        <table class="auth-table">
                            <tr>
                                <td style="width:50%;">1) {{ $pelepasanInformasi[0]->nama ?? '____________________' }} &nbsp;( No. HP : {{ $pelepasanInformasi[0]->no_telp ?? '____________' }} )</td>
                                <td style="width:50%;">3) {{ $pelepasanInformasi[2]->nama ?? '____________________' }} &nbsp;( No. HP : {{ $pelepasanInformasi[2]->no_telp ?? '____________' }} )</td>
                            </tr>
                            <tr>
                                <td>2) {{ $pelepasanInformasi[1]->nama ?? '____________________' }} &nbsp;( No. HP : {{ $pelepasanInformasi[1]->no_telp ?? '____________' }} )</td>
                                <td>4) {{ $pelepasanInformasi[3]->nama ?? '____________________' }} &nbsp;( No. HP : {{ $pelepasanInformasi[3]->no_telp ?? '____________' }} )</td>
                            </tr>
                        </table>
                    </li>
                </ol>
            </li>

            <li><strong>RUMAH SAKIT PENDIDIKAN :</strong> Saya mengetahui bahwa RSUD Karsa Husada Batu merupakan rumah sakit pendidikan yang menjadi tempat praktik klinik bagi mahasiswa kedokteran dan profesi-profesi kesehatan lainnya, karena itu mereka mungkin berpartisipasi dan atau terlibat dalam perawatan saya dan saya menyetujui bahwa mereka berpartisipasi dalam perawatan saya sepanjang di bawah supervisi dokter penanggung jawab pasien.</li>

            <li>Melalui dokumen ini, saya menegaskan kembali bahwa saya mempercayakan kepada semua tenaga kesehatan rumah sakit untuk memberikan perawatan, diagnostik dan terapi kepada saya sebagai pasien rawat inap atau rawat jalan atau Instalasi gawat darurat (IGD), termasuk semua pemeriksaan penunjang, yang dibutuhkan untuk pengobatan dan tindakan yang diperlukan.</li>
        </ol>
    </div>

    <!-- Closing Statement -->
    <div class="closing-text" style="font-size: 9.5pt; margin-top: 6px;">
        Dengan tanda tangan dibawah ini, saya menyatakan bahwa saya menyetujui setiap pernyataan yang terdapat pada formulir ini dan menandatangani tanpa paksaan serta dengan kesadaran penuh.
    </div>

    <!-- Signature Section -->
    <table class="signature-table">
        <tr>
            <td style="font-size: 9.5pt;">Mengetahui :<br>Petugas Rumah Sakit</td>
            <td style="font-size: 9.5pt;">Batu, {{ date('d-m-Y', strtotime($consent->tanggal)) }}<br>Pasien/Wali</td>
        </tr>
        <tr>
            <td>
                @if($consent->pegawai && $consent->pegawai->signature_base64)
                    <img src="{{ $consent->pegawai->signature_base64 }}" class="signature-image">
                @else
                    <div class="sig-space"></div>
                @endif
            </td>
            <td>
                @if($consent->regPeriksa->signaturePasien && $consent->regPeriksa->signaturePasien->signature_path)
                    <img src="{{ public_path('storage/' . $consent->regPeriksa->signaturePasien->signature_path) }}" class="signature-image">
                @else
                    <div class="sig-space"></div>
                @endif
            </td>
        </tr>
        <tr>
            <td>({{ $consent->pegawai->nama ?? '........................................' }})<br><span style="font-size:8.5pt;">Nama dan tanda tangan</span></td>
            <td>({{ $consent->nama_pj }})<br><span style="font-size:8.5pt;">Nama dan tanda tangan</span></td>
        </tr>
    </table>

    <!-- Device Info Footer -->
    <div style="margin-top: 10px; border-top: 1px dashed #999; padding-top: 4px; font-size: 7.5pt; color: #666; text-align: center; line-height: 1.3;">
        Dicetak pada {{ $deviceInfo['downloaded_at'] ?? now()->format('d/m/Y H:i:s') }} |
        IP: {{ $deviceInfo['ip'] ?? '-' }} |
        Koordinat: {{ $deviceInfo['lat'] ?? '-' }}, {{ $deviceInfo['lng'] ?? '-' }}
    </div>
</body>
</html>
