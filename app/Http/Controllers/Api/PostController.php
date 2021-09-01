<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;

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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'author' => ['required', 'string', 'max:30'],
            'title' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:500'],
            'categories' => ['required', 'max:255'],
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        foreach($request->categories as $category){
            $cat = Category::where('title', $category)->first();
            if(!$cat){
                Category::create([
                    'title' => $category
                ]);
            }
        }
        $categories = implode(", ", $request->categories);
        $images = $this->uploadMultiImages($request);
        $post = Post::create([
            'author' => $request->input('author'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'categories' => $categories,
            'images' => $images,
        ]);
        
        if($post){
            return ['success' => 'Post created successfully!'];
        }else{
            return ['fail' => 'Something went wrong!'];
        }  
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
       // 
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

