<?php

namespace App\Http\Middleware;

use App\Models\GithubApi;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleGithubRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = new GithubApi();
        $canContinue = $client->canContinue();
        $isRatePage = $request->is('*rate*');

        if ($isRatePage) {
            return $canContinue ? redirect('/') : $next($request);
        } else {
            return $canContinue ? $next($request) : redirect('/rate-limit');
        }
    }
}
