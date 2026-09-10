<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Penelusuran Reaksi Transfusi</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 3px 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h4 {
            margin: 3px 0;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .alert-phone {
            margin-top: 5px;
            font-size: 9.5pt;
            font-weight: bold;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .section-header {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 6px;
            font-size: 11pt;
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .data-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .label-col {
            width: 230px;
        }
        .colon-col {
            width: 15px;
        }

        .checkbox-symbol {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12pt;
            display: inline-block;
            width: 15px;
        }

        .gejala-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 12px;
        }
        .gejala-table td {
            padding: 3px 0;
            vertical-align: middle;
            width: 33%;
        }

        .dots-underline {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 200px;
            padding-bottom: 1px;
        }

        .footer-signatures {
            margin-top: 30px;
            width: 100%;
        }
        .footer-signatures td {
            vertical-align: top;
            text-align: center;
        }

        .watermark-info {
            position: fixed;
            bottom: -20px;
            left: 0; right: 0;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 4px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>RSUD KARSA HUSADA BATU</h2>
        <h3>MONITORING TRANSFUSI DARAH/ PRODUK DARAH</h3>
        <h4>FORMULIR PENELUSURAN REAKSI TRANSFUSI</h4>
        <div class="alert-phone">TELEPON KE BDRS (PSW.140) BILA ANDA MENEMUKAN GEJALA DIBAWAH INI</div>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 130px; font-weight: bold;">RUMAH SAKIT</td>
            <td style="width: 15px;">:</td>
            <td><span class="dots-underline" style="width: 100%;">{{ $data->rumah_sakit ?? 'RSUD KARSA HUSADA BATU' }}</span></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">RUANGAN</td>
            <td>:</td>
            <td><span class="dots-underline" style="width: 100%;">{{ $data->ruangan ?? '-' }}</span></td>
        </tr>
    </table>

    @php
        $gejalaList = $data->gejala_items ?? collect();
        $tindakanList = $data->tindakan_items ?? collect();
        $spesimenList = $data->spesimen_items ?? collect();
        $investigasiList = $data->investigasi_items ?? collect();
    @endphp

    <!-- A. IDENTITAS PASIEN -->
    <div class="section-header">A. IDENTITAS PASIEN</div>
    <table class="data-table">
        <tr>
            <td class="label-col">1. Nama Pasien</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline" style="width: 100%;"><strong>{{ $data->nama_pasien ?? '-' }}</strong></span></td>
        </tr>
        <tr>
            <td class="label-col">2. No. Rekam Medis</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline" style="width: 100%;">{{ $data->no_rekam_medis ?? '-' }} {{ $data->no_rawat ? '('.$data->no_rawat.')' : '' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">3. Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td>
                <span class="checkbox-symbol">{!! $data->jenis_kelamin == 'L' ? '&#9745;' : '&#9633;' !!}</span> L &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="checkbox-symbol">{!! $data->jenis_kelamin == 'P' ? '&#9745;' : '&#9633;' !!}</span> P
            </td>
        </tr>
        <tr>
            <td class="label-col">4. Tanggal Lahir / Umur</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline" style="width: 100%;">{{ $data->tanggal_lahir ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">5. Diagnosa</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline" style="width: 100%;">{{ $data->diagnosa ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">6. Dokter Penanggung Jawab</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline" style="width: 100%;">{{ $data->dokter_pj ?? '-' }}</span></td>
        </tr>
    </table>

    <!-- B. DATA TRANSFUSI -->
    <div class="section-header">B. DATA TRANSFUSI</div>
    <table class="data-table">
        <tr>
            <td class="label-col">1. Jenis Komponen Darah</td>
            <td class="colon-col">:</td>
            <td>
                <span class="checkbox-symbol">{!! $data->jenis_komponen == 'WB' ? '&#9745;' : '&#9633;' !!}</span> WB &nbsp;
                <span class="checkbox-symbol">{!! $data->jenis_komponen == 'PRC' ? '&#9745;' : '&#9633;' !!}</span> PRC &nbsp;
                <span class="checkbox-symbol">{!! $data->jenis_komponen == 'TC' ? '&#9745;' : '&#9633;' !!}</span> TC &nbsp;
                <span class="checkbox-symbol">{!! $data->jenis_komponen == 'FFP' ? '&#9745;' : '&#9633;' !!}</span> FFP &nbsp;
                <span class="checkbox-symbol">{!! $data->jenis_komponen == 'Cryo' ? '&#9745;' : '&#9633;' !!}</span> Cryo &nbsp;
                <span class="checkbox-symbol">{!! $data->jenis_komponen == 'Lainnya' ? '&#9745;' : '&#9633;' !!}</span> Lainnya : {{ $data->jenis_komponen_lain ?? '' }}
            </td>
        </tr>
        <tr>
            <td class="label-col">2. Golongan Darah Pasien</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->gol_darah ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">3. Golongan Darah Donor</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->gol_darah_donor ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">4. Nomor Kantong Darah</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->barcode ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">5. Volume</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->volume_transfusi ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">6. Tanggal Transfusi</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->tanggal_transfusi ? date('d-m-Y', strtotime($data->tanggal_transfusi)) : '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">7. Jam Mulai</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->jam_mulai ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">8. Jam Reaksi Terjadi</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->jam_reaksi ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">9. Jam Selesai / Dihentikan</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline">{{ $data->jam_selesai ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">10. Petugas Pemberi Transfusi</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline" style="width: 100%;">{{ $data->petugas_transfusi ?? '-' }}</span></td>
        </tr>
    </table>

    <!-- C. TANDA DAN GEJALA REAKSI TRANSFUSI -->
    <div class="section-header">C. TANDA DAN GEJALA REAKSI TRANSFUSI</div>
    @php $gejalaDefsPdf = ['Demam','Menggigil','Gatal / Urtikaria','Sesak Napas']; @endphp
    <table class="gejala-table">
        <tr>
            @foreach(array_slice($gejalaDefsPdf, 0, 2) as $g)
            <td><span class="checkbox-symbol">{!! $gejalaList->has($g) ? '&#9745;' : '&#9633;' !!}</span> {{ $g }}@if($gejalaList->has($g) && $gejalaList[$g]->nilai) : {{ $gejalaList[$g]->nilai }}@endif</td>
            @endforeach
        </tr>
        <tr>
            @foreach(array_slice($gejalaDefsPdf, 2) as $g)
            <td><span class="checkbox-symbol">{!! $gejalaList->has($g) ? '&#9745;' : '&#9633;' !!}</span> {{ $g }}@if($gejalaList->has($g) && $gejalaList[$g]->nilai) : {{ $gejalaList[$g]->nilai }}@endif</td>
            @endforeach
        </tr>
    </table>
    @if($gejalaList->has('Lain-lain') && $gejalaList['Lain-lain']->nilai)
        <div style="margin-bottom:10px;font-size:10pt;"><strong>Gejala Lainnya:</strong> {{ $gejalaList['Lain-lain']->nilai }}</div>
    @endif

    @php $jenisReaksi = $data->jenis_reaksi ?? ''; @endphp
    @if($jenisReaksi)
        <div style="margin-bottom:10px;font-size:10pt;"><strong>Klasifikasi / Jenis Reaksi:</strong> {{ $jenisReaksi }}</div>
    @endif
    @if($data->gejala)
        <div style="margin-bottom:10px;font-size:10pt;"><strong>Deskripsi Gejala Tambahan:</strong><br>{{ $data->gejala }}</div>
    @endif

    <!-- D. TANDA VITAL -->
    @php
        $vitalRows = [
            'Tekanan Darah' => ['sebelum' => $data->td_sebelum, 'reaksi' => $data->td_reaksi, 'setelah' => $data->td_setelah],
            'Nadi' => ['sebelum' => $data->nadi_sebelum, 'reaksi' => $data->nadi_reaksi, 'setelah' => $data->nadi_setelah],
            'Suhu' => ['sebelum' => $data->suhu_sebelum, 'reaksi' => $data->suhu_reaksi, 'setelah' => $data->suhu_setelah],
        ];
    @endphp
    <div class="section-header">D. TANDA VITAL</div>
    <table class="data-table" style="margin-bottom:12px;">
        <tr style="font-weight:bold;">
            <td class="label-col">Parameter</td>
            <td style="width:120px;">Sebelum Transfusi</td>
            <td style="width:120px;">Saat Reaksi</td>
            <td style="width:120px;">Setelah Reaksi</td>
        </tr>
        @foreach($vitalRows as $param => $vals)
        <tr>
            <td class="label-col">{{ $param }}</td>
            <td><span class="dots-underline" style="min-width:80px;">{{ $vals['sebelum'] ?? '-' }}</span></td>
            <td><span class="dots-underline" style="min-width:80px;">{{ $vals['reaksi'] ?? '-' }}</span></td>
            <td><span class="dots-underline" style="min-width:80px;">{{ $vals['setelah'] ?? '-' }}</span></td>
        </tr>
        @endforeach
    </table>

    <!-- E. TINDAKAN YANG DILAKUKAN -->
    <div class="section-header">E. TINDAKAN YANG DILAKUKAN</div>
    @php $tindakanDefsPdf = ['Transfusi dihentikan','Jalur infus dipertahankan dengan NaCl 0,9%','Dilaporkan ke dokter','Dilaporkan ke BDRS/UTD','Pemeriksaan laboratorium ulang']; @endphp
    <table class="data-table">
        @foreach($tindakanDefsPdf as $t)
        <tr>
            <td style="width:30px;"><span class="checkbox-symbol">{!! $tindakanList->has($t) ? '&#9745;' : '&#9633;' !!}</span></td>
            <td>{{ $t }}@if($tindakanList->has($t) && $tindakanList[$t]->nilai) : {{ $tindakanList[$t]->nilai }} @endif</td>
        </tr>
        @endforeach
        @if($tindakanList->has('Pemberian obat') && $tindakanList['Pemberian obat']->nilai)
        <tr><td></td><td><strong>Pemberian obat:</strong> {{ $tindakanList['Pemberian obat']->nilai }}</td></tr>
        @endif
        @if($tindakanList->has('Lain-lain') && $tindakanList['Lain-lain']->nilai)
        <tr><td></td><td><strong>Lain-lain:</strong> {{ $tindakanList['Lain-lain']->nilai }}</td></tr>
        @endif
    </table>

    <!-- F. SPESIMEN YANG DIKIRIM KE BDRS/UTD -->
    <div class="section-header">F. SPESIMEN YANG DIKIRIM KE BDRS/UTD</div>
    @php $spesimenDefsPdf = ['Kantong darah sisa transfusi','Selang transfusi','Sampel darah pasien pasca transfusi','Formulir investigasi reaksi transfusi','Urin pasien']; @endphp
    <table class="data-table">
        @foreach($spesimenDefsPdf as $s)
        <tr>
            <td style="width:30px;"><span class="checkbox-symbol">{!! $spesimenList->has($s) ? '&#9745;' : '&#9633;' !!}</span></td>
            <td>{{ $s }}@if($spesimenList->has($s) && $spesimenList[$s]->nilai) : {{ $spesimenList[$s]->nilai }} @endif</td>
        </tr>
        @endforeach
        @if($spesimenList->has('Lain-lain') && $spesimenList['Lain-lain']->nilai)
        <tr><td></td><td><strong>Lain-lain:</strong> {{ $spesimenList['Lain-lain']->nilai }}</td></tr>
        @endif
    </table>

    <!-- G. HASIL INVESTIGASI BDRS/UTD -->
    <div class="section-header">G. HASIL INVESTIGASI BDRS/UTD</div>
    @php $investigasiDefsPdf = ['Pemeriksaan Golongan Darah','Crossmatch Ulang','Uji Hemolisis','DAT (Direct Antiglobulin Test)','Kultur (bila perlu)']; @endphp
    <table class="data-table">
        @foreach($investigasiDefsPdf as $inv)
        <tr>
            <td class="label-col">{{ $inv }}</td>
            <td class="colon-col">:</td>
            <td><span class="dots-underline" style="min-width:200px;">{{ $investigasiList->has($inv) && $investigasiList[$inv]->nilai ? $investigasiList[$inv]->nilai : '-' }}</span></td>
        </tr>
        @endforeach
    </table>

    @php $kesimpulan = $data->kesimpulan_reaksi ?? ''; @endphp
    @if($kesimpulan)
        <div style="margin-bottom:10px;font-size:10pt;"><strong>Kesimpulan Reaksi Transfusi:</strong> {{ $kesimpulan }}</div>
    @endif
    @if($data->keterangan)
        <div style="margin-bottom:10px;font-size:10pt;"><strong>Keterangan:</strong> {{ $data->keterangan }}</div>
    @endif
    @if($data->tindak_lanjut)
        <div style="margin-bottom:10px;font-size:10pt;"><strong>Rencana Tindak Lanjut:</strong><br>{{ $data->tindak_lanjut }}</div>
    @endif

    <!-- H. TINDAK LANJUT & SIGNATURE -->
    <table class="footer-signatures">
        <tr>
            <td style="width: 50%;">
                <strong>Petugas Ruangan</strong><br><br><br><br>
                ( <strong>{{ $data->nama_petugas_ruangan ?? '.........................................' }}</strong> )
            </td>
            <td style="width: 50%;">
                <strong>Petugas BDRS/UTD</strong><br><br><br><br>
                ( <strong>{{ $data->nama_petugas_bdrs ?? '.........................................' }}</strong> )
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top:15px;">
                Batu, {{ date('d-m-Y', strtotime($data->created_at ?? now())) }}<br>
                Petugas Pelapor,<br><br><br>
                ( <strong>{{ $data->petugas_pelapor ?? '.........................................' }}</strong> )
            </td>
        </tr>
    </table>

    <div class="watermark-info">
        <span>No. Reaksi: {{ $data->no_reaksi ?? '-' }}</span>
        <span>Dicetak pada: {{ date('d/m/Y H:i') }} | IP: {{ $deviceInfo['ip'] ?? '-' }}</span>
    </div>

</body>
</html>
