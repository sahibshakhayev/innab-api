<?php

use Illuminate\Support\Facades\Route;
use Modules\Corporative\Http\Controllers\CorporativeController;
use Modules\Corporative\Http\Controllers\CorporativeApiController;


Route::middleware(['web', 'auth'])->group(function () {
    // 'delete_selected_items' metodu için 'checkpermission:8' middleware'i uygulandı
    Route::post('corporative/delete_selected_items', [CorporativeController::class, 'delete_selected_items'])
        ->middleware('checkpermission:8,4')
        ->name('corporative.delete_selected_items');
});


Route::prefix('{locale}')->group(function () {
    Route::get('/get_corporative', [CorporativeApiController::class, 'get_corporative'])->name('corporative.get_corporative');
});
