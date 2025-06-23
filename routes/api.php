<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\TaskController;
use App\Http\Controllers\Api\v1\ProjectController;
use App\Http\Controllers\Api\v1\TaskHistoryController;
use App\Http\Controllers\Api\v1\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update.status');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{id}/history', [TaskHistoryController::class, 'index'])->name('tasks.history.index');
    Route::post('/tasks/{id}/comments', [CommentController::class, 'store'])->name('comments.store');

});
