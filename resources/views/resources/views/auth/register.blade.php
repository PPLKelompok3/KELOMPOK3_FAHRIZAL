<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Register Basic - Pages | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!-- Theme Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        <img src="{{ asset('assets/img/icons/logo/workora.svg') }}" alt="Workora Logo" width="300">
                      </span>                   
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">
                Where <span style="color: #ffc901;">Work</span> Meets <span style="color: #ffc901;">Wonder</span>!
              </h4>
              <p class="mb-4">
                Ready to make work a little more magical? Sign up now and let the wonders of Workora begin!
              </p>
              
              <form method="POST" action="{{ route('register') }}">
                @csrf
    
                <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus />
                  @error('name')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
    
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required />
                  @error('email')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
    
                <div class="mb-3">
                  <label for="role" class="form-label">Register as</label>
                  <select id="role" name="role" class="form-control" required>
                    <option value="applicant" {{ old('role') == 'applicant' ? 'selected' : '' }}>Applicant</option>
                    <option value="recruiter" {{ old('role') == 'recruiter' ? 'selected' : '' }}>Recruiter</option>
                  </select>
                  @error('role')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
    
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <input type="password" class="form-control" name="password" id="password" required />
                  @error('password')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
    
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password_confirmation">Confirm Password</label>
                  <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required />
                  @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
    
                <button class="btn btn-primary d-grid w-100" style="background-color: #ffc901; border: none; color: #000;">Register</button>
              </form>
              <p class="text-center">
                <span>Already have an account?</span>
                <a href="auth-login-basic.html">
                  <span>Sign in instead</span>
                </a>
              </p>
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
