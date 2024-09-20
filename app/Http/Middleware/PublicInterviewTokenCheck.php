<?php

namespace App\Http\Middleware;

use App\PublicInterviewUrl;
use Carbon\Carbon;
use Closure;

class PublicInterviewTokenCheck
{
    /**
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            return $next($request);
        } else {
            $getRequestWithoutToken = ! $request->has('t') && request()->isMethod('get');
            if ($getRequestWithoutToken) {
                abort(403);
            }
        }

        $validToken = PublicInterviewUrl::isValid($this->fetchToken($request));
        if ($validToken) {
            if (is_null($validToken->first_opened_at)) {
                $validToken->first_opened_at = Carbon::now()->toDateTimeString('minutes');
                $validToken->save();
            }

            $submitRequest = request()->isMethod('post') && request()->has('publicInterviewToken');
            if ($submitRequest) {
                $validToken->submitted_at = Carbon::now()->toDateTimeString('minutes');

                $validToken->save();
            }

            return $next($request);
        } else {
            abort(403, 'Token not valid, contact your reference person.');
        }
    }

    /**
     * @return array
     */
    private function fetchToken($request)
    {
        if (! $request->has('t')) {
            parse_str(parse_url(request()->headers->get('referer'))['query'], $url);
            $uuid = $url['t'];
        } else {
            $uuid = request()->t;
        }

        return $uuid;
    }
}
