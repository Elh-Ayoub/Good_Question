<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use DB;
use File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|unique:users|between:5,30',
            'full_name' => 'required|string|between:5,30',
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|string',
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $name = substr($request->input('login'), 0, 2);
        $profile_photo = 'https://ui-avatars.com//api//?name='.$name.'&color=7F9CF5&background=EBF4FF';
        if($request->file('profile_photo')){
            $profile_photo = $this->uploadImage($request);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
            'profile_photo' => $profile_photo,
            ]
        ));
        event(new Registered($user));
        if($user){
            return back()->with('success', 'Account created successfully. And an email has been sent to: ' . $user->email);
        }else{
            return back()->with('fail', 'Somthing went wrong! Try again.');
        }
    }
    function uploadImage($request){
        $image = $request->file('profile_photo');
        if($image){
            $filename = str_replace(' ', '-', $request->input('login')). '.png';
            $image = $request->file('profile_photo')->store('public');
            $image1 = $request->file('profile_photo')->move(public_path('/profile-pictures'), $filename);
            return url('/profile-pictures/' . $filename);
        }
    }
    public function setDefaultAvatar(Request $request){
        $user = Auth::user();
        $name = substr($user->login, 0, 2);
        File::delete(public_path(parse_url($user->profile_photo, PHP_URL)));
        $profile_photo = 'https://ui-avatars.com//api//?name='.$name.'&color=7F9CF5&background=EBF4FF';
        DB::update('update users set profile_photo = ? where id = ?', [$profile_photo , $user->id]);
        return redirect('admin/profile');
    }

    public function UpdateAvatar(Request $request){
        $user = Auth::user();
        $image = $request->file('image');
        if($image){
            $fileName =str_replace(' ', '-', $user->login) . '.png';
            $image = $request->file('image')->store('public');
            $image1 = $request->file('image')->move(public_path('/profile-pictures'), $fileName);
            $user->profile_photo  = url('/profile-pictures/' . $fileName);
            DB::update('update users set profile_photo = ? where id = ?', [url('/profile-pictures/' . $fileName), $user->id]);
            return redirect('/admin/profile');
        }
        return response()->json('error', 404);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfiles(Request $request)
    {
        $user = User::find($request->user);
        $validator = Validator::make($request->all(), [
            'login' => 'string|between:5,30',
            'full_name' => 'string|between:5,30',
            'email' => 'string|email|max:100',
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $profile_photo = $user->profile_photo;
        if($user->login != $request->login && User::where('login', $request->login)->first()){
            return back()->with('fail', 'Login already exist!');
        }
        if($user->email != $request->email && User::where('email', $request->email)->first()){
            return back()->with('fail', 'Email already exist!');
        }
        if($request->file('profile_photo')){
            File::delete(public_path(parse_url($user->profile_photo, PHP_URL_PATH)));
            $profile_photo = $this->uploadImage($request);
        }else if($request->input('login') && !$request->file('profile_photo') && $user->login !== $request->input('login') ){
            $filename = str_replace(' ', '-', $request->input('login')) . '.png';
            Storage::move(parse_url($user->profile_photo, PHP_URL_PATH),
            '/profile-pictures/' . $filename);
            $profile_photo = url('profile-pictures/'. $filename);
        }
        $user->update(array_merge($request->all(), ['profile_photo' => $profile_photo]));
        return back()->with('success', 'Account Updated successfully!');
    }
    public function updateAdmin(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'login' => 'string|unique:users|between:5,30',
            'full_name' => 'string|unique:users|between:5,30',
            'email' => 'string|email|max:100|unique:users'
        ]);
        if($request->input('login')){
            if(!strpos($user->profile_photo, ".png")){
                $profile_photo = 'https://ui-avatars.com//api//?name='. substr($request->input('login'), 0, 2).'&color=7F9CF5&background=EBF4FF';
            }else{
                Storage::move(parse_url($user->profile_photo, PHP_URL_PATH),
                'profile-pictures/'. str_replace(' ', '-', $request->input('login')). '.png');
                $profile_photo = url('/profile-pictures/' . str_replace(' ', '-', $request->input('login')). '.png');
            }
        }
        else{
            $profile_photo = $user->profile_photo;
        }
        User::where('id', $user->id)->update([
            'login' => $request->input('login'), 
            'email' =>$request->input('email'), 
            'full_name' => $request->input('full_name'),
            'profile_photo' => $profile_photo,
        ]);
        return redirect('/admin/profile');
    }
    public function UpdateAdminPassword(Request $request){
        $user = Auth::user();
        if(Hash::check($request->current_password, $user->password)){
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|confirmed|min:8',
            ]);
            if($validator->fails()){
                return back()->with('password-fail-arr', json_decode($validator->errors()->toJson()));
            }
            User::where('id', $user->id)->update(['password' => bcrypt($request->password)]);
            return back()->with('password-success', 'Password Updtaed!');
        }else{
            return back()->with('password-fail', 'Incorrect password!');
        }
    }
    public function deleteProfiles(Request $request){
        User::destroy($request->user);
        return redirect('admin/users')->with('success', 'Account deleted successfully!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
