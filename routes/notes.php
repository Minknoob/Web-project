<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\NoteImageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::post('/notes/{note}/pin', [NoteController::class, 'togglePin'])->name('notes.pin');
    Route::post('/notes/{note}/images', [NoteImageController::class, 'store'])->name('notes.images.store');
    Route::delete('/notes/{note}/images/{image}', [NoteImageController::class, 'destroy'])->name('notes.images.destroy');
});
