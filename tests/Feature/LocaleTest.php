<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect;

test('routes share localization metadata', function () {
    $this->withoutMiddleware(LocaleCookieRedirect::class)
        ->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale.current', 'en')
            ->where('locale.default', 'en')
            ->where('locale.supported.0.code', 'en')
            ->where('locale.supported.0.url', '/en')
            ->where('locale.supported.1.code', 'km')
            ->where('locale.supported.1.url', '/km')
        );
});

test('localized urls keep the current path as same origin relative urls', function () {
    $this->withoutMiddleware(LocaleCookieRedirect::class)
        ->actingAs(User::factory()->create())
        ->get('/settings/appearance?tab=theme')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale.current', 'en')
            ->where('locale.supported.0.url', '/en/settings/appearance?tab=theme')
            ->where('locale.supported.1.url', '/km/settings/appearance?tab=theme')
        );
});

test('language cookie redirects to its localized url', function () {
    $this->withUnencryptedCookie('locale', 'km')
        ->get('/')
        ->assertRedirect('/km');
});

test('unsupported locales are not routed', function () {
    $this->get('/fr')->assertNotFound();
});
