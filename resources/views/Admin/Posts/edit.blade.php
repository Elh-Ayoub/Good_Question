<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
  <title>Update Post - {{env('APP_NAME')}}</title>
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
  @include('Admin.layouts.navbar')
  @include('Admin.layouts.sidebar')
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Update Post</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Post</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <form method="POST" action="{{route('posts.update', ['post' => $post->id])}}" class="card p-3" enctype="multipart/form-data">
        <div class="d-flex align-items-stretch flex-row">
          <div class="col-md-6">
            <div class="card card-primary p-2">
            @csrf
            @method('PATCH')
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
                  <input type="text" id="author" name="author" class="form-control" maxlength="20" value="{{$post->author}}">
                </div>
                <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" id="title" name="title" class="form-control" maxlength="100" value="{{$post->title}}">
                </div>
                <div class="form-group">
                  <label for="categories">Categories</label>
                  <div id="res"></div>
                  <input type="text" id="categories" class="form-control" maxlength="500" value="{{implode(' ', explode(', ',$post->categories))}}">
                </div>
                <div class="form-group">
                  <label for="status">Post's status</label>
                  <select id="status" name="status" class="form-control custom-select">
                    <option selected disabled>{{$post->status}}</option>
                    <option>active</option>
                    <option>inactive</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="images">Add a picture(s)</label>
                  <input type="file" id="images" name="images" class="form-control" multiple>
                </div>
              <div class="col-12">
                <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Update" class="btn btn-success float-right">
              </div>
            </div>
          </div>
        </div>  
      </form>
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
<script src="{{ asset('js/chip.js') }}"></script>
</body>
</html>
