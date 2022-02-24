<?php

use App\Http\Controllers\BattleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect((Auth::guest()) ? route('login-index'): route('home'));
});

Route::get('/login', fn() => view('login.index'))->name('login-index');
Route::post('/login/publish', [LoginController::class, 'publishHash'])->name('login-publish');
Route::get('/login/published', fn() => view('login.published'))->name('login-published');
Route::get('login/check/{hash}', [LoginController::class, 'hashCheck'])->name('login-check');

Route::get('/login/password', fn() => view('login.password'))->name('login-password');
Route::post('/login/password', [LoginController::class, 'password'])->name('login-password-post');

Route::middleware('auth')->group(function () {
    Route::get('/home', fn() => view('home'))->name('home');
    Route::resource('user', UserController::class);
    Route::resource('season', SeasonController::class);
    Route::put('season/{season}/activate', SeasonController::class . '@activate')->name('season.activate');
    Route::resource('battle', BattleController::class);
});
