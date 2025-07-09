<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Get all users except the authenticated user and users already in the group.
     *
     * @param int $authUserId
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsersExceptAuthAndGroupMembers($authUserId, $groupId)
    {
        return $this->baseQuery($authUserId, $groupId)->get();
    }

    /**
     * Search users by name excluding the authenticated user and group members.
     *
     * @param string $query
     * @param int $authUserId
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchUsers($query, $authUserId, $groupId)
    {
        return $this->baseQuery($authUserId, $groupId)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get();
    }

    /**
     * Base query to exclude authenticated user and group members.
     *
     * @param int $authUserId
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function baseQuery($authUserId, $groupId)
    {
        return User::where('id', '!=', $authUserId)
            ->whereDoesntHave('groupMember', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            });
    }
}





// namespace App\Repositories;

// use App\Models\User;
// use App\Models\GroupMember;

// class UserRepository
// {
//     /**
//      * Get all users except the authenticated user and users already in the group.
//      *
//      * @param int $authUserId
//      * @param int $groupId
//      * @return \Illuminate\Database\Eloquent\Collection
//      */
//     public function getAllUsersExceptAuthAndGroupMembers($authUserId, $groupId)
//     {
//         return User::where('id', '!=', $authUserId)
//                     ->whereDoesntHave('groupMember', function ($query) use ($groupId) {
//                         $query->where('group_id', $groupId);
//                     })
//                     ->get();
//     }

//  public function searchUsers($query, $authUserId, $groupId)
//   {
//         return User::where('id', '!=', $authUserId)
//         ->whereDoesntHave('groupMember', function ($query) use ($groupId) {
//          $query->where('group_id', $groupId);})
//          ->where('name', 'LIKE', '%' . $query . '%')
//          ->get();
// }
// }
