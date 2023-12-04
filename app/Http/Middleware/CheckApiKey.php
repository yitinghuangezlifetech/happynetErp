<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\SecurityKey;

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
        $apiKey = $request->header('ApiKey');

        $data = app(SecurityKey::class)->where('security_key', $apiKey)->first();

        if ($data) {
            $now = strtotime(now());
            $startDay = strtotime($data->start_day);
            $endDay = strtotime($data->end_day);

            if ($now >= $startDay && $now <= $endDay) {
                return response()->json([
                    'status' => true,
                    'message' => 'apiKey 尚未生效',
                    'data' => null,
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'apiKey 尚未生效',
                'data' => null,
            ], 401);
        }

        return $next($request);
    }
}
