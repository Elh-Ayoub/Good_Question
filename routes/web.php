<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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
    return view('welcome');
});
 //////////////////// ----------Authentication module----------  ////////////////////
Route::group([
    'prefix' => 'admin'
], function () {
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/home', function () {
        return view('Admin.home');
    })->name('admin.dashboard');
    Route::get('create/user', function(){
        return view('Admin.Users.create');
    })->name('create.user.view');
    Route::post('create/user', [UserController::class, 'create'])->name('create.user');
});
Route::group([
    'middleware' => 'AuthCheck',
    'prefix' => 'admin'
], function () {
    Route::get('/profile', [AuthController::class, 'useProfile'])->name('admin.profile');
});
Route::group([
    'middleware' => 'web',
    'prefix' => 'admin'
], function () {
    Route::get('/auth/login', function(){
        return view('Admin.Auth.login');
    })->name('login');
    Route::get('/auth/register', function(){
        return view('Admin.Auth.register');
    })->name('register');
//  ---------Forget password----------
    Route::get('auth/forgot-password', function(){
        return view('Admin.Auth.forgot-password');
    })->name('password.forgot');
    Route::get('/reset-password/{token}', function ($token) {
        return view('Admin.Auth.reset-password', ['token' => $token]);
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
});

