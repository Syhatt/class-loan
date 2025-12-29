<?php

namespace App\Helpers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class NodinBarangGenerator
{
    public static function generate($booking, $namaKabag, $nipKabag, $signaturePath)
    {
        $itemName = $booking->item->name ?? '-';
        $itemQty  = $booking->qty ?? 0;

        $tanggalKegiatan     = Carbon::parse($booking->bookingClass->start_date)->translatedFormat('d F Y');
        $tanggalSuratDibuat  = Carbon::now()->translatedFormat('d F Y');
        $hariPengembalian    = $booking->hari_pengembalian ?? '-';
        $tanggalPengembalian = $booking->tanggal_pengembalian
            ? Carbon::parse($booking->tanggal_pengembalian)->translatedFormat('d F Y')
            : '-';
        $jamPengembalian     = $booking->jam_pengembalian ?? '-';

        // ===============================
        // TTD KABAG (BASE64 – DOMPDF SAFE)
        // ===============================
        $ttdKabagImg = '';

        if ($signaturePath) {
            $fullPath = public_path('storage/' . $signaturePath);

            if (file_exists($fullPath)) {
                $info = getimagesize($fullPath);

                // Pastikan bukan SVG
                if ($info && $info['mime'] !== 'image/svg+xml') {
                    $imgData = base64_encode(file_get_contents($fullPath));
                    $mime    = $info['mime'];
                    $ttdKabagImg = "<img src='data:$mime;base64,$imgData' height='55'>";
                }
            }
        }

        $itemsHtml = "
            <tr>
                <td style='width: 20px;'>1.</td>
                <td>{$itemName} ({$itemQty} unit)</td>
            </tr>
        ";

        $html = "
        <style>
            @page { margin: 15px 20px; }

            body {
                font-family: Arial, sans-serif;
                font-size: 10pt;
                line-height: 1.15;
            }

            h3 {
                text-align: center;
                font-size: 11.5pt;
                margin-bottom: 10px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                vertical-align: top;
                padding: 2px 0;
            }

            .section {
                margin-top: 8px;
            }

            .ttd-wrapper {
                margin-top: 28px;
            }

            .ttd-left {
                width: 55%;
            }

            .ttd-right {
                width: 45%;
            }

            .ttd-space {
                height: 70px;
            }

            .ttd-space img {
                display: block;
                margin-top: 6px;
            }

            .sub-table td:first-child {
                width: 110px;
            }

            .sub-table td:nth-child(2) {
                width: 12px;
            }

            .notes-title {
                margin-top: 12px;
                font-weight: bold;
            }

            .notes-list {
                margin-left: 12px;
            }
        </style>

        <h3>SURAT PERNYATAAN KESANGGUPAN</h3>

        <div class='section'>
            <p>Saya yang bertanda tangan di bawah ini:</p>
            <table>
                <tr><td style='width:110px;'>Nama</td><td>:</td><td>{$booking->user->name}</td></tr>
                <tr><td>NIM</td><td>:</td><td>{$booking->user->nim}</td></tr>
                <tr><td>Organisasi</td><td>:</td><td>{$booking->bookingClass->organization}</td></tr>
                <tr><td>Nomor WA</td><td>:</td><td>{$booking->bookingClass->telp}</td></tr>
            </table>
        </div>

        <div class='section'>
            <table>
                <tr><td style='width:110px;'>Tanggal</td><td>:</td><td>{$tanggalKegiatan}</td></tr>
                <tr><td>Kegiatan</td><td>:</td><td>{$booking->bookingClass->activity_name}</td></tr>
            </table>
        </div>

        <div class='section'>
            <table>{$itemsHtml}</table>
        </div>

        <div class='section'>
            <p>
                Bertanggung jawab/mengganti kerusakan akibat kelalaian saya atau peserta kegiatan
                {$booking->bookingClass->activity_name}.
            </p>
        </div>

        <div class='section'>
            <p>
                Demikianlah surat pernyataan ini dibuat untuk digunakan sebagaimana mestinya.
            </p>
        </div>

        <div class='ttd-wrapper'>
            <table>
                <tr>
                    <td class='ttd-left'>
                        <p>Mengetahui</p>
                        <p>Kabag Tata Usaha {$booking->faculty->name},</p>
                        <div class='ttd-space'>{$ttdKabagImg}</div>
                        <p><strong>{$namaKabag}</strong></p>
                        <p>{$nipKabag}</p>
                    </td>

                    <td class='ttd-right'>
                        <p>Surabaya, {$tanggalSuratDibuat}</p>
                        <p>Yang Menyatakan,</p>
                        <div class='ttd-space'></div>
                        <p><strong>{$booking->user->name}</strong></p>
                        <p>{$booking->user->nim}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class='section'>
            <p><strong>Barang yang saya pinjam akan saya kembalikan pada:</strong></p>
            <table class='sub-table'>
                <tr><td>Hari</td><td>:</td><td>{$hariPengembalian}</td></tr>
                <tr><td>Tanggal</td><td>:</td><td>{$tanggalPengembalian}</td></tr>
                <tr><td>Pukul</td><td>:</td><td>{$jamPengembalian}</td></tr>
            </table>
        </div>
        <div class='section'>
            <p class='notes-title'>Catatan:</p>
        <div class='notes-list'>
            <p>- Menyerahkan KTP ke Tatausaha Fakultas</p>
            <p>- Menyerahkan Surat Pernyataan Kesanggupan ke Tatausaha Fakultas</p>
            </div>
        </div>
        ";

        $dompdf = new Dompdf(new Options([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
        ]));

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dirPath = storage_path('app/public/nodin_barang');
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $fileName = 'surat_kesanggupan_barang_' . $booking->id . '.pdf';
        file_put_contents($dirPath . '/' . $fileName, $dompdf->output());

        return 'storage/nodin_barang/' . $fileName;
    }
}
