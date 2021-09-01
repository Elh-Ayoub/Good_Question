<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getPostCategories($id){
        return ["Categories" =>Post::find($id)->categories];
    }
}
