<?php

use App\Filament\Unauthenticated\Pages\Login;
use App\Filament\Unauthenticated\Pages\Register;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('filament.unauthenticated.auth.login');
    Route::get('register', Register::class)->name('register');
});
