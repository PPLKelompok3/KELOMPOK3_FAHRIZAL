<!DOCTYPE html>
<html lang="en">
  <head>
    <title>@yield('title', 'Workora — Find Your Dream Job')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom-bs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/line-icons/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" />

    <style>
      .selected-skill {
        background-color: #ffa500 !important; /* darker orange */
        color: white !important;
      }
    </style>
    

    @stack('head')
  </head>

  <body id="top">
    {{-- <div id="overlayer"></div>
    <div class="loader">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div> --}}

    <div class="site-wrap">
      {{-- Mobile Navigation --}}
      <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
      </div>

      {{-- Navbar --}}
      <header class="site-navbar mt-3">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="site-logo col-6">
              <a href="{{ url('/') }}">
                <img src="{{ asset('assets/img/icons/logo/workora.svg') }}" alt="Workora Logo" height="120">
              </a>
            </div>

            <nav class="mx-auto site-navigation">
              <ul class="site-menu js-clone-nav d-none d-xl-block ml-0 pl-0">
                <li><a href="{{ url('/') }}" class="nav-link active">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Job Listings</a></li>
                <li class="has-children">
                  <a href="#">Pages</a>
                  <ul class="dropdown">
                    <li><a href="#">Service</a></li>
                    <li><a href="#">Portfolio</a></li>
                    <li><a href="#">Blog</a></li>
                  </ul>
                </li>
                <li><a href="#">Contact</a></li>
              </ul>
            </nav>

            <div class="right-cta-menu text-right d-flex aligin-items-center col-6">
              <div class="ml-auto">
                <a href="{{ route('jobs.create') }}" class="btn btn-outline-white border-width-2 d-none d-lg-inline-block"><span class="mr-2 icon-add"></span>Post a Job</a>
                @auth
                @if(auth()->user()->role === 'applicant')
                  <a href="/applicantdashboard" class="btn btn-outline-primary">Dashboard</a>
                @elseif(auth()->user()->role === 'recruiter')
                  <a href="{{ route('recruiter.dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                @endif
              
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                  @csrf
                  <button type="submit" class="btn btn-danger ms-2">Logout</button>
                </form>
              @else
                <a href="{{ route('login') }}" class="btn btn-primary ms-2">Login</a>
              @endauth
              
              </div>
              <a href="#" class="site-menu-toggle js-menu-toggle d-inline-block d-xl-none mt-lg-2 ml-3">
                <span class="icon-menu h3 m-0 p-0 mt-2"></span>
              </a>
            </div>
          </div>
        </div>
      </header>

      {{-- Main Content --}}
      @yield('content')

      {{-- Footer or scroll to top etc. --}}
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/stickyfill.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.animateNumber.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @stack('scripts')
  </body>
</html>
