<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/docs/api-docs.json', function () {
    $path = storage_path('api-docs/api-docs.json');

    if (!File::exists($path)) {
        return response()->json(['error' => 'File not found'], 404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/json',
    ]);
});
Route::get('/swagger-fix', function () {
    return view('swagger');
});