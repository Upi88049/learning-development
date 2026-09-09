@extends('layouts.admindlc')

@section('title', 'Master Divisi | Dharma Learning Center')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Divisi Perusahaan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Daftar Divisi</h1>
                        <p class="text-muted mb-0 small">Kelola seluruh divisi yang terdaftar beserta jumlah departemen di bawahnya.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2" href="{{ route('divisi.create') }}">
                        <i class="bi bi-plus-lg"></i> Tambah Divisi
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

        <section class="panel mt-3">
            <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-table me-2 text-primary" aria-hidden="true"></i><span>Data Divisi</span>
                    </h2>
                    <p class="text-muted mb-0 small">Daftar seluruh divisi operasional perusahaan.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <div class="input-group input-group-sm" style="max-width: 280px;">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input class="form-control border-start-0 ps-0" type="search" placeholder="Cari divisi..." data-table-search="divisiTable" aria-label="Search divisi">
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="divisiTable" data-searchable-table>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 60px;" class="text-center">No</th>
                            <th scope="col">Nama Divisi</th>
                            <th scope="col" class="text-center" style="width: 220px;">Jumlah Departemen</th>
                            <th scope="col" class="text-end" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($divisi as $index => $d)
                        <tr>
                            <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="p-2 rounded-2 bg-light text-primary"><i class="bi bi-diagram-3"></i></span>
                                    <strong class="text-dark">{{ $d->nama_divisi }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 rounded-pill fw-semibold">
                                    <i class="bi bi-building me-1"></i>{{ $d->departments_count }} Departemen
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-primary btn-sm me-1" href="{{ route('divisi.edit', $d->id_divisi) }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('divisi.destroy', $d->id_divisi) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">
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
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada data divisi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
                <p class="text-muted small mb-0">Total <strong class="text-dark">{{ count($divisi) }}</strong> divisi terdaftar</p>
            </div>
        </section>
    </div>
</main>

@endsection
