<?php

use App\Http\Controllers\PlayerLocationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('player-location/{gamemode}/{uuid}', [PlayerLocationController::class, 'show'])
        ->middleware('abilities:player-location:read');
});
