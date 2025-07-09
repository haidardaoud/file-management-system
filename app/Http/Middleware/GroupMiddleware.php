<?php
// app/Http/Middleware/GroupMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pre-logic (Before proceeding to the next request handler)
        $this->before($request);

        // Proceed to the next middleware/controller
        $response = $next($request);

        // Post-logic (After the request has been handled)
        $this->after($request, $response);

        return $response;
    }
    /** 
    * Pre-logic for AOP (Before request execution).
    *
    * @param  \Illuminate\Http\Request  $request
    * @return void
    */
   protected function before(Request $request)
   {
       Log::info('Pre-logic executed', ['url' => $request->url(), 'user' => $request->user()]);
       // Add additional logic here (e.g., tracking, security checks, etc.)
   }
     /**
     * Post-logic for AOP (After request execution).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response $response
     * @return void
     */
    protected function after(Request $request, $response)
{
    // Ensure the response is still valid JSON after modification
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        // Avoid manipulating the JSON content in an invalid way
        $response->setData(array_merge($response->getData(true), ['extra' => 'data']));
    }
    Log::info('Post-logic executed', ['response' => $response->getContent()]);
}
}
