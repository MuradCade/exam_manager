<?php

use App\Http\Controllers\editor\EditorController;
use App\Http\Controllers\FrontPagecontroller;
use App\Http\Controllers\owner\OwnerController;
use App\Http\Controllers\readonly\ReadonlyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontPagecontroller::class, 'index'])->name('home');


/*
* Owner Route
*/
Route::middleware(['auth', 'verified', 'role:owner', 'prevent-back-history'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard');
});


/*
* Editor Route
*/
Route::middleware(['auth', 'verified', 'role:editor', 'prevent-back-history'])->prefix('editor')->group(function () {
    Route::get('/dashboard', [EditorController::class, 'index'])->name('editor.dashboard');
});


/*
* Readonly Route
*/
Route::middleware(['auth', 'verified', 'role:readonly', 'prevent-back-history'])->prefix('readonly')->group(function () {
    Route::get('/dashboard', [ReadonlyController::class, 'index'])->name('readonly.dashboard');
});
