<?php

use Illuminate\Support\Facades\Route;
use Modules\VideoLessonsTitle\Http\Controllers\VideoLessonsTitleController;
use Modules\VideoLessonsTitle\Http\Controllers\VideoLessonsTitleApiController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/


Route::middleware(['web', 'auth'])->group(function () {
    Route::post('videolessonstitle/delete_selected_items', [VideoLessonsTitleController::class, 'delete_selected_items'])
        ->middleware('checkpermission:20,4')
        ->name('videolessonstitle.delete_selected_items');
});



Route::prefix('{locale}')->group(function () {
    Route::get('/get_videolessonstitle/{id}', [VideoLessonsTitleApiController::class, 'get_videolessonstitle'])->name('videolessonstitle.get_name');
});
