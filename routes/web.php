<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/signin', function () {
    return view('auth.signin'); // It's conventional to use dot notation for views
})->name('signin');

Route::get('/signup', function () {
    return view('auth.signup'); // It's conventional to use dot notation for views
})->name('signup');



