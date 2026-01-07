<?php

use App\Http\Controllers\admin\CountryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\TagsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\ProductsController as UserProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
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
Route::get('/catalog', [UserProductsController::class, 'index'])->name('catalog');
Route::get('/product',function(){
    return view('pages.ProductPage');
})->name('product.page');
Route::get('/lk',function(){
    return view('pages.UserPage');
})->name('lk');
Route::get('login_as/{id}', function($id){
    $user = User::findOrFail($id);
    Auth::login($user);
    return 'loggen as ' . $user->name;
});

Route::group(['prefix'=>'/admin','as'=>'admin.','middleware' => ['auth','verified']],function(){
    Route::get('/',[HomeController::class,'index'])->name('home');
    Route::group(['prefix'=>'/country'],function(){
        Route::get('/',[CountryController::class,'index'])->name('country');
        Route::get('/create',[CountryController::class,'create'])->name('country.create');
        Route::get('{id}/edit',[CountryController::class,'edit'])->name('country.edit');
        Route::post('/store',[CountryController::class, 'store'])->name('country.store');
        Route::delete('{id}/delete',[CountryController::class,'delete'])->name('country.delete');
        Route::put('{id}/update',[CountryController::class, 'update'])->name('country.update');
    });
    Route::group((['prefix'=>'/tags']), function(){
        Route::get('/',[TagsController::class,'index'])->name('tags');
        Route::get('/create',[TagsController::class, 'create'])->name('tags.create');
        Route::get('{id}/edit',[TagsController::class, 'edit'])->name('tag.edit');
        Route::post('/store',[TagsController::class, 'store'])->name('tag.store');
        Route::delete('{id}/delete',[TagsController::class,'delete'])->name('tag.delete');
        Route::put('{id}/update',[TagsController::class,'update'])->name('tag.update');
    });
    Route::group((['prefix'=>'/products']), function(){
        Route::get('/',[ProductController::class,'index'])->name('products');
        Route::get('/create',[ProductController::class, 'create'])->name('products.create');
        Route::post('/store',[ProductController::class, 'store'])->name('product.store');
        Route::get('{product}/edit',[ProductController::class, 'edit'])->name('product.edit');
        Route::put('{product}/update',[ProductController::class, 'update'])->name('product.update');
        Route::delete('{id}/delete', [ProductController::class, 'destroy'])->name('products.delete');
    });
    Route::get('seo', function(){
        Gate::authorize('admin-content');
        return 'seoPage';
    })->name('seo');


});
Route::get('/test-memcached', function () {
    Cache::put('cache_test', 'Cache works!', 60);
    $cacheValue = Cache::get('cache_test');
    
    session(['session_test' => 'Session works!']);
    $sessionValue = session('session_test');
    
    return response()->json([
        'cache' => [
            'driver' => config('cache.default'),
            'test_value' => $cacheValue,
        ],
        'session' => [
            'driver' => config('session.driver'),
            'test_value' => $sessionValue,
            'lifetime' => config('session.lifetime'),
        ],
        'memcached' => [
            'extension_loaded' => extension_loaded('memcached'),
            'host' => config('cache.stores.memcached.servers.0.host'),
            'port' => config('cache.stores.memcached.servers.0.port'),
        ],
        'timestamp' => now()->toDateTimeString(),
    ]);
});

require __DIR__.'/auth.php';
