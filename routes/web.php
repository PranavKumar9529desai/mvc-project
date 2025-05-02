<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StageRecordController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
        Route::resource('farms', FarmController::class);
        Route::resource('batches', BatchController::class);
        Route::resource('stage-records', StageRecordController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
