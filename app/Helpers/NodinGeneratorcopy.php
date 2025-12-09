<?php

namespace App\Helpers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class NodinGenerator
{
    public static function generate($booking, $noSurat, $namaWadek, $signaturePath)
    {
        // Tanggal dalam format Indonesia
        Carbon::setLocale('id');
        $tanggalSuratKeluar      = Carbon::now()->translatedFormat('d F Y');
        $tanggalSuratPermohonan  = Carbon::parse($booking->date_letter)->translatedFormat('d F Y');
        $hariTanggalKegiatan     = Carbon::parse($booking->start_date)->translatedFormat('l, d F Y');
        $jamKegiatan             = $booking->start_time . ' s.d. ' . $booking->end_time;

        // Path file ttd (disimpan di storage/app/public/...)
        $ttdPath = $signaturePath ? storage_path('app/public/' . $signaturePath) : '';

        // Mulai HTML
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                @page {
                    margin: 2cm 2.5cm;
                }
                body {
                    font-family: 'Times New Roman', Times, serif;
                    font-size: 12pt;
                    line-height: 1.4;
                    color: #000;
                }
                .header {
                    text-align: center;
                    margin-bottom: 10px;
                    border-bottom: 3px solid #000;
                    padding-bottom: 8px;
                }
                .header h3 {
                    margin: 0;
                    font-size: 14pt;
                    font-weight: bold;
                }
                .header h4 {
                    margin: 3px 0 2px 0;
                    font-size: 13pt;
                    font-weight: bold;
                }
                .header p {
                    margin: 1px 0;
                    font-size: 11pt;
                }
                .header .small {
                    font-size: 10pt;
                }
                .title {
                    text-align: center;
                    margin: 16px 0 10px 0;
                }
                .title h4 {
                    margin: 0;
                    font-size: 13pt;
                    font-weight: bold;
                    text-decoration: underline;
                }
                .nomor {
                    margin-bottom: 10px;
                }
                .nomor p {
                    margin: 0;
                }
                .info-table {
                    width: 100%;
                    margin-bottom: 12px;
                }
                .info-table td {
                    padding: 1px 0;
                    vertical-align: top;
                }
                .info-table td:first-child {
                    width: 70px;
                }
                .info-table td:nth-child(2) {
                    width: 15px;
                }
                .content {
                    text-align: justify;
                    margin-bottom: 10px;
                    line-height: 1.6;
                }
                .detail-table {
                    width: 100%;
                    margin: 10px 0 12px 40px;
                }
                .detail-table td {
                    padding: 1px 0;
                    vertical-align: top;
                }
                .detail-table td:first-child {
                    width: 110px;
                }
                .detail-table td:nth-child(2) {
                    width: 15px;
                }
                .greeting {
                    font-style: italic;
                    margin: 10px 0;
                }
                .signature-table {
                    width: 100%;
                    margin-top: 20px;
                }
                .signature-table td {
                    vertical-align: top;
                    font-size: 12pt;
                }
                .signature-right {
                    width: 45%;
                    text-align: left;
                }
                .signature-left {
                    width: 55%;
                }
                .signature-right p {
                    margin: 2px 0;
                }
                .ttd-img {
                    display: block;
                    margin: 10px 0 2px 0;
                    max-height: 80px;
                    width: auto;
                }
            </style>
        </head>
        <body>

            <!-- HEADER (sesuai template) -->
            <div class='header'>
                <h3>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h3>
                <h4>UNIVERSITAS ISLAM NEGERI SUNAN AMPEL SURABAYA</h4>
                <p><strong>{$booking->faculty->name}</strong></p>
                <p class='small'>
                    Jl. Dr. Ir. H. Soekarno No.682 Surabaya 60294<br>
                    E-Mail: saintek@uinsa.ac.id Website: https://uinsa.ac.id/fst
                </p>
            </div>

            <!-- JUDUL NOTA DINAS -->
            <div class='title'>
                <h4>NOTA DINAS</h4>
            </div>

            <!-- NOMOR SURAT -->
            <div class='nomor'>
                <p><strong>Nomor: {$noSurat}</strong></p>
            </div>

            <!-- Yth, Dari, Hal, Tanggal -->
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

            <p class='greeting'>Assalamu'alaikum Wr. Wb.</p>

            <!-- ISI SESUAI TEMPLATE -->
            <p class='content'>
                Memperhatikan surat dari Ketua {$booking->organization} {$booking->faculty->name}
                UIN Sunan Ampel Surabaya Nomor {$booking->no_letter} tanggal {$tanggalSuratPermohonan}
                perihal izin untuk acara <strong>{$booking->activity_name}</strong> oleh {$booking->organization}
                yang akan dilaksanakan pada:
            </p>

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

            <p class='content'>
                Maka bersama ini kami memberikan izin kegiatan dimaksud untuk dilaksanakan
                dengan sebaik-baiknya dengan mematuhi peraturan yang berlaku dan menjaga
                reputasi lembaga.
            </p>

            <p class='content'>
                Demikian atas perhatiannya disampaikan terima kasih.
            </p>

            <p class='greeting'>Wassalamu'alaikum Wr. Wb.</p>

            <!-- BLOK TANDA TANGAN (kanan, a.n. Dekan) -->
            <table class='signature-table'>
                <tr>
                    <td class='signature-left'></td>
                    <td class='signature-right'>
                        <p>a.n. Dekan</p>
                        <p>Wakil Dekan</p>
                        <p>Bidang Kemahasiswaan dan Kerjasama,</p>";

        // Tambah gambar TTD jika ada dan file-nya ditemukan
        if ($ttdPath && file_exists($ttdPath)) {
            $imageData = base64_encode(file_get_contents($ttdPath));
            $imageInfo = getimagesize($ttdPath);
            $mimeType  = $imageInfo['mime'];

            $html .= "
                        <img class='ttd-img' src='data:{$mimeType};base64,{$imageData}' alt='TTD'>";
        } else {
            // kalau belum ada ttd, kasih ruang kosong
            $html .= "
                        <p style='margin: 50px 0;'></p>";
        }

        $html .= "
                        <p><strong><u>{$namaWadek}</u></strong></p>
                    </td>
                </tr>
            </table>

        </body>
        </html>
        ";

        // Setup Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Folder penyimpanan
        $directory = storage_path('app/public/nodin');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Simpan PDF
        $fileName = 'nodin_' . $booking->id . '.pdf';
        $pdfPath  = $directory . '/' . $fileName;
        file_put_contents($pdfPath, $dompdf->output());

        // Path relatif untuk disimpan di DB
        return 'storage/nodin/' . $fileName;
    }
}
