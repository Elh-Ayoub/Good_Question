<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | {{env('APP_NAME')}}</title>
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
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
  <style>#from span:hover{color: midnightblue;}</style>
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
            <h1 class="m-0">Home</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <p id="result"></p>
    <section class="content">
      <div class="form-inline justify-content-center">
          <button class="filter btn btn-secondary m-3"><span class="filterBy" data-id="post">Only for posts</span><i class="icon ml-1"></i></button>
          <button class="filter btn btn-secondary m-3"><span class="filterBy" data-id="comment">Only for comments</span><i class="icon ml-1"></i></button>
          <button class="filter btn btn-info m-3"><span class="filterBy" data-id="both">Both</span><i class="icon ml-1 fa fa-arrow-down"></i> </button>
      </div>
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row justify-content-start">
            @foreach($data as $d)
                <div class="col-lg-3 col-6 likes-box" data-type="@if($d['post'])post @else comment @endif">
                    <div class="small-box @if($d['like']->type == 'like') bg-success @else bg-danger @endif">
                        <div class="inner">
                            <h3>Type: {{$d['like']->type}}</h3>
                            <p>For @if($d['post'])
                                <span class="type">Post</span>: {{$d['post']->title}}
                            @elseif($d['comment'])
                                <span class="type">Comment</span>: {{$d['comment']->content}}
                            @endif
                            </p>
                            <a id="from" class="text-white ml-1" href="{{route('users.update.view', ['user' => $d['author']->id])}}">
                                <img class="img-circle img-sm img-bordered-sm" src="{{$d['author']->profile_photo}}" alt="user image">
                                <span class="ml-2">From: {{\App\Models\User::find($d['like']->author)->login}}</span>
                            </a>
                        </div>
                        <div class="icon">
                            @if($d['like']->type == 'like')
                                <i class="far fa-thumbs-up"></i>
                            @else
                                <i class="far fa-thumbs-down"></i>
                            @endif
                        </div>
                        <a href="#" type="button" class="small-box-footer" data-toggle="modal" data-target="#modal-delete-{{$d['like']->id}}"><i class="fas fa-trash mr-1"></i>Delete</a>
                    </div>
                </div>
                <div class="modal fade" id="modal-delete-{{$d['like']->id}}">
                  <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                      <div class="modal-header">
                        <h4 class="modal-title">Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>You are about to delete a Like. Are you sure? </p>
                      </div>
                      <form action="{{route('likes.delete', $d['like']->id)}}" method="POST">
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
            @endforeach
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
<script src="{{ asset('js/filterLikes.js') }}"></script>
</body>
</html>
