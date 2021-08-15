<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function create(Request $request){

            $validator = Validator::make($request->all(), [
                'author' => ['required', 'string', 'max:30'],
                'title' => ['required', 'string', 'max:100'],
                'content' => ['required', 'string', 'max:500'],
                'categories' => ['required', 'string', 'max:255'],
            ]);
            if($validator->fails()){
                return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
            }
            $post = Post::create([
                'author' => $request->input('author'),
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'categories' => $request->input('categories')
            ]);
            // $categories = $this->StringToArr($request->input('categories'));
            // foreach($categories as $cat){
            //     $categorie = DB::table('categories')
            //     ->select('id' ,'title')->where('title', $cat)
            //     ->get();
            //     $categorie = json_decode($categorie, true);
            //     if(count($categorie) === 0){
            //         Categorie::create(['title' => $cat]);
            //     }          
            // }
            if($post){
                return back()->with('success', 'Post created successfully!');
            }else{
                return back()->with('fail', 'Something went wrong!');
            }           
    }

    function Postlist(){
        $data = [];
        $posts = Post::all();
        foreach($posts as $post){
            array_push($data, ['post' => $post, 'author' => User::where('login', $post->author)->first()]);
        }
        return view('Admin.Posts.list', ['data' => $data]);
    }
}
