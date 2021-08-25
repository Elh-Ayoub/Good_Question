<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.Categories.list', ['categories' => Category::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:categories|between:1,100',
        ]);
        if($validator->fails()){
            return redirect('admin/categories')->with('fail', ($validator->errors()->toJson()));
        }
        $category = Category::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        if($category){
            return redirect('admin/categories')->with('success', 'Category ' . $category->title . ' created successfully!');
        }else{
            return redirect('admin/categories')->with('fail', 'Something went wrong!');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $category = Category::find($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:1,100',
        ]);
        if(($category->title != $request->title) && Category::where('title', $request->title)->first()){
            return redirect('admin/categories')->with('fail', 'Title aleady exist!');
        }
        if($validator->fails()){
            return redirect('admin/categories')->with('fail', ($validator->errors()->toJson()));
        }
        
        $category->update($request->all());
        return redirect('admin/categories')->with('success', 'Category ' . $category->title . ' updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return redirect('admin/categories')->with('success', 'Post deleted successfully!');
    }
}
