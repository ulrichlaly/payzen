<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BulletinController;

Route::get('/', function () {
    return 'Bienvenue sur AdminPlus 🚀';
});

Route::get('/bulletins/{id}', [BulletinController::class, 'show'])->name('bulletin.show');
Route::get('/bulletins/{id}/download', [BulletinController::class, 'download'])->name('bulletin.download');


Route::get('/clear-cache', function () {
    $output = [];
    Artisan::call('cache:clear', $output);
    Artisan::call('cache:clear', $output);
    Artisan::call('optimize:clear', $output);
    
    
    dd($output);
});



Route::get('/art-storage', function () {
    $output = [];
    Artisan::call('storage:link', $output);
    dd($output);
});