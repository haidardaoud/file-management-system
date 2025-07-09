<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupRepository
{
    public function createGroup($userId, $request)
    {
        return DB::transaction(function () use ($userId, $request) {
            $imageUrl = $this->handleImageUpload($request);

            $group = Group::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'image' => $imageUrl,
            ]);

            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $userId,
                'isOwner' => true,
            ]);

            return $group;
        });
    }

    private function handleImageUpload($request)
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public', $imageName);
        return Storage::url($path);
    }

    public function getUserGroups($userId)
    {
        return Group::with(['groupMember.user:id,name,email'])
            ->whereHas('groupMember', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->select('id', 'name', 'description', 'image')
            ->get();
    }

    public function getGroupById($groupId)
    {
        return Group::with('groupMember.user')->find($groupId);
    }
    public function getGroupOwnerId($groupId)
    {
        $owner =Group::with('groupMember.user')->find($groupId);

        return $owner->isOwner;
    }

    public function isAdmin($groupId, $userId)
    {
        return GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->where('isOwner', true)
            ->exists();
    }

    public function findGroupMember($groupId, $userId)
    {
        return GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();
    }

    public function removeMember($groupMember)
    {
        $groupMember->delete();
    }
}













// namespace App\Repositories;

// use App\Models\Group;
// use App\Models\GroupMember;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;

// class GroupRepository
// {
//     /**
//      * Create a new group with the given data and associate the user as the owner.
//      *
//      * @param int $userId
//      * @param array $groupData
//      * @return Group
//      */
//     public function createGroup($userId, $request)
// {
//     return DB::transaction(function () use ($userId, $request) {


//         // Check if an image is uploaded
//         if ($request->hasFile('image'))
//         {
//            $image = $request->file('image');
//            $imageName = time() . '.' . $image->getClientOriginalExtension();
//            $path = $image->storeAs('public', $imageName);
//            $imageUrl = Storage::url($path);
//        }
//         // Create the group
//         $group = Group::create([
//             'name' => $request->input('name'),
//             'description' => $request->input('description'),
//             'image' => $imageUrl, // Save the image path or null if no image uploaded
//         ]);

//         // Add the user as the owner of the group
//         GroupMember::create([
//             'group_id' => $group->id,
//             'user_id' => $userId,
//             'isOwner' => true,
//         ]);

//         return $group;
//     });
// }

// public function getUserGroups($user_id)
// {
//     return Group::with('groupMember')->whereHas('groupMember', function ($query) use ($user_id) {
//         $query->where('user_id', $user_id);
//     })->select('id', 'name', 'description', 'image')->get();
// }

// public function getGroupById($id)
// {
//     return Group::with('groupMember')->find($id);
// }


// }
