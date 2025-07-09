<?php
namespace App\Services;

use App\Repositories\RequestApprovalRepository;
use Illuminate\Support\Facades\Storage;

class RequestApprovalService
{
    protected $requestApprovalRepo;

    public function __construct(RequestApprovalRepository $requestApprovalRepo)
    {
        $this->requestApprovalRepo = $requestApprovalRepo;
    }

    public function createRequest($data,$file)
    {
        return $this->requestApprovalRepo->createRequest($data,$file);
    }

    public function getPendingRequests($groupId)
    {
        return $this->requestApprovalRepo->getPendingRequestsByGroupId($groupId);
    }

    public function approveRequest($requestId, $fileData)
{
    $fileRequest = $this->requestApprovalRepo->findRequestById($requestId);

    if (!$fileRequest) {
        throw new \Exception('Request not found.');
    }

    if ($fileRequest->status !== 'pending') {
        throw new \Exception('Request already processed.');
    }

    $file = $fileRequest->file;

    if (!$file || !Storage::exists($file->filePath)) {
        throw new \Exception('File not found on the server.');
    }

    // Update request status
    $this->requestApprovalRepo->updateRequestStatus($requestId, 'approved');

    // Move file to the approved directory
    $newFilePath = 'approved/' . $file->name;
    Storage::move($file->filePath, $newFilePath);

    // Update file path in the database
    $file->filePath = $newFilePath;
    $file->save();
}


    public function rejectRequest($requestId)
    {
        $fileRequest = $this->requestApprovalRepo->findRequestById($requestId);
        if ($fileRequest->status !== 'pending') {
            throw new \Exception('Request already processed.');
        }

        return $this->requestApprovalRepo->updateRequestStatus($requestId, 'rejected');
    }
}
