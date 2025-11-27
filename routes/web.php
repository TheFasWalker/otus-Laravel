<?php

use App\Http\Controllers\admin\CountryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\TagsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function(){
    return view('pages.HomePage');
})->name('home');
Route::get('/catalog',function(){
    return view('pages.CatalogPage');
})->name('catalog');
Route::get('/product',function(){
    return view('pages.ProductPage');
})->name('product.page');
Route::get('/lk',function(){
    return view('pages.UserPage');
})->name('lk');

Route::group(['prefix'=>'/admin','as'=>'admin.','middleware' => ['auth','verified']],function(){
    Route::get('/',[HomeController::class,'index'])->name('home');
    Route::group(['prefix'=>'/country'],function(){
        Route::get('/',[CountryController::class,'index'])->name('country');
    });
    Route::group((['prefix'=>'/tags']), function(){
        Route::get('/',[TagsController::class,'index'])->name('tags');
    });
    Route::group((['prefix'=>'/products']), function(){
        Route::get('/',[ProductsController::class,'index'])->name('products');
    });


});

require __DIR__.'/auth.php';
