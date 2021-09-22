<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
  <title>AdminLTE 3 | User Profile</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
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
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    @if(Session::get('success'))
            <div class="alert alert-success col-sm-3" role="alert">
                {{Session::get('success')}}
            </div>
            @endif
            @if(Session::get('fail'))
            <div class="alert alert-danger col-sm-3" role="alert">
                {{Session::get('fail')}}
             </div>
            @endif
             @if(Session::get('fail-arr'))
                <div class="alert alert-danger col-sm-3" role="alert">
                    @foreach(Session::get('fail-arr') as $key => $err)
                    <p>{{$key . ': ' . $err[0]}}</p>
                    @endforeach
                </div>
            @endif
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img id="profile-pic" class="profile-user-img img-fluid img-circle"
                       src="{{$user->profile_photo}}"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{$user->login}}</h3>

                <p class="text-muted text-center">{{$user->full_name}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Role</b> <a class="float-right">{{$user->role}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Rating</b> <a class="float-right">{{$user->rating}}</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col --> 
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Info</a></li>
                    <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>            
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                <div class="active tab-pane" id="settings">
                    <form id="infoForm" action="{{route('users.update', ['user' => $user->id])}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                      <div class="form-group row">
                        <label for="inputLogin" class="col-sm-2 col-form-label">Login</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="login" id="inputLogin" placeholder="Login" value="{{$user->login}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" value="{{$user->email}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputfull_name" class="col-sm-2 col-form-label">Full name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputfull_name" name="full_name" placeholder="Full name" value="{{$user->full_name}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputRole" class="col-sm-2 col-form-label">Role</label>
                        <div class="col-sm-10">
                            <select id="inputRole" name="role" class="form-control custom-select">
                                <option selected disabled>{{$user->role}}</option>
                                <option>admin</option>
                                <option>user</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Profile picture</label>
                        <div class="col-sm-10">
                        <label class="selectfile btn btn-primary" for="choosefile">Edit profile picture</label>
                            <input id="choosefile" type="file" name="profile_photo" class="invisible"> 
                        </div>
                      </div>
                    </form>
                    <div class="form-group d-flex justify-content-between">
                        <div class="offset-sm-2">
                          <button id="SubmitInfoForm" type="submit" class="btn btn-success">Save</button>
                        </div>
                        <form action="{{route('users.delete', ['user' => $user->id])}}" method="POST">
                          @csrf
                          @method('DELETE')
                            <div class="offset-sm-2">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>                   
                  </div>
                  <div class="tab-pane" id="activity">
                    @foreach($posts as $post)
                    <div class="post">
                    <form action="{{route('comments.create' , ['post_id' => $post->id])}}" method="POST">   
                      @csrf
                      <div class="user-block mt-3">
                        <img class="img-circle img-bordered-sm" src="{{$user->profile_photo}}" alt="user image">
                        <span class="username">
                          <a href="#">{{\App\Models\User::find($post->author)->login}}</a>
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
                            @foreach(explode(' ',$post->images) as $img)
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
                          <button type="submit" class="link-black text-sm like-btn"><i class="@if(\App\Models\Like::where(['post_id' => $post->id, 'type' => 'like', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Like({{count(\App\Models\Like::where(['post_id' => $post->id, 'type' => 'like'])->get())}})</button>
                        </form>
                        <form action="{{route('dislike.post.create',['post_id' => $post->id])}}" method="POST">
                          @csrf
                          <button type="submit" class="link-black text-sm ml-2 like-btn"><i class="@if(\App\Models\Like::where(['post_id' => $post->id, 'type' => 'dislike', 'author' => Auth::id()])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Dislike({{count(\App\Models\Like::where(['post_id' => $post->id, 'type' => 'dislike'])->get())}})</button>
                        </form>
                      </div>
                        <span class="float-right">
                        <a class="link-black text-sm" data-toggle="collapse" href="#comment-{{$post->id}}" role="button" aria-expanded="false" aria-controls="comment-{{$post->id}}">
                            <i class="far fa-comments mr-1"></i> Comments ({{count(\App\Models\Comment::where(['post_id' => $post->id])->get())}})
                          </a>
                        </span><br>
                      </p>
                      <div class="collapse" id="comment-{{$post->id}}">
                        @foreach(\App\Models\Comment::where(['post_id' => $post->id])->get() as $comment)
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
                                  <button type="submit" class="link-black text-sm like-btn"><i class="@if(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'like', 'author' => Auth::user()->login])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Like({{count(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'like'])->get())}})</button>
                                </form>
                                <form action="{{route('dislike.comment.create',['comment_id' => $comment->id])}}" method="POST">
                                  @csrf
                                  <button type="submit" class="link-black text-sm ml-2 like-btn"><i class="@if(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'dislike', 'author' => Auth::user()->login])->first())fas fa-thumbs-up @else far fa-thumbs-up @endif mr-1"></i> Dislike({{count(\App\Models\Like::where(['comment_id' => $comment->id, 'type' => 'dislike'])->get())}})</button>
                                </form>
                              </div>
                              <div class="d-flex justify-content-end">
                                 <a class="link-black mr-3" href="" data-toggle="modal" data-target="#modal-edit-{{$comment->id}}">Edit</a>
                                 <a class="link-black" href="" data-toggle="modal" data-target="#modal-deleteComment-{{$comment->id}}">Remove</a>
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
                              <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
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
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
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
<script>
function readImage(input) {
  if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#profile-pic').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#choosefile").change(function(){
    readImage(this);
});
$('#SubmitInfoForm').click(function(){
    $('#infoForm').submit();
})
</script>
</body>
</html>
