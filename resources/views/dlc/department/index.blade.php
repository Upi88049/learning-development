@extends('layouts.admindlc')

@section('title', 'Master Department | Dharma Learning Center')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-building" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Struktur Organisasi</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Daftar Department</h1>
                        <p class="text-muted mb-0 small">Struktur department perusahaan dan pemetaannya ke divisi terkait.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm" href="{{ route('department.create') }}">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Department
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <section class="panel">
            <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-table text-primary me-1" aria-hidden="true"></i>
                        <span>Department List</span>
                    </h2>
                    <p class="text-muted mb-0 small">Daftar unit department dan jumlah karyawan yang bernaung</p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="table-entries-selector me-sm-2">
                        <span>Show</span>
                        <select class="form-select form-select-sm" data-table-entries="deptTable">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 260px;">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input class="form-control border-start-0 ps-0" type="search" placeholder="Cari department..." data-table-search="deptTable" aria-label="Search department">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="deptTable" data-searchable-table>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 60px;">No</th>
                            <th scope="col" style="min-width: 220px;">Nama Department</th>
                            <th scope="col" style="min-width: 180px;">Divisi</th>
                            <th scope="col" class="text-center" style="min-width: 130px;">Jumlah Staff</th>
                            <th scope="col" class="text-end" style="min-width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $index => $d)
                        <tr>
                            <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark">{{ $d->nama_department }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $d->divisi ? $d->divisi->nama_divisi : '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $d->staff_count }} Staff</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-primary btn-sm me-1" href="{{ route('department.edit', $d->id_department) }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('department.destroy', $d->id_department) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus department ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                Belum ada data department.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination-footer" data-table-pagination="deptTable">
                <p class="table-pagination-info"></p>
                <div class="pagination-container"></div>
            </div>
        </section>
    </div>
</main>

@endsection
