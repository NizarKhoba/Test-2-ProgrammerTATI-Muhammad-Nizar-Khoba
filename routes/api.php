<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\TestController;

Route::apiResource('provinsi', ProvinsiController::class);
Route::get('test', function () {
    return response()->json(['message' => 'API berjalan']);
});
