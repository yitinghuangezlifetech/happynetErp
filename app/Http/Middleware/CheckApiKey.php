<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        
        $apiKey = $request->header('apikey');
        $account = $request->input('password');
        // return response()->json(['request' => 'request'.$account  ], 401);
        if (!$apiKey || $apiKey !== env('HAPPYNET_APIKEY')) {

            return response()->json(['Middleware message' => 'Invalid API key' .$apiKey ], 401);
        }

        return $next($request);
    }
}
