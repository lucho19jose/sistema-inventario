<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-1">
        <div class="container">
            <a class="navbar-brand p-0" href="{{ url('/') }}">
                <img src="img/logo.png" class="img-fluid" alt="Responsive image" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            {{ __('Login') }}
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="py-5 my-5">
       @yield('content')
</main>

<footer class="main-footer text-center">
    Copyright &copy; 2020
    <strong>
        | {{ config('app.name') }}
    </strong>
</footer>
</div>

