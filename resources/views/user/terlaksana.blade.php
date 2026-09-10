@extends('layouts.admin')

@section('title', 'Training Terlaksana | Dharma Learning Center')

@section('content')
<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">

    {{-- Hero Header Card --}}
    <div class="hero-header-card mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="page-icon bg-success bg-opacity-10 text-success rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem;">
            <i class="bi bi-check2-circle" aria-hidden="true"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1">TNA Monitoring</span>
              <span class="text-muted small">Training Selesai</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-dark">Total Training Terlaksana</h1>
            <p class="text-muted mb-0 small">Daftar program training yang telah selesai dilaksanakan oleh staff (diurutkan berdasarkan Jenis Training)</p>
          </div>
        </div>
        <div class="d-flex gap-2">
          <a class="btn btn-outline-secondary btn-sm" href="{{ route('users') }}">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Staff List
          </a>
        </div>
      </div>
    </div>

    <section class="panel">
      <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
        <div>
          <h2 class="h5 mb-1 section-title">
            <i class="bi bi-table text-primary me-1" aria-hidden="true"></i>
            <span>Daftar Training Yang Telah Terlaksana</span>
          </h2>
          <p class="text-muted mb-0 small">Rincian topik pelatihan yang sudah terlaksana beserta daftar staff yang hadir</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
          {{-- Selector Entries --}}
          <div class="table-entries-selector me-sm-2">
            <span>Show</span>
            <select class="form-select form-select-sm" data-table-entries="terlaksanaTable">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
            </select>
            <span>entries</span>
          </div>

          <div class="input-group input-group-sm" style="max-width: 280px;">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input class="form-control border-start-0 ps-0" type="search" placeholder="Cari Training..." data-table-search="terlaksanaTable" aria-label="Search training">
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="terlaksanaTable" data-searchable-table>
          <thead>
            <tr>
              <th scope="col" style="min-width: 180px;">Jenis Training</th>
              <th scope="col" style="min-width: 280px;">Judul Training</th>
              <th style="text-align: right; min-width: 140px;" scope="col">Total Staff</th>
            </tr>
          </thead>
          <tbody>
            @forelse($trainings as $t)
            <tr>
              <td>
                <span class="badge bg-success">{{ $t['jenis_training'] }}</span>
              </td>
              <td>
                <strong class="text-dark">{{ $t['nama_training'] }}</strong>
              </td>
              <td class="text-end">
                <span class="badge bg-primary px-3 py-1.5">{{ $t['jumlah'] }} Staff</span>
                @if(!empty($t['staff_list']))
                <button class="btn btn-sm btn-outline-secondary ms-2 py-1 px-2.5" type="button" data-bs-toggle="collapse" data-bs-target="#staff-detail-t-{{ $t['id_training'] }}" aria-expanded="false" title="Lihat rincian staff">
                  <i class="bi bi-people me-1"></i> Rincian
                </button>
                @endif
              </td>
            </tr>
            @if(!empty($t['staff_list']))
            <tr class="collapse" id="staff-detail-t-{{ $t['id_training'] }}">
              <td colspan="3" class="bg-light bg-opacity-50 p-3 border-top-0">
                <div class="p-3 bg-white border rounded-3 shadow-xs">
                  <small class="fw-bold text-secondary d-flex align-items-center gap-1 mb-2">
                    <i class="bi bi-people text-success"></i> Daftar Staff Yang Telah Mengikuti:
                  </small>
                  <div class="d-flex flex-wrap gap-2">
                    @foreach($t['staff_list'] as $s)
                    <span class="badge bg-light text-dark border py-1.5 px-3">
                      <i class="bi bi-check-circle-fill text-success me-1"></i>{{ $s['npk_staff'] }} &bull; {{ $s['nama_staff'] }}
                    </span>
                    @endforeach
                  </div>
                </div>
              </td>
            </tr>
            @endif
            @empty
            <tr>
              <td colspan="3" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                Belum ada data training yang terlaksana.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Table Pagination Footer --}}
      <div class="table-pagination-footer" data-table-pagination="terlaksanaTable">
        <p class="table-pagination-info"></p>
        <div class="pagination-container"></div>
      </div>
    </section>
  </div>
</main>
@endsection
