<?php

use App\Http\Controllers\admin\api\TagController;
use App\Http\Controllers\admin\api\TagsController;
use Illuminate\Support\Facades\Route;

// Route::prefix('api')->group(function(){
//     Route::get('/testApi',[TagsController::class, 'index'] );
// });
Route::group([
    'middleware'=>['auth:api']

],function(){
    Route::apiResource('api/tags', TagController::class);
});
