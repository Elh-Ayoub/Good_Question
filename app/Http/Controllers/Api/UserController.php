<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
    public function index(){
        return User::all();
    }

    public function show($id){
        return User::find($id);
    }

    public function profile(){
        $user = Auth::user();
        return ['profile' => $user];
    }
    public function updateAvatar(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpg,png|max:20000',
        ]);
        $user = Auth::user();
        $image = $request->file('image');
        if($image){
            $fileName =str_replace(' ', '-', $user->login) . '.png';
            $image = $request->file('image')->store('public');
            $image1 = $request->file('image')->move(public_path('/profile-pictures'), $fileName);
            $user->profile_photo  = url('/profile-pictures/' . $fileName);
            DB::update('update users set profile_photo = ? where id = ?', [url('/profile-pictures/' . $fileName), $user->id]);
            return ['success' => 'Profile picture updated successfully!'];
        }
        return response()->json('error', 404);
    }
    public function deleteAvatar(){
        $user = Auth::user();
        $name = substr($user->login, 0, 2);
        $defaulImgae = 'https://ui-avatars.com//api//?name='.$name.'&color=7F9CF5&background=EBF4FF';
        $user->update(['profile_photo' => $defaulImgae]);
        return ['success' => 'Avatar deleted successfully!'];
    }
    public function update(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'login' => 'unique:users|between:5,30',
            'full_name' => 'between:5,30',
            'email' => 'email|max:50|unique:users',
            'password' => 'string|confirmed|min:8',
        ]);
        if($validator->fails()){
            return json_decode($validator->errors()->toJson());
        }
    }
}
