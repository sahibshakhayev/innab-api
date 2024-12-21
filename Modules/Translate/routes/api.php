<?php

use Illuminate\Support\Facades\Route;
use Modules\Translate\Http\Controllers\TranslateController;
use Modules\Translate\Http\Controllers\TranslateApiController;


Route::middleware(['web', 'auth'])->group(function () {
    Route::post('translate/delete_selected_items', [TranslateController::class, 'delete_selected_items'])
        ->middleware('checkpermission:3,4')
        ->name('translate.delete_selected_items');
});



Route::prefix('{locale}')->group(function () {
    Route::get('/get_translate', [TranslateApiController::class, 'get_translate'])->name('translate.get_translate');
    Route::get('/get_translate', [TranslateApiController::class, 'get_translate'])->name('translate.get_translate');

});
    Route::get('/get_translate/{group}', [TranslateApiController::class, 'get_translate_group'])->name('translate.get_translate_group');