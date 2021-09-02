<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Like::all();
        $data = [];
        foreach($likes as $like){
            array_push($data, ['like' => $like, 'author' => User::where('login', $like->author)->first(),
                 'post' => Post::find($like->post_id), 'comment' => Comment::find($like->comment_id),
        ]);
        }
        return view('Admin.Likes.list', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createLikePost(Request $request){
        $validator = Validator::make($request->all(), [
            'post_id' => ['required', 'string'],
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $checkLike = Like::where(['post_id' => $request->post_id, 'author' => Auth::user()->login])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'like'){
                Like::destroy($checkLike->id);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Liked removed successfully!');
            }
            //if it's dislike
            else{
                $checkLike->update(['type' => 'like']);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Liked post successfully!');
            }
        }else{
            $like = Like::create([
                'author' => Auth::user()->login,
                'post_id' => $request->post_id,
                'type' => 'like',
            ]);
            if($like){
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Like post successfully!');
            }else{
                return back()->with('fail', 'Something went wrong!');
            }
        }
    }
    public function createDislikePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => ['required', 'string'],
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $checkLike = Like::where(['post_id' => $request->post_id, 'author' => Auth::user()->login])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'dislike'){
                Like::destroy($checkLike->id);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Dislike removed successfully!');
            }
            //if it's dislike
            else{
                $checkLike->update(['type' => 'dislike']);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'disiked post successfully!');
            }
        }else{
            $like = Like::create([
                'author' => Auth::user()->login,
                'post_id' => $request->post_id,
                'type' => 'dislike',
            ]);
            if($like){
                $this->calculateRating(Auth::user());
                return back()->with('success', 'disiked post successfully!');
            }else{
                return back()->with('fail', 'Something went wrong!');
            }
        }
    }

    public function createLikeComment(Request $request){
        $validator = Validator::make($request->all(), [
            'comment_id' => ['required', 'string'],
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $checkLike = Like::where(['comment_id'=> $request->comment_id, 'author' => Auth::user()->login])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'like'){
                Like::destroy($checkLike->id);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Liked removed successfully!');
            }
            //if it's dislike
            else{
                $checkLike->update(['type' => 'like']);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Liked comment successfully!');
            }
        }else{
            $like = Like::create([
                'author' => Auth::user()->login,
                'comment_id' => $request->comment_id,
                'type' => 'like',
            ]);
            if($like){
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Like comment successfully!');
            }else{
                return back()->with('fail', 'Something went wrong!');
            }
        }
    }
    public function createdislikeComment(Request $request){
        $validator = Validator::make($request->all(), [
            'comment_id' => ['required', 'string'],
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $checkLike = Like::where(['comment_id'=> $request->comment_id, 'author' => Auth::user()->login])->first();
        if($checkLike){
            //if it's like
            if($checkLike->type == 'dislike'){
                Like::destroy($checkLike->id);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'Dislike removed successfully!');
            }
            //if it's dislike
            else{
                $checkLike->update(['type' => 'dislike']);
                $this->calculateRating(Auth::user());
                return back()->with('success', 'disiked comment successfully!');
            }
        }else{
            $like = Like::create([
                'author' => Auth::user()->login,
                'comment_id' => $request->comment_id,
                'type' => 'dislike',
            ]);
            if($like){
                $this->calculateRating(Auth::user());
                return back()->with('success', 'disiked comment successfully!');
            }else{
                return back()->with('fail', 'Something went wrong!');
            }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Like::destroy($id);
        $this->calculateRating(Auth::user());
        return back()->with('success', 'Like/dislike deleted successfully!');
    }
}
