<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Dompdf\Dompdf;
use Dompdf\Options;

class NodinBarangGenerator
{
    public static function generate($booking, $namaKabag, $nipKabag, $signaturePath)
    {
        // Ambil data barang yang dipinjam - JIKA LEBIH DARI 1 BARANG, SESUAIKAN LOGIKA INI
        $itemName = $booking->item->name ?? '-';
        $itemQty = $booking->qty ?? 0;
        
        // Format tanggal
        $tanggalKegiatan = Carbon::parse($booking->bookingClass->start_date)->translatedFormat('d F Y');
        $tanggalSuratDibuat = Carbon::now()->translatedFormat('d F Y');
        $hariPengembalian = $booking->hari_pengembalian ?? '-';
        $tanggalPengembalian = $booking->tanggal_pengembalian 
            ? Carbon::parse($booking->tanggal_pengembalian)->translatedFormat('d F Y') 
            : '-';
        $jamPengembalian = $booking->jam_pengembalian ?? '-';

        // Buat HTML untuk PDF
        $html = "
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12pt;
                line-height: 1.6;
                margin: 40px;
            }
            h3 {
                text-align: center;
                margin-bottom: 5px;
                font-size: 14pt;
            }
            .center {
                text-align: center;
            }
            table {
                width: 100%;
                margin-top: 20px;
                border-collapse: collapse;
            }
            table td {
                padding: 5px;
                vertical-align: top;
            }
            .signature-section {
                margin-top: 50px;
                text-align: right;
            }
            .signature-section p {
                margin: 5px 0;
            }
            .notes {
                margin-top: 30px;
                font-weight: bold;
            }
            hr {
                border: none;
                border-top: 2px solid #000;
                margin: 10px 0;
            }
        </style>
        
        <h3>SURAT PERNYATAAN KESANGGUPAN</h3>
        
        <table style='margin-top: 30px;'>
            <tr>
                <td colspan='3'><strong>Saya yang bertandatangan dibawah ini:</strong></td>
            </tr>
            <tr>
                <td style='width: 150px;'>Nama</td>
                <td style='width: 20px;'>:</td>
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

        <table style='margin-top: 20px;'>
            <tr>
                <td style='width: 150px;'>Tanggal</td>
                <td style='width: 20px;'>:</td>
                <td>{$tanggalKegiatan}</td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td>:</td>
                <td>{$booking->bookingClass->activity_name}</td>
            </tr>
        </table>

        <table style='margin-top: 20px;'>
            <tr>
                <td style='width: 30px;'>1.</td>
                <td>{$itemName} ({$itemQty} unit)</td>
            </tr>
        </table>

        <p style='margin-top: 20px; text-align: justify;'>
            <strong>Bertanggung jawab/menggantikan kerusakan akibat kelalaian saya atau peserta kegiatan 
            {$booking->bookingClass->activity_name}.</strong>
        </p>

        <p style='margin-top: 15px; text-align: justify;'>
            Demikianlah surat pernyataan kesanggupan ini dibuat untuk digunakan sebagaimana mestinya.
        </p>

        <div class='signature-section'>
            <p>Surabaya, {$tanggalSuratDibuat}</p>
            <p>Yang Menyatakan,</p>
            <br><br><br>
            <p><strong>{$booking->user->name}</strong></p>
            <p>{$booking->user->nim}</p>
        </div>

        <table style='margin-top: 40px;'>
            <tr>
                <td style='width: 200px;'><strong>Mengetahui</strong></td>
                <td></td>
            </tr>
            <tr>
                <td colspan='2'><strong>Kabag Tata Usaha {$booking->faculty->name}</strong></td>
            </tr>
            <tr>
                <td colspan='2' style='padding-top: 10px;'>
                    <img src='" . public_path('storage/' . $signaturePath) . "' height='80' style='display: block;'>
                </td>
            </tr>
            <tr>
                <td colspan='2'><strong>{$namaKabag}</strong></td>
            </tr>
            <tr>
                <td colspan='2'>{$nipKabag}</td>
            </tr>
        </table>

        <div class='notes'>
            <p>Barang yang saya pinjam akan saya kembalikan pada:</p>
            <table style='margin-top: 10px;'>
                <tr>
                    <td style='width: 150px;'>Hari</td>
                    <td style='width: 20px;'>:</td>
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

        <div style='margin-top: 30px;'>
            <p><strong>Catatan:</strong></p>
            <p><strong>- Menyerahkan KTP Ke Tatausaha Fakultas</strong></p>
            <p><strong>- Menyerahkan Surat Pernyataan kesanggupan ke Tatausaha Fakultas</strong></p>
        </div>
        ";

        // Setup Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Simpan PDF
        $fileName = 'surat_kesanggupan_barang_' . $booking->id . '.pdf';
        $pdfPath = storage_path('app/public/nodin_barang/' . $fileName);
        
        // Buat folder jika belum ada
        if (!file_exists(storage_path('app/public/nodin_barang'))) {
            mkdir(storage_path('app/public/nodin_barang'), 0755, true);
        }

        file_put_contents($pdfPath, $dompdf->output());

        // Return relative path untuk disimpan di database
        return 'storage/nodin_barang/' . $fileName;
    }
}