<?php

use Illuminate\Support\Facades\Route;
use admin\wishlists\Controllers\WishlistManagerController;


Route::name('admin.')->middleware(['web','auth:admin'])->group(function () {  
    Route::middleware('auth:admin')->group(function () {
         Route::resource('wishlists', WishlistManagerController::class)->only(['index','show']);
   
    });
}); 