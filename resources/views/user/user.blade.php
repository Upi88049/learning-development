@extends('layouts.admin')

@section('title', 'Dashboard | Learning & Development')

@section('content')
<style>
.metric-card-link {
  display: block;
  width: 100%;
  text-decoration: none;
  color: inherit;
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
}

.metric-card-link:hover,
.metric-card-link:focus {
  text-decoration: none;
  color: inherit;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}

.metric-card-link:active {
  transform: translateY(0);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.metric-card-link:focus-visible {
  box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.35);
}

.accordion-button:not(.collapsed) {
  background-color: #e7f1ff;
  color: #0c63e4;
}
</style>

<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
      <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <div>
          <p class="eyebrow mb-1">TNA</p>
          <h1 class="h3 mb-1">{{ $departmentName }}</h1>
          <p class="text-muted mb-0">Training Need Analysis<br>PT Dharma Polimetal Tbk</p>
        </div>
      </div>
    </div>

    <!-- ========== KARTU METRIK UTAMA ========== -->
    <section class="row g-3 mt-1" aria-label="User summary">
      <!-- ==========TOTAL PERMINTAAN TRAINING IN HOUSE========== -->
      <div class="col-12 col-sm-6 col-xl-4">
        <a href="{{ route('users.permintaan') }}" class="metric-card metric-warning metric-card-link">
          <div class="metric-top">
            <span class="metric-label">Total Permintaan Training In House</span>
            <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalPermintaan }}</div>
          <div class="metric-meta">
            <span>Klik untuk melihat detail</span>
          </div>
        </a>
      </div>
      <!-- ==========TOTAL PERMINTAAN TRAINING IN HOUSE========== -->
      
      <!-- ==========TOTAL TERLAKSANA========== -->
      <div class="col-12 col-sm-6 col-xl-4">
        <a href="{{ route('users.terlaksana') }}" class="metric-card metric-success metric-card-link">
          <div class="metric-top">
            <span class="metric-label">Total Terlaksana</span>
            <span class="metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalTerlaksana }}</div>
          <div class="metric-meta">
            <span>Klik untuk melihat detail</span>
          </div>
        </a>
      </div>
      <!-- ==========TOTAL TERLAKSANA========== -->
      
      <!-- ==========TOTAL KETIDAKHADIRAN TRAINING========== -->
      <div class="col-12 col-sm-6 col-xl-4">
        <a href="{{ route('users.tidakhadir') }}" class="metric-card metric-danger metric-card-link">
          <div class="metric-top">
            <span class="metric-label">Total Ketidakhadiran Training</span>
            <span class="metric-icon"><i class="bi bi-slash-circle" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalKetidakhadiran }}</div>
          <div class="metric-meta">
            <span>Klik untuk melihat detail</span>
          </div>
        </a>
      </div>
      <!-- ==========TOTAL KETIDAKHADIRAN TRAINING========== -->
    </section>

    <!-- ========== DAFTAR STAFF (HANYA BAWAHAN & DIRI SENDIRI) ========== -->
    <section class="panel mt-4">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Staff List</span></h2>
          <p class="text-muted mb-0">{{ $departmentName }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <input class="form-control form-control-sm table-search" type="search" placeholder="Search staff" data-table-search="usersTable" aria-label="Search staff">
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
          <thead>
            <tr>
              <th scope="col">NPK</th>
              <th scope="col">Nama Peserta</th>
              <!-- <th class="text-center" scope="col">Tanggal Lahir</th> -->
              <th class="text-center" scope="col">Umur</th>
              <th class="text-center" scope="col">Department</th>
              <th class="text-center" scope="col">Level Jabatan</th>
              <th scope="col" class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($staff as $s)
            <tr>
              <td>{{ $s->npk_staff }}</td>
              <td>
                  <div>
                    <p class="fw-semibold mb-0">{{ $s->nama_staff }}</p>
                    @if(session('user') && session('user')->id_staff == $s->id_staff)
                      <span class="badge bg-info text-dark">Saya</span>
                    @endif
                  </div>
              </td>
              <!-- <td class="text-center">{{ $s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '-' }}</td> -->
              <td class="text-center">{{ $s->umur }}</td>
              <td class="text-center">{{ $s->department ? $s->department->nama_department : '-' }}</td>
              <td class="text-center">{{ $s->levelJabatan ? $s->levelJabatan->kode_level_jabatan : '-' }}</td>
              <td class="text-end"><a class="btn btn-light btn-sm" href="{{ route('users.detail', $s->id_staff) }}">View</a></td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada data staff.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
</main>
@endsection