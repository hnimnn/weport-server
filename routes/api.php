<?php

use App\Http\Controllers\api\v1\ProjectController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\AuthController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    //----Auth
    Route::post(
        '/v1/register',
        [AuthController::class, 'register']
    );
    Route::post(
        '/v1/login',
        [AuthController::class, 'login']
    );
    Route::post(
        '/v1/logout',
        [AuthController::class, 'logout']
    );
    //----User
    Route::get(
        '/v1/user/projects',
        [UserController::class, 'myProject']
    );

    //----Project
    Route::group(['prefix' => 'v1'], function () {
        Route::apiResource('projects', ProjectController::class);
    Route::post(
        '/projects/{id}/like',
        [ProjectController::class, 'like']
    );
    Route::post(
        '/projects/{id}/save',
        [ProjectController::class, 'save']
    );
    Route::post(
        '/projects/{id}/buy',
        [ProjectController::class, 'buy']
    );
    Route::post(
        '/projects/{id}/view',
        [ProjectController::class, 'view']
    );

    Route::group([
        'middleware' => 'checkAdmin',
        'prefix' => 'admin',
    ], function ($router) {
        Route::get(
            '/projects/all',
            [ProjectController::class, 'allProjects']
        );
        Route::post(
            '/projects/{id}/approve',
            [ProjectController::class, 'approve']
        );
    });
    });

});
