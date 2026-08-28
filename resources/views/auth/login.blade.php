<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Learning & Development</title>
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <style>
    body {
      background-color: #f4f6f9;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .login-card {
      width: 100%;
      max-width: 440px;
      padding: 2.5rem;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    .login-logo {
      display: block;
      margin: 0 auto 1.5rem auto;
      max-height: 60px;
      object-fit: contain;
    }
    .login-title {
      font-size: 1.5rem;
      font-weight: 700;
      text-align: center;
      color: #333;
      margin-bottom: 0.25rem;
    }
    .login-subtitle {
      font-size: 0.875rem;
      text-align: center;
      color: #6c757d;
      margin-bottom: 1.5rem;
    }
    .btn-primary-custom {
      background-color: #0d6efd;
      border-color: #0d6efd;
      font-weight: 600;
      padding: 0.6rem;
      border-radius: 8px;
    }
    .btn-primary-custom:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
    }
    .nav-pills .nav-link {
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.875rem;
      color: #495057;
    }
    .nav-pills .nav-link.active {
      background-color: #0d6efd;
      color: #fff;
    }
  </style>
</head>
<body>

<div class="login-card">
  <img src="{{ asset('assets/images/logo-dharma.png') }}" alt="Logo" class="login-logo" onerror="this.style.display='none'">
  <h1 class="login-title">Learning & Development</h1>
  <p class="login-subtitle">Silakan pilih metode login sesuai peran Anda</p>

  {{-- Pesan Sukses --}}
  @if(session('success'))
      <div class="alert alert-success text-center mb-3 py-2 small">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
      </div>
  @endif

  {{-- Pesan Error --}}
  @if(session('error'))
      <div class="alert alert-danger text-center mb-3 py-2 small">
          <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
      </div>
  @endif

  {{-- Tab Navigation --}}
  <ul class="nav nav-pills nav-justified mb-3" id="loginTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="im-tab" data-bs-toggle="pill" data-bs-target="#im-login" type="button" role="tab">
        <i class="bi bi-person-badge me-1"></i> Immediate Manager
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="dlc-tab" data-bs-toggle="pill" data-bs-target="#dlc-login" type="button" role="tab">
        <i class="bi bi-shield-lock me-1"></i> DLC
      </button>
    </li>
  </ul>

  {{-- Tab Content --}}
  <div class="tab-content" id="loginTabContent">

    {{-- ========== TAB 1: IMMEDIATE MANAGER (NPK) ========== --}}
    <div class="tab-pane fade show active" id="im-login" role="tabpanel">
      <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="npk" class="form-label fw-semibold">NPK</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text"
                   class="form-control @error('npk') is-invalid @enderror"
                   id="npk"
                   name="npk"
                   placeholder="Masukkan NPK (contoh: 11990935)"
                   value="{{ old('npk') }}"
                   required
                   autofocus>
          </div>
          @error('npk')
              <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-primary-custom w-100 mt-2">
          <i class="bi bi-box-arrow-in-right me-1"></i> Masuk sebagai Immediate Manager
        </button>
      </form>
    </div>

    {{-- ========== TAB 2: DLC (USERNAME + PASSWORD) ========== --}}
    <div class="tab-pane fade" id="dlc-login" role="tabpanel">
      <form action="{{ route('login.dlc') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label fw-semibold">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text"
                   class="form-control @error('username') is-invalid @enderror"
                   id="username"
                   name="username"
                   placeholder="Masukkan username DLC"
                   value="{{ old('username') }}"
                   required>
          </div>
          @error('username')
              <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label fw-semibold">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   placeholder="Masukkan password"
                   required>
          </div>
          @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-primary-custom w-100 mt-2">
          <i class="bi bi-shield-lock me-1"></i> Masuk sebagai DLC
        </button>
      </form>
    </div>

  </div>

  <div class="text-center mt-4 text-muted small">
      &copy; {{ date('Y') }} Learning & Development Team
  </div>
</div>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
