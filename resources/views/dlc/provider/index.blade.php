@extends('layouts.admindlc')

@section('title', 'Master Provider | Dharma Learning Center')

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
.badge-external {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
.badge-internal {
    background-color: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.badge-konsultan {
    background-color: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
.badge-sertifikasi {
    background-color: #f3e8ff;
    color: #7e22ce;
    border: 1px solid #e9d5ff;
}
.badge-lainnya {
    background-color: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-building-gear" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Penyedia Pelatihan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Master Provider</h1>
                        <p class="text-muted mb-0 small">Daftar lembaga penyelenggara pelatihan, vendor eksternal, konsultan, dan unit internal DLC.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" href="{{ route('provider.create') }}">
                        <i class="bi bi-plus-lg"></i> Tambah Provider
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
        <!-- <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <a href="{{ route('provider.index') }}" class="text-decoration-none">
                    <div class="stat-card {{ !request('type') ? 'border-primary' : '' }}">
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Total Provider Terdaftar</div>
                            <div class="fs-4 fw-bold text-dark">{{ $totalProviders }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('provider.index', ['type' => 'External']) }}" class="text-decoration-none">
                    <div class="stat-card {{ request('type') == 'External' ? 'border-info' : '' }}">
                        <div class="stat-icon bg-info-subtle text-info">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Provider External</div>
                            <div class="fs-4 fw-bold text-dark">{{ $externalCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('provider.index', ['type' => 'Internal']) }}" class="text-decoration-none">
                    <div class="stat-card {{ request('type') == 'Internal' ? 'border-success' : '' }}">
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="bi bi-house-door"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Provider Internal</div>
                            <div class="fs-4 fw-bold text-dark">{{ $internalCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div> -->

        {{-- Main Table Panel --}}
        <section class="panel">
            <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-table text-primary me-1" aria-hidden="true"></i>
                        <span>Provider List</span>
                    </h2>
                    <p class="text-muted mb-0 small">Katalog kontak person, nomor telepon, dan email penyedia pelatihan.</p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="table-entries-selector me-sm-2">
                        <span>Show</span>
                        <select class="form-select form-select-sm" data-table-entries="providerTable">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 260px;">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input class="form-control border-start-0 ps-0" type="search" placeholder="Cari provider / PIC..." data-table-search="providerTable" aria-label="Search provider">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="providerTable" data-searchable-table>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50px;" class="text-center">No</th>
                            <th scope="col" style="min-width: 130px;">Provider Code</th>
                            <th scope="col" style="min-width: 230px;">Provider Name</th>
                            <th scope="col" style="min-width: 140px;">Provider Type</th>
                            <th scope="col" style="min-width: 180px;">Person in Charge (PIC)</th>
                            <th scope="col" style="min-width: 140px;">Phone</th>
                            <th scope="col" style="min-width: 180px;">Email</th>
                            <th scope="col" class="text-end" style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($providers as $index => $p)
                        <tr>
                            <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2 py-1">{{ $p->provider_code }}</span>
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ $p->provider_name }}</strong>
                                @if($p->address)
                                <small class="text-muted d-block text-truncate" style="max-width: 250px;" title="{{ $p->address }}">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $p->address }}
                                </small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $typeClass = match(strtolower($p->provider_type)) {
                                        'external' => 'badge-external',
                                        'internal' => 'badge-internal',
                                        'konsultan' => 'badge-konsultan',
                                        'lembaga sertifikasi' => 'badge-sertifikasi',
                                        default => 'badge-lainnya',
                                    };
                                @endphp
                                <span class="badge {{ $typeClass }} px-2.5 py-1">
                                    {{ $p->provider_type }}
                                </span>
                            </td>
                            <td>
                                @if($p->pic)
                                <div class="d-flex align-items-center gap-1.5">
                                    <i class="bi bi-person text-secondary"></i>
                                    <span class="text-dark">{{ $p->pic }}</span>
                                </div>
                                @else
                                <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->phone)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $p->phone) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-telephone text-primary small"></i>
                                    <span>{{ $p->phone }}</span>
                                </a>
                                @else
                                <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->email)
                                <a href="mailto:{{ $p->email }}" class="text-decoration-none text-primary d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-envelope small"></i>
                                    <span class="text-truncate" style="max-width: 170px;" title="{{ $p->email }}">{{ $p->email }}</span>
                                </a>
                                @else
                                <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-primary btn-sm me-1" href="{{ route('provider.edit', $p->id_provider) }}" title="Edit Provider">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('provider.destroy', $p->id_provider) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus provider {{ $p->provider_name }} ({{ $p->provider_code }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Provider">
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
                                Belum ada data provider yang terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination-footer" data-table-pagination="providerTable">
                <p class="table-pagination-info"></p>
                <div class="pagination-container"></div>
            </div>
        </section>
    </div>
</main>

@endsection
