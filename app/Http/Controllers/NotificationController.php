<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class NotificationController extends Controller
{
    public function index()
{
    // استرداد الإشعارات للمستخدم الحالي
    $notifications = auth()->user()->notifications;
    // تمرير الإشعارات إلى العرض 'notifications'
    return view('notifications', ['notifications' => $notifications]);
}



// public function markAsRead($id)
// {

//     $notification = auth()->user()->notifications()->find($id);

//     if ($notification) {
//         $notification->markAsRead(); // تعليم الإشعار كمقروء باستخدام الميثود المدمجة

//         // تحديث read_at يدويًا
//         $n = Notification::find($notification->id);
//         $n->update(['read_at' => now()]);

//         return redirect()->back()->with('success', 'Notification marked as read.');
//     }

//     return redirect()->back()->with('error', 'Notification not found.');
// }



public function markAsRead($id)
{
   // Log::info('Received Notification ID:', ['id' => $id]); // Log the received ID

    // Ensure the ID is a valid UUID
    if (!Str::isUuid($id)) {
        return response()->json(['error' => 'Invalid notification ID.'], 400);
    }

    // Search for the notification directly in the database
    $notification = Notification::where('id', $id)->first();

    if (!$notification) {
        return response()->json(['error' => 'Notification not found.'], 404);
    }

    // Update the read_at field
    $notification->update(['read_at' => now()]);

    return response()->json(['success' => true, 'message' => 'Notification marked as read.', 'read_at' => $notification->read_at]);
}

    public function clearAll()
    {
        auth()->user()->notifications()->delete();
        return back()->with('success', 'All notifications cleared.');
    }


}
