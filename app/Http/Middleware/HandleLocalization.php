<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\HttpFoundation\Response;

class HandleLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale')
            ?? $this->supportedSegment($request)
            ?? $request->cookie('locale');

        LaravelLocalization::setLocale(is_string($locale) ? $locale : null);

        $currentLocale = LaravelLocalization::getCurrentLocale();

        URL::defaults(['locale' => $currentLocale]);
        View::share('locale', $currentLocale);

        return $next($request);
    }

    private function supportedSegment(Request $request): ?string
    {
        $segment = $request->segment(1);

        if (! is_string($segment)) {
            return null;
        }

        return array_key_exists($segment, config('laravellocalization.supportedLocales'))
            ? $segment
            : null;
    }
}
