<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class NodinGenerator
{
    public static function generate($booking, $noSurat, $namaWadek, $signaturePath)
    {
        $templatePath = storage_path('app/templates/nodin_template.docx');
        $phpWord = new TemplateProcessor($templatePath);

        // --- Isi template berdasarkan data booking ---
        $phpWord->setValue('FAKULTAS', $booking->faculty->name ?? '-');
        $phpWord->setValue('NO_SURAT_KELUAR', $noSurat);
        $phpWord->setValue('TANGGAL_SURAT', Carbon::now()->translatedFormat('d F Y'));
        $phpWord->setValue('NAMA_ORGANISASI', $booking->organization);
        $phpWord->setValue('NO_SURAT_PERMOHONAN', $booking->no_letter);
        $phpWord->setValue('TANGGAL_SURAT_PERMOHONAN', Carbon::parse($booking->date_letter)->translatedFormat('d F Y'));
        $phpWord->setValue('NAMA_KEGIATAN', $booking->activity_name);
        $phpWord->setValue('TANGGAL_KEGIATAN', Carbon::parse($booking->start_date)->translatedFormat('l, d F Y'));
        $phpWord->setValue('JAM_KEGIATAN', $booking->start_time . ' s.d. ' . $booking->end_time);
        $phpWord->setValue('RUANGAN', $booking->classmodel->name);
        $phpWord->setValue('NAMA_WADEK', $namaWadek);

        // Temp file DOCX
        $outputDocx = storage_path('app/public/nodin/nodin_' . $booking->id . '.docx');
        $phpWord->saveAs($outputDocx);

        // Convert ke PDF
        $pdfOutput = storage_path('app/public/nodin/nodin_' . $booking->id . '.pdf');

        // Konversi manual DOCX -> PDF (render HTML dulu)
        $dompdf = new Dompdf();
        $html = "
            <h2 style='text-align:center;'>Nota Dinas Peminjaman Ruangan</h2>
            <p>Nomor Surat: $noSurat</p>
            <p>Fakultas: {$booking->faculty->name}</p>
            <p>Organisasi: {$booking->organization}</p>
            <p>Kegiatan: {$booking->activity_name}</p>
            <p>Tanggal: " . Carbon::parse($booking->start_date)->translatedFormat('d F Y') . "</p>
            <p>Waktu: {$booking->start_time} s.d. {$booking->end_time}</p>
            <p>Ruangan: {$booking->classmodel->name}</p>
            <br><br>
            <p style='text-align:right;'>Mengetahui,<br>Wakil Dekan<br><br><img src='" . public_path('storage/' . $signaturePath) . "' height='80'><br>$namaWadek</p>
        ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        file_put_contents($pdfOutput, $dompdf->output());

        // Simpan path PDF
        return 'storage/nodin/nodin_' . $booking->id . '.pdf';
    }
}
