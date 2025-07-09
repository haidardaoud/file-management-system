<?php
namespace App\Repositories;

use App\Models\Log;

class LogRepository
{
    public function findById($logId)
    {
        return Log::with('user')->findOrFail($logId);
    }
}
