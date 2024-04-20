<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CheckAuthentication;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/recipes',[RecipeController::class, 'store'])->name('recipes.store');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/recipes/{id}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
Route::put('/recipes/{id}', [RecipeController::class, 'update'])->name('recipes.update');
Route::put('/recipes/verified/{id}', [RecipeController::class, 'verified'])->name('recipes.verified');
Route::delete('/recipes/delete/{id}',[RecipeController::class, 'destroy'])->name('recipes.delete');

Route::get('/banners', [BannerController::class, 'index'])->name('banner.index');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

Route::post('/login',[LoginController::class, 'login']);
Route::get('/user',[LoginController::class, 'userInfo'])->middleware("auth");

Route::post('/upload-image', [ImageController::class, 'store'])->name('image.store');
Route::delete('/delete-image', [ImageController::class, 'deleteImage'])->name('image.delete');



// Route::middleware('auth:api')->group(function () {
//     // Route::get('/user', function (Request $request) {
//     //     return $request->user();
//     // });

//     // Tambahkan rute API lainnya yang memerlukan otentikasi di sini
// });



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


URL::forceScheme('https');
