<?php

namespace App\Services;

use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Models\FileVersion;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


    class FileService
    {
        protected $fileRepository;

        public function __construct(FileRepository $fileRepository)
        {
            $this->fileRepository = $fileRepository;
        }

        public function uploadFile(FileRequest $request, $groupId, $userId)
        {
            try {
                $validatedData = $request->validated();

                $file = $request->file('file');
                $this->fileRepository->saveFile($file, $groupId, $userId);

            } catch (\Exception $e) {
                Log::error('Error in uploading file: ' . $e->getMessage());
                throw $e;
            }
        }

        public function deleteFile($fileId)
        {
            try {
                $this->fileRepository->deleteFile($fileId);
            } catch (\Exception $e) {
                Log::error('Error deleting file: ' . $e->getMessage());
                throw $e;
            }
        }
//         public function prepareForEdit($fileId)
//         {
//             $file = $this->fileRepository->findById($fileId);
//             $this->fileRepository->updateAvailability($fileId, false); // Mark as unavailable
//             return $file;
//         }
//         public function updateFile($request, $fileId)
//         {
//             $newFile = $request->file('updated_file'); // Get the uploaded file

//             try {
//                 // Replace the file with the new one
//                 $this->fileRepository->replaceFile($fileId, $newFile);

//                 // Mark the file as available again (set isAvailable to true)
//                 $this->fileRepository->updateAvailability($fileId, true);
//             } catch (\Exception $e) {
//                 // Log the error for debugging purposes
//                 Log::error('Error updating file: ' . $e->getMessage());
//                 throw $e; // Rethrow the exception to the controller
//             }
//         }
//     public function lockFileForEdit($fileId)
// {
//     $this->fileRepository->updateAvailability($fileId, false); // Lock the file
//     return $this->fileRepository->findById($fileId);
// }

// public function getFileById($fileId)
// {
//     return $this->fileRepository->findById($fileId);
// }
public function lockFileForEdit($fileId)
{
    $file = $this->fileRepository->findById($fileId);

    if (!$file->isAvailable) {
        throw new \Exception('File is already locked by another user.');
    }

    $this->fileRepository->updateAvailability($fileId, false); // Lock the file
    return $file;
}

public function checkOutFile($request, $fileId)
{
    $newFile = $request->file('updated_file');

    $this->fileRepository->replaceFile($fileId, $newFile); // Replace the file
    $this->fileRepository->updateAvailability($fileId, true); // Unlock the file
}

public function getFileById($fileId)
{
    return $this->fileRepository->findById($fileId);
}
public function backupFile(File $file)
{
    $latestVersion = FileVersion::where('file_id', $file->id)->max('version_number') ?? 0;
    $newVersionNumber = $latestVersion + 1;

    $backupPath = 'backups/' . $file->filePath;
    Storage::disk('public')->copy($file->filePath, $backupPath);

    FileVersion::create([
        'file_id' => $file->id,
        'version_number' => $newVersionNumber,
        'file_path' => $backupPath,
    ]);
}

}
