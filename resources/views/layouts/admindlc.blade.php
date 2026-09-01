<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="adminHMD professional admin dashboard">
  <title>@yield('title', 'Dashboard | Learning & Development')</title>
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<style>
  .brand-icon img {
    width: 32px;   /* sesuaikan ukuran */
    height: 32px;
    object-fit: contain;
}
</style>
<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="{{ route('dashboarddlc') }}" aria-label="adminHMD dashboard">
          <span class="brand-icon">
            <img src="{{ asset('assets/images/logo-dharma.png') }}" alt="Logo Perusahaan">
          </span>
          <span class="brand-copy">
            <span class="brand-title">Learning & <br>Development</span>
            <!-- <span class="brand-subtitle">Admin</span> -->
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a class="nav-link" href="{{ route('dashboarddlc') }}">
          <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>
        <a class="nav-link" href="{{ route('divisi.index') }}">
          <span class="nav-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
          <span class="nav-text">Master Divisi</span>
        </a>
        <a class="nav-link" href="{{ route('department.index') }}">
          <span class="nav-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
          <span class="nav-text">Master Department</span>
        </a>
        <a class="nav-link" href="{{ route('training.index') }}">
          <span class="nav-icon"><i class="bi bi-mortarboard" aria-hidden="true"></i></span>
          <span class="nav-text">Master Training</span>
        </a>
        <a class="nav-link" href="{{ route('member-list') }}">
          <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
          <span class="nav-text">Member List</span>
        </a>
        <a class="nav-link" href="{{ route('penerima-email') }}">
          <span class="nav-icon"><i class="bi bi-envelope-at" aria-hidden="true"></i></span>
          <span class="nav-text">Konfigurasi Email</span>
        </a>
        <a class="nav-link" href="{{ route('body-email') }}">
          <span class="nav-icon"><i class="bi bi-card-text" aria-hidden="true"></i></span>
          <span class="nav-text">Body Email</span>
        </a>
        <a class="nav-link" href="{{ route('periode-tna') }}">
          <span class="nav-icon"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
          <span class="nav-text">Periode TNA</span>
        </a>
      </nav>

      <!-- <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ asset('assets/images/avatar/avatar.jpg') }}" alt="Admin Hasan">
        <strong>Admin Hasan</strong>
        <small>Active Workspace</small>
      </div> -->

      <div class="sidebar-footer">
        <span class="status-dot"></span>
        <span class="sidebar-footer-text">System running smoothly</span>
      </div>
    </aside>
    <!-- ==========NAVBAR========== -->
    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>
          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>
            @if(session('user'))
            <div class="dropdown ms-2">
              <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span>{{ session('user')->nama_staff ?? 'Admin' }} {{ isset(session('user')->npk_staff) ? '('.session('user')->npk_staff.')' : '' }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text text-muted small">Bagian: {{ session('user')->bagian_staff ?? 'DLC' }}</span></li>
                <li><span class="dropdown-item-text text-muted small">Role: {{ session('role', 'DLC') }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                  </form>
                </li>
              </ul>
            </div>
            @endif
          </div>
        </div>
      </nav>
      <!-- ==========NAVBAR========== -->
      @yield('content')
    </div>
  </div>

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
