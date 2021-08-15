<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Categorie;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all();
    }

    function bubble_Sort($my_array )
    {
	    do{
		    $swapped = false;
		    for( $i = 0, $c = count( $my_array ) - 1; $i < $c; $i++ )
		    {
			    if( $my_array[$i]['id'] < $my_array[$i + 1]['id'] )
			    {
				    list( $my_array[$i + 1], $my_array[$i] ) =
					array( $my_array[$i], $my_array[$i + 1] );
				    $swapped = true;
			    }
		    }
	    }while( $swapped );
        return $my_array;
    }

    public function indexAdmin(){
        $posts = $this->bubble_Sort(Post::all());
        return view('allPosts', ['posts' => $posts], ['users' => User::all()], ['likes' => Like::all()]);
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
    
    public static function countLikes($id){
        $like = 0;
        $dislike = 0;
        $likes = DB::table('likes')->select('id' ,'author', 'postid', 'type')->where('postid', (int) $id)->get();
        $likes = json_decode($likes, true);
        foreach($likes as $d){
            if($d['type'] === 'like'){
                $like++;
            }
            if($d['type'] === 'dislike'){
                $dislike++;
            }
        }
        return " $like Like,  $dislike Dislike";
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function StringToArr($str){
        $arr = explode(' ', $str);
        return $arr;
    }
    public function store(Request $request)
    {
        if(Auth::user()){
            Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string', 'max:500'],
                'categories' => ['required', 'string', 'max:255']
            ])->validate();
            Post::create([
                'author' => Auth::user()->login,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'categories' => $request->input('categories')
            ]);
            $categories = $this->StringToArr($request->input('categories'));
            foreach($categories as $cat){
                $categorie = DB::table('categories')
                ->select('id' ,'title')->where('title', $cat)
                ->get();
                $categorie = json_decode($categorie, true);
                if(count($categorie) === 0){
                    Categorie::create(['title' => $cat]);
                }          
            }
            return ["Post created"];
        }else{
            return ["Unauthenticated"];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::find($id);
    }

    public function createLike(Request $request, $id)
    {
        if(!Auth::user()){
            return ["Unauthenticated"];
        }
        $post = Post::find($id);
        if(!$post){
            return ['Post not found'];
        }else{
            $like = DB::table('likes')
            ->select('id' ,'author', 'postid', 'type')->where(['postid' => $id ,'author' => Auth::user()->login])
            ->get();
            $like = json_decode($like, true);
            if(count($like) === 0){
                Like::create([
                    'author' => Auth::user()->login,
                    'postid' => (int)$id,
                    'type' => $request->input('type')
                ]);
                $user = User::where('login', $post['author'])->get();
                $rating = $user[0]['rating'];
                if($request->input('type') === 'like'){
                    $rating += 1;
                }else{
                    $rating -= 1;
                }
                User::where('login', $post['author'])->update(['rating' => $rating]);
                return [$request->input('type') . " for " . $post['author'] . "'s post"];
            }else{  
                if($like[0]['type'] === $request->input('type')){
                    return ['already '. $request->input('type') . ' this post'];
                }else{
                    Like::where(['id' => $like[0]['id']])->update(['type' => $request->input('type')]);
                    $user = User::where('login', $post['author'])->get();
                    $rating = $user[0]['rating'];
                    if($request->input('type') === 'like'){
                        $rating += 2;
                    }else{
                        $rating -= 2;
                    }
                    User::where('login', $post['author'])->update(['rating' => $rating]);
                    return [$request->input('type') . " for " . $post['author'] . "'s post"];
                }
            }
        }
        
           
    }
    public function deleteLike(Request $request, $id)
    {
        if(!Auth::user()){
            return ["Unauthenticated"];
        }
        $post = Post::find($id);
        if(!$post){
            return ['Post not found'];
        }else{
            $likes = DB::table('likes')
            ->select('id' ,'author', 'postid', 'type')->where('author', Auth::user()->login)
            ->get();
            $likes = json_decode($likes, true);
            if(count($likes) === 0){
                return ["There is no likes/dislikes of yours in this post!"];
            }else{
                foreach($likes as $like){
                    if($like['postid'] === (int)$id && $like['author'] === Auth::user()->login){
                        $user = User::where('login', $post['author'])->get();
                        $rating = $user[0]['rating'];
                        if($like['type'] === 'like'){
                            $rating -= 1;
                        }else{
                            $rating += 1;
                        }
                        User::where('login', $post['author'])->update(['rating' => $rating]);
                        Like::destroy($like['id']);
                        return [$like['type'] . " deleted!"];
                    }
                }
            }
        }
    }

    public function getLike($id){
        $likes = DB::table('likes')->select('id' ,'author', 'postid', 'type')->where('postid', (int) $id)->get();
        return $likes;
    }

    public function getComment($id){
        $post = Post::find($id);
        if(!$post){
            return ['Post not found'];
        }else{
            $comments = DB::table('comments')
            ->select('id' ,'author', 'content', 'postid')->where('postid', $id)
            ->get();
            return $comments;
        }
    }
    public function createComment(Request $request, $id){
        if(!Auth::user()){
            return ["Unauthenticated"];
        }
        $post = Post::find($id);
        if(!$post){
            return ['Post not found'];
        }else{
            Comment::create([
                'author' => Auth::user()->login,
                'content' => $request->input('content'),
                'postid' => (int)$id
            ]);
            return ["You've commented to " . $post['author'] . "'s post!"];
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()){
            $post = Post::find($id);
            if(!$post){
                return ["Post not found!"];
            }else{
                if(Auth::user()->login === $post['author']){
                    Post::where('id', $id)->update($request->all());
                    $categories = $this->StringToArr($request->input('categories'));
                    if($categories){
                        foreach($categories as $cat){
                            $categorie = DB::table('categories')
                            ->select('id' ,'title')->where('title', $cat)
                            ->get();
                            $categorie = json_decode($categorie, true);
                            if(count($categorie) === 0){
                                Categorie::create(['title' => $cat]);
                            }          
                        }
                        return ["Post updated"];
                    }
                    
                }else{
                    return ["You're not the author of this post!"];
                }
            }
        }else{
            return ["Unauthenticated"];
        }
        
    }
    public function getCategories($id){
        return [Post::find($id)->categories];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::destroy($id);
    }
}
