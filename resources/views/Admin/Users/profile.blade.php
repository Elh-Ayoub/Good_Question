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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{asset('images/Logo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      @if(!Auth::user())
      <li class="nav-item">
        <a class="nav-link" href="{{route('login')}}">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('register')}}">Register</a>
      </li>
      @else
      <li class="nav-item">
        <a class="nav-link" href="{{route('auth.logout')}}">Log out</a>
      </li>
      @endif
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
      <img src="{{asset('images/Logo.png')}}" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
      <span class="brand-text font-weight-light">GoodQuestion</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      @if(Auth::user())
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{Auth::user()->profile_photo}}" class="img-circle elevation-2" alt="User-Image">
        </div>
        <div class="info">
          <a href="{{route('admin.profile')}}" class="d-block">{{Auth::user()->login}}</a>
        </div>
      </div>
      @endif

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
              <a href="{{route('admin.dashboard')}}" class="nav-link">
                <i class="fa fa-home"></i>
                <p>Home</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{route('users.list')}}" class="nav-link active">
                <i class="fa fa-user"></i>
                <p>Manage Users</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{route('posts.list')}}" class="nav-link">
                <i class="fa fa-book"></i>
                <p>Manage Posts</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{route('categories.list')}}" class="nav-link">
                <i class="fas fa fa-list-alt"></i>
                <p>Manage Categories</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{route('comments.list')}}" class="nav-link">
                <i class="far fa-comment"></i>
                <p>Manage Comments</p>
              </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                      <div class="user-block mt-3">
                        <img class="img-circle img-bordered-sm" src="{{$user->profile_photo}}" alt="user image">
                        <span class="username">
                          <a href="#">{{$post->author}}</a>
                          @if($post->status == "active")
                          <span class="float-right btn-tool text-success">{{$post->status}}</span>
                          @else
                          <span class="float-right btn-tool text-danger">{{$post->status}}</span>
                          @endif
                        </span>
                        <span class="description">Shared publicly - {{$post->created_at}}</span>
                      </div>
                      <!-- /.user-block -->
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
                      <p>
                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                        <a href="#" class="link-black text-sm ml-2"><i class="far fa-thumbs-down"></i> Dislike</a>
                        <span class="float-right">
                          <a href="#" class="link-black text-sm">
                            <i class="far fa-comments mr-1"></i> Comments (5)
                          </a>
                        </span>
                      </p>
                      <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                    </div>
                    @endforeach
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.1.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

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
