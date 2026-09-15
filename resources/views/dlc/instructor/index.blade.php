@extends('layouts.admindlc')

@section('title', 'Master Instruktur | Dharma Learning Center')

@section('content')
<style>
.stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
}
.stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-person-video3" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Instruktur Pelatihan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Master Instruktur</h1>
                        <p class="text-muted mb-0 small">Katalog data instruktur, fasilitator, dan trainer pelatihan internal maupun eksternal.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" href="{{ route('instructor.create') }}">
                        <i class="bi bi-plus-lg"></i> Tambah Instruktur
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Alert --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3 border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3 border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Metric Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-card border-primary">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Instruktur Terdaftar</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalInstructors }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Panel --}}
        <section class="panel">
            <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-table text-primary me-1" aria-hidden="true"></i>
                        <span>Instructor List</span>
                    </h2>
                    <p class="text-muted mb-0 small">Daftar kode dan nama instruktur pengampu materi pelatihan.</p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="table-entries-selector me-sm-2">
                        <span>Show</span>
                        <select class="form-select form-select-sm" data-table-entries="instructorTable">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 260px;">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input class="form-control border-start-0 ps-0" type="search" placeholder="Cari instruktur..." data-table-search="instructorTable" aria-label="Search instructor">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="instructorTable" data-searchable-table>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 60px;" class="text-center">No</th>
                            <th scope="col" style="min-width: 180px;">Instructor Code</th>
                            <th scope="col" style="min-width: 320px;">Instructor Name</th>
                            <th scope="col" class="text-end" style="min-width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($instructors as $index => $inst)
                        <tr>
                            <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2.5 py-1.5 fs-7">
                                    <i class="bi bi-person-vcard text-primary me-1"></i>{{ $inst->instructor_code }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ $inst->instructor_name }}</strong>
                                @if($inst->specialization)
                                <small class="text-muted">
                                    <i class="bi bi-award me-1"></i>{{ $inst->specialization }}
                                </small>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-primary btn-sm me-1" href="{{ route('instructor.edit', $inst->id_instructor) }}" title="Edit Instruktur">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('instructor.destroy', $inst->id_instructor) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus instruktur {{ $inst->instructor_name }} ({{ $inst->instructor_code }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Instruktur">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                Belum ada data instruktur yang terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination-footer" data-table-pagination="instructorTable">
                <p class="table-pagination-info"></p>
                <div class="pagination-container"></div>
            </div>
        </section>
    </div>
</main>

@endsection
