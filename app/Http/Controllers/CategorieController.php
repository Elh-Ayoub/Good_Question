<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Categorie::all();
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
        Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255']
        ])->validate();
        Categorie::create([
            'title' => $request->input('title')
        ]);
        return ['Categorie created !!'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Categorie::find($id);
    }


    public function getPosts($id){
        $categorie = Categorie::find($id);
        $posts = Post::all();
        $result = array();
        $i = 0;
        foreach($posts as $post){
            if(str_contains($post['categories'], $categorie['title'])){
                $result[$i] = $post;
                $i++;
            }
        }
        return $result;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function edit(Categorie $categorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Categorie::find($id)->update($request->all());
        return ["Categorie Updated to " . $request->input('title')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Categorie::destroy($id);
    }
}
