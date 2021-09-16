<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::where('status', 'active')->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:500'],
            'categories' => ['required', 'max:255'],
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
        $categories = explode(" ", $request->categories);
        foreach($categories as $category){
            $cat = Category::where('title', $category)->first();
            if(!$cat){
                Category::create([
                    'title' => trim($category),
                ]);
            }
        }
        $categories = implode(", ", $categories);
        $images = $this->uploadMultiImages($request->file('images'), $request->user);
        $post = Post::create([
            'author' => $user_id,
            'title' => $request->title,
            'content' => $request->content,
            'categories' => $categories,
            'images' => $images,
        ]);
        
        if($post){
            return ['success' => 'Post created successfully!'];
        }else{
            return ['fail' => 'Something went wrong!'];
        }  
    }
    function uploadMultiImages($images, $user_id = null){
        if(!$images){
            return null;
        }
        $i = 1;
        $result = "";
        foreach($images as $image){
            if($image){
                if(Auth::user()){
                    $user = Auth::user();
                }
                if($user_id){
                    $user= User::find($user_id);
                }
                $filename = str_replace(' ', '-', "post". '-' . $user->login . "-" . $i). '.png';
                $j=2;
                while(file_exists(public_path('/posts-images/' .$filename))){
                    $filename  = str_replace(' ', '-', "post". '-' . $user->login). $j . "-" . $i . '.png';
                    $j++;
                }
                $image->store('public');
                $image->move(public_path('/posts-images'), $filename);
                $result .= url('/posts-images/' . $filename) . " ";
            }
            $i++;
        }
        return $result;
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
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::find($id);
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
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'title' => ['string', 'max:100'],
            'categories' => ['max:255'],
        ]);
        if($validator->fails()){
            return json_decode($validator->errors()->toJson());
        }
        $user_id = null;
        if(Auth::user()){
            $user_id = Auth::id();
        }
        elseif($request->user){
            $user_id = (int)$request->user;
        }
        $post = Post::find($id);
        if($post && $post->author === $user_id){
            $categories = $post->categories;
            if($request->categories){
                $categories = explode(" ", $request->categories);
                foreach($categories as $category){
                    $cat = Category::where('title', $category)->first();
                    if(!$cat){
                        Category::create([
                            'title' => trim($category),
                        ]);
                    }
                }
                $categories = implode(", ", $categories);
            }
            $images = $post->images;
            if($request->file('images')){
                $images = $this->uploadMultiImages($request->file('images'), $request->user);
            }  
            $post->update(array_merge($request->all(), ['images' => $images, 'categories' => $categories]));
            return ['success' => 'Post updated successfully!']; 
        }else{
            return ['fail' => 'Post not exist or a post you didn\'t create!']; 
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Post::find($id)){
            Post::destroy($id);
            return ['success' => 'Post deleted successfully!'];
        }else{
            return ['fail' => 'Post requested not exist!']; 
        }      
    }
}

