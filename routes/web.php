<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LikeController;
use App\Http\Controllers\Admin\VerifyEmailController;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;

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
    return redirect('admin/auth/login');
});
 //////////////////// ----------Authentication module----------  ////////////////////

Route::group([
    'middleware' => ['web', 'AuthCheck'],
    'prefix' => 'admin',
], function () {
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/auth/login', function(){
        return view('Admin.Auth.login');
    })->name('login');
    Route::get('/auth/register', function(){
        return view('Admin.Auth.register');
    })->name('register');
});
//  ---------Forget password----------
Route::get('auth/forgot-password', function(){
    return view('Admin.Auth.forgot-password');
})->name('password.forgot');
Route::get('/reset-password/{token}', function (Request $request, $token) {
    return view('Admin.Auth.reset-password', ['token' => $token, 'email' => $request->email]);
})->middleware('guest')->name('password.reset');
Route::patch('auth/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');
Route::post('auth/forgot-password',[AuthController::class, 'sendResetLink'])->middleware('guest')->name('password.send');
//  ---------Email verification----------
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
->middleware(['signed', 'throttle:6,1'])
->name('verification.verify');
Route::post('/email/verify/resend', [VerifyEmailController::class, 'resendVerification'] )->name('verification.send');
Route::get('/email/verify', function(){
    return view('Admin.Auth.verifyEmail');
})->name('verification.resend');
Route::get('/email/verify/success', function(){
    return redirect('admin/auth/login')->with('success', 'Email verified successfully!');
});
Route::get('/email/verify/already-success', function(){
    return redirect('admin/auth/login')->with('success', 'Email already verified! Thank you.');
});
 //////////////////// ----------Users module----------  ////////////////////

Route::group([
    'middleware' => 'AuthCheck',
    'prefix' => 'admin',
], function () {
    Route::get('/profile', [AuthController::class, 'useProfile'])->name('admin.profile');
    Route::get('/home', function () {
        $users = count(User::where('role', 'user')->get());
        $admins = count(User::where('role', 'admin')->get());
        $posts = count(Post::all());
        $categories = count(Category::all());
        $comments = count(Comment::all());
        $likes = count(Like::all());
        return view('Admin.home', ['users' => $users, 'admins' => $admins, 'posts' => $posts, 'categories' => $categories, 'comments' => $comments, 'likes' => $likes]);
    })->name('admin.dashboard');
    Route::get('user/create', function(){
        return view('Admin.Users.create');
    })->name('create.user.view');
    Route::post('user/create', [UserController::class, 'create'])->name('create.user');
    Route::patch('profile/update/', [UserController::class, 'UpdateAdmin'])->name('admin.update');
    Route::patch('password/update/', [UserController::class, 'UpdateAdminPassword'])->name('admin.password');
    Route::patch('avatar/update', [UserController::class, 'UpdateAvatar'])->name('admin.update.avatar');
    Route::delete('avatar/delete', [UserController::class, 'setDefaultAvatar'])->name('admin.delete.avatar');
    Route::get('/users', function(){return view('Admin.Users.list', ['users' => User::all()]);})->name('users.list');
    Route::get('users/update',function(Request $request){$user = User::find($request->user);return view('Admin.Users.profile', ['user' => $user, 'posts' => Post::where('author', $user->id)->get()]);})->name('users.update.view');
    Route::patch('users/update',[UserController::class, 'updateProfiles'])->name('users.update');
    Route::delete('users/delete',[UserController::class, 'deleteProfiles'])->name('users.delete');
});
 //////////////////// ----------Posts module----------  ////////////////////
 Route::group([
    'middleware' => 'AuthCheck',
    'prefix' => 'admin',
], function () {
    Route::get('/posts', [PostController::class, 'Postlist'])->name('posts.list');
    Route::get('posts/create', function(){return view('Admin.Posts.create');})->name('posts.create.view');
    Route::post('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('posts/update/{id}', function($id){return view('Admin.Posts.edit', ['post' => Post::find($id)]);})->name('posts.update.view');
    Route::patch('posts/update', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/delete/{id}', [PostController::class, 'destroy'])->name('posts.delete');
});
 //////////////////// ----------Posts module----------  ////////////////////
 Route::group([
    'middleware' => 'AuthCheck',
    'prefix' => 'admin',
], function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.list');
    Route::post('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::patch('categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

});
 //////////////////// ----------Comments module----------  ////////////////////
Route::group([
    'middleware' => 'AuthCheck',
    'prefix' => 'admin',
], function () {
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.list');
    Route::post('/comments/create', [CommentController::class, 'create'])->name('comments.create');
    Route::patch('comments/update/{id}', [CommentController::class, 'updateStatus'])->name('comments.update');
    Route::delete('comments/delete/{id}', [CommentController::class, 'destroy'])->name('comments.delete');
});
 //////////////////// ----------Like module----------  ////////////////////
 Route::group([
    'middleware' => 'AuthCheck',
    'prefix' => 'admin',
], function () {
    Route::get('/likes', [LikeController::class, 'index'])->name('likes.list');
    //like/dislike post
    Route::post('post/like/create', [LikeController::class, 'createLikePost'])->name('like.post.create');
    Route::post('post/dislike/create', [LikeController::class, 'createDislikePost'])->name('dislike.post.create');
    //like/dislike comment
    Route::post('comment/like/create', [LikeController::class, 'createLikeComment'])->name('like.comment.create');
    Route::post('comment/dislike/create', [LikeController::class, 'createDislikeComment'])->name('dislike.comment.create');
    //delete
    Route::delete('likes/delete/{id}', [LikeController::class, 'destroy'])->name('likes.delete');

});
