<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Categories | {{env('APP_NAME')}}</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  @include('Admin.layouts.navbar')
  @include('Admin.layouts.sidebar')
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List of categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="form-inline">
        <button type="button" class="btn btn-primary mb-2 mr-3" data-toggle="modal" data-target="#modal-default"><i class="fas fa-plus mr-2"></i>Create category</button>
        <div class="input-group mb-2" data-widget="sidebar-search">
          <input type="text" id="searchByCategory" type="search" placeholder="Search...">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
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
      <div class="card card-solid">
          <div class="card-body pb-0">
            <div class="row justify-content-start">
                @foreach($categories as $category)
                  <div class="col-lg-3 col-6 d-flex align-items-stretch flex-column">
                    <div class="small-box categories flex-fill" id="{{str_replace(' ', '-', $category->title)}}">
                      <div class="inner">
                          <h3 class="title">{{$category->title}}</h3>
                          @if($category->description)
                            <p>{{$category->description}}</p>
                          @else
                            <p>No description</p>
                          @endif
                      </div>
                      <div class="icon">
                          <i class="fab fa-{{strtolower($category->title)}}"></i>
                      </div>
                      <a href="#" type="button" class="small-box-footer" data-toggle="modal" data-target="#modal-default-{{$category->id}}">Edit or Delete <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <div class="modal fade" id="modal-default-{{$category->id}}">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Update Category</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="{{route('categories.update', $category->id)}}" method="POST">
                          @csrf
                          @method('PATCH')
                          <div class="modal-body">
                              <div class="form-group">
                                  <label for="title">Title</label>
                                  <input type="text" name="title" class="form-control" maxlength="100" value="{{$category->title}}">
                              </div>
                              <div class="form-group">
                                  <label for="description">Description</label>
                                  <textarea name="description" class="form-control" maxlength="200">{{$category->description}}</textarea>
                              </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-{{$category->id}}">Delete</button>
                              <button type="submit" class="btn btn-primary">Update</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade" id="modal-delete-{{$category->id}}">
                    <div class="modal-dialog">
                      <div class="modal-content bg-danger">
                        <div class="modal-header">
                          <h4 class="modal-title">Confirmation</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="{{route('categories.delete', $category->id)}}" method="POST">
                          @csrf
                          @method('DELETE')
                          <div class="modal-body">
                              <p>You are about to delete a category. Are you sure?</p>
                          </div>
                          <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-outline-light">Delete</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
              @endforeach
            </div>
          </div>
      </div>
      <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Create Category</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form action="{{route('categories.create')}}" method="POST">
                  @csrf
                  <div class="modal-body">
                      <div class="form-group">
                          <label for="title">Title</label>
                          <input type="text" id="title" name="title" class="form-control" maxlength="100">
                      </div>
                      <div class="form-group">
                          <label for="description">Description</label>
                          <textarea id="description" name="description" class="form-control" maxlength="200"></textarea>
                      </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Create</button>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  @include('Admin.layouts.footer')
</div>
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{ asset('js/lazyLoading.js')}}"></script>
<script src="{{ asset('js/search.js')}}"></script>
<script src="{{ asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
<script src="{{ asset('js/categories.js')}}"></script>
</body>
</html>
