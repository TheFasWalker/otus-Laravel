<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Pages.HomePage');
});

Route::get('/registration', function(){
    return view('Pages.RegistrationPage');
});

Route::get('/user',function(){
    return view('Pages.UserPage');
});

Route::get('/static', function(){
    return view('Pages.StaticPage');
});
Route::get('/base', function(){
    return view('welcome');
});
