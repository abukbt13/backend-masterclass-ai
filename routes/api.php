<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Thread\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [AuthenticationController::class, 'Register']);
        Route::post('login', [AuthenticationController::class, 'Login']);
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('auth/user-auth', [AuthenticationController::class, 'auth']);
        Route::post('chats', [ChatController::class, 'asKQuestion']);
        Route::get('chats', [ChatController::class, 'showQuestion']);

        Route::get('thread/active', [ThreadController::class, 'showActiveThread']);
        Route::get('thread/update/{id}', [ThreadController::class, 'ActivateThread']);
        Route::get('threads', [ThreadController::class, 'showThreads']);

    });

});



