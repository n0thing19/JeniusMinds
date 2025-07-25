<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthContoller;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\RouteRegistrar;
use App\Http\Controllers\HomepageController;

// Route untuk halaman utama
Route::get('/', [HomepageController::class, 'index'])->name('homepage');


// Grup route untuk autentikasi
Route::controller(AuthContoller::class)->middleware('guest')->group(function () {
    Route::get('/signin', 'signin')->name('signin');
    Route::post('/signin', 'authenticate')->name('authenticate');
    Route::get('/signup', 'signup')->name('signup');
    Route::post('/signup', 'register')->name('register');
});


// Grup route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::post('/signout', [AuthContoller::class, 'signout'])->name('signout');
    
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function() {
            Route::get('/', 'show')->name('show');
            Route::get('/edit', 'edit')->name('edit');
            Route::patch('/update', 'update')->name('update');
            Route::get('/history/{attempt}', 'reviewAttempt')->name('review_attempt');


    });

    quizeditor();
});


// Fungsi untuk mengelompokkan semua route terkait kuis
function quizeditor(): RouteRegistrar
{
    return Route::controller(QuizController::class)
        ->prefix('quiz')
        ->name('quiz.') 
        ->group(function () {
            Route::get('/editor', 'editor')->name('editor');
            Route::post('/editor/store-all', 'storeAll')->name('store.all');
            Route::get('/editor/{topic}', [QuizController::class, 'edit'])->name('edit');
            Route::patch('/editor/{topic}', [QuizController::class, 'update'])->name('update');
            Route::delete('/topic/{topic}', 'destroy')->name('destroy');
            Route::get('/addbutton', 'addbutton')->name('addbutton');
            Route::get('/addcheckbox', 'addcheckbox')->name('addcheckbox');
            Route::get('/addtypeanswer', 'addtypeanswer')->name('addtypeanswer');
            Route::get('/addreorder', 'addreorder')->name('addreorder');
            Route::get('/start/{topic}', 'start')->name('start');
            Route::post('/submit', 'submit')->name('submit');
            Route::post('/join-with-code', 'joinWithCode')->name('join_with_code');
        });
}
