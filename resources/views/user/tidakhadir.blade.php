@extends('layouts.admin')

@section('title', 'Ketidakhadiran Training | Learning & Development')

@section('content')
<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
      <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-slash-circle" aria-hidden="true"></i></span>
        <div>
          <p class="eyebrow mb-1">TNA</p>
          <h1 class="h3 mb-1">Ketidakhadiran Training</h1>
          <p class="text-muted mb-0">Daftar ketidakhadiran training (diurutkan berdasarkan Jenis Training)</p>
        </div>
      </div>
      <div class="heading-actions">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('users') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Staff List</a>
      </div>
    </div>

    <section class="panel mt-3">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Ketidakhadiran Training</span></h2>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <input class="form-control form-control-sm table-search" type="search" placeholder="Search Training" data-table-search="usersTable" aria-label="Search training">
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
            <thead>
                <tr>
                    <th scope="col">Jenis Training</th>
                    <th scope="col">Judul Training</th>
                    <th style="text-align: right;" scope="col">Total Staff</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $t)
                <tr>
                    <td>
                        <span class="badge bg-danger">{{ $t['jenis_training'] }}</span>
                    </td>
                    <td><strong>{{ $t['nama_training'] }}</strong></td>
                    <td class="text-end">
                        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $t['jumlah'] }}</span>
                        @if(!empty($t['staff_list']))
                        <button class="btn btn-sm btn-outline-secondary ms-2 py-0 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#staff-detail-th-{{ $t['id_training'] }}" aria-expanded="false" title="Lihat Staff">
                            <i class="bi bi-people"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @if(!empty($t['staff_list']))
                <tr class="collapse" id="staff-detail-th-{{ $t['id_training'] }}">
                    <td colspan="3" class="bg-light p-3">
                        <small class="fw-bold text-secondary d-block mb-1"><i class="bi bi-people me-1"></i>Daftar Staff Yang Tidak Hadir:</small>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($t['staff_list'] as $s)
                            <span class="badge bg-white text-dark border py-2 px-3"><i class="bi bi-person me-1"></i>{{ $s['npk_staff'] }} - {{ $s['nama_staff'] }}</span>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">Tidak ada data ketidakhadiran training.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
      </div>
      <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
        <p class="text-muted small mb-0">Total {{ count($trainings) }} topik training ditemukan</p>
      </div>
    </section>
  </div>
</main>
@endsection
