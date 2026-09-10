@extends('layouts.admindlc')

@section('title', 'Daftar Penerima Email Immediate Manager | Dharma Learning Center')

@section('content')
<style>
/* ========== SMOOTH GLOBAL ENHANCEMENTS ========== */
/* .penerima-hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    margin-bottom: 1.5rem;
} */

.panel-smooth {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    padding: 1.5rem;
}

/* Metric Stats Cards */
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

/* Filter Search Bar */
.search-input-group {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.2s ease;
}
.search-input-group:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    background: #ffffff;
}
.search-input-group .input-group-text {
    background: transparent;
    border: none;
    color: #64748b;
    padding-left: 14px;
}
.search-input-group .form-control {
    background: transparent;
    border: none;
    font-size: 0.875rem;
    padding: 9px 12px;
}
.search-input-group .form-control:focus {
    box-shadow: none;
}

/* Table Styling */
.table-smooth-container {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    background: #ffffff;
}
.table-smooth {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}
.table-smooth thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 13px 16px;
    border-bottom: 1.5px solid #e2e8f0;
    border-top: none;
}
.table-smooth tbody td {
    padding: 13px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: 0.875rem;
    color: #1e293b;
    transition: background-color 0.15s ease;
}
.table-smooth tbody tr:hover td {
    background-color: #f8fafc;
}
.table-smooth tbody tr:last-child td {
    border-bottom: none;
}

/* Action Button */
.btn-action-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    transition: all 0.15s ease;
}
.btn-action-icon:hover {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
    transform: translateY(-1px);
}

.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e0f2fe;
    color: #0369a1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    flex-shrink: 0;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Hero Header --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-envelope-at"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">
                                Setting Notifikasi
                            </span>
                            <span class="text-muted small">Immediate Manager Contacts</span>
                        </div>
                        <h1 class="h4 mb-1 text-dark fw-bold">Daftar Penerima Email Immediate Manager</h1>
                        <p class="text-muted mb-0 small">Kelola data kontak email Immediate Manager dari member list untuk keperluan notifikasi pembukaan TNA dan persetujuan training.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('periode-tna') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-arrow-left"></i> Periode TNA
                    </a>
                    <a href="{{ route('body-email') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-card-text"></i> Atur Body Email
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-circle me-1"></i> Terjadi kesalahan input:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Metric Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Immediate Manager</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalManagers }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Kontak Email Terdaftar</div>
                        <div class="fs-4 fw-bold text-success">{{ $withEmailCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon {{ $missingEmailCount > 0 ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary' }}">
                        <i class="bi bi-envelope-slash-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Kontak Belum Terisi</div>
                        <div class="fs-4 fw-bold {{ $missingEmailCount > 0 ? 'text-warning' : 'text-secondary' }}">{{ $missingEmailCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Panel --}}
        <div class="panel-smooth">
            {{-- Toolbar: Search & Action Buttons --}}
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    {{-- Selector Entries --}}
                    <div class="table-entries-selector me-sm-2">
                        <span>Show</span>
                        <select class="form-select form-select-sm" data-table-entries="penerimaTable">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>

                    {{-- Search Form --}}
                    <form action="{{ route('penerima-email') }}" method="GET" class="d-flex gap-2" style="max-width: 360px;">
                        <div class="input-group search-input-group flex-grow-1">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari NPK, Nama, atau Email..." value="{{ request('search') }}" data-table-search="penerimaTable">
                        </div>
                        @if(request('search'))
                        <a href="{{ route('penerima-email') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center px-3" title="Reset Pencarian">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        @endif
                        <button type="submit" class="btn btn-primary btn-sm px-3">Cari</button>
                    </form>
                </div>

                {{-- Action Buttons: Export, Import, Template --}}
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('penerima-email.export') }}" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1" title="Export seluruh kontak manager ke CSV/Excel">
                        <i class="bi bi-file-earmark-excel"></i> Export
                    </a>
                    <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalImportEmail">
                        <i class="bi bi-file-earmark-arrow-up"></i> Import
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small" id="penerimaTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th style="width: 130px;">NPK</th>
                                <th>Nama Immediate Manager</th>
                                <!-- <th>Department / Divisi</th>
                                <th style="width: 100px;">Jabatan</th> -->
                                <th>Kontak (Email)</th>
                                <th style="width: 120px;" class="text-center">Status</th>
                                <th style="width: 80px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($managers as $index => $m)
                            @php
                                $initials = collect(explode(' ', $m->nama_staff))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
                                $dept = $m->department ? $m->department->nama_department : null;
                                $div = $m->divisi ? $m->divisi->nama_divisi : null;
                                $hasEmail = !empty($m->email);
                            @endphp
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                        {{ $m->npk_staff }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <!-- <div class="avatar-circle">
                                            {{ $initials }}
                                        </div> -->
                                        <div>
                                            <div class="fw-bold text-dark">{{ $m->nama_staff }}</div>
                                            <!-- <div class="text-muted small" style="font-size: 0.75rem;">ID: #{{ $m->id_staff }}</div> -->
                                        </div>
                                    </div>
                                </td>
                                <!-- <td>
                                    @if($dept)
                                        <div class="fw-medium text-dark">{{ $dept }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Divisi: {{ $div ?: '-' }}</div>
                                    @elseif($div)
                                        <div class="fw-medium text-dark">{{ $div }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Department: -</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td> -->
                                <!-- <td>
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                        {{ $m->levelJabatan ? $m->levelJabatan->kode_level_jabatan : '-' }}
                                    </span>
                                </td> -->
                                <td>
                                    @if($hasEmail)
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="mailto:{{ $m->email }}" class="text-primary text-decoration-none fw-medium d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-envelope text-primary"></i>
                                                <span>{{ $m->email }}</span>
                                            </a>
                                        </div>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                            <i class="bi bi-exclamation-circle me-1"></i> Belum Diisi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($hasEmail)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                            <i class="bi bi-check2 me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted border rounded-pill px-2.5 py-1">
                                            Kosong
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-action-icon" data-bs-toggle="modal" data-bs-target="#modalEditEmail{{ $m->id_staff }}" title="Edit Kontak Email">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                        <p class="mb-1 fw-semibold">Tidak ada data Immediate Manager ditemukan.</p>
                                        @if(request('search'))
                                            <p class="small text-muted mb-2">Kata kunci pencarian "{{ request('search') }}" tidak cocok dengan data manapun.</p>
                                            <a href="{{ route('penerima-email') }}" class="btn btn-outline-primary btn-sm">Reset Pencarian</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            <!-- </div> -->
            
            {{-- Table Pagination Footer --}}
            <div class="table-pagination-footer" data-table-pagination="penerimaTable">
                <p class="table-pagination-info"></p>
                <div class="pagination-container"></div>
            </div>

            <div class="text-end text-muted small mt-2">
                <span>Sinkronisasi otomatis dengan notifikasi pembukaan TNA</span>
            </div>
        </div>

    </div>
</main>

{{-- ==================== MODALS ==================== --}}

{{-- Modal Edit Kontak untuk Setiap Manager --}}
@foreach($managers as $m)
<div class="modal fade" id="modalEditEmail{{ $m->id_staff }}" tabindex="-1" aria-labelledby="modalEditEmailLabel{{ $m->id_staff }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('penerima-email.update', $m->id_staff) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h5 class="modal-title fs-6 fw-bold text-dark" id="modalEditEmailLabel{{ $m->id_staff }}">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Kontak Immediate Manager
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">NPK</label>
                        <input type="text" class="form-control bg-light" value="{{ $m->npk_staff }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control bg-light" value="{{ $m->nama_staff }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Department / Bagian</label>
                        <input type="text" class="form-control bg-light" value="{{ $m->department ? $m->department->nama_department : ($m->divisi ? $m->divisi->nama_divisi : '-') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email_{{ $m->id_staff }}" class="form-label text-dark small fw-bold">
                            Kontak (Email) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email_{{ $m->id_staff }}" class="form-control" placeholder="contoh: {{ strtolower(str_replace(' ', '.', explode(' ', trim($m->nama_staff))[0])) }}@dharmap.com" value="{{ old('email', $m->email) }}">
                        </div>
                        <small class="text-muted d-block mt-1">Kosongkan jika ingin menghapus kontak email untuk manager ini.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-save me-1"></i> Simpan Kontak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Import Kontak Email --}}
<div class="modal fade" id="modalImportEmail" tabindex="-1" aria-labelledby="modalImportEmailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('penerima-email.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fs-6 fw-bold text-dark" id="modalImportEmailLabel">
                        <i class="bi bi-file-earmark-arrow-up text-primary me-2"></i>Import Kontak Email Manager
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 d-flex gap-2.5 mb-3" style="background-color: #eff6ff; color: #1e40af;">
                        <i class="bi bi-info-circle-fill fs-5 flex-shrink-0"></i>
                        <div class="small">
                            <strong>Petunjuk Import:</strong>
                            <ul class="mb-1 ps-3 mt-1">
                                <li>Format berkas yang didukung: <strong>.CSV</strong> atau <strong>.TXT</strong>.</li>
                                <li>Pastikan kolom <strong>NPK</strong> sesuai dengan data member list di sistem.</li>
                                <li>Email akan otomatis diperbarui berdasarkan kecocokan NPK.</li>
                            </ul>
                            <a href="{{ route('penerima-email.template') }}" class="btn btn-light btn-sm border text-primary fw-semibold mt-1">
                                <i class="bi bi-download me-1"></i> Unduh Format Template CSV
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="importFile" class="form-label text-dark small fw-bold">Pilih Berkas File (CSV / TXT) <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="importFile" class="form-control" accept=".csv, .txt, text/csv, application/vnd.ms-excel" required>
                        <small class="text-muted d-block mt-1">Maksimal ukuran berkas 5MB.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-upload me-1"></i> Unggah &amp; Perbarui Kontak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection