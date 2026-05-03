<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeCookieRedirect', 'localizationRedirect', 'localeViewPath'],
], function (): void {
    Route::inertia('/', 'welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ])->name('home');

    Route::middleware(['auth', 'verified'])->group(function (): void {
        Route::inertia('dashboard', 'dashboard')->name('dashboard');
    });

    require __DIR__.'/settings.php';
});
