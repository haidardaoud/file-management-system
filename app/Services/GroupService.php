<?php
namespace App\Services;

use App\Repositories\GroupRepository;

class GroupService
{
    protected $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function createGroup($userId, $request)
    {
        return $this->groupRepository->createGroup($userId, $request);
    }

    public function getGroupsForUser($userId)
    {
        return $this->groupRepository->getUserGroups($userId);
    }

    public function getGroupById($id)
    {
        return $this->groupRepository->getGroupById($id);
    }
    public function getGroupOwnerId($id)
    {
        return $this->groupRepository->getGroupOwnerId($id);
    }

    public function removeUserFromGroup($groupId, $userId, $authUserId)
    {
        if (!$this->groupRepository->isAdmin($groupId, $authUserId)) {
            throw new \Exception(__('Only the group admin can remove users.'));
        }

        $groupMember = $this->groupRepository->findGroupMember($groupId, $userId);

        if (!$groupMember) {
            throw new \Exception(__('User not found in the group.'));
        }

        $this->groupRepository->removeMember($groupMember);
    }
}
