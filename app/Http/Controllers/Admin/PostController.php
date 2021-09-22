<?php

namespace App\Http\Controllers\Admin;

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
    public function create(Request $request){

            $validator = Validator::make($request->all(), [
                'author' => ['required', 'max:30'],
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
                return back()->with('success', 'Post created successfully!');
            }else{
                return back()->with('fail', 'Something went wrong!');
            }           
    }

    function Postlist(){
        $data = [];
        $posts = Post::simplePaginate(10);
        return view('Admin.Posts.list', ['posts' => $posts]);
    }
    function uploadMultiImages($request){
        $images = $request->file('images');
        if(!$images){
            return null;
        }
        $i = 2;
        $result = "";
        foreach($images as $image){
            if($image){
                $filename = str_replace(' ', '-', "post". '-' . $request->input('author') . "-" . $i). '.png';
                $j=2;
                while(file_exists(public_path('/posts-images/' .$filename))){
                    $filename  = str_replace(' ', '-', "post". '-' . $request->input('author')). $j . "-" . $i . '.png';
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
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'author' => ['required', 'string', 'max:30'],
            'title' => ['required', 'string', 'max:100'],
            'categories' => ['max:255'],
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $post = Post::find($request->post);
        $categories = $post->categories;
        if($request->categories){
            $categories = implode(", ", $request->categories);
        }
        $images = $this->uploadMultiImages($request);
        $post->update(array_merge($request->all(), ['images' => $images, 'categories' => $categories]));
        return redirect('admin/posts/update/' . $post->id)->with('success', 'Post updated successfully!');
    }
    public function destroy($id){
        Post::destroy($id);
        return redirect('admin/posts')->with('success', 'Post deleted successfully!');
    }
}
