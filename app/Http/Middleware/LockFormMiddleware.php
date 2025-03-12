<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LockFormMiddleware
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $cacheKey = 'login_attempts_' . $ip;
        $blockTimeKey = 'block_time_' . $ip;

        // Check if the IP address is in the cache
        if (Cache::has($cacheKey)) {
            $attempts = Cache::get($cacheKey);

            // If the user has logged in incorrectly 5 times, block their IP address
            if ($attempts >= 5) {
                // Store the block time
                if($attempts = 5) {
                    $blockTime = Cache::get($blockTimeKey, Carbon::now()->addMinutes(15)); // Default to 15 minutes
                    $attempts = 6;
                }
                Cache::put($blockTimeKey, $blockTime, $blockTime->diffInMinutes(Carbon::now()) + 1);

                // Redirect the user to a page indicating that their IP address has been blocked
                return redirect('/blocked')->with('blockTime', $blockTime);
            }
        } else {
            // Set block time only when attempting to log in
            Cache::put($blockTimeKey, Carbon::now()->addMinutes(15), 15);
        }

        // Increment the login attempts for the IP address
        Cache::increment($cacheKey, 1, 60); // Expire after 60 minutes

        return $next($request);
    }
}
