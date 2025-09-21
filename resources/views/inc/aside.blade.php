<link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
<style>
    .no-underline:hover {
        text-decoration: none;
    }
</style>
<style>
    /* Positioning styles for overlay text */
    .logo-time {
        position: absolute;
        top: 3px;
        right: 5px;
        font-size: 12px;
        font-weight: 500;
        color: #333;
        background: rgba(255, 255, 255, 0.8);
        padding: 1px 4px;
        border-radius: 3px;
    }

    .logo-2play {
        position: absolute;
        bottom: 3px;
        right: 5px;
        font-size: 13px;
        font-weight: bold;
        color: #007bff;
        background: rgba(255, 255, 255, 0.8);
        padding: 1px 4px;
        border-radius: 3px;
    }

    .logo-tagline {
        position: absolute;
        bottom: -18px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 11px;
        color: #333;
        background: rgba(255, 255, 255, 0.7);
        padding: 0 5px;
        font-weight: 500;
        margin: 0;
        white-space: nowrap;
    }

    /* Hide text when sidebar collapses */
    .sidebar-collapse .logo-tagline {
        display: none !important;
    }

    /* Optional: shrink logo content in collapsed state */
    .sidebar-collapse .logo-box {
        margin-right: 0 !important;
    }
</style>
<style>
    .logo-title-container {
        position: absolute;
        top: 50%;
        left: 100%;
        transform: translate(-20%, -50%);
        white-space: nowrap;
    }

    .logo-title-line-1 {
        display: block;
        font-size: 40px;
        font-weight: bold;
        color: #003366;
        margin-right: 125px;
    }

    .logo-title-line-2 {
        display: block;
        font-size: 40px;
        font-weight: bold;
        color: #003366;
        margin-right: 120px;
        margin-top: -55px;
    }

    /* Hide text when sidebar collapses */
    .sidebar-collapse .logo-title-container {
        display: none !important;
    }
</style>

<aside class="main-sidebar sidebar-light elevation-4" style="background: #fff; overflow-x: hidden;">
    <a href="{{ url('/') }}"
        class="brand-link d-flex justify-content-center align-items-center py-3 position-relative">
        <div class="position-relative logo-box" style="height: 70px; width: 120px; margin-right: 50px;">
            <img src="{{ url('public/assets/dist/img/mlogo.png') }}" alt="Logo" class="img-fluid w-80 h-100">
            <div class="logo-title-container text-center">
                <span class="logo-title-line-1">Time</span><br>
                <span class="logo-title-line-2">2Play</span>
            </div>

            <!-- Bottom center text (hidden when sidebar collapses) -->
            <p class="logo-tagline">Your Game, Your Time</p>
        </div>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

                <!-- Dashboard -->
                @php $isActive = session('tab') == 'dashboard'; @endphp
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Dashboard</p>
                    </a>
                </li>

                <!-- Users Listing -->
                @php $isActive = session('tab') == 'users'; @endphp
                <li class="nav-item">
                    <a href="{{ url('users') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-users {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Users Listing</p>
                    </a>
                </li>

                <!-- Events -->
                @php $isActive = session('tab') == 'events'; @endphp
                <li class="nav-item">
                    <a href="{{ url('events') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Events Requests</p>
                    </a>
                </li>

                <!-- Center Request -->
                @php $isActive = session('tab') == 'center-request'; @endphp
                <li class="nav-item">
                    <a href="{{ url('center-request') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-building {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Center Requests</p>
                    </a>
                </li>
                <!-- Sports Management -->
                @php $isActive = session('tab') == 'sports-management'; @endphp
                <li class="nav-item">
                    <a href="{{ url('sports-management') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-futbol {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Sports Management</p>
                    </a>
                </li>

                <!-- Subscription Control -->
                @php $isActive = session('tab') == 'subscription-control'; @endphp
                <li class="nav-item">
                    <a href="{{ url('subscription-control') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-credit-card {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Subscription Control</p>
                    </a>
                </li>


                <!-- Custom Notifications -->
                @php $isActive = session('tab') == 'notifications'; @endphp
                <li class="nav-item">
                    <a href="{{ url('notifications') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-bell {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Custom Notifications</p>
                    </a>
                </li>

                <li>
                    <hr class="text-dark mx-3">
                </li>

                <!-- Version Control -->
                @php $isActive = session('tab') == 'versions'; @endphp
                <li class="nav-item">
                    <a href="{{ url('versions') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-file-alt {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Version Control</p>
                    </a>
                </li>

                <!-- Policy Documents -->
                @php $isActive = session('tab') == 'policies'; @endphp
                <li class="nav-item">
                    <a href="{{ url('policies') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-file-contract {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Policy Documents</p>
                    </a>
                </li>

                <!-- Change Password -->
                @php $isActive = session('tab') == 'password'; @endphp
                <li class="nav-item">
                    <a href="{{ url('change-password') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-lock {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Change Password</p>
                    </a>
                </li>

                <!-- Help Requests -->
                @php $isActive = session('tab') == 'help'; @endphp
                <li class="nav-item">
                    <a href="{{ url('help-requests') }}" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}">
                        <i class="nav-icon fas fa-question-circle {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Help Requests</p>
                    </a>
                </li>

                <!-- Logout -->
                @php $isActive = session('tab') == 'logout'; @endphp
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link"
                        style="{{ $isActive ? 'background: var(--buttons-primary-color); color: #fff' : '' }}"
                        data-toggle="modal" data-target="#logoutModal">
                        <i class="nav-icon fas fa-sign-out-alt {{ $isActive ? 'text-white' : 'text-dark' }}"></i>
                        <p class="{{ $isActive ? 'text-white' : 'text-dark' }}">Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center p-4">
            <div class="modal-body">
                <div class="rounded-circle mx-auto mb-3"
                    style="width: 60px; height: 60px; background-color: #f2f2f2;">
                    <i class="fas fa-sign-out-alt"
                        style="font-size: 28px; color: var(--buttons-primary-color); line-height: 60px;"></i>
                </div>
                <h5 class="font-weight-bold">Logout</h5>
                <p class="text-muted mt-2">Are you absolutely certain you wish to proceed with logging out?</p>
                <form method="POST" action="{{ url('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary mt-3"
                        style="background: var(--buttons-primary-color); border: none;">
                        Yes, Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>






{{-- <style>
    .no-underline:hover {
        text-decoration: none;
    }
</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: #fff">
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ url('public/assets/dist/img/mlogo.png') }}" style="height: 97%; width: 97%" alt="Logo" class="p-2">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="{{ url('/') }}" class="nav-link" @if (session('tab') == 'dashboard') style="background: var(--buttons-primary-color); color: #fff" @else style="background: transparent" @endif>
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('users') }}" class="nav-link text-decoration-none" @if (session('tab') == 'users') style="background: var(--buttons-primary-color); color: #fff" @endif>
                        <i class="text-dark nav-icon fas fa-users"></i>
                        <p class="text-dark text-decoration-none">Users Listing</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('notifications') }}" class="nav-link" @if (session('tab') == 'notifications') style="background: var(--buttons-primary-color); color: #fff" @endif>
                        <i class="text-dark nav-icon fa fa-bell"></i>
                        <p class="text-dark">Custom Notifications</p>
                    </a>
                </li>
                <li class="">
                    <hr class="text-dark px-5">
                </li>
                <li>
                    <a href="javascript:void(0);" class="nav-link"
                       @if (session('tab') == 'logout') style="background: var(--buttons-primary-color); color: #fff" @endif
                       data-toggle="modal" data-target="#logoutModal">
                        <i class="text-dark nav-icon fas fa-sign-out-alt"></i>
                        <p class="text-dark">Logout</p>
                    </a>
                </li>

                <script>
                    function confirmAction() {
                        return confirm("Are you absolutely certain you wish to proceed with logging out?");
                    }
                </script>
            </ul>
        </nav>
    </div>
</aside>
<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content text-center p-4">

        <div class="modal-body">
          <!-- Icon -->
          <div class="rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; background-color: #f2f2f2;">
            <i class="fas fa-sign-out-alt" style="font-size: 28px; color: var(--buttons-primary-color); line-height: 60px;"></i>
          </div>

          <!-- Title -->
          <h5 class="font-weight-bold">Logout</h5>

          <!-- Message -->
          <p class="text-muted mt-2">Are you absolutely certain you wish to proceed with logging out?</p>

          <!-- Logout Form -->
          <form method="POST" action="{{ url('logout') }}">
              @csrf
              <button type="submit" class="btn btn-primary mt-3" style="background: var(--buttons-primary-color); border: none;">
                  Yes, Logout
              </button>
          </form>
        </div>

      </div>
    </div>
  </div> --}}
