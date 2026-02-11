<?php

use App\Http\Controllers\admin\api\TagController;
use Illuminate\Support\Facades\Route;

// Route::prefix('api')->group(function(){
//     Route::get('/testApi',[TagsController::class, 'index'] );
// });
Route::group([
    'middleware'=>['auth:api']

],function(){
    Route::apiResource('tags', TagController::class);
});
