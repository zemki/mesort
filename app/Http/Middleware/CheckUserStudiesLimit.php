<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserStudiesLimit
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if (auth()->user()->hasReachMaxNumberOfStudies()) {
                session(['hasReachMaxNumberOfStudies' => true]);
            } else {
                session(['hasReachMaxNumberOfStudies' => false]);
            }
        }

        return $next($request);
    }
}
