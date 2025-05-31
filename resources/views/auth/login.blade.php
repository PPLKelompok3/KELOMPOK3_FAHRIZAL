<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login - Workora</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Login Card -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="{{ route('login') }}" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <img src="{{ asset('assets/img/icons/logo/workora.svg') }}" alt="Workora Logo" width="300">
                  </span>
                </a>
              </div>

              <h4 class="mb-2">Welcome to <span style="color: #ffc901;">Workora</span>! 👋</h4>
              <p class="mb-4">Please sign-in to your account to continue</p>

              <!-- Session Status -->
              @if (session('status'))
                <div class="alert alert-success mb-3">
                  {{ session('status') }}
                </div>
              @endif

              <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus />
                  @error('email')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>

                <!-- Password -->
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <input type="password" class="form-control" name="password" id="password" required />
                  @error('password')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-3">
                  <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" />
                    <span class="form-check-label">Remember Me</span>
                  </label>
                </div>

                <button dusk="LoginButton" class="btn btn-primary d-grid w-100" style="background-color: #ffc901; border: none; color: #000;">Login</button>
              </form>

              <p class="text-center mt-3">
                <a href="{{ route('register') }}">Don’t have an account? Register here</a>
              </p>

              @if (Route::has('password.request'))
              <p class="text-center">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
              </p>
              @endif
            </div>
          </div>
          <!-- /Login Card -->
        </div>
      </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
  </body>
</html>
