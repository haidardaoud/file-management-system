<?php

// namespace App\Http\Controllers;

// use App\Services\LogService;

// class MemberController extends Controller
// {
//     protected $logService;

//     public function __construct(LogService $logService)
//     {
//         $this->logService = $logService;
//     }

//     // public function show($groupId)
//     // {
//     //     // استخدام الخدمة للحصول على السجلات
//     //     $logs = $this->logService->getLogsByGroupId($groupId);
//     //     return view('member', compact('logs'));
//     // }

//     public function exportLog($logId, $format)
//     {
//         // استخدام الخدمة للعثور على السجل
//         $log = $this->logService->export($logId,$format);

//         if ($format === 'csv') {
//             return $this->logService->exportAsCsv($log);
//         } elseif ($format === 'pdf') {
//             return $this->logService->exportAsPdf($log);
//         }

//         abort(404);
//     }
// }
