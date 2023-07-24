<?php

use App\Http\Controllers\api\v1\projectController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function() {
    Route::apiResource('projects', projectController::class);
});
