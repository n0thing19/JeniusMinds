<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthContoller;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\RouteRegistrar;
use App\Http\Controllers\HomepageController;

// Route untuk halaman utama
Route::get('/', [HomepageController::class, 'index']);


// Grup route untuk autentikasi (hanya untuk tamu)
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
    });

    // Memanggil fungsi grup route untuk kuis
    quizeditor();
});


// Fungsi untuk mengelompokkan semua route terkait kuis
function quizeditor(): RouteRegistrar
{
    return Route::controller(QuizController::class)
        ->prefix('quiz') // Semua URL di sini akan diawali dengan /quiz
        ->name('quiz.')   // Semua nama route di sini akan diawali dengan quiz.
        ->group(function () {
            
            // --- Route untuk Editor Kuis ---
            Route::get('/editor', 'editor')->name('editor');
            Route::get('/addbutton', 'addbutton')->name('addbutton');
            Route::get('/addcheckbox', 'addcheckbox')->name('addcheckbox');
            Route::get('/addtypeanswer', 'addtypeanswer')->name('addtypeanswer');
            Route::get('/addreorder', 'addreorder')->name('addreorder');
            Route::post('/editor/store-all', 'storeAll')->name('store.all');
            
            // --- Route untuk Mengerjakan Kuis (YANG PERLU DITAMBAHKAN) ---

            // 1. Route untuk menampilkan halaman pengerjaan kuis
            // URL yang dihasilkan: /quiz/start/{topic}
            Route::get('/start/{topic}', 'start')->name('start');

            // 2. Route untuk memproses jawaban yang dikirim
            // URL yang dihasilkan: /quiz/submit
            Route::post('/submit', 'submit')->name('submit');
        });
}
