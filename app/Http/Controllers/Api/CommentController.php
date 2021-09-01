<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function getPostComments($id){
        return Comment::where('post_id', $id)->get();
    }
    
    public function createPostComment(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string', 'max:500'],
        ]);
        if($validator->fails()){
            return json_decode($validator->errors()->toJson());
        }
        $comment = Comment::create([
            'author' => Auth::user()->login,
            'content' => $request->content,
            'post_id' => $id,
        ]);
        if($comment){
            return ['success' => 'Commented in post successfully!'];
        }else{
            return ['fail' => 'Something went wrong!'];
        }
    }
}
