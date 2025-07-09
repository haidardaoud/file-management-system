<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestApprovalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/home',function(){
    return view('home');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/index', [GroupController::class, 'index'])->name('index');

   Route::middleware(['auth'])->group(function () {
       Route::get('groupCreate', function () {
           return view('Group.create');
        })->name('groupCreate');

        Route::get('/mygroup', [GroupController::class, 'myGroups'])->name('mygroup');



        Route::post('store', [GroupController::class, 'create'])->name('store');
        Route::get('/view/{id}', [GroupController::class, 'view'])->name('view');



        Route::get('/adduser/{groupId}', [GroupController::class, 'addUser'])->name('adduser');
        Route::post('/adduser/{groupId}', [GroupController::class, 'storeUsers'])->name('storeUsers');
        Route::delete('/group/{groupId}/remove-user/{userId}', [GroupController::class, 'removeUser'])->name('removeUser');
        Route::get('/group/{groupId}/search-users', [GroupController::class, 'searchUsers'])->name('searchUsers');

        Route::get('group/{groupId}/add-file', [GroupController::class, 'addFile'])->name('addFile');
        Route::post('group/{groupId}/store-file', [GroupController::class, 'storeFile'])->name('storeFile');
        Route::delete('file/{fileId}/delete/{groupId}', [GroupController::class, 'deleteFile'])->name('deleteFile');
        Route::get('/files/{fileId}/edit', [FileController::class, 'editFile'])->name('editFile');
        Route::put('/files/{fileId}', [FileController::class, 'updateFile'])->name('updateFile');
//        Route::put('/file/{fileId}/update', [FileController::class, 'updateFile'])->name('updateFile');



Route::get('/groups/{groupId}/request-file-upload', [RequestApprovalController::class, 'showRequestUploadForm'])->name('requestFileUpload');
Route::post('/groups/{groupId}/submit-file-request', [RequestApprovalController::class, 'createRequest'])->name('submitFileRequest');


Route::get('/groups/{groupId}/pending-requests', [RequestApprovalController::class, 'listPendingRequests'])->name('pendingRequests');
Route::post('/groups/{groupId}/requests/{requestId}/approve', [RequestApprovalController::class, 'approveRequest'])->name('approveRequest');
Route::post('/groups/{groupId}/requests/{requestId}/reject', [RequestApprovalController::class, 'rejectRequest'])->name('rejectRequest');
//Route::get('/download/{fileId}', [FileController::class, 'downloadFile'])->name('downloadFile');
// Route::get('/files/{fileId}/check-in', [FileController::class, 'checkInFile'])->name('checkInFile');
// Route::post('/files/{fileId}/check-out', [FileController::class, 'checkOutFile'])->name('checkOutFile');


    Route::get('/group/{groupId}/add-user', [GroupController::class, 'showAddUserPage'])->name('addUserPage');
//working for download and check in and check out
    Route::post('/files/check-in/', [FileController::class, 'checkInFile'])->name('checkInFile');
    Route::post('/files/check-out/{fileId}', [FileController::class, 'checkOutFile'])->name('checkOutFiles');
    Route::get('/download-file/{fileId}', [FileController::class, 'downloadFile'])->name('downloadFile');
    Route::get('/files/versions/{fileId}', [FileController::class, 'viewVersions'])->name('viewFileVersions');

    Route::get('/download-file-version/{versionId}', [FileController::class, 'downloadFileVersion'])
        ->name('download.file.version');

    // the main route
    //     Route::post('/check-in-files/', [FileController::class, 'checkInFiles'])->name('checkInFiles');
    //     Route::post('/check-out-files/', [FileController::class, 'checkOutFiles'])->name('checkOutFiles');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');


    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');

    Route::get('/file/{fileId}/logs', [FileController::class, 'fileLogs'])->name('fileLogs');
    Route::get('/group/{groupId}/member-logs', [GroupController::class, 'memberLogs'])->name('memberLogs');

    Route::get('/export-log/{logId}/{format}', [LogController::class, 'export'])->name('export.log');

    });

