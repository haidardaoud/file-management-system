<?php
namespace App\Repositories;

use App\Models\File;
use App\Models\Group;
use App\Models\Group_members;
use App\Models\GroupMember;
use App\Models\Request_approval;
use App\Models\RequestApproval;

class RequestApprovalRepository
{
    public function createRequest($data, $file)
    {
        $groupID = $data['group_id'];

        // Get the group owner
        $owner = GroupMember::where('group_id', $groupID)
            ->where('isOwner', 1)
            ->first();

        if (!$owner) {
            throw new \Exception('Owner not found for the group.');
        }

        // Save the file
        $filePath = $file->store('uploads');
        if (!$filePath) {
            throw new \Exception('File upload failed.');
        }

        // Create file record
        $fileModel = new File();
        $fileModel->name = $file->getClientOriginalName();
        $fileModel->filePath = $filePath;
        $fileModel->user_id = auth()->id();
        $fileModel->group_id = $groupID;
        $fileModel->save();

        // Create request approval
        $data['user_id'] = auth()->id();
        $data['owner_id'] = $owner->user_id;
        $data['file_id'] = $fileModel->id;

        return RequestApproval::create($data);
    }

public function getPendingRequestsByGroupId($groupId)
{
    return RequestApproval::with(['user', 'file'])
        ->where('status', 'pending')
        ->whereHas('file', function ($query) use ($groupId) {
            $query->where('group_id', $groupId);
        })
        ->get();
}


    public function findRequestById($id)
{
    return RequestApproval::with('file')->find($id);
}


    public function updateRequestStatus($requestId, $status)
    {
        $request = $this->findRequestById($requestId);
        $request->status = $status;
        return $request->save();
    }
}
