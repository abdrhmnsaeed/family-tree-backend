<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/img/{filename}', function ($filename) {
    $path = base_path('img/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});

