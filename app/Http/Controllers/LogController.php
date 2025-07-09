<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogController extends Controller
{
    public function export($logId, $format)
    {
        $log = Log::findOrFail($logId);

        if ($format === 'csv') {
            return $this->exportAsCsv($log);
        } elseif ($format === 'pdf') {
            return $this->exportAsPdf($log);
        }

        return redirect()->back()->with('error', 'Invalid format selected');
    }

    private function exportAsCsv($log)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="log-' . $log->id . '.csv"',
        ];

        $callback = function () use ($log) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Action', 'Performed By', 'Details', 'Date']);
            fputcsv($file, [$log->action, $log->user->name, $log->details, $log->created_at]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportAsPdf($log)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('exports.log', ['log' => $log]);
        return $pdf->download('log-' . $log->id . '.pdf');
    }
}

// namespace App\Http\Controllers;

// use App\Models\Log;
// use Illuminate\Http\Request;

// class LogController extends Controller
// {
//     public function export($logId, $format)
//     {
//         $log = Log::findOrFail($logId);

//         if ($format === 'csv') {
//             // Generate CSV
//             $headers = ['Content-Type' => 'text/csv'];
//             $filename = "log_{$logId}.csv";
//             $content = "Action,Performed By,Details,Date\n";
//             $content .= "{$log->action},{$log->user->name},{$log->details},{$log->created_at}";

//             return response($content, 200, array_merge($headers, [
//                 'Content-Disposition' => "attachment; filename=$filename"
//             ]));
//         }

//         if ($format === 'pdf') {
//             // Generate PDF
//             $pdf = PDF::loadView('logs.pdf', compact('log')); // Use a dedicated Blade template for PDF
//             return $pdf->download("log_{$logId}.pdf");
//         }

//         return back()->with('error', 'Invalid format selected');
//     }

// }
