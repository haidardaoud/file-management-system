<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;
use App\Models\File;
use App\Models\FileVersion;
//use App\Models\Notification;
use App\Notifications\FileStatusChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

public function viewVersions($fileId)
{
    $file = File::findOrFail($fileId);
    $versions = $file->versions; // Assumes a relationship in the File model

    return view('file_versions', compact('file', 'versions'));

}


public function checkInFile(Request $request)
{
    $fileIds = $request->input('file_ids', []);
    $filePaths = [];
    $user = auth()->user();

    foreach ($fileIds as $fileId) {
        $file = File::where('id', $fileId)->lockForUpdate()->firstOrFail();

        if (!$file->isAvailable) {
            return response()->json(['message' => "File {$file->name} is already under modification."], 400);
        }

        $file->isAvailable = false;
        $file->save();

        $fileOwner = $file->uploadedBy;

        // تحويل البيانات إلى JSON
        $notificationData = json_encode([
            'fileName' => $file->name,
            'action' => 'checked-in',
            'userName' => $user->name,
        ]);

        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\FileStatusChanged',
            'data' => $notificationData, // JSON data
            'notifiable_id' => $fileOwner->id,
            'notifiable_type' => 'App\Models\User',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // إضافة سجل في جدول الـ logs
        $file->logs()->create([
            'action' => 'checked-in',
            'user_id' => $user->id,
            'file_id' => $file->id,
            'details' => "User {$user->name} checked in the file.",
        ]);

        session()->push('checkedInFileIds', $fileId);
        $filePaths[] = asset('storage/' . $file->filePath);
    }

    return response()->json(['message' => 'Files checked in successfully.', 'filePaths' => $filePaths]);
}


public function fileLogs($fileId)
{
    $file = File::with('logs')->findOrFail($fileId);

    return view('logs.file', compact('file'));
}

public function checkOutFile(Request $request, $fileId)
{
    $file = File::findOrFail($fileId);
    $user = auth()->user(); // Current user

    if (!session()->has('checkedInFileIds') || !in_array($fileId, session()->get('checkedInFileIds'))) {
        abort(403, 'Unauthorized action.');
    }

    // Upload the updated file
    if ($request->hasFile('updated_file')) {
        $uploadedFile = $request->file('updated_file');
        $filename = $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('files', $filename, 'public');

        // Get the latest version number for the file
        $latestVersion = FileVersion::where('file_id', $file->id)->latest('version_number')->first();
        $newVersionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;

        // Add the new version to the file_versions table
        FileVersion::create([
            'file_id' => $file->id,
            'version_number' => $newVersionNumber,
            'file_path' => $path,
        ]);

        // Update the file's main path in the files table
        $file->filePath = $path;
    }

    // Change file status to available
    $file->isAvailable = true;
    $file->save();

    // Notify the file owner
    $fileOwner = $file->uploadedBy;

    $notificationData = [
        'fileName' => $file->name,
        'action' => 'checked-out',
        'userName' => $user->name,
    ];

    DB::table('notifications')->insert([
        'id' => Str::uuid(),
        'type' => 'App\Notifications\FileStatusChanged',
        'data' => json_encode($notificationData),
        'notifiable_id' => $fileOwner->id,
        'notifiable_type' => 'App\Models\User',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // إضافة سجل في جدول الـ logs
    $file->logs()->create([
        'action' => 'checked-out',
        'user_id' => $user->id,
        'file_id' => $file->id,
        'details' => "User {$user->name} checked out the file.",
    ]);

    // Remove the file from the list of checked-in files
    $checkedInFileIds = session()->get('checkedInFileIds');
    unset($checkedInFileIds[array_search($fileId, $checkedInFileIds)]);
    session()->put('checkedInFileIds', $checkedInFileIds);

    return back()->with('success', 'File checked out successfully.');
}
//معلم كل نسخة بتزيد يعني تبصير 1 بعدين 2 بعدين 3


// تابع تنزيل إصدار الملف


public function downloadFileVersion($versionId)
{
    $version = FileVersion::findOrFail($versionId);

    // تحقق من وجود الملف
    $filePath = storage_path("app/public/{$version->file_path}");
    if (!file_exists($filePath)) {
        abort(404, 'The file does not exist.');
    }

    return response()->download($filePath);
}

}
// تابع Check-Out

// this code is working
// public function checkOutFile(Request $request, $fileId)
// {
//     $file = File::findOrFail($fileId);
//     $user = auth()->user(); // المستخدم الحالي

//     if (!session()->has('checkedInFileIds') || !in_array($fileId, session()->get('checkedInFileIds'))) {
//         abort(403, 'Unauthorized action.');
//     }

//     // رفع الملف الجديد بعد التعديل
//     if ($request->hasFile('updated_file')) {
//         $uploadedFile = $request->file('updated_file');
//         $filename = $uploadedFile->getClientOriginalName();
//         $path = $uploadedFile->storeAs('files', $filename, 'public');

//         // إضافة النسخة الجديدة من الملف
//         FileVersion::create([
//             'file_id' => $file->id,
//             'version_name' => now()->format('Y-m-d_H:i:s'),
//             'file_path' => $path,
//         ]);

//         // تحديث مسار الملف في السجل
//         $file->filePath = $path;
//     }

//     // تغيير حالة الملف إلى متاح
//     $file->isAvailable = true;
//     $file->save();

//     // الحصول على مالك الملف
//     $fileOwner = $file->uploadedBy;

//     // بيانات الإشعار
//     $notificationData = [
//         'fileName' => $file->name,
//         'action' => 'checked-out',
//         'userName' => $user->name,
//     ];

//     // إرسال البيانات إلى جدول الإشعارات بتنسيق JSON
//     DB::table('notifications')->insert([
//         'id' => Str::uuid(),
//         'type' => 'App\Notifications\FileStatusChanged',
//         'data' => json_encode($notificationData),  // تحويل المصفوفة إلى JSON
//         'notifiable_id' => $fileOwner->id,
//         'notifiable_type' => 'App\Models\User',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ]);

//     // إزالة الملف من قائمة الملفات المحجوزة
//     $checkedInFileIds = session()->get('checkedInFileIds');
//     unset($checkedInFileIds[array_search($fileId, $checkedInFileIds)]);
//     session()->put('checkedInFileIds', $checkedInFileIds);

//     return back()->with('success', 'File checked out successfully.');
// }
//     public function editFile($fileId)
// {
//     $file = $this->fileService->lockFileForEdit($fileId); // Lock the file
//     return view('File.edit', compact('file'));
// }

// public function updateFile(Request $request, $fileId)
// {
//     try {
//         $this->fileService->updateFile($request, $fileId); // Update file logic in the service layer
//         $file = $this->fileService->getFileById($fileId); // Fetch the file
//         $groupId = $file->group_id; // Retrieve the associated group ID

//         // Redirect to the group view page with a success message
//         return redirect()->route('view', ['id' => $groupId])
//                          ->with('success', 'File updated successfully.');
//     } catch (\Exception $e) {
//         // Handle any exception and redirect back with an error message
//         return redirect()->back()->with('error', 'Error updating the file: ' . $e->getMessage());
//     }
// }

//the main code before adding multiple files functionality


// public function downloadFile($fileId)
// {
//     $file = File::find($fileId);

//     if (!$file) {
//         abort(404, 'File not found in database.');
//     }

//     $filePath = $file->filePath; // المسار النسبي المخزن في قاعدة البيانات

//     // التأكد من وجود الملف باستخدام قرص public
//     if (Storage::disk('public')->exists($filePath)) {
//         return Storage::disk('public')->download($filePath, $file->name);
//     }

//     abort(404, 'File not found in storage.');
// }

// public function checkInFile($fileId)
// {
//     try {
//         $file = $this->fileService->lockFileForEdit($fileId); // Lock the file
//         return redirect()->route('downloadFile', ['fileId' => $fileId])
//             ->with('success', 'File locked and ready for editing. Please download the file to make changes.');
//     } catch (\Exception $e) {
//         return redirect()->back()->with('error', 'Error locking file: ' . $e->getMessage());
//     }
// }

// public function checkOutFile(Request $request, $fileId)
// {
//     try {
//         $this->fileService->checkOutFile($request, $fileId); // Unlock and update the file
//         return redirect()->route('view', ['id' => $this->fileService->getFileById($fileId)->group_id])
//             ->with('success', 'File updated and released for others.');
//     } catch (\Exception $e) {
//         return redirect()->back()->with('error', 'Error updating file: ' . $e->getMessage());
//     }
// }
// public function downloadFile($fileId)
// {
//     $file = File::findOrFail($fileId);
//     return response()->download(storage_path("app/public/{$file->filePath}"));
// }



///---------------------------------------------------------------------------------------------------------------------------------


//     }
//--------------------------------------------------------------------------------------------------------------------
// the code for multiple files is working and good and this is the main code gggggg
//  public function downloadFile($fileId)
//     {
//         $file = File::findOrFail($fileId);
//         return response()->download(storage_path("app/public/{$file->filePath}"));
//     }

//     public function checkInFile(Request $request)
//     {
//         $fileIds = $request->input('file_ids', []);
//         $filePaths = [];

//         foreach ($fileIds as $fileId) {
//             $file = File::findOrFail($fileId);

//             if (!$file->isAvailable) {
//                 return response()->json(['message' => "File {$file->name} is already under modification."], 400);
//             }

//             $file->isAvailable = false;
//             $file->save();

//             // Store the checked-in file IDs in the session
//             session()->push('checkedInFileIds', $fileId);

//             // Prepare the file path for download
//             $filePaths[] = asset('storage/' . $file->filePath);
//         }

//         return response()->json(['message' => 'Files checked in successfully.', 'filePaths' => $filePaths]);
//     }

//     public function checkOutFile(Request $request, $fileId)
//     {
//         $file = File::findOrFail($fileId);

//         // Validate the user against the session
//         if (!session()->has('checkedInFileIds') || !in_array($fileId, session()->get('checkedInFileIds'))) {
//             abort(403, 'Unauthorized action.');
//         }

//         if ($request->hasFile('updated_file')) {
//             $uploadedFile = $request->file('updated_file');
//             $filename = $uploadedFile->getClientOriginalName();
//             $path = $uploadedFile->storeAs('files', $filename, 'public');

//             $file->filePath = $path;
//         }

//         $file->isAvailable = true;
//         $file->save();

//         // Remove the checked-in file ID from the session
//         $checkedInFileIds = session()->get('checkedInFileIds');
//         unset($checkedInFileIds[array_search($fileId, $checkedInFileIds)]);
//         session()->put('checkedInFileIds', $checkedInFileIds);

//         return back()->with('success', 'File checked out successfully.');
//     }
//--------------------------------------------------------------------------------------------------------------------
//testing for backups and it worked
// public function downloadFile($fileId)
// {
//     $file = File::findOrFail($fileId);
//     return response()->download(storage_path("app/public/{$file->filePath}"));
// }

// public function checkInFile(Request $request)
// {
//     $fileIds = $request->input('file_ids', []);
//     $filePaths = [];

//     foreach ($fileIds as $fileId) {
//         $file = File::findOrFail($fileId);

//         if (!$file->isAvailable) {
//             return response()->json(['message' => "File {$file->name} is already under modification."], 400);
//         }

//         $file->isAvailable = false;
//         $file->save();

//         // Store the checked-in file IDs in the session
//         session()->push('checkedInFileIds', $fileId);

//         // Prepare the file path for download
//         $filePaths[] = asset('storage/' . $file->filePath);
//     }

//     return response()->json(['message' => 'Files checked in successfully.', 'filePaths' => $filePaths]);
// }

// public function checkOutFile(Request $request, $fileId)
// {
//     $file = File::findOrFail($fileId);

//     // Validate the user against the session
//     if (!session()->has('checkedInFileIds') || !in_array($fileId, session()->get('checkedInFileIds'))) {
//         abort(403, 'Unauthorized action.');
//     }

//     if ($request->hasFile('updated_file')) {
//         $uploadedFile = $request->file('updated_file');
//         $filename = $uploadedFile->getClientOriginalName();
//         $path = $uploadedFile->storeAs('files', $filename, 'public');

//         // Create a new file version
//         FileVersion::create([
//             'file_id' => $file->id,
//             'version_name' => now()->format('Y-m-d_H:i:s'),
//             'file_path' => $path,
//         ]);

//         // Update the main file record
//         $file->filePath = $path;
//     }

//     $file->isAvailable = true;
//     $file->save();

//     // Remove the checked-in file ID from the session
//     $checkedInFileIds = session()->get('checkedInFileIds');
//     unset($checkedInFileIds[array_search($fileId, $checkedInFileIds)]);
//     session()->put('checkedInFileIds', $checkedInFileIds);

//     return back()->with('success', 'File checked out successfully.');
// }
