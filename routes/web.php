<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

// Route::get('/edit', function () {
//     return view('editPost');
// });

Route::get('/recipes/detail/{id}', [App\Http\Controllers\RecipeController::class, 'showView']);

// Route::get('/recipes/detail/edit/{id}', [App\Http\Controllers\RecipeController::class, 'editView']);

Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('user');

// Route::get('/home/customer', [App\Http\Controllers\HomeController::class, 'customer'])->middleware('user','fireauth');

Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');

Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

Route::resource('/account', App\Http\Controllers\Auth\AccountController::class)->middleware('user','fireauth');

Route::resource('/profile', App\Http\Controllers\Auth\ProfileController::class)->middleware('user','fireauth');

Route::resource('/post', App\Http\Controllers\Auth\PostController::class)->middleware('user','fireauth');

Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);



// Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

Route::resource('/img', App\Http\Controllers\ImageController::class);

URL::forceScheme('https');
