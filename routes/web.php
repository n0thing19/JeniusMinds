<?php

use App\Http\Controllers\AuthContoller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use Illuminate\Routing\RouteRegistrar;


Route::get('/', function () {
    return view('homepage.index');
});

// --- Authentication Routes ---
// Routes for guests (users who are not logged in)
Route::controller(AuthContoller::class)
    ->group(function () {
        Route::get('/signin', 'signin')->name('signin');
        Route::post('/signin', 'authenticate')->name('authenticate');
        Route::get('/signup', 'signup')->name('signup');
        Route::post('/signup', 'register')->name('register');
    });

// --- Protected Routes ---
// Routes that require a user to be authenticated
Route::middleware('auth')->group(function () {
    // It's good practice to protect the signout route
    Route::post('/signout', [AuthContoller::class, 'signout'])->name('signout');
    quizeditor();
    Route::get('/profile', function () {
        return view('profile.myprofile');
    })->name('profile');
    Route::get('/editprofile', function () {
        return view('profile.editprofile');
    })->name('editprofile');
});


function quizeditor(): RouteRegistrar{
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
