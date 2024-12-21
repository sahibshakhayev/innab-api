<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\TrainingApiController;
use Modules\Training\Http\Controllers\TrainingController;

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
    Route::post('training/delete_selected_items', [TrainingController::class, 'delete_selected_items'])
        ->middleware('checkpermission:23,4')
        ->name('training.delete_selected_items');
});


Route::prefix('{locale}')->group(function () {
    Route::get('/get_trainings/{id}', [TrainingApiController::class, 'get_trainings'])->name('training.get_trainings');
});
