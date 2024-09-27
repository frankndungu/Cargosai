<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RateLimitMiddleware
{
    protected $maxRequests = 60; // Maximum requests allowed
    protected $timeFrame = 60; // Time frame in seconds

    public function handle(Request $request, Closure $next)
    {
        // Logic to handle rate limiting
        $ip = $request->ip();
        $key = "rate_limit:{$ip}";

        // Get the current request count from the cache
        $requests = cache()->get($key, 0);

        // Check if the limit has been reached
        if ($requests >= $this->maxRequests) {
            return response()->json(['message' => 'Too Many Requests'], 429);
        }

        // Increment the request count
        cache()->increment($key);
        // Set the expiration for the rate limit
        cache()->put($key, $requests + 1, $this->timeFrame);

        return $next($request);
    }
}
