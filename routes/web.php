<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthContoller;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\RouteRegistrar;

Route::get('/', function () {
    return view('homepage.index');
});

Route::controller(AuthContoller::class)->middleware('guest')->group(function () {
    Route::get('/signin', 'signin')->name('signin');
    Route::post('/signin', 'authenticate')->name('authenticate');
    Route::get('/signup', 'signup')->name('signup');
    Route::post('/signup', 'register')->name('register');
});


Route::middleware('auth')->group(function () {
    Route::post('/signout', [AuthContoller::class, 'signout'])->name('signout');
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function() {
            Route::get('/', 'show')->name('show');
            Route::get('/edit', 'edit')->name('edit');
    });

    quizeditor();
});


function quizeditor(): RouteRegistrar
{
    return Route::controller(QuizController::class)
        ->prefix('quiz')
        ->name('quiz.')
        ->group(function () {
            Route::get('/editor', 'editor')->name('editor');
            Route::get('/addbutton', 'addbutton')->name('addbutton');
            Route::get('/addcheckbox', 'addcheckbox')->name('addcheckbox');
            Route::get('/typeanswer', 'addtypeanswer')->name('typeanswer');
            Route::get('/reorder', 'addreorder')->name('addreorder');
        });
}