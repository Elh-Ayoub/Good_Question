<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
  <title>Comments - {{env('APP_NAME')}}</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- /.navbar -->
  @include('Admin.layouts.navbar')
  @include('Admin.layouts.sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Comments</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Comments</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
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
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row">
            @foreach($comments as $comment)
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
              <div class="card bg-light d-flex flex-fill">
                <div class="card-body pt-0">
                <div class="post">
                      <div class="user-block mt-3">
                        <img class="img-circle img-bordered-sm" src="{{\App\Models\User::find($comment->author)->profile_photo}}" alt="user image">
                        <span class="username">
                          <a href="{{route('users.update.view', ['user' => \App\Models\User::find($comment->author)->id])}}">{{\App\Models\User::find($comment->author)->login}}</a>
                          @if($comment->status == "active")
                          <span class="float-right btn-tool text-success">{{$comment->status}}</span>
                          @else
                          <span class="float-right btn-tool text-danger">{{$comment->status}}</span>
                          @endif
                        </span>
                        <span class="description">Shared publicly - {{$comment->created_at}}</span>
                      </div>
                      <!-- /.user-block -->
                      <p class="text-center lead font-weight-bold text-muted">{{$comment->post_id ? ("Comment for post") : ("Reply of comment")}}</p>
                      <p>
                      {{$comment->content}}
                      </p>
                      <p>
                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                        <a href="#" class="link-black text-sm ml-2"><i class="far fa-thumbs-down"></i> Dislike</a>
                      </p>
                </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="#" class="btn btn-sm bg-teal mr-2" data-toggle="modal" data-target="#modal-edit-{{$comment->id}}">
                      <i class="fas fa-pen"></i>Edit status
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-danger-{{$comment->id}}">
                      <i class="fas fa-times"></i> Delete
                    </button>
                  </div>
                </div>
                <div class="modal fade" id="modal-danger-{{$comment->id}}">
                  <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                      <div class="modal-header">
                        <h4 class="modal-title">Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>You are about to delete a comment. Are you sure? </p>
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
              </div>
            </div>
            @endforeach
          </div>
        </div>
        <div class="m-auto mb-2">
            {{ $comments->links() }}
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
