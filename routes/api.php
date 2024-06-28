<?php

use Illuminate\Support\Facades\Route;

Route::post('images/upload', \App\Http\Controllers\Image\Upload::class)->name('upload_image');
Route::get('images', \App\Http\Controllers\Image\RetrieveCollection::class)->name('images');
Route::post('images/verify', \App\Http\Controllers\Image\Verify::class)->name('verify_image');
