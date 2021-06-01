<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Post\CommentController;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/posts', [PostController::class, 'index', 'middleware' => 'auth:api'])->name('posts');
Route::get('/user/{user}/posts', [PostController::class, 'user_post'])->name('posts.show')->name('user.posts');
Route::post('/posts', [PostController::class, 'store', 'middleware' => 'auth:api'])->name('posts.create');
Route::put('/posts/{post}', [PostController::class, 'update', 'middleware' => 'auth:api'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->name('posts.delete');


Route::get('/posts/{post}/comments', [CommentController::class, 'index', 'middleware' => 'auth:api'])->name('posts.comments');
Route::post('/posts/{post}/comments', [CommentController::class, 'store', 'middleware' => 'auth:api']);
Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update', 'middleware' => 'auth:api']);
Route::delete('/posts/comments/{comment}', [CommentController::class, 'destroy', 'middleware' => 'auth:api']);


Route::middleware('auth:api')->post('logout', [LogoutController::class, 'logout'])->name('logout');
