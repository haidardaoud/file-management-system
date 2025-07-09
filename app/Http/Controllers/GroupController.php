<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Log as ModelsLog;
use App\Models\RequestApproval;
use App\Models\User;
use App\Services\FileService;
use App\Services\GroupService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    protected $groupService;
    protected $requestApprovalService;
    protected $userService;
    protected $fileService;

    public function __construct(GroupService $groupService, UserService $userService, FileService $fileService)
    {
        $this->middleware('groupmiddleware')->except(['show']);
        $this->groupService = $groupService;
        $this->userService = $userService;
        $this->fileService = $fileService;
    }
    public function memberLogs($groupId)
    {
        // التحقق من أن المستخدم الحالي هو منشئ المجموعة
        $group = Group::findOrFail($groupId);
        $user = auth()->user();

        // إذا لم يكن المستخدم هو منشئ المجموعة، يجب إرجاع خطأ أو توجيهه إلى صفحة أخرى
        $isOwner = $group->groupMember()->where('user_id', $user->id)->where('isOwner', 1)->exists();
        if (!$isOwner) {
            abort(403, 'You do not have permission to view member logs.');
        }

        // استرجاع سجلات الأعضاء مع بياناتهم
        $logs = ModelsLog::join('group_members', 'group_members.user_id', '=', 'logs.user_id')
                   ->where('group_members.group_id', '=', $groupId)
                   ->join('users', 'users.id', '=', 'logs.user_id')
                   ->select('logs.*', 'users.name as user_name')
                   ->get();
                //    dd($logs);

        // إرجاع العرض الخاص بالسجلات
        return view('logs.members', compact('group', 'logs'));
    }





    /*
    *   View Group
    */
      public function index()
    {

    $groups = DB::table('groups')
    ->join('group_members', 'groups.id', '=', 'group_members.group_id') // ربط الجداول
    ->select('groups.*', DB::raw('count(group_members.id) as member_count')) // اختيار المجموعات مع عدد الأعضاء
    ->groupBy('groups.id') // تجميع البيانات حسب المجموعة
    ->orderBy('groups.created_at', 'desc') // ترتيب المجموعات حسب الأحدث
    ->take(6) // أخذ أول 6 مجموعات
    ->get();


        return view('index', compact('groups')); // تمرير المجموعات إلى الـ view
    }


    /**
     * Create a new group.
     */
    public function create(GroupRequest $request)
    {
        try {
            $group = $this->groupService->createGroup($request->user()->id, $request);
            return redirect()->route('index')->with('success', __('Group created successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(__('Failed to create group: ') . $e->getMessage());
        }
    }

    /**
     * Display the user's groups.
     */
    public function myGroups()
    {
        $userId = auth()->id();
        $groups = $this->groupService->getGroupsForUser($userId);

        return view('Group.mygroup', compact('groups'));
    }

    /**
     * View details of a specific group.
     */
    public function view($id)
{
    try {
        // جلب المجموعة
        $group = Group::with(['files' => function ($query) {
            $query->whereDoesntHave('requestApproval')
                  ->orWhereHas('requestApproval', function ($subQuery) {
                      $subQuery->where('status', 'approved');
                  });
        }])->findOrFail($id);

        // تحقق إذا كان المستخدم أدمن للمجموعة
        $isAdmin = $this->userService->checkIfAdmin($id, auth()->id());


        return view('Group.view', compact('group', 'isAdmin'));
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(__('Failed to load group details: ') . $e->getMessage());
    }
}



    /**
     * Show users who can be added to the group.
     */
    public function addUser($groupId)
    {
        try {
            $users = $this->userService->getAllUsersExceptAuthAndGroupMembers(auth()->id(), $groupId);
            return view('User.add', compact('groupId', 'users'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(__('Failed to load users: ') . $e->getMessage());
        }
    }

    /**
     * Add selected users to a group.
     */
    public function storeUsers(Request $request, $groupId)
    {
        $userIds = $request->input('user_ids', []);

        if (empty($userIds)) {
            return redirect()->back()->withErrors(__('Please select at least one user to add.'));
        }

        try {
            $this->userService->addUsersToGroup($userIds, $groupId);
            return redirect()->route('view', ['id' => $groupId])->with('success', __('Users added successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(__('Failed to add users: ') . $e->getMessage());
        }
    }

    /**
     * Search for users to add to the group.
     */
    public function searchUsers(Request $request, $groupId)
    {
        try {
            $query = $request->input('query');
            $users = $this->userService->searchUsers($query, auth()->id(), $groupId);

            if ($users->isEmpty()) {
                return redirect()->back()->with('info', __('No users found matching your query.'));
            }

            return view('User.add', compact('groupId', 'users'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(__('Failed to search users: ') . $e->getMessage());
        }
    }

    /**
     * Remove a user from a group.
     */
    public function removeUser($groupId, $userId)
    {
        try {
            $this->groupService->removeUserFromGroup($groupId, $userId, auth()->id());
            return redirect()->route('view', ['id' => $groupId])->with('success', __('User removed successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(__('Failed to remove user: ') . $e->getMessage());
        }
    }

    //----------------------------------------------------------------
    //*****                  File               *****/
    //----------------------------------------------------------------

      /**
     * صفحة إضافة ملف
     */
    public function addFile($groupId)
    {
        $group = $this->groupService->getGroupById($groupId);
        return view('File.add', ['group' => $group]);
    }

    /**
     * رفع ملف إلى الغروب
     */
    public function storeFile(FileRequest $request, $groupId)
    {
        $userId = auth()->id();

        if (!$this->userService->checkIfAdmin($groupId, $userId)) {
            return redirect()->route('view', ['id' => $groupId])->with('error', 'Unauthorized action.');
        }

        try {
            $this->fileService->uploadFile($request, $groupId, $userId);
            return redirect()->route('view', ['id' => $groupId])->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload file: ' . $e->getMessage());
        }
    }


    /**
     * حذف ملف
     */
    public function deleteFile($fileId, $groupId)
    {
        $userId = auth()->id();

        // تحقق من صلاحيات الأدمن
        if (!$this->userService->checkIfAdmin($groupId, $userId)) {
            return redirect()->route('view', ['id' => $groupId])->with('error', 'Unauthorized action.');
        }

        try {
            $this->fileService->deleteFile($fileId);
            return redirect()->route('view', ['id' => $groupId])->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function showAddUserPage($groupId)
    {
        $group = Group::findOrFail($groupId);
        $users = User::all();  // يمكنك تخصيص الاستعلام وفقًا لاحتياجاتك
        return view('user.add', compact('groupId', 'users'));
    }
}

//edin in layout and index and Group.create and

//create Aspect for Transaction and call him in Group ERepoitory




























// // app/Http/Controllers/GroupController.php

// namespace App\Http\Controllers;

// use App\Http\Requests\GroupRequest;
// use App\Models\GroupMember;
// use App\Services\GroupService;
// use App\Services\UserService;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;

// class GroupController extends Controller
// {
//     protected $groupService;
//     protected $userService;
//  public function __construct(GroupService $groupService, UserService $userService)
//   {
//      $this->middleware('groupmiddleware')->except(['show']);
//      $this->groupService = $groupService;
//      $this->userService = $userService;
//      }

// public function create(GroupRequest $request)
// {
//     // Access the validated data using $request->validated()
//     $data = $request->validated();

//     // Create the group using the GroupService
//     $group = $this->groupService->createGroup($request->user()->id, $request); // تمرير الطلب هنا بدلاً من البيانات المفصلة
//     //return $group;
//     return redirect()->route('index');
// }


// public function myGroups()
// {
//     $userId = auth()->id();
//     $groups = $this->groupService->getGroupsForUser($userId);
//     return view('Group.mygroup', ['groups' => $groups]);
// }

// public function view($id)
// {
//     $group = $this->groupService->getGroupById($id);

//     return view('Group.view', ['group' => $group]);
// }

// // app/Http/Controllers/GroupController.php

// public function addUser($groupId)
// {
//     $userId = auth()->id();
//     $users = $this->userService->getAllUsersExceptAuthAndGroupMembers($userId,$groupId);
//     return view('User.add', ['groupId' => $groupId, 'users' => $users]);
// }

// // app/Http/Controllers/GroupController.php

// public function storeUsers(Request $request, $groupId)
// {
//      $userIds = $request->input('user_ids');
//       if (empty($userIds)) {
//          return redirect()->back()->with('error', 'Please select at least one user to add.');
//         }
//          foreach ($userIds as $userId) {
//              GroupMember::create([
//                 'group_id' => $groupId,
//                 'user_id' => $userId,
//                  'isOwner' => false,
//                   ]);
//                  }
//                   return redirect()->route('view', ['id' => $groupId]);
//                 }

//                 public function removeUser($groupId, $userId)
//                 {
//                     $authUserId = auth()->id();
//                     $isAdmin = GroupMember::where('group_id', $groupId)->where('user_id', $authUserId)->where('isOwner', true)->exists();
//                     if (!$isAdmin) {
//                         return redirect()->back()->with('error', 'Only the group admin can remove users.');
//                     }
//                     $member = GroupMember::where('group_id', $groupId)->where('user_id', $userId)->first();
//                     if (!$member) {
//                         return redirect()->back()->with('error', 'User not found in the group.');
//                     }
//                     $member->delete();
//                     return redirect()->route('view', ['id' => $groupId])->with('success', 'User removed successfully.');
//                 }

//                 public function searchUsers(Request $request, $groupId)
//                  {
//                      $query = $request->input('query');
//                       $userId = auth()->id();
//                        $users = $this->userService->searchUsers($query, $userId, $groupId);
//                        return view('User.add', ['groupId' => $groupId, 'users' => $users]);
//                     }

//     }
//
