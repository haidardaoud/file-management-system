<?php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\RequestApprovalService;
use Illuminate\Http\Request;

class RequestApprovalController extends Controller
{
    protected $requestApprovalService;

    public function __construct(RequestApprovalService $requestApprovalService)
    {
        $this->requestApprovalService = $requestApprovalService;
    }

    public function createRequest(Request $request, $groupId)
    {

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'file' => 'required|file|mimes:pdf,docx,txt|max:2048',
        ]);
        //dd($validated);

        // Call the service to create the request with the file
        $this->requestApprovalService->createRequest($validated, $request->file('file'));

        return redirect()->back()->with('success');
        //->route('Files.pending', ['groupId' => $groupId])->with('success', 'Request submitted successfully!');
    }

    public function listPendingRequests($groupId)
    {

        $requests = $this->requestApprovalService->getPendingRequests($groupId);
        return view('File.pending', compact('requests', 'groupId'));
    }

    public function approveRequest($groupId, $requestId, Request $request)
    {
        $this->requestApprovalService->approveRequest($requestId, $request->all());
        return redirect()->back()->with('success', 'Request approved!');
    }

    public function rejectRequest($groupId, $requestId)
    {
        $this->requestApprovalService->rejectRequest($requestId);
        return redirect()->back()->with('success', 'Request rejected!');
    }

    public function showRequestUploadForm($groupId)
    {
        $group = Group::findOrFail($groupId); // Retrieve group details
        return view('File.request-file-upload', compact('group')); // تمرير البيانات بالطريقة الصحيحة
    }

}
