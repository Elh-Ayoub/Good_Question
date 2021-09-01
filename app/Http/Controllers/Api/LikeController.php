<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function getPostLike($id){
        return ['likes' => Like::where('post_id', $id)->get()];
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
                    return ['success' => 'Like removed successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    $checkLike->update(['type' => 'dislike']);
                    return ['success' => 'Disliked post successfully!'];
                }            
            }
            //if it's dislike
            elseif($checkLike->type == 'dislike'){
                // and requested like type is "like"
                if($request->type == 'like'){
                    $checkLike->update(['type' => 'like']);
                    return ['success' => 'Liked post successfully!'];
                }
                // and requested like type is "dislike"
                else{
                    Like::destroy($checkLike->id);
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
            return ['success' => 'Like deleted successfully!'];
        }
        else{
            return ['fail' => 'Like not exist under this post!'];
        }
    }
}
