<?php
namespace App\Aspects;

use Closure;
use Illuminate\Support\Facades\DB;

class TransactionAspect
{
    /**
     * Execute a callback within a database transaction.
     *
     * @param Closure $callback
     * @return mixed
     * @throws \Throwable
     */
    public function execute(Closure $callback)
    {
        return DB::transaction($callback);
    }
}
