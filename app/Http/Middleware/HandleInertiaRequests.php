<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'locale' => [
                'current' => LaravelLocalization::getCurrentLocale(),
                'default' => LaravelLocalization::getDefaultLocale(),
                'fallback' => config('app.fallback_locale'),
                'supported' => collect(LaravelLocalization::getSupportedLocales())
                    ->map(fn (array $properties, string $locale): array => [
                        'code' => $locale,
                        'name' => $properties['name'],
                        'native' => $properties['native'],
                        'url' => $this->localizedUrl($request, $locale),
                    ])
                    ->values(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    private function localizedUrl(Request $request, string $locale): string
    {
        $segments = $request->segments();
        $supportedLocales = array_keys(LaravelLocalization::getSupportedLocales());

        if (isset($segments[0]) && in_array($segments[0], $supportedLocales, true)) {
            array_shift($segments);
        }

        array_unshift($segments, $locale);

        $path = '/'.implode('/', $segments);
        $query = $request->getQueryString();

        return $query ? "{$path}?{$query}" : $path;
    }
}
