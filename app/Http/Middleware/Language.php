<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public const SESSION_KEY = 'locale';

    public const LOCALES = ['en', 'de', 'da'];

    public function handle($request, Closure $next)
    {
        /** @var Session $session */
        $session = $request->session();

        if (! $session->has(self::SESSION_KEY)) {
            $session->put(self::SESSION_KEY, $request->getPreferredLanguage(self::LOCALES));
        }

        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if (in_array($lang, self::LOCALES)) {
                $session->put(self::SESSION_KEY, $lang);
            }
        }

        app()->setLocale($session->get(self::SESSION_KEY));

        return $next($request);
    }
}
