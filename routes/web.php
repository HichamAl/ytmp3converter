<?php

use App\Http\Controllers\YouTubeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('converter'); // This points to the `resources/views/converter.blade.php` file
});

Route::get('/convert', [YouTubeController::class, 'convert'])->name('convert');
