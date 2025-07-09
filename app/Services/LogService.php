<?php
namespace App\Services;

use App\Models\Log;
use App\Repositories\LogRepository;
use Illuminate\Support\Facades\Response;

class LogService
{
    protected $logRepository;

    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function show($groupId)
    {
        $logs = Log::where('group_id', $groupId)->get();
        return view('member', compact('logs'));
    }
    public function export($logId, $format)
    {
        $log = $this->logRepository->findById($logId);

        if ($format === 'csv') {
            return $this->exportAsCsv($log);
        } elseif ($format === 'pdf') {
            return $this->exportAsPdf($log);
        }

        throw new \Exception('Invalid format selected');
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

        return Response::stream($callback, 200, $headers);
    }

    private function exportAsPdf($log)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('exports.log', ['log' => $log]);
        return $pdf->download('log-' . $log->id . '.pdf');
    }
}
