<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\FileVersion;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use SebastianBergmann\Diff\Differ;

class PDFController extends Controller
{
    public function generatePDFDiff($logId)
    {
        $log = Log::findOrFail($logId);
        $fileId = $log->file_id;
        $currentVersion = FileVersion::where('file_id', $fileId)->max('version_number');

        if (!$currentVersion || $currentVersion < 2) {
            throw new \Exception('Not enough versions available for comparison.');
        }

        $diffContent = $this->generateDiffContent($fileId, $currentVersion);
        $pdfPath = storage_path("app/public/diffs/{$fileId}_diff_v{$currentVersion}.pdf");

        PDF::loadHTML($diffContent)->save($pdfPath);

        $log->update(['details' => "Diff file generated: {$pdfPath}"]);

        return response()->json(['message' => 'PDF generated successfully']);
    }

    public function viewPDFDiff($logId)
    {
        $log = Log::findOrFail($logId);
        $fileId = $log->file_id;
        $currentVersion = FileVersion::where('file_id', $fileId)->max('version_number');
        $pdfPath = storage_path("app/public/diffs/{$fileId}_diff_v{$currentVersion}.pdf");

        if (!file_exists($pdfPath)) {
            abort(404, 'PDF file not found');
        }

        return response()->file($pdfPath);
    }

    private function generateDiffContent($fileId, $currentVersion)
    {
        $oldVersion = $currentVersion - 1;

        // استرجاع محتوى الإصدارين (تعديل هذا الجزء حسب هيكل جدول FileVersion لديك)
        $oldFile = FileVersion::where('file_id', $fileId)
                              ->where('version_number', $oldVersion)
                              ->first();

        $newFile = FileVersion::where('file_id', $fileId)
                              ->where('version_number', $currentVersion)
                              ->first();

        $oldContent = $oldFile ? $oldFile->getContent() : '';
        $newContent = $newFile ? $newFile->getContent() : '';

        // إنشاء المقارنة
        $differ = new Differ($oldContent);
        $diff = $differ->diff($oldContent, $newContent);

        // تنسيق المخرجات بشكل HTML
        $htmlDiff = "<h2>Differences between version {$oldVersion} and {$currentVersion}:</h2>";
        $htmlDiff .= "<pre>" . htmlspecialchars($diff) . "</pre>";

        return $htmlDiff;
    }
}
