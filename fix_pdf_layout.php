<?php
$bladeFile = 'resources/views/edukasi_pasien/pdf.blade.php';
$content = file_get_contents($bladeFile);

// Extract the content starting from <!-- Title and Patient Info -->
$mainContentStart = strpos($content, '<!-- Title and Patient Info -->');
if ($mainContentStart === false) {
    // maybe it got lost, try to find <table class="form-title-table">
    $mainContentStart = strpos($content, '<table class="form-title-table">');
}
if ($mainContentStart === false) {
    die("Could not find main content marker.\n");
}
$mainContentEnd = strpos($content, '</body>');
$mainContent = substr($content, $mainContentStart, $mainContentEnd - $mainContentStart);

// Clean up any remaining wrapper tags in mainContent
// We must remove the </td></tr></tbody></table> that was added at the end
$mainContent = str_replace('                </td>', '', $mainContent);
$mainContent = str_replace('            </tr>', '', $mainContent);
$mainContent = str_replace('        </tbody>', '', $mainContent);
$mainContent = str_replace('    </table>', '', $mainContent);

$newHtml = <<<HTML
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
        Dicetak pada {{ \$deviceInfo['downloaded_at'] ?? now()->format('d/m/Y H:i:s') }} | IP: {{ \$deviceInfo['ip'] ?? '-' }}
    </div>

    <main>
{$mainContent}
    </main>
</body>
</html>
HTML;

file_put_contents($bladeFile, $newHtml);
echo "pdf.blade.php fixed with properly spaced position:fixed header.\n";
