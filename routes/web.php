<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NoteController;
use App\Http\Controllers\GuestController;

Route::get('/', [GuestController::class, 'index']);

Route::get('/admin/notes/{note}/download', [NoteController::class, 'download'])
    ->name('admin.notes.download')
    ->middleware(['auth']);
