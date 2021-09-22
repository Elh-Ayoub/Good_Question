<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Users | {{env('APP_NAME')}}</title>
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
  <style>.title, .table-row{cursor: pointer;}</style>
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
    <!-- Main content -->
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
    <p id="result"></p>
    <section class="content">
      <div class="form-inline justify-content-between mb-2">
          <a href="{{route('create.user.view')}}" class="btn btn-primary col-sm-2"><i class="fas fa-plus"></i> Create new User/Admin</a>
          <div class="input-group mb-1">
            <input type="text" id="searchByLogin" type="search" placeholder="Search by login...">
              <div class="input-group-append">
                <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
          </div>
          <div class="input-group mb-1">
            <input type="text" id="searchByemail" type="search" placeholder="Search by email...">
              <div class="input-group-append">
                <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
          </div>
      </div>
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row">
            <table class="table text-center orders table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th><i class="fas fa-sort"></i><span class="title">Login</span></th>
                        <th><i class="fas fa-sort"></i><span class="title">Email</span></th>
                        <th><i class="fas fa-sort"></i><span class="title">Full name</span></th>
                        <th><i class="fas fa-sort"></i><span class="title">Role</span></th>
                        <th><i class="fas fa-sort"></i><span class="title">Rating</span></th>
                        <th><i class="fas fa-sort"></i><span class="title">Created at</span></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                    <tr class="table-row" data-id="{{$user->id}}">
                        <td><img class="img-size-64" src="{{$user->profile_photo}}" alt="Avatar"></td>
                        <td class="login">{{$user->login}}</td>
                        <td class="email">{{$user->email}}</td>
                        <td class="full_name">{{$user->full_name}}</td>
                        <td>{{$user->role}}</td>
                        <td>{{$user->rating}}</td>
                        <td class="created_at">{{$user->created_at}}</td>
                        <td><a href="{{route('users.update.view', ['user' => $user->id])}}" type="button" class="btn btn-warning">Edit or Delete</a></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
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
<script src="{{ asset('js/sortTable.js')}}"></script>
<script src="{{ asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-slider/bootstrap-slider.min.js')}}"></script>
<script>
   $('.table-row').on('click', function(){
      id = $(this).data('id');
      window.location = "{{route('users.update.view')}}" + "?user="+id
   })
</script>
</body>
</html>
