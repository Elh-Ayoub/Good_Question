<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comment::all();
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
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Comment::find($id);
    }

    public function createLike(Request $request, $id){
        if (!Auth::user()){
            return ["Unauthenticated"];
        }
        $comment = Comment::find($id);
        if(!$comment){
            return ['Post not found'];
        }else{
            $like = DB::table('likes')
            ->select('id' ,'author', 'commentid', 'type')->where(['commentid' => $id ,'author' => Auth::user()->login])
            ->get();
            $like = json_decode($like, true);
            if(count($like) === 0){
                Like::create([
                    'author' => Auth::user()->login,
                    'commentid' => (int)$id,
                    'type' => $request->input('type')
                ]);
                $comment = Comment::find($id);
                $user = User::where('login', $comment['author'])->get();
                $rating = $user[0]['rating'];
                if($request->input('type') === 'like'){
                    $rating += 1;
                }else{
                    $rating -= 1;
                }
                User::where('login', $comment['author'])->update(['rating' => $rating]);
                return [$request->input('type') . " for " . $comment['author'] . "'s post"];
            }else{  
                if($like[0]['type'] === $request->input('type')){
                    return ['already '. $request->input('type') . ' this post'];
                }else{
                    $user = User::where('login', $comment['author'])->get();
                    $rating = $user[0]['rating'];
                    if($request->input('type') === 'like'){
                        $rating += 2;
                    }else{
                        $rating -= 2;
                    }
                    User::where('login', $comment['author'])->update(['rating' => $rating]);
                    Like::where(['id' => $like[0]['id']])->update(['type' => $request->input('type')]);
                    return [$request->input('type') . " for " . $comment['author'] . "'s post"];
                }
            }
        }
    }

    public function getLike($id){
        $likes = DB::table('likes')->select('id' ,'author', 'commentid', 'type')->where('commentid', (int) $id)->get();
        return $likes;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()){
            $comment = Comment::find($id);
            if(!$comment){
                return ["Post not found!"];
            }else{
                if(Auth::user()->login === $comment['author']){
                    Comment::where('id', $id)->update($request->all());
                    return ["Comment Updated !!"];
                }else{
                    return ["You're not the author of this post!"];
                }
            }
        }else{
            return ["Unauthenticated"];
        }
    }

    public function deleteLike($id)
    {
        if(!Auth::user()){
            return ["Unauthenticated"];
        }
        $comment = Comment::find($id);
        if(!$comment){
            return ['Post not found'];
        }else{
            $likes = DB::table('likes')
            ->select('id' ,'author', 'commentid', 'type')->where(['author'=> Auth::user()->login , 'commentid' => $id])
            ->get();
            $likes = json_decode($likes, true);
            if(count($likes) === 0){
                return ["There is no likes/dislikes of yours in this post!"];
            }else{
                foreach($likes as $like){
                    if($like['commentid'] === (int)$id && $like['author'] === Auth::user()->login){
                        $user = User::where('login', $comment['author'])->get();
                        $rating = $user[0]['rating'];
                        if($like['type'] === 'like'){
                            $rating -= 1;
                        }else{
                            $rating += 1;
                        }
                        User::where('login', $comment['author'])->update(['rating' => $rating]);
                        Like::destroy($like['id']);
                        return [$like['type'] . " deleted!"];
                    }
                }
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::destroy($id);
    }
}
