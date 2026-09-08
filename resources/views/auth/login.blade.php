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
      background: radial-gradient(circle at 50% 15%, #f0f7ff 0%, #f8fafc 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 1.5rem 1rem;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    .login-card {
      width: 100%;
      max-width: 450px;
      padding: 2.75rem 2.5rem;
      background: #ffffff;
      border-radius: 20px;
      border: 1px solid rgba(226, 232, 240, 0.85);
      box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.09), 0 8px 20px -4px rgba(15, 23, 42, 0.04);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .login-logo-wrap {
      width: 68px;
      height: 68px;
      margin: 0 auto 1.25rem auto;
      border-radius: 16px;
      background: #ffffff;
      box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.12);
      border: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 8px;
    }
    .login-logo {
      max-height: 48px;
      max-width: 48px;
      object-fit: contain;
    }
    .login-title {
      font-size: 1.55rem;
      font-weight: 800;
      text-align: center;
      color: #0f172a;
      letter-spacing: -0.025em;
      margin-bottom: 0.35rem;
    }
    .login-subtitle {
      font-size: 0.875rem;
      text-align: center;
      color: #64748b;
      margin-bottom: 1.75rem;
    }
    .segmented-tabs {
      background: #f1f5f9;
      padding: 5px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      gap: 4px;
    }
    .segmented-tabs .nav-item {
      flex: 1;
    }
    .segmented-tabs .nav-link {
      width: 100%;
      border-radius: 9px;
      font-weight: 600;
      font-size: 0.825rem;
      color: #64748b;
      padding: 8px 12px;
      border: none;
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .segmented-tabs .nav-link:hover:not(.active) {
      color: #1e293b;
      background: rgba(255, 255, 255, 0.6);
    }
    .segmented-tabs .nav-link.active {
      background: #ffffff;
      color: #2563eb;
      box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }
    .form-control {
      border-radius: 10px;
      border: 1.5px solid #cbd5e1;
      padding: 0.65rem 0.85rem;
      font-size: 0.88rem;
      transition: all 0.15s ease;
      background-color: #ffffff;
    }
    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3.5px rgba(59, 130, 246, 0.15);
    }
    .input-group-text {
      background-color: #f8fafc;
      border: 1.5px solid #cbd5e1;
      border-right: none;
      border-radius: 10px 0 0 10px;
      color: #64748b;
      padding-left: 14px;
      padding-right: 12px;
    }
    .input-group .form-control {
      border-left: none;
      border-radius: 0 10px 10px 0;
    }
    .input-group:focus-within .input-group-text {
      border-color: #3b82f6;
      color: #2563eb;
    }
    .btn-primary-custom {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border: none;
      font-weight: 600;
      font-size: 0.9rem;
      padding: 0.75rem 1rem;
      border-radius: 10px;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-primary-custom:hover {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      transform: translateY(-1.5px);
      box-shadow: 0 6px 18px rgba(37, 99, 235, 0.36);
    }
    .btn-primary-custom:active {
      transform: translateY(0);
    }
  </style>
</head>
<body>

<div class="login-card">
  <div class="login-logo-wrap">
    <img src="{{ asset('assets/images/logo-dharma.png') }}" alt="Logo" class="login-logo" onerror="this.style.display='none'">
  </div>
  <h1 class="login-title">Learning &amp; Development</h1>
  <p class="login-subtitle">Silakan pilih metode login sesuai peran Anda</p>

  {{-- Pesan Sukses --}}
  @if(session('success'))
      <div class="alert alert-success text-center mb-3 py-2 small rounded-3">
          <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
      </div>
  @endif

  {{-- Pesan Error --}}
  @if(session('error'))
      <div class="alert alert-danger text-center mb-3 py-2 small rounded-3">
          <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
      </div>
  @endif

  {{-- Tab Navigation --}}
  <ul class="nav segmented-tabs mb-4" id="loginTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="im-tab" data-bs-toggle="pill" data-bs-target="#im-login" type="button" role="tab">
        <i class="bi bi-person-badge me-1.5"></i> Immediate Manager
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="dlc-tab" data-bs-toggle="pill" data-bs-target="#dlc-login" type="button" role="tab">
        <i class="bi bi-shield-lock me-1.5"></i> Admin DLC
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
