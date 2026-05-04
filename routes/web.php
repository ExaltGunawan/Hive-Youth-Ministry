<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NoteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/notes/{note}/download', [NoteController::class, 'download'])
    ->name('admin.notes.download')
    ->middleware(['auth']);
