<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
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
        $checkLike = Like::where(['post_id'=> $id, 'author' => Auth::user()->login])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'like'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    Like::destroy($checkLike->id);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Like removed successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    $checkLike->update(['type' => 'dislike']);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Disliked post successfully!'];
                }            
            }
            //if it's dislike
            elseif($checkLike->type == 'dislike'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    $checkLike->update(['type' => 'like']);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Liked post successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    Like::destroy($checkLike->id);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Dislike removed successfully!'];
                } 
            }
        }else{
            $like = Like::create([
                'author' => Auth::user()->login,
                'post_id' => $id,
                'type' => $request->type,
            ]);
            if($like){
                $this->calculateRating(Auth::user());
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
        if(!Comment::find($id)){
            return ['fail' => 'Comment Requested not exist!'];
        }
        $checkLike = Like::where(['comment_id'=> $id, 'author' => Auth::user()->login])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'like'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    Like::destroy($checkLike->id);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Like removed successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    $checkLike->update(['type' => 'dislike']);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Disliked post successfully!'];
                }            
            }
            //if it's dislike
            elseif($checkLike->type == 'dislike'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    $checkLike->update(['type' => 'like']);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Liked post successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    Like::destroy($checkLike->id);
                    $this->calculateRating(Auth::user());
                    return ['success' => 'Dislike removed successfully!'];
                } 
            }
        }else{
            $like = Like::create([
                'author' => Auth::user()->login,
                'comment_id' => $id,
                'type' => $request->type,
            ]);
            if($like){
                $this->calculateRating(Auth::user());
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

    public function calculateRating($user){
        $likes = Like::where('author', $user->login)->get();
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
