<?php

use Illuminate\Support\Facades\Route;
use admin\wishlists\Controllers\WishlistManagerController;


Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('wishlists', WishlistManagerController::class)->only(['index','show']);

}); 