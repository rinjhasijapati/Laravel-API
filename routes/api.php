<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Location\AreaController;
use App\Http\Controllers\Location\CountryController;
use App\Http\Controllers\Location\MainLocationController;
use App\Http\Controllers\Location\SubLocationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/me', function (Request $request) {
    return Auth::user();
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('agents', AgentController::class, ['except' => ['create', 'edit']]);
    Route::resource('countries', CountryController::class, ['except' => ['create', 'edit']]);
    Route::resource('areas', AreaController::class, ['except' => ['create', 'edit']]);
    Route::resource('main-locations', MainLocationController::class, ['except' => ['create', 'edit']]);
    Route::resource('sub-locations', SubLocationController::class, ['except' => ['create', 'edit']]);

    Route::resource('user', UserController::class, ['except' => ['create', 'edit']]);
    Route::post('user/logout', [UserController::class, 'logout']);
});


require __DIR__ . '/auth.php';


