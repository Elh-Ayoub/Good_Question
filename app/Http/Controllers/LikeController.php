<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Like::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $like = Like::find($id);
        if($like['postid']){
            $post = Post::find($like['postid']);
            $user = User::where('login', $post['author'])->get();
            $rating = $user[0]['rating'];
            if($like['type'] === 'like'){
                $rating -= 1;
            }else{
                $rating += 1;
            }
            User::where('login', $post['author'])->update(['rating' => $rating]);
        }
        if($like['commentid']){
            $comment = Comment::find($like['commentid']);
            $user = User::where('login', $comment['author'])->get();
            $rating = $user[0]['rating'];
            if($like['type'] === 'like'){
                $rating -= 1;
            }else{
                $rating += 1;
            }
            User::where('login', $comment['author'])->update(['rating' => $rating]);
        }
        Like::destroy($id);
    }
}
