<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\GroupMember;
use Exception;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users except authenticated user and group members.
     *
     * @param int $authUserId
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsersExceptAuthAndGroupMembers($authUserId, $groupId)
    {
        return $this->userRepository->getAllUsersExceptAuthAndGroupMembers($authUserId, $groupId);
    }

    /**
     * Search for users by query, excluding authenticated user and group members.
     *
     * @param string $query
     * @param int $authUserId
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchUsers($query, $authUserId, $groupId)
    {
        return $this->userRepository->searchUsers($query, $authUserId, $groupId);
    }

    /**
     * Check if a user is the admin of a group.
     *
     * @param int $groupId
     * @param int $userId
     * @return bool
     */
    public function checkIfAdmin($groupId, $userId)
    {
        return GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->where('isOwner', true)
            ->exists();
    }

    /**
     * Add multiple users to a group.
     *
     * @param array $userIds
     * @param int $groupId
     * @throws Exception
     */
    public function addUsersToGroup($userIds, $groupId)
    {
        foreach ($userIds as $userId) {
            GroupMember::create([
                'group_id' => $groupId,
                'user_id' => $userId,
                'isOwner' => false,
            ]);
        }
    }

    /**
     * Remove a user from a group.
     *
     * @param int $groupId
     * @param int $userId
     * @param int $authUserId
     * @throws Exception
     */
    public function removeUserFromGroup($groupId, $userId, $authUserId)
    {
        if (!$this->checkIfAdmin($groupId, $authUserId)) {
            throw new Exception('Only the group admin can remove users.');
        }

        $member = GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();

        if (!$member) {
            throw new Exception('User not found in the group.');
        }

        $member->delete();
    }
}


// namespace App\Services;

// use App\Repositories\UserRepository;

// class UserService
// {
//     protected $userRepository;

//     public function __construct(UserRepository $userRepository)
//     {
//         $this->userRepository = $userRepository;
//     }

//     public function getAllUsersExceptAuthAndGroupMembers($authUserId, $groupId)
//     {
//         return $this->userRepository->getAllUsersExceptAuthAndGroupMembers($authUserId, $groupId);
//     }

//     public function searchUsers($query, $authUserId, $groupId)
//      {
//          return $this->userRepository->searchUsers($query, $authUserId, $groupId);
//      }
// }
