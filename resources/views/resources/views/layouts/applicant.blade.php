<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"" />
  <title>@yield('title', 'Recruiter Area')</title>

  <!-- Sneat CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}">
  @stack('head')
  <style>
  body {
    font-family: "Roboto", sans-serif;
    background: #EFF1F3;
    min-height: 100vh;
    position: relative;
}

.section-50 {
    padding: 50px 0;
}

.m-b-50 {
    margin-bottom: 50px;
}

.dark-link {
    color: #333;
}

.heading-line {
    position: relative;
    padding-bottom: 5px;
}

.heading-line:after {
    content: "";
    height: 4px;
    width: 75px;
    background-color: #29B6F6;
    position: absolute;
    bottom: 0;
    left: 0;
}

.notification-ui_dd-content {
    margin-bottom: 30px;
}

.notification-list {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 20px;
    margin-bottom: 7px;
    background: #fff;
    -webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
}

.notification-list--unread {
    border-left: 2px solid #29B6F6;
}

.notification-list .notification-list_content {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}

.notification-list .notification-list_content .notification-list_img img {
    height: 48px;
    width: 48px;
    border-radius: 50px;
    margin-right: 20px;
}

.notification-list .notification-list_content .notification-list_detail p {
    margin-bottom: 5px;
    line-height: 1.2;
}

.notification-list .notification-list_feature-img img {
    height: 48px;
    width: 48px;
    border-radius: 5px;
    margin-left: 20px;
}
.notification-list--unread {
    background-color: #eef3fd;
    border-left: 4px solid #4361ee;
}
</style>
</head>
<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo">
              <img src="{{ asset('assets/img/icons/logo/workora.svg') }}" alt="Workora Logo" height="120">

            </span>

          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item {{ request()->routeIs('applicantdashboard') ? 'active' : '' }}">
            <a href="{{ route('applicantdashboard') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Application</div>
            </a>
          </li>
          <li class="menu-item {{ request()->routeIs('applicantnotification') ? 'active' : '' }}">
            <a href="{{ route('applicantnotification') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Notification</div>
            </a>
          </li>
        </ul>
      </aside>
      <div class="layout-page">
        <!-- Navbar -->

        <nav
            class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                </div>
              </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->
              <li class="nav-item dropdown notification-dropdown me-3">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-bell fs-4"></i>
                  @php
                  $unreadCount = Auth::user()->customNotifications()->where('is_read', false)->count();
                @endphp
                
                @if ($unreadCount > 0)
                  <span class="badge bg-danger rounded-pill badge-notifications">{{ $unreadCount }}</span>
                @endif
                </a>
              
                <ul class="dropdown-menu dropdown-menu-end">
                  <li class="dropdown-header">Notifications</li>
              
                  @php
                  $notifications = Auth::user()->customNotifications()->latest()->take(5)->get();
                @endphp
              
                  @forelse ($notifications as $notification)
                    <li>
                      <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="flex-shrink-0 me-3">
                          @if ($notification->type === 'Application Status')
                            <i class="bx bx-briefcase text-success"></i>
                          @else
                            <i class="bx bx-bell text-primary"></i>
                          @endif
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-0">{{ $notification->content }}</h6>
                          <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                      </a>
                    </li>
                  @empty
                    <li>
                      <a class="dropdown-item text-center" href="#">No notifications yet</a>
                    </li>
                  @endforelse
              
                  <li><hr class="dropdown-divider" /></li>
                  <li>
                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                      @csrf
                      <button type="submit" class="dropdown-item text-center" style="background: none; border: none;">
                        View All Notifications
                      </button>
                  </form>                  
                  </li>
                </ul>
              </li>
              
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img
  src="{{ Auth::user()->profile?->profile_picture 
            ? asset('storage/' . Auth::user()->profile->profile_picture) 
            : asset('assets/img/avatars/1.png') }}"
  alt="Profile Picture"
  class="w-px-40 h-auto rounded-circle"
/>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img
  src="{{ Auth::user()->profile?->profile_picture 
            ? asset('storage/' . Auth::user()->profile->profile_picture) 
            : asset('assets/img/avatars/1.png') }}"
  alt="Profile Picture"
  class="w-px-40 h-auto rounded-circle"
/>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                          <small class="text-muted">Applicant</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-cog me-2"></i>
                      <span class="align-middle">Settings</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="d-flex align-items-center align-middle">
                        <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                        <span class="flex-grow-1 align-middle">Billing</span>
                        <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                      </span>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </button>
                    </form>                    
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>
        <div class="content-wrapper">
          <div class="container-fluid flex-grow-1 container-p-y">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <!-- JS Core -->
<!-- Required Vendor Scripts -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<!-- ✅ Add this -->
<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

<!-- Sneat Menu System -->
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- Sneat Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>


  
  @stack('scripts')
</body>
</html>
