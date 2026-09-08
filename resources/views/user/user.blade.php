@extends('layouts.admin')

@section('title', 'Dashboard | Learning & Development')

@section('content')
<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">

    {{-- Hero Header Card --}}
    <div class="hero-header-card mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem;">
            <i class="bi bi-people" aria-hidden="true"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">TNA Portal</span>
              <span class="text-muted small">Immediate Manager</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-dark">{{ $departmentName }}</h1>
            <p class="text-muted mb-0 small">Training Need Analysis &bull; PT Dharma Polimetal Tbk</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ========== KARTU METRIK UTAMA ========== -->
    <section class="row g-3" aria-label="User summary">
      <!-- ==========TOTAL PERMINTAAN TRAINING IN HOUSE========== -->
      <div class="col-12 col-sm-6 col-xl-4">
        <a href="{{ route('users.permintaan') }}" class="metric-card metric-warning metric-card-link">
          <div class="metric-top">
            <span class="metric-label">Total Permintaan Training In House</span>
            <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalPermintaan }}</div>
          <div class="metric-meta">
            <span>Klik untuk melihat rincian</span>
          </div>
        </a>
      </div>
      
      <!-- ==========TOTAL TERLAKSANA========== -->
      <div class="col-12 col-sm-6 col-xl-4">
        <a href="{{ route('users.terlaksana') }}" class="metric-card metric-success metric-card-link">
          <div class="metric-top">
            <span class="metric-label">Total Terlaksana</span>
            <span class="metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalTerlaksana }}</div>
          <div class="metric-meta">
            <span>Klik untuk melihat rincian</span>
          </div>
        </a>
      </div>
      
      <!-- ==========TOTAL KETIDAKHADIRAN TRAINING========== -->
      <div class="col-12 col-sm-6 col-xl-4">
        <a href="{{ route('users.tidakhadir') }}" class="metric-card metric-danger metric-card-link">
          <div class="metric-top">
            <span class="metric-label">Total Ketidakhadiran Training</span>
            <span class="metric-icon"><i class="bi bi-slash-circle" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalKetidakhadiran }}</div>
          <div class="metric-meta">
            <span>Klik untuk melihat rincian</span>
          </div>
        </a>
      </div>
    </section>

    <!-- ========== DAFTAR STAFF ========== -->
    <section class="panel mt-4">
      <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
        <div>
          <h2 class="h5 mb-1 section-title">
            <i class="bi bi-person-lines-fill text-primary me-1" aria-hidden="true"></i>
            <span>Daftar Anggota Staff</span>
          </h2>
          <p class="text-muted mb-0 small">Bawahan langsung dalam lingkup departemen <strong>{{ $departmentName }}</strong></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <div class="input-group input-group-sm" style="max-width: 280px;">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input class="form-control border-start-0 ps-0" type="search" placeholder="Cari nama / NPK..." data-table-search="usersTable" aria-label="Search staff">
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="usersTable" data-searchable-table>
          <thead>
            <tr>
              <th scope="col" style="min-width: 100px;">NPK</th>
              <th scope="col" style="min-width: 180px;">Nama Peserta</th>
              <th class="text-center" scope="col" style="min-width: 80px;">Umur</th>
              <th class="text-center" scope="col" style="min-width: 140px;">Department</th>
              <th class="text-center" scope="col" style="min-width: 120px;">Level Jabatan</th>
              <th scope="col" class="text-end" style="min-width: 100px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($staff as $s)
            <tr>
              <td>
                <span class="badge bg-light text-dark border font-monospace">{{ $s->npk_staff }}</span>
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <span class="fw-semibold text-dark">{{ $s->nama_staff }}</span>
                  @if(session('user') && session('user')->id_staff == $s->id_staff)
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 0.7rem;">Saya</span>
                  @endif
                </div>
              </td>
              <td class="text-center">
                <span class="badge bg-light text-secondary border">{{ $s->umur ?: '-' }}</span>
              </td>
              <td class="text-center">
                <span class="badge bg-secondary">{{ $s->department ? $s->department->nama_department : '-' }}</span>
              </td>
              <td class="text-center">
                <span class="badge bg-info text-dark">{{ $s->levelJabatan ? $s->levelJabatan->kode_level_jabatan : '-' }}</span>
              </td>
              <td class="text-end">
                <a class="btn btn-outline-primary btn-sm px-3" href="{{ route('users.detail', $s->id_staff) }}">
                  <i class="bi bi-eye me-1"></i> Detail
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                Belum ada data staff yang terdaftar.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
</main>
@endsection