<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
  <title>Create Post - {{env('APP_NAME')}}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
  <link rel="stylesheet" href="{{ asset('css/chip.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{asset('images/Logo.png')}}" alt="AdminLTELogo" height="60" width="60">
    </div>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
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
    <a href="" class="brand-link">
      <img src="{{asset('images/Logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">GoodQuestion</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{Auth::user()->profile_photo}}" class="img-circle elevation-2" alt="User-Image">
        </div>
        <div class="info">
          <a href="{{route('admin.profile')}}" class="d-block">{{Auth::user()->login}}</a>
        </div>
      </div>

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
              <a href="{{route('users.list')}}" class="nav-link">
                <i class="fa fa-user"></i>
                <p>Manage Users</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{route('posts.list')}}" class="nav-link active">
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
            <h1>Create Post</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Post</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <form method="POST" action="{{route('posts.create')}}" class="card p-3" enctype="multipart/form-data">
        <div class="d-flex align-items-stretch flex-row">
        <div class="col-md-6">
          <div class="card card-primary p-2">
          @csrf
            <div class="card-header">
              <h3 class="card-title">General</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
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
              <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" class="form-control" maxlength="20">
              </div>
              <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" maxlength="100">
              </div>
              <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" class="form-control" maxlength="500"></textarea>
              </div>
              <div class="form-group">
                <label for="categories">Categories</label>
                <div id="res"></div>
                <input type="text" id="categories" class="form-control" maxlength="500">
              <div class="form-group">
                <label for="images">Add a picture(s)</label>
                <input type="file" id="images" name="images[]" class="form-control" multiple>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="col-12">
              <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Cancel</a>
              <input type="submit" value="Create" class="btn btn-success float-right">
            </div>
          </div>
          <!-- /.card -->
        </div>
        </div>
        
      </form>
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
<script src="{{asset('js/chip.js')}}"></script>
</body>
</html>
