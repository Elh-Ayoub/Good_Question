<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;


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
        $name = substr($user->username, 0, 2);
        File::delete(public_path('/profile-pictures/'. str_replace(' ', '-', $user->username) . '.png'));
        $profile_photo_path = 'https://ui-avatars.com//api//?name='.$name.'&color=7F9CF5&background=EBF4FF';
        DB::update('update users set profile_photo_path = ? where id = ?', [$profile_photo_path , $user->id]);
        return redirect('/admin/profile');
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
    public function update(Request $request, $id)
    {
        //
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
