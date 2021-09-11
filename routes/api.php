<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LikeController;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;


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

 //////////////////// ----------Authentication module----------  ////////////////////

    Route::post('auth/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::post('auth/register', [AuthController::class, 'register'])->name('api.auth.register');
    //Send reset password link
    Route::post('auth/password-reset',[AuthController::class, 'sendResetLink']);
    
 //////////////////////////////////////////////////////////////////////////

 //////////////////// ----------User module----------  ////////////////////
    Route::get('users/profile', [UserController::class, 'profile']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users/avatar', [UserController::class, 'updateAvatar']);
    Route::delete('users/avatar', [UserController::class, 'deleteAvatar']);
    Route::patch('users/{id}', [UserController::class, 'update']);
    Route::patch('users/{id}/password', [UserController::class, 'updatePassword']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

 //////////////////////////////////////////////////////////////////////////

 //////////////////// ----------Posts module----------  ////////////////////

 Route::get('posts', [PostController::class, 'index']);
 Route::post('posts', [PostController::class, 'create']);
 Route::get('posts/{id}', [PostController::class, 'show']);
 Route::patch('posts/{id}', [PostController::class, 'update']);
 Route::delete('posts/{id}', [PostController::class, 'destroy']);
//comments of posts
Route::get('posts/{id}/comments', [CommentController::class, 'getPostComments']);
Route::post('posts/{id}/comments', [CommentController::class, 'createPostComment']);
//categories of post
Route::get('posts/{id}/categories', [CategoryController::class, 'getPostCategories']);
//like of post
 Route::get('posts/{id}/like', [LikeController::class, 'getPostLike']);
 Route::post('posts/{id}/like', [LikeController::class, 'createPostLike']);
 Route::delete('posts/{id}/like', [LikeController::class, 'deletePostLike']);
 //////////////////////////////////////////////////////////////////////////

 //////////////////// ----------Categories module----------  ////////////////////
  Route::get('categories', [CategoryController::class, 'index']);
  Route::get('categories/{id}', [CategoryController::class, 'show']);
  Route::get('categories/{id}/posts', [CategoryController::class, 'getPostsByCategory']);
  Route::post('categories', [CategoryController::class, 'create']);
  Route::patch('categories/{id}', [CategoryController::class, 'update']);
  Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
 //////////////////////////////////////////////////////////////////////////

 //////////////////// ----------Like module----------  ////////////////////
  Route::get('likes', [LikeController::class, 'index']);
  Route::delete('likes/{id}', [LikeController::class, 'destroy']);
 //////////////////////////////////////////////////////////////////////////

  //////////////////// ----------Comment module----------  ////////////////////
  Route::get('comments', [CommentController::class, 'index']);
  Route::get('comments/{id}', [CommentController::class, 'show']);
  Route::get('comments/{id}/like', [LikeController::class, 'getCommentLike']);
  Route::post('comments/{id}/like', [LikeController::class, 'createCommentLike']);
  Route::patch('comments/{id}', [CommentController::class, 'update']);
  Route::delete('comments/{id}', [CommentController::class, 'destroy']);
  Route::delete('comments/{id}/like', [LikeController::class, 'deleteCommentLike']);
 //////////////////////////////////////////////////////////////////////////

