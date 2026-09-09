@extends('layouts.admindlc')

@section('title', 'Master Training | Learning & Development')

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
.badge-inhouse {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
.badge-outhouse {
    background-color: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-mortarboard" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Kurikulum Pelatihan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Daftar Training</h1>
                        <p class="text-muted mb-0 small">Katalog seluruh topik pelatihan, kode training, scope pelaksanaan (In House &amp; Out House), mandatory status, dan golongan.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" href="{{ route('training.create') }}">
                        <i class="bi bi-plus-lg"></i> Tambah Training
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

        {{-- Metric Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <a href="{{ route('training.index') }}" class="text-decoration-none">
                    <div class="stat-card {{ !request('scope') ? 'border-primary' : '' }}">
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Total Training Terdaftar</div>
                            <div class="fs-4 fw-bold text-dark">{{ $totalTrainings }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('training.index', ['scope' => 'In House']) }}" class="text-decoration-none">
                    <div class="stat-card {{ request('scope') == 'In House' ? 'border-info' : '' }}">
                        <div class="stat-icon bg-info-subtle text-info">
                            <i class="bi bi-building-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Scope: In House</div>
                            <div class="fs-4 fw-bold text-primary">{{ $inHouseCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('training.index', ['scope' => 'Out House']) }}" class="text-decoration-none">
                    <div class="stat-card {{ request('scope') == 'Out House' ? 'border-warning' : '' }}">
                        <div class="stat-icon bg-warning-subtle text-warning">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Scope: Out House</div>
                            <div class="fs-4 fw-bold text-warning">{{ $outHouseCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <section class="panel">
            <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-table text-primary me-2" aria-hidden="true"></i>
                        <span>Training List</span>
                    </h2>
                    <p class="text-muted mb-0 small">
                        Daftar silabus pelatihan yang tersedia di sistem
                    </p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    {{-- Filter Scope Pill Buttons --}}
                    <div class="btn-group btn-group-sm" role="group" aria-label="Filter Scope">
                        <a href="{{ route('training.index') }}" class="btn {{ !request('scope') ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Semua
                        </a>
                        <a href="{{ route('training.index', ['scope' => 'In House']) }}" class="btn {{ request('scope') == 'In House' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            In House
                        </a>
                        <a href="{{ route('training.index', ['scope' => 'Out House']) }}" class="btn {{ request('scope') == 'Out House' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Out House
                        </a>
                    </div>

                    {{-- Search Form --}}
                    <form action="{{ route('training.index') }}" method="GET" class="d-flex gap-1" style="max-width: 320px;">
                        @if(request('scope'))
                            <input type="hidden" name="scope" value="{{ request('scope') }}">
                        @endif
                        <div class="input-group input-group-sm">
                            <div class="input-group input-group-sm" style="max-width: 280px;">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input class="form-control border-start-0 ps-0" type="search" name="search" placeholder="Cari kode, judul, atau jenis..." value="{{ request('search') }}" aria-label="Search training">
                            </div> 
                        </div>
                        @if(request('search'))
                            <a href="{{ route('training.index', request('scope') ? ['scope' => request('scope')] : []) }}" class="btn btn-outline-secondary btn-sm" title="Reset Cari">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary btn-sm px-2.5">Cari</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="trainingTable">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50px;" class="text-center">No</th>
                            <th scope="col" style="min-width: 120px;">Kode Training</th>
                            <th scope="col" style="min-width: 220px;">Judul Training</th>
                            <th scope="col" style="min-width: 120px;" class="text-center">Scope Training</th>
                            <th scope="col" style="min-width: 150px;">Jenis Training</th>
                            <th scope="col" style="min-width: 130px;">Mandatory</th>
                            <th scope="col" style="min-width: 120px;">Golongan</th>
                            <th scope="col" class="text-end" style="min-width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trainings as $index => $t)
                        <tr>
                            <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2.5 py-1">
                                    {{ $t->kode_training ?: '-' }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-dark">{{ $t->nama_training }}</strong>
                            </td>
                            <td class="text-center">
                                @if($t->scope_training == 'Out House')
                                    <span class="badge badge-outhouse rounded-pill px-2.5 py-1">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Out House
                                    </span>
                                @else
                                    <span class="badge badge-inhouse rounded-pill px-2.5 py-1">
                                        <i class="bi bi-building-check me-1"></i> In House
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1">{{ $t->jenis_training }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $t->mandatory_training ?: '-' }}</span>
                            </td>
                            <td>{{ $t->gol_training ?: '-' }}</td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('training.edit', $t->id_training) }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('training.destroy', $t->id_training) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus training ini?')">
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
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                Belum ada data training yang sesuai kriteria pencarian/filter.
                                @if(request('search') || request('scope'))
                                    <div class="mt-2">
                                        <a href="{{ route('training.index') }}" class="btn btn-outline-primary btn-sm">Reset Filter</a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-2 pt-2 border-top">
                <p class="text-muted small mb-0">Menampilkan <strong class="text-dark">{{ count($trainings) }}</strong> topik training</p>
                <p class="text-muted small mb-0">Total Keseluruhan: <strong class="text-dark">{{ $totalTrainings }}</strong> topik</p>
            </div>
        </section>
    </div>
</main>

@endsection
