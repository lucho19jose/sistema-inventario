<div class="wrapper" id="app">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light mt-0">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li  v-on:click.prevent="menu=0" class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @else
            <li class="nav-item dropdown">
                <a id="nav-link dropdown-toggle nav-link" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    <img src="./img/user2-160x160.jpg" alt="User Image" class="img-circle elevation-1" width="30" height="30">
                    <span class="caret">{{  $staff->first_name .' '.  $staff->last_name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right pt-0" aria-labelledby="navbarDropdown">
                    <div class="dropdown-header text-center bg-light">
                        <strong>Cuenta</strong>
                    </div>
                    <div class="pt-3">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user"></i>
                            <span> Editar Perfil</span>
                        </a>
                    </div>
                    <hr>
                    <div class="px-2">
                        <a class="dropdown-item text-center bg-gradient-danger text-white" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            <i class="fa fa-lock">
                            </i> <span> {{ __('Logout') }}</span>
                        </a>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
         @endguest
        </ul>
    </nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('home') }}" class="brand-link">
        <img src="./img/logo_0.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            {{ config('app.name', 'Laravel') }}
        </span>
    </a>

    <!-- Sidebar -->

    @include('layouts.sidebar')

    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <main>
        @yield('content2')
    </main>

</div>
<!-- /.content-wrapper -->

<!-- Main Footer -->
<footer class="main-footer text-center">
    Copyright &copy; 2020
    <strong>
        | {{ config('app.name') }}
    </strong>
</footer>

</div>