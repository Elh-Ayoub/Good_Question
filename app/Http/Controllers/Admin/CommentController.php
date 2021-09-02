<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $comments = Comment::all();
        foreach($comments as $comment){
            array_push($data, ['comment' => $comment, 'author' => User::where('login', $comment->author)->first(),
                'post_title' => Post::find($comment->post_id)->title,
            ]);
        }
        return view('Admin.Comments.list', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => ['required', 'string', 'max:500'],
            'post_id' => ['required', 'string'],
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $comment = Comment::create([
            'author' => Auth::user()->login,
            'content' => $request->comment,
            'post_id' => $request->post_id,
        ]);
        if($comment){
            return back()->with('success', 'Commented in post successfully!');
        }else{
            return back()->with('fail', 'Something went wrong!');
        }
        // return $request->all();
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
    public function updateStatus(Request $request, $id)
    {
        if($request->status){
            Comment::where('id', $id)->update(['status' => $request->status]);
            return back()->with('success', 'Comment status updated successfully!'); 
        }
        return back()->with('fail', 'Nothing updated!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::destroy($id);
        return back()->with('success', 'Comment deleted successfully!');
    }
}
