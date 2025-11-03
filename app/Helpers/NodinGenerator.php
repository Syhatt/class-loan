<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Dompdf\Dompdf;

class NodinGenerator
{
    public static function generate($booking, $noSurat, $namaWadek, $signaturePath)
    {
        // Path template DOCX asli
        $templatePath = storage_path('app/templates/nodin_template.docx');

        // Buat TemplateProcessor
        $template = new TemplateProcessor($templatePath);

        // === ISI SEMUA FIELD SESUAI TEMPLATE ===
        $template->setValue('FAKULTAS YANG MENYEDIAKAN RUANGAN', $booking->faculty->name ?? '-');
        $template->setValue('NO SURAT KELUAR', $noSurat);
        $template->setValue('NAMA ORGANISASI', $booking->organization);
        $template->setValue('NOMOR SURAT PERMOHONAN IZIN KEGIATAN', $booking->no_letter);
        $template->setValue('TANGGAL SURAT PERMOHONAN IZIN KEGIATAN', Carbon::parse($booking->date_letter)->translatedFormat('d F Y'));
        $template->setValue('NAMA KEGIATAN', $booking->activity_name);
        $template->setValue('HARI, TANGGAL KEGIATAN', Carbon::parse($booking->start_date)->translatedFormat('l, d F Y'));
        $template->setValue('JAM KEGIATAN (AWAL s.d. SELESAI)', $booking->start_time . ' s.d. ' . $booking->end_time);
        $template->setValue('RUANG/KELAS YANG DIPINJAM', $booking->classmodel->name);
        $template->setValue('TANGGAL SURAT KELUAR', Carbon::now()->translatedFormat('d F Y'));
        $template->setValue('NAMA WADEK FAKULTAS', $namaWadek);

        // === Simpan hasil DOCX sementara ===
        $docxPath = storage_path('app/public/nodin/nodin_' . $booking->id . '.docx');
        $template->saveAs($docxPath);

        // === Buat versi PDF-nya (tampilan mirip template, rapi) ===
        $html = "
        <div style='font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5;'>
            <h3 style='text-align:center; margin-bottom: 0;'>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h3>
            <h4 style='text-align:center; margin-top: 0;'>UNIVERSITAS ISLAM NEGERI SUNAN AMPEL SURABAYA</h4>
            <p style='text-align:center; font-weight:bold;'>{$booking->faculty->name}</p>
            <p style='text-align:center;'>Jl. Dr. Ir. H. Soekarno No.682 Surabaya 60294<br>
            E-Mail: saintek@uinsa.ac.id Website: https://uinsa.ac.id/fst</p>
            <hr>

            <h4 style='text-align:center; text-decoration:underline;'>NOTA DINAS</h4>
            <p><strong>Nomor:</strong> {$noSurat}</p>

            <table style='width:100%; margin-top:10px;'>
                <tr><td style='width:15%;'>Yth.</td><td>: Ketua {$booking->organization}</td></tr>
                <tr><td>Dari</td><td>: Dekan</td></tr>
                <tr><td>Hal</td><td>: Izin Kegiatan</td></tr>
                <tr><td>Tanggal</td><td>: " . Carbon::now()->translatedFormat('d F Y') . "</td></tr>
            </table>

            <p style='margin-top:20px;'>Assalamu’alaikum Wr. Wb.</p>

            <p style='text-align:justify;'>
                Memperhatikan surat dari Ketua {$booking->organization} Fakultas {$booking->faculty->name}
                UIN Sunan Ampel Surabaya Nomor {$booking->no_letter} tanggal
                " . Carbon::parse($booking->date_letter)->translatedFormat('d F Y') . "
                perihal izin untuk acara <strong>{$booking->activity_name}</strong> oleh {$booking->organization}
                yang akan dilaksanakan pada:
            </p>

            <table style='margin-left:40px;'>
                <tr><td>Hari, Tanggal</td><td>: " . Carbon::parse($booking->start_date)->translatedFormat('l, d F Y') . "</td></tr>
                <tr><td>Jam</td><td>: {$booking->start_time} s.d. {$booking->end_time} WIB</td></tr>
                <tr><td>Tempat</td><td>: {$booking->classmodel->name}</td></tr>
            </table>

            <p style='margin-top:15px; text-align:justify;'>
                Maka bersama ini kami memberikan izin kegiatan dimaksud untuk dilaksanakan
                dengan sebaik-baiknya dengan mematuhi peraturan yang berlaku dan menjaga
                reputasi lembaga.
            </p>

            <p style='margin-top:15px;'>Demikian atas perhatiannya disampaikan terima kasih.</p>

            <p>Wassalamu’alaikum Wr. Wb.</p>

            <div style='text-align:right; margin-top:50px;'>
                <p>a.n. Dekan<br>Wakil Dekan Bidang Kemahasiswaan dan Kerjasama,</p>
                <img src='" . public_path('storage/' . $signaturePath) . "' height='80'>
                <p style='margin-top:10px;'><strong>{$namaWadek}</strong></p>
            </div>
        </div>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfPath = storage_path('app/public/nodin/nodin_' . $booking->id . '.pdf');
        file_put_contents($pdfPath, $dompdf->output());

        return 'storage/nodin/nodin_' . $booking->id . '.pdf';
    }
}
