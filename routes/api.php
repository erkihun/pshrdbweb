<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\Admin\TicketController as AdminTicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/news', [PostController::class, 'news']);
    Route::get('/announcements', [PostController::class, 'announcements']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/downloads', [DocumentController::class, 'index']);

    Route::post('/tickets', [TicketController::class, 'store'])->middleware('throttle:tickets');
    Route::get('/tickets/{reference_code}', [TicketController::class, 'show']);

    Route::middleware('auth:sanctum')->prefix('admin')->name('api.admin.')->group(function () {
        Route::middleware('api_permission:manage posts')->group(function () {
            Route::apiResource('posts', AdminPostController::class);
        });

        Route::middleware('api_permission:manage services')->group(function () {
            Route::apiResource('services', AdminServiceController::class);
        });

        Route::middleware('api_permission:manage documents')->group(function () {
            Route::apiResource('documents', AdminDocumentController::class);
        });

        Route::middleware('api_permission:manage tickets')->group(function () {
            Route::apiResource('tickets', AdminTicketController::class)->only(['index', 'show', 'update', 'destroy']);
        });
    });
});
