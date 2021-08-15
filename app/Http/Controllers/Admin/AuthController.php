<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Mail;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'login' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if(Auth::user()->role == 'admin'){
                return redirect('admin/home');
            }else{
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('fail', 'Only Admins can log in.');
            }
        }
        return back()->with('fail', 'The provided credentials do not match our records.');
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|unique:users|between:5,30',
            'full_name' => 'required|string|between:5,30',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        if($validator->fails()){
            return back()->with('fail', json_decode($validator->errors()->toJson()));
        }
        $name = substr($request->input('login'), 0, 2);
        $role = 'user';
        if(($request->path() == 'admin/auth/register')){
            $role = 'admin';
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
            'profile_photo' => 'https://ui-avatars.com//api//?name='.$name.'&color=7F9CF5&background=EBF4FF',
            'role' => $role]
        ));
        event(new Registered($user));
        if($user){
            return back()->with('success', 'Account created successfully. Please check mailbox to verify email.');
        }else{
            return back()->with('fail', ['error' => 'Somthing went wrong! Try again.']);
        }
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('admin/auth/login');
    }
    public function useProfile() {
        $data = ['loggedUserInfo' => Auth::user()];
        return view('Admin.profile', $data);
    }
    public function dashboard(){
        return view('home', ['products' => Product::all()]);
    }
    public function mailUser($user, $sm) {
        $data = array('username'=> $user->username,
          'full_name'=> $user->full_name,
          'email' => $user->email,
          'social_media' => $sm,
        ); 
        Mail::send('mailUser',$data, function($message ) use($data) {
           $message->to($data['email'], 'Testing Point')->subject
              ('Registration Email');
           $message->from('ayoub.el-haddadi@gmail.com','WantOrder');
        });
    }
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function sendResetLink(Request $request){
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['success' => __($status)])
                    : back()->with(['fail' => __($status)]);
    }
    function resetPassword(Request $request){
        $request->validate([
            'token',
            'email' => 'email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->with(['email' => [__($status)]]);
    }
}