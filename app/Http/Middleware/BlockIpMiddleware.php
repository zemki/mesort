<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlockIpMiddleware
{
    public array $blockIps;

    public function __construct()
    {
        $this->blockIps = config('utilities.blockedIps');

    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (in_array($request->ip(), $this->blockIps)) {
            abort(403, 'Nope.');
        }

        return $next($request);
    }
}
