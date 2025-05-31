<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Profile Setup') - Workora</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />


    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <style>
.modal-backdrop {
  z-index: 1050 !important;
}

.modal {
  z-index: 1055 !important;
  position: fixed; /* ensure modal breaks out of layout context */
}

    </style>

    @stack('head')
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo (optional, customizable) -->
                        <div class="app-brand justify-content-center mb-4">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('assets/img/icons/logo/workora.svg') }}" alt="Workora Logo"
                                        width="200">
                                </span>
                            </a>
                        </div>
                        <!-- Step title and description -->
                        <h4 class="mb-2">@yield('step-title', 'Profile Setup')</h4>
                        <p class="mb-4">@yield('step-desc')</p>

                        <!-- Step-specific content -->
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script> --}}
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>


    @stack('head')
    @stack('scripts')

    <!-- Cropper Modal (Global for Profile Setup Pages) -->
    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="cropper-image" class="w-100" alt="Image to crop">
                </div>
                <div class="modal-footer">
                    <button type="button" id="crop-confirm" class="btn btn-primary">Crop & Use</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
