<?php
// app/Repositories/FileRepository.php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
    public function saveFile($file, $groupId, $userId)
    {
        // تخزين الملف
        $filePath = $file->store('uploads', 'public');

        // حفظ البيانات في قاعدة البيانات
        return File::create([
            'user_id' => $userId,
            'group_id' => $groupId,
            'name' => $file->getClientOriginalName(),
            'filePath' => $filePath,
            'isAvailable' => true, // يمكنك تخصيص هذه القيمة
        ]);
    }

    // لحذف الملف من التخزين وقاعدة البيانات
    public function deleteFile($fileId)
    {
        $file = File::find($fileId);

        if ($file) {
            // حذف الملف من التخزين
            Storage::disk('public')->delete($file->filePath);

            // حذف السجل من قاعدة البيانات
            return $file->delete();
        }

        return false;
    }

    // جلب الملفات المرفوعة للمجموعة
    public function getFilesByGroup($groupId)
    {
        return File::where('group_id', $groupId)->get();
    }

    public function createFile($data)
    {
        return File::create($data);
    }
    public function findById($fileId)
    {
        return File::with('group')->findOrFail($fileId);
    }

    public function updateAvailability($fileId, $isAvailable)
    {
        $file = File::find($fileId);
        $file->isAvailable = $isAvailable; // Fix: Use the parameter instead of hardcoding
        $file->save();
    }

    public function replaceFile($fileId, $newFile)
{
    $file = $this->findById($fileId);

    // Validate that the new file name matches the old one
    if ($newFile->getClientOriginalName() !== $file->name) {
        throw new \Exception('The uploaded file name must match the original file name.');
    }

    // Delete the old file
    Storage::delete($file->filePath);

    // Upload the new file
    $newPath = $newFile->storeAs('files', $file->name);

    // Update the file path
    $file->filePath = $newPath;
    $file->save();
}

}
