<?php

namespace App\Services;

use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Settings;

class NodinGenerator
{
    public static function generate($booking, $noSurat, $signaturePath)
    {
        $templatePath = storage_path('app/public/templates/nodin_template.docx');

        if (!file_exists($templatePath)) {
            throw new \Exception("Template Nota Dinas tidak ditemukan di: {$templatePath}");
        }

        $template = new TemplateProcessor($templatePath);

        $tanggal = \Carbon\Carbon::parse($booking->start_date)->translatedFormat('d F Y');
        $hari = \Carbon\Carbon::parse($booking->start_date)->translatedFormat('l');
        $waktu = $booking->start_time . ' s.d. ' . $booking->end_time;

        $template->setValue('NO_SURAT', $noSurat);
        $template->setValue('FAKULTAS', $booking->faculty->name ?? '-');
        $template->setValue('ORGANISASI', $booking->organization);
        $template->setValue('NAMA_KEGIATAN', $booking->activity_name);
        $template->setValue('TANGGAL_SURAT_PERMOHONAN', $booking->date_letter);
        $template->setValue('HARI_TANGGAL', "{$hari}, {$tanggal}");
        $template->setValue('WAKTU', $waktu);
        $template->setValue('RUANGAN', $booking->classmodel->name ?? '-');
        $template->setValue('WADEK', 'Wakil Dekan ' . ($booking->faculty->name ?? ''));

        if ($signaturePath && file_exists(public_path('storage/' . $signaturePath))) {
            $template->setImageValue('TTD_PATH', [
                'path' => public_path('storage/' . $signaturePath),
                'width' => 120,
                'height' => 120,
                'ratio' => false,
            ]);
        }

        $fileName = 'nodin_' . $booking->id . '_' . \Illuminate\Support\Str::random(5);
        $wordPath = storage_path("app/public/nodin/{$fileName}.docx");
        $pdfPath = storage_path("app/public/nodin/{$fileName}.pdf");

        if (!file_exists(storage_path('app/public/nodin'))) {
            mkdir(storage_path('app/public/nodin'), 0777, true);
        }

        $template->saveAs($wordPath);

        // 🔧 Konfigurasi renderer untuk export PDF
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

        // ✅ Konversi ke PDF
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($wordPath);
        $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
        $pdfWriter->save($pdfPath);

        return 'storage/nodin/' . basename($pdfPath);
    }
}

