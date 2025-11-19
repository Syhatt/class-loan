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

        // Buat HTML untuk PDF dengan styling yang rapi
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
                    margin-bottom: 15px;
                    border-bottom: 3px solid #000;
                    padding-bottom: 10px;
                }
                .header h3 {
                    margin: 0;
                    padding: 0;
                    font-size: 14pt;
                    font-weight: bold;
                }
                .header h4 {
                    margin: 3px 0;
                    padding: 0;
                    font-size: 13pt;
                    font-weight: bold;
                }
                .header p {
                    margin: 2px 0;
                    font-size: 11pt;
                }
                .title {
                    text-align: center;
                    margin: 20px 0 15px 0;
                }
                .title h4 {
                    text-decoration: underline;
                    font-weight: bold;
                    margin: 0;
                    font-size: 13pt;
                }
                .nomor {
                    margin-bottom: 15px;
                }
                .info-table {
                    width: 100%;
                    margin-bottom: 15px;
                }
                .info-table td {
                    padding: 2px 0;
                    vertical-align: top;
                }
                .info-table td:first-child {
                    width: 80px;
                }
                .info-table td:nth-child(2) {
                    width: 20px;
                }
                .content {
                    text-align: justify;
                    margin-bottom: 15px;
                    line-height: 1.6;
                }
                .detail-table {
                    margin: 15px 0 15px 40px;
                }
                .detail-table td {
                    padding: 2px 0;
                    vertical-align: top;
                }
                .detail-table td:first-child {
                    width: 120px;
                }
                .detail-table td:nth-child(2) {
                    width: 20px;
                }
                .signature {
                    margin-top: 40px;
                    text-align: left;
                    padding-left: 50%;
                }
                .signature p {
                    margin: 3px 0;
                }
                .signature img {
                    display: block;
                    margin: 10px 0;
                    max-height: 80px;
                    width: auto;
                }
                .greeting {
                    font-style: italic;
                    margin: 15px 0;
                }
            </style>
        </head>
        <body>
            <!-- HEADER -->
            <div class='header'>
                <h3>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h3>
                <h4>UNIVERSITAS ISLAM NEGERI SUNAN AMPEL SURABAYA</h4>
                <p><strong>{$booking->faculty->name}</strong></p>
                <p style='font-size: 10pt;'>Jl. Dr. Ir. H. Soekarno No.682 Surabaya 60294<br>
                E-Mail: saintek@uinsa.ac.id Website: https://uinsa.ac.id/fst</p>
            </div>

            <!-- TITLE -->
            <div class='title'>
                <h4>NOTA DINAS</h4>
            </div>

            <!-- NOMOR SURAT -->
            <div class='nomor'>
                <p><strong>Nomor: {$noSurat}</strong></p>
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
                perihal izin untuk acara <strong>{$booking->activity_name}</strong> oleh {$booking->organization} 
                yang akan dilaksanakan pada:
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
                dengan sebaik-baiknya dengan mematuhi peraturan yang berlaku dan menjaga 
                reputasi lembaga.
            </p>

            <p class='content'>
                Demikian atas perhatiannya disampaikan terima kasih.
            </p>

            <!-- CLOSING GREETING -->
            <p class='greeting'>Wassalamu'alaikum Wr. Wb.</p>

            <!-- SIGNATURE -->
            <div class='signature'>
                <p>a.n. Dekan</p>
                <p>Wakil Dekan Bidang</p>
                <p>Kemahasiswaan dan Kerjasama,</p>";

        // Tambahkan gambar TTD jika ada
        if ($ttdPath && file_exists($ttdPath)) {
            // Konversi gambar ke base64 agar bisa diembed di PDF
            $imageData = base64_encode(file_get_contents($ttdPath));
            $imageInfo = getimagesize($ttdPath);
            $mimeType = $imageInfo['mime'];

            $html .= "
                <img src='data:{$mimeType};base64,{$imageData}' alt='TTD'>";
        } else {
            $html .= "
                <p style='margin: 60px 0;'></p>";
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
