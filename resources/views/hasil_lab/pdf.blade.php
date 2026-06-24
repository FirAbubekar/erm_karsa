<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 10mm 12mm 20mm 12mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.1;
            color: #000;
        }
        
        table { width: 100%; border-collapse: collapse; }
        
        /* Hospital Header */
        .hosp-table { width: 100%; border-collapse: collapse; }
        .logo { width: 55px; }
        .hosp-info { text-align: center; }
        .hosp-info .lab { font-weight: bold; font-size: 10pt; }
        .hosp-info .name { font-weight: bold; font-size: 13pt; margin: 1px 0; }
        .hosp-info .addr { font-size: 7.5pt; }

        /* Patient Info - Final Polish */
        .patient-info-container { margin-top: 5px; margin-bottom: 8px; }
        .p-info-table { width: 100%; border-collapse: collapse; }
        .p-info-table td { padding: 1px 0; vertical-align: top; font-size: 9pt; }
        .p-info-table .label { width: 85px; color: #333; }
        .p-info-table .colon { width: 10px; text-align: center; }
        .p-info-table .val { font-weight: normal; }

        /* Result Table */
        .main-table th {
            border-top: 1.2px solid #000;
            border-bottom: 1.2px solid #000;
            padding: 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }
        .main-table td { padding: 4px 5px; font-size: 8.5pt; border-bottom: 0.1px solid #f2f2f2; }
        
        .group-header td {
            background-color: #C6E2E9;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px !important;
            font-size: 8.5pt;
        }
        .abnormal { font-weight: bold; }

        /* Footer Signatures */
        .last-page-bottom {
            position: absolute;
            bottom: 0px;
            width: 100%;
        }
        .keterangan-box {
            border-top: 1.5px double #000;
            border-bottom: 1.5px solid #000;
            padding: 4px 0;
            font-size: 7.5pt;
            text-align: center;
            margin-bottom: 12px;
        }
        .sig-table { width: 100%; text-align: center; }
        .sig-table td { vertical-align: top; width: 50%; font-size: 9.5pt; }
        .sig-space { height: 60px; }
        
        .page-footer { 
            position: fixed; 
            bottom: -8mm; 
            left: 0; 
            right: 0; 
            text-align: right; 
            font-size: 7.5pt; 
            color: #666;
            border-top: 0.5px solid #eee;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    <table class="main-table">
        <thead>
            <tr>
                <th colspan="5" style="border:none; padding:0; text-align:left;">
                    <!-- Hospital Header -->
                    <table class="hosp-table">
                        <tr>
                            <td class="logo">
                                @if(file_exists(public_path('images/logo-jatim.png')))
                                    <img src="{{ public_path('images/logo-jatim.png') }}" width="50">
                                @endif
                            </td>
                            <td class="hosp-info">
                                <div class="lab">LABORATORIUM</div>
                                <div class="name">RSU KARSA HUSADA BATU</div>
                                <div class="addr">Jl. Jenderal Ahmad Yani No. 10-13, Telp. 0341 - 596898</div>
                                <div class="addr">Ngaglik Kota Batu 65311 Jawa Timur - Indonesia</div>
                            </td>
                            <td class="logo" style="text-align:right">
                                @if(file_exists(public_path('images/logo-rs.png')))
                                    <img src="{{ public_path('images/logo-rs.png') }}" width="50">
                                @endif
                            </td>
                        </tr>
                    </table>
                    <div style="border-bottom: 1.5px solid #000; margin: 4px 0;"></div>
                    
                    <!-- Patient Info -->
                    <div class="patient-info-container">
                        <table class="p-info-table">
                            <tr>
                                <td style="width: 48%;">
                                    <table style="width: 100%;">
                                        <tr><td class="label">No.RM</td><td class="colon">:</td><td class="val">{{ $header->PID }}</td></tr>
                                        <tr><td class="label">Nama Pasien</td><td class="colon">:</td><td class="val"><strong>{{ $header->PNAME }}</strong></td></tr>
                                        <tr><td class="label">Jenis Kelamin</td><td class="colon">:</td><td class="val">{{ $header->SEX == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                                        <tr><td class="label">Tanggal Lahir</td><td class="colon">:</td><td class="val">{{ $header->BIRTH_DT ? date('d-m-Y', strtotime($header->BIRTH_DT)) : '-' }}</td></tr>
                                        <tr><td class="label">Usia</td><td class="colon">:</td><td class="val" style="white-space: nowrap;">
                                            @php
                                                if($header->BIRTH_DT) {
                                                    $birthDate = new DateTime($header->BIRTH_DT);
                                                    $today = new DateTime($header->REQUEST_DT);
                                                    $diff = $today->diff($birthDate);
                                                    echo $diff->y . " th " . $diff->m . " bln " . $diff->d . " hr";
                                                } else { echo "-"; }
                                            @endphp
                                        </td></tr>
                                    </table>
                                </td>
                                <td style="width: 4%;"></td>
                                <td style="width: 48%;">
                                    <table style="width: 100%;">
                                        <tr><td class="label">No.Lab</td><td class="colon">:</td><td class="val">{{ $header->ONO }}</td></tr>
                                        <tr><td class="label">Tgl. Registrasi</td><td class="colon">:</td><td class="val">{{ date('d-m-Y H:i', strtotime($header->REQUEST_DT)) }}</td></tr>
                                        <tr><td class="label">Tgl. Selesai</td><td class="colon">:</td><td class="val">{{ $validate_on ? date('d-m-Y H:i', strtotime($validate_on)) : '-' }}</td></tr>
                                        <tr><td class="label">Ruang</td><td class="colon">:</td><td class="val">{{ $header->ROOM }}</td></tr>
                                        <tr><td class="label">Dokter Pengirim</td><td class="colon">:</td><td class="val" style="font-size: 8.5pt;">{{ $header->CLINICIAN_NM }}</td></tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </th>
            </tr>
            <tr>
                <th style="width: 32%;">JENIS PEMERIKSAAN</th>
                <th style="width: 18%;">HASIL</th>
                <th style="width: 15%;">UNIT</th>
                <th style="width: 20%;">NILAI RUJUKAN</th>
                <th style="width: 15%;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $group => $items)
                <tr class="group-header">
                    <td colspan="5">{{ $group ?: 'PEMERIKSAAN' }}</td>
                </tr>
                @foreach($items as $item)
                @php
                    $isAbnormal = $item->FLAG && (strtoupper($item->FLAG) == 'H' || strtoupper($item->FLAG) == 'L' || $item->FLAG == '*');
                @endphp
                <tr>
                    <td style="padding-left: 12px;">{{ $item->TEST_NM }}</td>
                    <td class="{{ $isAbnormal ? 'abnormal' : '' }}">
                        @if($item->FLAG)
                            <span style="display: inline-block; width: 12px;">{{ $item->FLAG }}</span>
                        @endif
                        {{ $item->RESULT_VALUE }}
                    </td>
                    <td>{{ $item->UNIT }}</td>
                    <td>{{ $item->REF_RANGE }}</td>
                    <td></td>
                </tr>
                @endforeach
            @endforeach
            <!-- Spacer for bottom signature -->
            <tr><td colspan="5" style="border:none; height: 130px;"></td></tr>
        </tbody>
    </table>

    <div class="last-page-bottom">
        <div class="keterangan-box">
            Keterangan : <strong>HH</strong> (High 2x) : Nilai Kritis Tinggi &nbsp;--&nbsp; 
            <strong>LL</strong> (Low 2x) : Nilai Kritis Rendah &nbsp;--&nbsp; 
            <strong>H</strong> (High) : Hasil diatas Nilai Normal &nbsp;--&nbsp; 
            <strong>L</strong> (Low) : Hasil dibawah Nilai Normal
        </div>
        
        <table class="sig-table">
            <tr>
                <td>
                    Penanggung Jawab,<br>
                    <div class="sig-space">
                        @if(isset($sigBase64) && $sigBase64)
                            <img src="{{ $sigBase64 }}" style="max-height: 55px; width: auto; margin-top: 5px;">
                        @endif
                    </div>
                    <u>dr. Kemala Hayati, Sp.PK</u><br>
                    NIP. 19710622 200212 2 004
                </td>
                <td>
                    Pemeriksa<br>
                    <div class="sig-space"></div>
                    {{ $validate_by ?: 'Efendi Satriya Farida Diana' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="page-footer">
        Dicetak pada: {{ date('d-m-Y H:i:s') }} | Hal : <span class="pagenum"></span>
    </div>
</body>
</html>
