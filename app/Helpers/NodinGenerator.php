<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class NodinGenerator
{
    public static function generate($booking, $noSurat, $namaWadek, $signaturePath)
    {
        // Format tanggal Indonesia
        Carbon::setLocale('id');
        $tanggalSuratKeluar = Carbon::now()->translatedFormat('d F Y');
        $tanggalSuratPermohonan = Carbon::parse($booking->date_letter)->translatedFormat('d F Y');
        $hariTanggalKegiatan = Carbon::parse($booking->start_date)->translatedFormat('l, d F Y');
        $jamKegiatan = $booking->start_time . ' s.d. ' . $booking->end_time;

        // Path TTD untuk ditampilkan di PDF
        $ttdPath = $signaturePath ? public_path('storage/' . $signaturePath) : '';

        // Path logo UINSA untuk header (fallback: storage -> public)
        $logoCandidates = [
            public_path('images/logo_uinsa.png'),
            storage_path('app/public/images/logo_uinsa.png'),
        ];
        $logoImgTag = '';
        foreach ($logoCandidates as $candidate) {
            if (file_exists($candidate)) {
                $logoData = base64_encode(file_get_contents($candidate));
                $logoInfo = getimagesize($candidate);
                $logoMime = $logoInfo['mime'] ?? 'image/png';
                $logoImgTag = "<img src='data:$logoMime;base64,$logoData' alt='Logo UINSA' style='height:80px;width:auto;' />";
                break;
            }
        }

        // Buat HTML untuk PDF dengan styling yang rapi (1 halaman)
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                @page {
                    margin: 1.2cm 1.8cm;
                }
                body {
                    font-family: 'Times New Roman', Times, serif;
                    font-size: 11pt;
                    line-height: 1.25;
                    color: #000;
                }
                .header-table {
                    width: 100%;
                    margin-bottom: 10px;
                    border-bottom: 3px solid #000;
                    padding: 8px 0;
                    border-collapse: collapse;
                }
                .header-table td {
                    vertical-align: top;
                    padding: 0;
                }
                .header-table .logo-cell {
                    width: 100px;
                    text-align: center;
                    padding-right: 12px;
                }
                .header-table .logo-cell img {
                    height: 80px;
                    width: auto;
                    display: block;
                    margin: 0 auto;
                }
                .header-table .titles-cell {
                    text-align: center;
                    vertical-align: middle;
                }
                .header-table h3 {
                    margin: 0;
                    padding: 0;
                    font-size: 13pt;
                    font-weight: bold;
                    letter-spacing: 0.3px;
                    line-height: 1.4;
                }
                .header-table h4 {
                    margin: 3px 0 2px 0;
                    padding: 0;
                    font-size: 12pt;
                    font-weight: bold;
                    line-height: 1.3;
                }
                .header-table p {
                    margin: 2px 0;
                    font-size: 9.5pt;
                    line-height: 1.3;
                }
                .title {
                    text-align: center;
                    margin: 12px 0 10px 0;
                }
                .title h4 {
                    text-decoration: underline;
                    font-weight: bold;
                    margin: 0;
                    font-size: 13pt;
                    letter-spacing: 0.5px;
                }
                .nomor {
                    margin-bottom: 6px;
                }
                .nomor p {
                    margin: 0;
                    font-size: 11pt;
                    font-weight: bold;
                }
                .info-table {
                    width: 100%;
                    margin-bottom: 6px;
                    font-size: 10.5pt;
                }
                .info-table td {
                    padding: 1px 0;
                    vertical-align: top;
                }
                .info-table td:first-child {
                    width: 60px;
                }
                .info-table td:nth-child(2) {
                    width: 12px;
                    text-align: center;
                }
                .content {
                    text-align: justify;
                    margin-bottom: 6px;
                    line-height: 1.35;
                    font-size: 10.5pt;
                }
                .detail-table {
                    margin: 6px 0 6px 25px;
                    font-size: 10.5pt;
                }
                .detail-table td {
                    padding: 1px 0;
                    vertical-align: top;
                }
                .detail-table td:first-child {
                    width: 90px;
                }
                .detail-table td:nth-child(2) {
                    width: 12px;
                    text-align: center;
                }
                .signature {
                    margin-top: 15px;
                    text-align: left;
                    padding-left: 55%;
                }
                .signature p {
                    margin: 0;
                    padding: 0;
                    font-size: 10.5pt;
                    line-height: 1.3;
                }
                .signature .sig-title {
                    font-size: 9pt;
                }
                .signature img {
                    display: block;
                    margin: 4px 0 2px 0;
                    max-height: 50px;
                    width: auto;
                }
                .greeting {
                    font-style: italic;
                    margin: 6px 0;
                    font-size: 10.5pt;
                }
            </style>
        </head>
        <body>
            <!-- HEADER -->
            <table class='header-table'>
                <tr>
                    <td class='logo-cell'>{$logoImgTag}</td>
                    <td class='titles-cell'>
                        <h3>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h3>
                        <h4>UNIVERSITAS ISLAM NEGERI SUNAN AMPEL SURABAYA</h4>
                        <h4>" . strtoupper($booking->faculty->name) . "</h4>
                        <p> Jl. Jend. A. Yani 117 Surabaya 60237 Telp. 031-8410298, Fax. 031-8413300 | Jl. Dr. Ir. H. Soekarno 682 Surabaya 60294 | </p>
                    </td>
                </tr>
            </table>

            <!-- TITLE -->
            <div class='title'>
                <h4>NOTA DINAS</h4>
            </div>

            <!-- NOMOR SURAT -->
            <div class='nomor'>
                <p>Nomor: {$noSurat}</p>
            </div>

            <!-- INFO TABLE -->
            <table class='info-table'>
                <tr>
                    <td>Yth.</td>
                    <td>:</td>
                    <td>Ketua {$booking->organization}</td>
                </tr>
                <tr>
                    <td>Dari</td>
                    <td>:</td>
                    <td>Dekan</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td>Izin Kegiatan</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{$tanggalSuratKeluar}</td>
                </tr>
            </table>

            <!-- GREETING -->
            <p class='greeting'>Assalamu'alaikum Wr. Wb.</p>

            <!-- CONTENT -->
            <p class='content'>
                Memperhatikan surat dari Ketua {$booking->organization} Fakultas {$booking->faculty->name}
                UIN Sunan Ampel Surabaya Nomor {$booking->no_letter} tanggal {$tanggalSuratPermohonan}
                perihal izin untuk acara <strong>{$booking->activity_name}</strong> yang akan dilaksanakan pada:
            </p>

            <!-- DETAIL TABLE -->
            <table class='detail-table'>
                <tr>
                    <td>Hari, Tanggal</td>
                    <td>:</td>
                    <td>{$hariTanggalKegiatan}</td>
                </tr>
                <tr>
                    <td>Jam</td>
                    <td>:</td>
                    <td>{$jamKegiatan} WIB</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>:</td>
                    <td>{$booking->classmodel->name}</td>
                </tr>
            </table>

            <!-- CLOSING CONTENT -->
            <p class='content'>
                Maka bersama ini kami memberikan izin kegiatan dimaksud untuk dilaksanakan
                dengan sebaik-baiknya dengan mematuhi peraturan yang berlaku dan menjaga reputasi lembaga.
            </p>

            <p class='content'>Demikian atas perhatiannya disampaikan terima kasih.</p>

            <!-- CLOSING GREETING -->
            <p class='greeting'>Wassalamu'alaikum Wr. Wb.</p>

            <!-- SIGNATURE -->
            <div class='signature'>
                <p class='sig-title'>a.n. Dekan</p>
                <p class='sig-title'>Wakil Dekan Bidang</p>
                <p class='sig-title'>Kemahasiswaan &amp; Kerjasama,</p>";

        // Tambahkan gambar TTD jika ada
        if ($ttdPath && file_exists($ttdPath)) {
            $imageData = base64_encode(file_get_contents($ttdPath));
            $imageInfo = getimagesize($ttdPath);
            $mimeType = $imageInfo['mime'];
            $html .= "
                <img src='data:{$mimeType};base64,{$imageData}' alt='TTD'>";
        } else {
            $html .= "
                <br><br>";
        }

        $html .= "
                <p><strong><u>{$namaWadek}</u></strong></p>
            </div>
        </body>
        </html>";

        // Setup Dompdf dengan options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Buat folder jika belum ada
        $directory = storage_path('app/public/nodin');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Simpan PDF
        $fileName = 'nodin_' . $booking->id . '.pdf';
        $pdfPath = $directory . '/' . $fileName;
        file_put_contents($pdfPath, $dompdf->output());

        // Return relative path untuk disimpan di database
        return 'storage/nodin/' . $fileName;
    }
}
