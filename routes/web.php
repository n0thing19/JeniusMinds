<?php

use App\Http\Controllers\AuthContoller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::controller(AuthContoller::class)
    ->group(function () {
        Route::get('/signin', 'signin')->name('signin');
        Route::post('/signin', 'authenticate')->name('authenticate');
        Route::get('/signup', 'signup')->name('signup');
        Route::post('/signup', 'register')->name('register');
        Route::post('/signout', 'signout')->name('signout');
    });


