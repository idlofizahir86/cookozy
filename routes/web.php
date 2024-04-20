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
if (App::environment('production')) {
    // Pengalihan untuk '/'
    Route::redirect('/', 'https://cookozy.web.app/');

    // Pengalihan untuk '/home'
    Route::redirect('/home', 'https://cookozy.web.app/home');

    // Pengalihan untuk '/logout'
    Route::redirect('/logout', 'https://cookozy.web.app/logout');

    // Pengalihan untuk '/login'
    Route::redirect('/login', 'https://cookozy.web.app/login');

    // Pengalihan untuk '/register'
    Route::redirect('/register', 'https://cookozy.web.app/regiter');

    // Pengalihan untuk '/about'
    Route::redirect('/about', 'https://cookozy.web.app/about');

    // Pengalihan untuk '/profile'
    Route::redirect('/profile', 'https://cookozy.web.app/profile');

    // Pengalihan untuk '/account'
    Route::redirect('/account', 'https://cookozy.web.app/account');
    // Pengalihan untuk '/contact'

    Route::redirect('/post', 'https://cookozy.web.app/post');
}


Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/edit', function () {
    return view('editPost');
});


Route::get('/recipes/detail/{id}', [App\Http\Controllers\RecipeController::class, 'showView']);

Route::get('/recipes/verified/{id}', [App\Http\Controllers\RecipeController::class, 'verifiedView'])->middleware('fireauth');

// Route::get('/recipes/detail/edit/{id}', [App\Http\Controllers\RecipeController::class, 'editView']);

Auth::routes();

    Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('user');

// Route::get('/home/customer', [App\Http\Controllers\HomeController::class, 'customer'])->middleware('user','fireauth');

Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');

Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

Route::resource('/account', App\Http\Controllers\Auth\AccountController::class)->middleware('user','fireauth');

Route::resource('/profile', App\Http\Controllers\Auth\ProfileController::class)->middleware('user','fireauth');

Route::resource('/post', App\Http\Controllers\Auth\PostController::class)->middleware('user','fireauth');
Route::get('/post/{id}/edit', [App\Http\Controllers\Auth\PostController::class, 'edit'])->middleware('user','fireauth');
Route::put('/post/{id}', [App\Http\Controllers\Auth\PostController::class, 'update'])->middleware('user','fireauth');

// Route::resource('/post/edit', [App\Http\Controllers\Auth\PostController::class, 'edit'])->middleware('user','fireauth');

Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);



// Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

Route::resource('/img', App\Http\Controllers\ImageController::class);

// URL::forceScheme('https');
