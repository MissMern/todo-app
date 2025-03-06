<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

// Redirect root URL to login page
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// Authentication routes (Login, Register, Logout)
require __DIR__.'/auth.php';

// To-Do Management Routes (Protected by 'auth' middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [TodoController::class, 'index'])->name('dashboard');
    Route::resource('todos', TodoController::class);
});