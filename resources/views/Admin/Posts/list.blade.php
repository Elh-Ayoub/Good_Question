<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
  <title>Posts - {{env('APP_NAME')}}</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  @include('Admin.layouts.navbar')
  @include('Admin.layouts.sidebar')
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Posts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Contacts</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    @if(Session::get('success'))
      <div class="form-group">
        <p class="success">{{Session::get('success')}}</p>
      </div>
    @endif
    @if(Session::get('fail'))
      <div class="form-group">
        <p class="fail">{{Session::get('fail')}}</p>
      </div>
    @endif
    @if(Session::get('fail-arr'))
      <div class="input-field">
        @foreach(Session::get('fail-arr') as $key => $err)
          <p class="fail">{{$key . ': ' . $err[0]}}</p>
        @endforeach
      </div>
    @endif
    <a href="{{route('posts.create.view')}}" class="btn btn-primary m-2"><i class="fas fa-plus mr-2"></i>Create post</a>
    <!-- Main content -->
    <section class="content">
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row">
            @foreach($posts as $post)
            <div class="col-12  col-md-6 d-flex align-items-stretch flex-column">
              <div class="card bg-light d-flex flex-fill">
                <div class="card-body pt-0">
                  <div class="post">
                    <form action="{{route('comments.create' , ['post_id' => $post->id])}}" method="POST">   
                      @csrf
                        <div class="user-block mt-3">
                          <img class="img-circle img-bordered-sm" src="{{\App\Models\User::find($post->author)->profile_photo}}" alt="user image">
                          <span class="username">
                            <a href="{{route('users.update.view', ['user' => $post->author])}}">{{\App\Models\User::find($post->author)->login}}</a>
                            @if($post->status == "active")
                              <span class="float-right btn-tool text-success">{{$post->status}}</span>
                            @else
                              <span class="float-right btn-tool text-danger">{{$post->status}}</span>
                            @endif
                          </span>
                          <span class="description">Shared publicly - {{$post->created_at}}</span>
                        </div>
                        <!-- /.user-block -->
                        <p class="text-center lead font-weight-bold text-muted">{{$post->title}}</p>
                        <p>
                        {{$post->content}}
                        </p>
                        <div class="col-lg-10">
                          <div class="row">
                        @if($post->images)
                          @foreach(explode(" ", $post->images) as $img)
                            @if($img != "")
                              <div class="col-lg-6">
                                <img class="img-fluid mb-3" src="{{$img}}" alt="Photo">
                              </div>
                            @endif
                          @endforeach            
                        @endif 
                          </div>
                        </div>
                        
                        <div class="input-group mb-2">
                          <input class="form-control form-control-sm" name="comment" type="text" placeholder="Type a comment" require>
                          <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-arrow-right"></i></button>
                        </div>
                    </form> 
                    <p>
                      <div class="input-group">
                        <form action="{{route('like.post.create', ['post_id' => $post->id])}}" method="POST">
                          @csrf
                          <button type="submit" class="link-black text-sm like-btn"><i class="@if(\App\Models\Like::where(['post_id' => $post->id, 'type' => 'like', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Like({{count(\App\Models\Like::where(['post_id'=> $post->id, 'type' => 'like'])->get())}})</button>
                        </form>
                        <form action="{{route('dislike.post.create',['post_id' => $post->id])}}" method="POST">
                          @csrf
                          <button type="submit" class="link-black text-sm ml-2 like-btn"><i class="@if(\App\Models\Like::where(['post_id' => $post->id, 'type' => 'dislike', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Dislike({{count(\App\Models\Like::where(['post_id'=> $post->id, 'type' => 'dislike'])->get())}})</button>
                        </form>
                      </div>
                      <span class="float-right">
                        <a class="link-black text-sm" data-toggle="collapse" href="#comment-{{$post->id}}" role="button" aria-expanded="false" aria-controls="comment-{{$post->id}}">
                          <i class="far fa-comments mr-1"></i> Comments ({{count(\App\Models\Comment::where('post_id', $post->id)->get())}})
                        </a>
                      </span><br>
                    </p> 
                    <div class="collapse" id="comment-{{$post->id}}">
                      @foreach(\App\Models\Comment::where('post_id', $post->id)->get() as $comment)
                      <div class="card card-body">
                        <div>
                            <div>
                              <img class="img-circle img-sm img-bordered-sm" src="{{\App\Models\User::find($comment->author)->profile_photo}}" alt="user image">  
                              <a class="ml-1" href="{{route('users.update.view', ['user' => \App\Models\User::find($comment->author)->id])}}">{{\App\Models\User::find($comment->author)->login}}</a>
                              <span class="text-muted text-sm text-right">{{$comment->created_at}}</span>
                              @if($comment->status == "active")
                                <span class="float-right btn-tool text-success">{{$comment->status}}</span>
                              @else
                                <span class="float-right btn-tool text-danger">{{$comment->status}}</span>
                              @endif
                            </div>
                            <div class="mt-1 ml-2">
                              <span>{{$comment->content}}</span>
                            </div>
                            <div class="input-group">
                              <form action="{{route('like.comment.create', ['comment_id' => $comment->id])}}" method="POST">
                                @csrf
                                <button type="submit" class="link-black text-sm like-btn"><i class="@if(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'like', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Like({{count(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'like'])->get())}})</button>
                              </form>
                              <form action="{{route('dislike.comment.create',['comment_id' => $comment->id])}}" method="POST">
                                @csrf
                                <button type="submit" class="link-black text-sm ml-2 like-btn"><i class="@if(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'dislike', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Dislike({{count(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'dislike'])->get())}})</button>
                              </form>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a class="link-black mr-3" href="" data-toggle="modal" data-target="#modal-edit-{{$comment->id}}">Edit</a>
                                <a class="link-black mr-3" href="" data-toggle="modal" data-target="#modal-deleteComment-{{$comment->id}}">Remove</a>
                                <a class="link-black" data-toggle="collapse" href="#replies-{{$comment->id}}" role="button" aria-expanded="false" aria-controls="replies-{{$comment->id}}">reply({{count(\App\Models\Comment::where('comment_id', $comment->id)->get())}})</a>
                                <br>
                            </div>
                            <div class="collapse" id="replies-{{$comment->id}}">
                                <form class="input-group mb-2" action="{{route('comments.reply.create' , ['comment_id' => $comment->id])}}" method="POST">
                                  @csrf
                                  <input class="form-control form-control-sm" name="reply" type="text" placeholder="Type a reply..." require>
                                  <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-arrow-right"></i></button>
                                </form>
                                @foreach(\App\Models\Comment::where('comment_id', $comment->id)->get() as $reply)
                                <div>
                                  <img class="img-circle img-sm img-bordered-sm" src="{{\App\Models\User::find($reply->author)->profile_photo}}" alt="user image">  
                                  <a class="ml-1" href="{{route('users.update.view', ['user' => \App\Models\User::find($reply->author)->id])}}">{{\App\Models\User::find($reply->author)->login}}</a>
                                  <span class="text-muted text-sm text-right">{{$reply->created_at}}</span>
                                  @if($reply->status == "active")
                                    <span class="float-right btn-tool text-success">{{$reply->status}}</span>
                                  @else
                                    <span class="float-right btn-tool text-danger">{{$reply->status}}</span>
                                  @endif
                                </div>
                                <div class="mt-1 ml-2">
                                  <span>{{$reply->content}}</span>
                                </div>
                                <div class="input-group">
                                  <form action="{{route('like.comment.create', ['comment_id' => $reply->id])}}" method="POST">
                                    @csrf
                                    <button type="submit" class="link-black text-sm like-btn"><i class="@if(\App\Models\Like::where(['comment_id' => $reply->id, 'type' => 'like', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Like({{count(\App\Models\Like::where(['comment_id' => $reply->id, 'type' => 'like'])->get())}})</button>
                                  </form>
                                  <form action="{{route('dislike.comment.create',['comment_id' => $reply->id])}}" method="POST">
                                    @csrf
                                    <button type="submit" class="link-black text-sm ml-2 like-btn"><i class="@if(\App\Models\Like::where(['comment_id' => $reply->id, 'type' => 'dislike', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Dislike({{count(\App\Models\Like::where(['comment_id' => $reply->id, 'type' => 'dislike'])->get())}})</button>
                                  </form>
                                </div>
                                @endforeach
                              </div>
                        </div>                         
                      </div>
                      <div class="modal fade" id="modal-deleteComment-{{$comment->id}}">
                        <div class="modal-dialog">
                          <div class="modal-content bg-danger">
                            <div class="modal-header">
                              <h4 class="modal-title">Confirmation</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p>You are about to delete a post. Are you sure? </p>
                            </div>
                            <form action="{{route('comments.delete', $comment->id)}}" method="POST">
                              @csrf
                              @method('DELETE')
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-outline-light">Delete</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      <div class="modal fade" id="modal-edit-{{$comment->id}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Update Category</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="{{route('comments.update', $comment->id)}}" method="POST">
                              @csrf
                              @method('PATCH')
                              <div class="modal-body">
                                  <div class="form-group">
                                      <label for="status">Comment's status</label>
                                      <select id="status" name="status" class="form-control custom-select">
                                          <option selected disabled>{{$comment->status}}</option>
                                          <option>active</option>
                                          <option>inactive</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-primary">Update</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      @endforeach
                    </div>                  
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="{{route('posts.update.view', $post->id)}}" class="btn btn-sm bg-teal mr-2">
                      <i class="fas fa-pen"></i>Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger-{{$post->id}}">
                      <i class="fas fa-times"></i> Delete
                    </button>
                  </div>
                </div>
                <div class="modal fade" id="modal-danger-{{$post->id}}">
                  <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                      <div class="modal-header">
                        <h4 class="modal-title">Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>You are about to delete a post. Are you sure? </p>
                      </div>
                      <form action="{{route('posts.delete', $post->id)}}" method="POST">
                      @csrf
                      @method('DELETE')
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-outline-light">Delete</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach           
          </div>
        </div>
        <div class="m-auto mb-2">
            {{ $posts->links() }}
        </div>   
      </div> 
    </section>
  </div>
  @include('Admin.layouts.footer')
</div>
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
</body>
</html>
