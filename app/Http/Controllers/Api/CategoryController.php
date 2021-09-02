<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(){
        return Category::all();
    }

    public function getPostCategories($id){
        return json_encode(Post::find($id)->categories);
    }

    public function show($id){
        $category = Category::find($id);
        if($category){
            return $category;
        }else{
            return ['fail' => 'Category not found!'];
        }
        
    }
    public function getPostsByCategory($id){
        $posts = Post::all();
        $category = Category::find($id);
        if($category){
            $res = [];
            foreach($posts as $post){
                if(str_contains(strtoupper($post->categories), strtoupper($category->title))){
                    array_push($res, $post);
                }
            }
            return $res;
        }else{
            return ['fail' => 'Category requested not found!'];
        }
            
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:categories|between:1,100',
        ]);
        if($validator->fails()){
            return json_decode($validator->errors()->toJson());
        }
        $category = Category::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        if($category){
            return ['success' => 'Category ' . $category->title . ' created successfully!'];
        }else{
            return ['fail' => 'Something went wrong!'];
        }
    }
    public function update(Request $request, $id){
        $category = Category::find($id);
        if($category){
            $validator = Validator::make($request->all(), [
                'title' => 'string|between:1,100',
                'description' => 'string|between:1,100',
            ]);
            if($validator->fails()){
                return json_decode($validator->errors()->toJson());
            }
            if($category->title != $request->title && Category::where('title', $request->title)->first()){
                return ['fail' => 'The title has already been taken.'];
            }else{
                $category->update([
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
                return ['success' => 'Category updated successfully!'];
            }            
        }else{
            return ['fail' => 'Category requested not found!'];
        }
        
    }
    
    public function destroy($id){
        if(Category::find($id)){
            Category::destroy($id);
            return ['success' => 'Category deleted successfully!'];
        }
        else{
            return ['fail' => 'Category requested not found!'];
        }
    }
}
