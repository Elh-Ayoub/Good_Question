<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index(){
        return Like::all();
    } 

    public function getPostLike($id){
        return Like::where('post_id', $id)->get();
    }

    public function createPostLike(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
        ]);
        if($validator->fails()){
            return json_decode($validator->errors()->toJson());
        }
        $user_id = null;
        if(Auth::user()){
            $user_id = Auth::id();
        }
        if($request->user){
            $user_id = $request->user;
        }
        $checkLike = Like::where(['post_id'=> $id, 'author' => $user_id])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'like'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    Like::destroy($checkLike->id);
                    $this->calculateRating($user_id);
                    return ['success' => 'Like removed successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    $checkLike->update(['type' => 'dislike']);
                    $this->calculateRating($user_id);
                    return ['success' => 'Disliked post successfully!'];
                }            
            }
            //if it's dislike
            elseif($checkLike->type == 'dislike'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    $checkLike->update(['type' => 'like']);
                    $this->calculateRating($user_id);
                    return ['success' => 'Liked post successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    Like::destroy($checkLike->id);
                    $this->calculateRating($user_id);
                    return ['success' => 'Dislike removed successfully!'];
                } 
            }
        }else{
            $like = Like::create([
                'author' => $user_id,
                'post_id' => $id,
                'type' => $request->type,
            ]);
            if($like){
                $this->calculateRating($user_id);
                return ['success' => $request->type. ' post successfully!'];
            }else{
                return ['fail' => 'Something went wrong!'];
            }
        }
    }

    public function deletePostLike($id){
        $like = Like::where(['post_id'=> $id, 'author' => Auth::user()->login])->first();
        if($like){
            Like::destroy($like->id);
            $this->calculateRating(Auth::user());
            return ['success' => 'Like deleted successfully!'];
        }
        else{
            return ['fail' => 'Like not exist under this post!'];
        }
    }

    public function getCommentLike($id){
        return Like::where('comment_id', $id)->get();
    }

    public function destroy($id){
        if(Like::find($id)){
            Like::destroy($id);
            $this->calculateRating(Auth::user());
            return ['success' => 'Like deleted successfully!'];
        }else{
            return ['fail' => 'Like requested not found!'];
        }
    }
    public function createCommentLike(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
        ]);
        if($validator->fails()){
            return json_decode($validator->errors()->toJson());
        }
        if(Auth::user()){
            $user_id = Auth::id();
        }
        if($request->user){
            $user_id = $request->user;
        }
        if(!Comment::find($id)){
            return ['fail' => 'Comment Requested not exist!'];
        }
        $checkLike = Like::where(['comment_id'=> $id, 'author' => $user_id])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'like'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    Like::destroy($checkLike->id);
                    $this->calculateRating($user_id);
                    return ['success' => 'Like removed successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    $checkLike->update(['type' => 'dislike']);
                    $this->calculateRating($user_id);
                    return ['success' => 'Disliked post successfully!'];
                }            
            }
            //if it's dislike
            elseif($checkLike->type == 'dislike'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    $checkLike->update(['type' => 'like']);
                    $this->calculateRating($user_id);
                    return ['success' => 'Liked post successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    Like::destroy($checkLike->id);
                    $this->calculateRating($user_id);
                    return ['success' => 'Dislike removed successfully!'];
                } 
            }
        }else{
            $like = Like::create([
                'author' => $user_id,
                'comment_id' => $id,
                'type' => $request->type,
            ]);
            if($like){
                $this->calculateRating($user_id);
                return ['success' => $request->type. ' post successfully!'];
            }else{
                return ['fail' => 'Something went wrong!'];
            }
        }
    }

    public function deleteCommentLike($id){
        $like = Like::where(['comment_id'=> $id, 'author' => Auth::user()->login])->first();
        if($like){
            Like::destroy($like->id);
            $this->calculateRating(Auth::user());
            return ['success' => 'Like deleted successfully!'];
        }
        else{
            return ['fail' => 'Like not exist under this comment!'];
        }
    }

    public function calculateRating($user_id){
        $user = User::find($user_id);
        $likes = Like::where('author', $user_id)->get();
        $rating = 0;
        foreach($likes as $like){
            if($like->type == 'like'){
                $rating++;
            }else{
                $rating--;
            }
        }
        $user->update(['rating' => $rating]);
    }
}
