<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
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
    <link rel="stylesheet" href="{{ asset('css/profile.css')}}">
    <link rel="stylesheet" href="{{ asset('css/auth.css')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.png')}}"/>
    <title>Profile - {{env('APP_NAME')}}</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
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
    <a href="{{route('admin.dashboard')}}" class="brand-link">
      <img src="{{asset('images/Logo.png')}}" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
      <span class="brand-text font-weight-light">GoodQuestion</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{Auth::user()->profile_photo}}" class="img-circle elevation-2" alt="User Image">
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
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
              <a href="{{route('admin.dashboard')}}" class="nav-link">
                <i class="fa fa-home"></i>
                <p>Home</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="" class="nav-link">
                <i class="fa fa-briefcase"></i>
                <p>Manage Users</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="" class="nav-link">
                <i class="fa fa-bookmark"></i>
                <p>Orders</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="" class="nav-link">
                <i class="fa  fa-binoculars"></i>
                <p>Track my Order</p>
              </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
    <div class="profileContainer">
        <div class="profile-picture">
        <form  method="POST" action=""  enctype="multipart/form-data">
        @csrf
            <img id="profile-pic" class="user-picture" src="{{Auth::user()->profile_photo}}">
            <div class="file-submit">
                <label class="selectfile" for="choosefile">Edit profile picture</label>
                <input id="choosefile" type="file" name="image" style="opacity: 0;position: absolute; z-index: -1;"> 
                <input class="save-btn"type="submit" value="save">
            </div>
        </form>
        @if(strpos(Auth::user()->profile_photo, ".png") !== false)
        <form method="POST" action="">
            @csrf
            @method('DELETE')
            <input type="submit" class="deleteavatar" value="Remove Photo">
        </form>
        @endif
        </div>
        
        <div class="forms">
            <form class="profileform" method="POST" action="">
                <p style="font-size: medium; color: #2d3748">You can edit your personal information.</p>
                @csrf
                @method('PATCH')
                <div class="input-field" style="margin-bottom: 10px;">
                    <label for="login">Login</label>
                    <input id="login" class="inputText" type="text" name="login" value="{{Auth::user()->login}}" required/>
                </div>
                <div class="input-field" style="margin-bottom: 10px;">
                    <label for="email">email</label>
                    <input id="email" class="inputText" type="email" name="email" value="{{Auth::user()->email}}" required/>
                </div>
                <div class="input-field" style="margin-bottom: 10px;">
                    <label for="full_name">Full name</label>
                    <input id="full_name" class="inputText" type="text" name="full_name" value="{{Auth::user()->full_name}}" required/>
                </div>
                <div class="input-field" style="margin-bottom: 10px;">
                    <label for="role">Role</label>
                    <span id="role" class="inputText">{{Auth::user()->role}}</span>
                </div>
                <div class="txt-btn">
                    <input class="save-btn" type="submit" class="ml-4" value="save">
                </div>
                @if(Session::get('personal-fail'))
                <div class="input-field">
                    <p class="fail">{{Session::get('personal-fail')}}</p>
                </div>
                @endif
            </form>
                
            <form class="profileform" method="POST" action="">
                @csrf
                @method('PATCH')
                <p style="font-size: medium; color: #2d3748">You can edit your password.</p>
                <div class="input-field" style="margin-bottom: 10px;">
                    <label for="current_password">Current password</label>
                    <input id="current_password" class="inputText" type="password" name="current_password" required/>
                </div>
                <div class="input-field" style="margin-bottom: 10px;">
                    <label for="new_password">New password</label>
                    <input id="new_password" class="inputText" type="password" name="password" required/>
                </div>
                <div class="input-field" style="margin-bottom: 10px;">
                    <label for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" class="inputText" type="password" name="password_confirmation" required/>
                </div>
                <div class="txt-btn">
                    <input class="save-btn" type="submit" class="ml-4" value="save">
                </div>
                @if(Session::get('password-success'))
                <div class="input-field">
                    <p class="success">{{Session::get('password-success')}}</p>
                </div>
                @endif

                @if(Session::get('password-fail'))
                <div class="input-field">
                    <p class="fail">{{Session::get('password-fail')}}</p>
                </div>
                @endif
                @if(Session::get('password-fail-arr'))
                <div class="input-field">
                    @foreach(Session::get('password-fail-arr') as $key => $err)
                    <p class="fail">{{$key . ': ' . $err[0]}}</p>
                    @endforeach
                </div>
                @endif
            </form>
        </div>
    </div>
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
</script>
</body>
</html>