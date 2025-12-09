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

        $tanggalKegiatan      = Carbon::parse($booking->bookingClass->start_date)->translatedFormat('d F Y');
        $tanggalSuratDibuat   = Carbon::now()->translatedFormat('d F Y');
        $hariPengembalian     = $booking->hari_pengembalian ?? '-';
        $tanggalPengembalian  = $booking->tanggal_pengembalian
            ? Carbon::parse($booking->tanggal_pengembalian)->translatedFormat('d F Y')
            : '-';
        $jamPengembalian      = $booking->jam_pengembalian ?? '-';

        $itemsHtml = "
            <tr>
                <td style='width: 20px;'>1.</td>
                <td>{$itemName} ({$itemQty} unit)</td>
            </tr>
        ";

        $html = "
        <style>
            @page {
                margin: 15px 20px;
            }
            * {
                box-sizing: border-box;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 10pt;
                line-height: 1.15;
                margin: 0;
            }
            h3 {
                text-align: center;
                margin: 0 0 8px 0;
                font-size: 11.5pt;
                text-transform: uppercase;
            }
            p {
                margin: 2px 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            td {
                vertical-align: top;
                padding: 1px 0;
            }
            .section {
                margin-top: 6px;
            }
            .section-title {
                margin-top: 8px;
                font-weight: bold;
            }
            .ttd-wrapper {
                margin-top: 14px;
            }
            .ttd-table {
                width: 100%;
            }
            .ttd-left, .ttd-right {
                font-size: 10pt;
            }
            .ttd-left {
                width: 55%;
            }
            .ttd-right {
                width: 45%;
                text-align: left;
            }
            .ttd-space {
                height: 32px;
            }
            .notes-title {
                margin-top: 10px;
                font-weight: bold;
            }
            .sub-table td:first-child {
                width: 110px;
            }
            .sub-table td:nth-child(2) {
                width: 12px;
            }
        </style>

        <h3>SURAT PERNYATAAN KESANGGUPAN</h3>

        <div class='section'>
            <p>Saya yang bertanda tangan di bawah ini:</p>
            <table>
                <tr>
                    <td style='width: 110px;'>Nama</td>
                    <td style='width: 12px;'>:</td>
                    <td>{$booking->user->name}</td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td>:</td>
                    <td>{$booking->user->nim}</td>
                </tr>
                <tr>
                    <td>Organisasi</td>
                    <td>:</td>
                    <td>{$booking->bookingClass->organization}</td>
                </tr>
                <tr>
                    <td>Nomor WA</td>
                    <td>:</td>
                    <td>{$booking->bookingClass->telp}</td>
                </tr>
            </table>
        </div>

        <div class='section'>
            <table>
                <tr>
                    <td style='width: 110px;'>Tanggal</td>
                    <td style='width: 12px;'>:</td>
                    <td>{$tanggalKegiatan}</td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td>:</td>
                    <td>{$booking->bookingClass->activity_name}</td>
                </tr>
            </table>
        </div>

        <div class='section'>
            <table>
                {$itemsHtml}
            </table>
        </div>

        <div class='section' style='text-align: justify;'>
            <p>
                Bertanggung jawab/mengganti kerusakan akibat kelalaian saya atau peserta kegiatan
                {$booking->bookingClass->activity_name}.
            </p>
        </div>

        <div class='section' style='text-align: justify;'>
            <p>
                Demikianlah surat pernyataan kesanggupan ini dibuat untuk digunakan sebagaimana mestinya.
            </p>
        </div>

        <div class='ttd-wrapper'>
            <table class='ttd-table'>
                <tr>
                    <td class='ttd-left'>
                        <p>Mengetahui</p>
                        <p>Kabag Tata Usaha {$booking->faculty->name},</p>
                        <div class='ttd-space'>
                            <img src='" . public_path('storage/' . $signaturePath) . "' height='55'>
                        </div>
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
            <p class='section-title'>Barang yang saya pinjam akan saya kembalikan pada:</p>
            <table class='sub-table'>
                <tr>
                    <td>Hari</td>
                    <td>:</td>
                    <td>{$hariPengembalian}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{$tanggalPengembalian}</td>
                </tr>
                <tr>
                    <td>Pukul</td>
                    <td>:</td>
                    <td>{$jamPengembalian}</td>
                </tr>
            </table>
        </div>

        <div class='section'>
            <p class='notes-title'>Catatan:</p>
            <p>- Menyerahkan KTP ke Tatausaha Fakultas</p>
            <p>- Menyerahkan Surat Pernyataan Kesanggupan ke Tatausaha Fakultas</p>
        </div>
        ";

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = 'surat_kesanggupan_barang_' . $booking->id . '.pdf';
        $dirPath  = storage_path('app/public/nodin_barang');
        $pdfPath  = $dirPath . '/' . $fileName;

        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        file_put_contents($pdfPath, $dompdf->output());

        return 'storage/nodin_barang/' . $fileName;
    }
}
