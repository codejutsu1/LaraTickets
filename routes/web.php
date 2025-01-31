<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/hey', function() {
    $currentPath = request()->path();

    dd($currentPath);
});

Route::get('/login', function () {
    return redirect(route('filament.dashboard.auth.login'));
})->name('login');

Route::get('/register', function () {
    return redirect(route('filament.dashboard.auth.register'));
})->name('register');
