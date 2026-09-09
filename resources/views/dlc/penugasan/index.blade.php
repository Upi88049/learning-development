@extends('layouts.admindlc')

@section('content')
<style>
/* ========== SMOOTH GLOBAL ENHANCEMENTS ========== */
/* .admin-content {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
} */

/* Page Hero Header */
/* .penugasan-hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    margin-bottom: 1.5rem;
} */

/* Main Container Panel */
.panel-smooth {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    padding: 1.5rem;
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
/* .table-smooth-container {
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
    padding: 14px 16px;
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
} */

/* Pill Badges */
.badge-pill-soft {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1.2;
}
.badge-sent-im {
    background-color: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

/* Action Icon Buttons */
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
    background: #f1f5f9;
    color: #1e293b;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}
.btn-action-view:hover {
    background: #f0f9ff;
    color: #0284c7;
    border-color: #bae6fd;
}
.btn-action-download:hover {
    background: #f0fdf4;
    color: #16a34a;
    border-color: #bbf7d0;
}
.btn-action-edit:hover {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
}
.btn-action-delete:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}

/* Send to IM Button */
.btn-send-im {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-weight: 600;
    font-size: 0.78rem;
    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-send-im:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-1.5px);
    box-shadow: 0 5px 14px rgba(37, 99, 235, 0.35);
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Hero Header --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">DLC Administrator</span>
                            <span class="text-muted small">Form Penugasan</span>
                        </div>
                        <h1 class="h3 mb-1 text-dark fw-bold">Formulir Pendaftaran &amp; Penugasan Training</h1>
                        <p class="text-muted mb-0 small">Kelola, buka akses pengiriman ke Immediate Manager, dan unduh dokumen resmi Dharma Learning Center.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('penugasan.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2">
                        <i class="bi bi-plus-lg"></i> Buat Form Penugasan Baru
                    </a>
                </div>
            </div>
        </div>

        {{-- Feedback Alert --}}
        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show my-3 rounded-3 p-3 d-flex align-items-center gap-2" role="alert" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important;">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <span class="text-dark">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show my-3 rounded-3 p-3 d-flex align-items-center gap-2" role="alert" style="background-color: #fef2f2; border-left: 4px solid #ef4444 !important;">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <span class="text-dark">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Main Table Panel --}}
        <div class="panel-smooth">
            {{-- Filter & Search Bar --}}
            <form action="{{ route('penugasan.index') }}" method="GET" class="row g-3 align-items-end mb-4 pb-3 border-bottom">
                <div class="col-md-7">
                    <label class="form-label small fw-bold text-muted mb-1">Cari Formulir Training</label>
                    <div class="input-group search-input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Ketik nama training, divisi, atau tempat pelatihan...">
                    </div>
                </div>

                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 flex-grow-1 py-2 d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-funnel"></i> Filter Data
                    </button>
                    @if($search)
                    <a href="{{ route('penugasan.index') }}" class="btn btn-outline-secondary rounded-3 px-3 d-inline-flex align-items-center gap-1" title="Reset Pencarian">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="penugasanTable" data-searchable-table>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50px;" class="text-center">No.</th>
                            <th scope="col">Nama Training</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">SubCo / Divisi</th>
                            <th scope="col">Peserta</th>
                            <th scope="col">Biaya Investasi</th>
                            <th scope="col">Tempat &amp; Tanggal</th>
                            <th scope="col" style="min-width: 140px;">Status Kirim IM</th>
                            <th scope="col">Dibuat</th>
                            <th scope="col" class="text-end" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penugasanList as $index => $item)
                        @php
                            $peserta = $item->peserta;
                        @endphp
                        <tr>
                            <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark d-block" style="font-size: 0.9rem;">{{ $item->nama_training }}</strong>
                                <span class="badge bg-light text-secondary border font-monospace mt-1" style="font-size: 0.72rem;">{{ $item->no_form }}</span>
                                @if($item->requestOuthouse)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace ms-1" style="font-size: 0.72rem;" title="Terkait Request OH">
                                        <i class="bi bi-link-45deg"></i> {{ $item->requestOuthouse->no_request }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1" style="font-size: 0.75rem;">{{ $item->jenis_training }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->sub_co }}</div>
                                <small class="text-muted">Div: {{ $item->divisi ?: '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                    {{ $item->jumlah_peserta }} Peserta
                                </span>
                                @if(!empty($peserta))
                                    <div class="mt-1 text-muted" style="max-width: 180px; font-size: 0.72rem;">
                                        {{ implode(', ', array_slice(array_column($peserta, 'nama'), 0, 2)) }}
                                        @if(count($peserta) > 2) ... @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success d-block" style="font-size: 0.88rem;">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</strong>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">(@ Rp {{ number_format($item->biaya_per_peserta, 0, ',', '.') }})</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->tempat_tanggal_training ?: '-' }}</small>
                            </td>
                            <td>
                                @if($item->is_sent)
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-pill-soft badge-sent-im">
                                            <i class="bi bi-check2-all"></i> Terkirim ke IM
                                        </span>
                                        <small class="text-muted" style="font-size: 0.72rem;">
                                            <i class="bi bi-clock-history me-1"></i>{{ $item->sent_at ? $item->sent_at->format('d/m/Y H:i') : '' }}
                                        </small>
                                        <form action="{{ route('penugasan.cancelSendToIm', $item->id_penugasan) }}" method="POST"
                                            onsubmit="return confirm('Batalkan akses download dokumen ini untuk Immediate Manager?');">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 text-danger small text-decoration-none" style="font-size: 0.72rem;" title="Batalkan Pengiriman">
                                                <i class="bi bi-x-circle me-1"></i>Batal Kirim
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="d-flex flex-column gap-1">
                                        <form action="{{ route('penugasan.sendToIm', $item->id_penugasan) }}" method="POST"
                                            onsubmit="return confirm('Kirim dokumen formulir ini ke akun Immediate Manager? Immediate Manager akan dapat mengunduh dokumen.');">
                                            @csrf
                                            <button type="submit" class="btn-send-im" title="Kirim / Buka Akses Dokumen ke Immediate Manager">
                                                <i class="bi bi-send-fill"></i> Kirim ke IM
                                            </button>
                                        </form>
                                        <small class="text-muted" style="font-size: 0.72rem;">
                                            <i class="bi bi-hourglass-split me-1"></i>Belum dikirim
                                        </small>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</small>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1" role="group">
                                    <a href="{{ route('penugasan.previewPdf', $item->id_penugasan) }}" target="_blank" class="btn-action-icon btn-action-view" title="Preview PDF">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('penugasan.downloadPdf', $item->id_penugasan) }}" class="btn-action-icon btn-action-download" title="Download PDF">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('penugasan.edit', $item->id_penugasan) }}" class="btn-action-icon btn-action-edit" title="Edit Form">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action-icon btn-action-delete" data-bs-toggle="modal" data-bs-target="#modalDeletePenugasan{{ $item->id_penugasan }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- Modal Delete Penugasan --}}
                                <div class="modal fade text-start" id="modalDeletePenugasan{{ $item->id_penugasan }}" tabindex="-1" aria-labelledby="modalDeletePenugasanLabel{{ $item->id_penugasan }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
                                            <form action="{{ route('penugasan.destroy', $item->id_penugasan) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header bg-danger bg-opacity-10 text-danger">
                                                    <h5 class="modal-title fs-6 fw-bold" id="modalDeletePenugasanLabel{{ $item->id_penugasan }}">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Dokumen
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    Apakah Anda yakin ingin menghapus Formulir Penugasan Training <strong>{{ $item->nama_training }}</strong>?
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger btn-sm px-4 rounded-3">
                                                        <i class="bi bi-trash me-1"></i> Ya, Hapus Dokumen
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                                <i class="bi bi-file-earmark-x fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                <span>Belum ada formulir pendaftaran &amp; penugasan training yang dibuat.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                <span>Total <strong>{{ count($penugasanList) }}</strong> formulir penugasan training tercatat.</span>
                <span class="badge bg-light text-secondary border">Form 013/WI- Official Document</span>
            </div>
        </div>
    </div>
</main>
@endsection
