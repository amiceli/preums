<?php

namespace App\Http\Middleware;

use App\Models\GithubApi;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleGithubRateLimit {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $client = new GithubApi();
        $rateLimit = $client->getRateLimit();

        $isRatePage = $request->is('*rate*');

        if ($isRatePage) {
            return $rateLimit->remaining ? redirect('/') : $next($request);
        } else {
            return $rateLimit->remaining
                ? $next($request)
                : redirect('/rate-limit')->with(
                    array(
                        'nextReset' => $rateLimit->nextResetStr,
                    )
                );
        }
    }
}
