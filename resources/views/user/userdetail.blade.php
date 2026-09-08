@extends('layouts.admin')

@section('title', 'Detail Training Staff | Learning & Development')

@section('content')
<style>
/* ========== SMOOTH GLOBAL ENHANCEMENTS ========== */
.dashboard-content {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Page Hero Card */
.profile-hero-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    margin-bottom: 1.5rem;
    transition: all 0.2s ease;
}
.staff-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 4px 12px;
    font-size: 0.8rem;
    color: #475569;
    font-weight: 500;
}
.staff-meta-pill strong {
    color: #1e293b;
}

/* Legend / Keterangan Card */
.legend-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}
.legend-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 10px;
}
.legend-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 14px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    font-size: 0.8rem;
    font-weight: 500;
    color: #334155;
    transition: all 0.15s ease;
}
.legend-chip:hover {
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Training Mini Cards (Smooth & Modern) */
.mini-card {
    border-radius: 14px;
    padding: 14px;
    border: 1px solid transparent;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.mini-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
}

.card-green {
    background: linear-gradient(145deg, #059669 0%, #047857 100%);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}
.card-green strong { color: #ffffff; font-weight: 700; }
.card-green .card-subtext { color: rgba(255, 255, 255, 0.85); font-size: 0.725rem; }

.card-yellow {
    background: linear-gradient(145deg, #d97706 0%, #b45309 100%);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}
.card-yellow strong { color: #ffffff; font-weight: 700; }
.card-yellow .card-subtext { color: rgba(255, 255, 255, 0.85); font-size: 0.725rem; }

.card-red {
    background: linear-gradient(145deg, #e11d48 0%, #be123c 100%);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}
.card-red strong { color: #ffffff; font-weight: 700; }
.card-red .card-subtext { color: rgba(255, 255, 255, 0.85); font-size: 0.725rem; }

.card-blue {
    background: linear-gradient(145deg, #2563eb 0%, #1d4ed8 100%);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}
.card-blue strong { color: #ffffff; font-weight: 700; }
.card-blue .card-subtext { color: rgba(255, 255, 255, 0.85); font-size: 0.725rem; }

.card-gray {
    background: linear-gradient(145deg, #475569 0%, #334155 100%);
    border-color: rgba(255, 255, 255, 0.15);
    color: #ffffff;
}
.card-gray strong { color: #ffffff; font-weight: 700; }
.card-gray .card-subtext { color: rgba(255, 255, 255, 0.8); font-size: 0.725rem; }

/* Dropdown inside Mini Card */
.mini-card select.form-select {
    background-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.35);
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
    backdrop-filter: blur(4px);
    transition: all 0.2s ease;
}
.mini-card select.form-select:hover,
.mini-card select.form-select:focus {
    background-color: rgba(255, 255, 255, 0.3);
    border-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
    color: #ffffff;
}
.mini-card select.form-select option {
    background-color: #ffffff;
    color: #1e293b;
}

/* Accordion Smooth Styling */
.training-accordion .accordion-item {
    border: 1px solid #e2e8f0;
    border-radius: 14px !important;
    margin-bottom: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    transition: all 0.2s ease;
    background: #ffffff;
}
.training-accordion .accordion-item:hover {
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
}
.training-accordion .accordion-button {
    padding: 1rem 1.25rem;
    background: #ffffff;
    border: none;
    font-weight: 700;
    color: #1e293b;
    border-radius: 14px;
    transition: all 0.2s ease;
}
.training-accordion .accordion-button:not(.collapsed) {
    background: #f8fafc;
    color: #2563eb;
    box-shadow: inset 0 -1px 0 #e2e8f0;
}
.training-accordion .accordion-button:focus {
    box-shadow: none;
}
.training-accordion .accordion-body {
    background: #f8fafc;
    padding: 1.25rem;
}

/* Panels & Card Container */
.panel-smooth {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
    padding: 1.5rem;
}

/* Modern Clean Table */
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
    padding: 12px 16px;
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
}

/* Modern Pill Badges */
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
.badge-pending {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}
.badge-verified {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
.badge-approve {
    background-color: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.badge-rejected {
    background-color: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

/* Download Action Buttons */
.btn-download-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 9px;
    padding: 6px 14px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}
.btn-download-active:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-1.5px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
}
.btn-download-active:active {
    transform: translateY(0);
}

.btn-download-disabled {
    background-color: #f1f5f9;
    color: #94a3b8 !important;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    padding: 6px 14px;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: not-allowed;
    opacity: 0.85;
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

/* Form Controls Smooth */
.form-control-smooth {
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background-color: #ffffff;
}
.form-control-smooth:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    background-color: #ffffff;
    outline: none;
}
</style>

@php
    $statusColor = [
        1 => 'card-green',   // Sudah Terlaksana
        2 => 'card-yellow',  // Mandatory Training
        3 => 'card-red',     // Didaftarkan Tetapi Tidak Hadir
        4 => 'card-blue',    // In House Training
    ];
@endphp

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Hero Header Card --}}
        <div class="profile-hero-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 font-monospace" style="font-size: 0.72rem;">
                                NPK: {{ $staff->npk_staff }}
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                Immediate Manager View
                            </span>
                        </div>
                        <h1 class="h4 mb-2 text-dark fw-bold">{{ $staff->nama_staff }}</h1>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="staff-meta-pill">
                                <i class="bi bi-diagram-3 text-primary"></i> Divisi: <strong>{{ $staff->divisi ? $staff->divisi->nama_divisi : '-' }}</strong>
                            </span>
                            <span class="staff-meta-pill">
                                <i class="bi bi-building text-info"></i> Dept: <strong>{{ $staff->department ? $staff->department->nama_department : '-' }}</strong>
                            </span>
                            <span class="staff-meta-pill">
                                <i class="bi bi-award text-warning"></i> Level: <strong>{{ $staff->levelJabatan ? $staff->levelJabatan->kode_level_jabatan : '-' }}</strong>
                            </span>
                            <span class="staff-meta-pill">
                                <i class="bi bi-calendar3 text-success"></i> Umur: <strong>{{ $staff->umur ? $staff->umur : '-' }}</strong>
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <a class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2" href="{{ route('users') }}">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Staff
                    </a>
                </div>
            </div>
        </div>

        {{-- Status Periode TNA Notice --}}
        @if(!$isTnaActive)
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3 rounded-3" role="alert" style="background-color: #fffbeb; border-left: 4px solid #f59e0b !important;">
                <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                <div>
                    <strong class="d-block text-dark">Periode Pengisian TNA Sedang Ditutup / Berakhir</strong>
                    <small class="text-muted">
                        Periode TNA: <strong>{{ $tnaStartDate ? \Carbon\Carbon::parse($tnaStartDate)->format('d M Y') : '-' }}</strong> s/d <strong>{{ $tnaEndDate ? \Carbon\Carbon::parse($tnaEndDate)->format('d M Y') : '-' }}</strong>.
                        Karena saat ini berada di luar periode aktif, Anda hanya dapat <strong>melihat (view only)</strong> status training staff.
                    </small>
                </div>
            </div>
        @else
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3 rounded-3" role="alert" style="background-color: #eff6ff; border-left: 4px solid #3b82f6 !important;">
                <i class="bi bi-info-circle-fill fs-3 text-primary"></i>
                <div>
                    <strong class="d-block text-dark">Periode TNA Aktif ({{ \Carbon\Carbon::parse($tnaStartDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tnaEndDate)->format('d M Y') }})</strong>
                    <small class="text-muted">Sebagai Immediate Manager, Anda dapat memilih status <strong>In House Training</strong> untuk diajukan bagi staff Anda.</small>
                </div>
            </div>
        @endif

        {{-- Keterangan / Legend Card --}}
        <div class="legend-card">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-palette text-primary"></i>
                <h6 class="fw-bold mb-0 text-dark">Keterangan Status Training</h6>
            </div>
            <div class="legend-grid">
                <div class="legend-chip">
                    <span class="legend-dot" style="background-color: #059669;"></span>
                    <span>Sudah Terlaksana</span>
                </div>
                <div class="legend-chip">
                    <span class="legend-dot" style="background-color: #d97706;"></span>
                    <span>Mandatory Training (Rekomendasi DLC)</span>
                </div>
                <div class="legend-chip">
                    <span class="legend-dot" style="background-color: #e11d48;"></span>
                    <span>Didaftarkan Tetapi Tidak Hadir</span>
                </div>
                <div class="legend-chip">
                    <span class="legend-dot" style="background-color: #2563eb;"></span>
                    <span>In House Training (Pilihan IM)</span>
                </div>
            </div>
        </div>

        {{-- Training Panels dynamically grouped in Accordion --}}
        @php
            $groupedTrainings = $trainings->groupBy('jenis_training');
        @endphp

        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <span class="text-muted small fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-collection text-primary"></i> Modul Training ({{ count($groupedTrainings) }} Kategori, {{ count($trainings) }} Total Training)
                </span>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnExpandAllTrainings">
                        <i class="bi bi-arrows-expand me-1"></i> Buka Semua
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnCollapseAllTrainings">
                        <i class="bi bi-arrows-collapse me-1"></i> Tutup Semua
                    </button>
                </div>
            </div>

            <div class="accordion training-accordion" id="accordionTrainingCategories">
                @forelse ($groupedTrainings as $jenis => $items)
                @php
                    $collapseId = 'collapseCat_' . md5($jenis);
                    $headingId = 'headingCat_' . md5($jenis);
                @endphp
                <div class="accordion-item">
                    <h2 class="accordion-header position-relative" id="{{ $headingId }}">
                        <button class="accordion-button collapsed pe-5" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#{{ $collapseId }}" 
                                aria-expanded="false" 
                                aria-controls="{{ $collapseId }}">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-journal-bookmark-fill text-primary fs-5"></i>
                                <span class="fs-6 text-dark fw-bold">{{ $jenis ?: 'Training Lainnya' }}</span>
                            </div>
                        </button>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 position-absolute top-50 translate-middle-y" style="right: 3.5rem; font-size: 0.75rem; pointer-events: none;">
                            {{ count($items) }} Training
                        </span>
                    </h2>
                    <div id="{{ $collapseId }}" 
                         class="accordion-collapse collapse" 
                         aria-labelledby="{{ $headingId }}">
                        <div class="accordion-body">
                            <div class="row g-3">
                                @foreach ($items as $t)
                                @php
                                    $record = $staffTrainings->get($t->id_training);
                                    $colorClass = $record ? ($statusColor[$record->id_status] ?? 'card-gray') : 'card-gray';
                                    $statusId = $record ? $record->id_status : null;
                                @endphp
                                <div class="col-md-3 col-sm-6">
                                    <div class="mini-card {{ $colorClass }}" id="card-{{ $t->id_training }}">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                                @if($t->kode_training)
                                                    <span class="badge bg-white bg-opacity-25 text-white font-monospace" style="font-size: 0.68rem;">{{ $t->kode_training }}</span>
                                                @else
                                                    <span></span>
                                                @endif
                                                <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-25" style="font-size: 0.68rem;">{{ $t->scope_training ?: 'In House' }}</span>
                                            </div>
                                            <span class="card-subtext d-block">{{ $t->mandatory_training ?: '-' }}</span>
                                            <strong class="d-block my-1 text-white" style="font-size: 0.9rem; line-height: 1.3;">{{ $t->nama_training }}</strong>
                                            <span class="card-subtext d-block">{{ $t->gol_training ?: '-' }}</span>
                                        </div>
                                        
                                        {{-- Dropdown Status Training (Immediate Manager logic) --}}
                                        <div class="mt-3">
                                            @if(!$isTnaActive)
                                                {{-- Read-only mode saat TNA ditutup --}}
                                                <div class="pt-2 border-top border-white border-opacity-25 small" style="color: rgba(255,255,255,0.85); font-size: 0.75rem;">
                                                    Status: <strong class="text-white">
                                                        @if($statusId == 1) Sudah Terlaksana
                                                        @elseif($statusId == 2) Mandatory Training
                                                        @elseif($statusId == 3) Tidak Hadir
                                                        @elseif($statusId == 4) In House Training
                                                        @else Belum Diisi
                                                        @endif
                                                    </strong>
                                                </div>
                                            @else
                                                {{-- Active TNA Mode: Immediate Manager hanya boleh memilih In House Training --}}
                                                <select class="form-select form-select-sm status-select" data-training="{{ $t->id_training }}" {{ in_array($statusId, [1, 2, 3]) ? 'disabled' : '' }}>
                                                    @if(in_array($statusId, [1, 2, 3]))
                                                        @if($statusId == 1) <option selected disabled>Sudah Terlaksana (DLC)</option>
                                                        @elseif($statusId == 2) <option selected disabled>Mandatory (DLC)</option>
                                                        @elseif($statusId == 3) <option selected disabled>Tidak Hadir (DLC)</option>
                                                        @endif
                                                    @else
                                                        <option value="" {{ !$record ? 'selected' : '' }} disabled>- Pilih Status -</option>
                                                        <option value="4" {{ $statusId == 4 ? 'selected' : '' }}>In House Training</option>
                                                    @endif
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="panel-smooth text-center text-muted py-4">
                    Belum ada data training yang terdaftar.
                </div>
                @endforelse
            </div>
        </div>

        {{-- ========== SECTION REQUEST TRAINING OUT HOUSE (OH) ========== --}}
        <section class="mt-4">
            <div class="panel-smooth">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom">
                    <div>
                        <h2 class="h5 mb-1 text-dark fw-bold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-up-right text-primary"></i>
                            <span>Request Training Out House (OH)</span>
                        </h2>
                        <p class="text-muted mb-0 small">
                            Form pengajuan permohonan training Out House untuk staff: <strong>{{ $staff->nama_staff }} ({{ $staff->npk_staff }})</strong>
                        </p>
                    </div>
                </div>

                {{-- Form Input Request OH --}}
                <form action="{{ route('outhouse.store') }}" method="POST" class="p-4 bg-light bg-opacity-50 border rounded-3 mb-4">
                    @csrf
                    <input type="hidden" name="id_staff" value="{{ $staff->id_staff }}">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="judul_training" class="form-label fw-semibold text-dark small mb-1">Judul Training <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-smooth" id="judul_training" name="judul_training" placeholder="Contoh: Pelatihan Sertifikasi BNSP, Advanced Data Analytics, dll." required>
                        </div>

                        <div class="col-md-6">
                            <label for="deskripsi_training" class="form-label fw-semibold text-dark small mb-1">Deskripsi Training <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-smooth" id="deskripsi_training" name="deskripsi_training" rows="3" placeholder="Uraikan ringkasan materi, lembaga/vendor penyelenggara, atau silabus..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="reason" class="form-label fw-semibold text-dark small mb-1">Reason / Alasan Kebutuhan <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-smooth" id="reason" name="reason" rows="3" placeholder="Jelaskan alasan bisnis, urgensi tugas kerja, atau kompetensi yang ingin ditingkatkan..." required></textarea>
                        </div>

                        <div class="col-12 text-end pt-2">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
                                <i class="bi bi-send"></i> Ajukan Request Training OH
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Tabel Riwayat Request Training OH --}}
                <div class="table-smooth-container">
                    <div class="table-responsive">
                        <table class="table-smooth align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 170px;">No. Request</th>
                                    <th scope="col">Judul Training</th>
                                    <th scope="col">Deskripsi Training</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col" style="width: 150px;">Status</th>
                                    <th scope="col" style="width: 180px;">Dokumen Formulir</th>
                                    <th scope="col" class="text-end" style="width: 110px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outhouseRequests ?? [] as $req)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.75rem;">{{ $req->no_request }}</span>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">{{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '' }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block" style="font-size: 0.88rem;">{{ $req->judul_training }}</strong>
                                    </td>
                                    <td><small class="text-muted">{{ $req->deskripsi_training }}</small></td>
                                    <td><small class="text-muted">{{ $req->reason }}</small></td>
                                    <td>
                                        @if($req->status === 'Pending')
                                            <span class="badge-pill-soft badge-pending">
                                                <i class="bi bi-hourglass-split"></i> Pending
                                            </span>
                                        @elseif($req->status === 'Verified by DLC')
                                            <span class="badge-pill-soft badge-verified">
                                                <i class="bi bi-patch-check"></i> Verified by DLC
                                            </span>
                                        @elseif($req->status === 'Approve')
                                            <span class="badge-pill-soft badge-approve">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </span>
                                        @elseif($req->status === 'Rejected With Reason')
                                            <span class="badge-pill-soft badge-rejected">
                                                <i class="bi bi-x-circle"></i> Rejected
                                            </span>
                                            @if($req->alasan_reject)
                                                <div class="mt-1 small text-danger" style="font-size: 0.72rem;">
                                                    <strong>Alasan:</strong> {{ $req->alasan_reject }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary rounded-pill">{{ $req->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->status === 'Verified by DLC' || $req->status === 'Approve')
                                            @if($req->penugasan && $req->penugasan->is_sent)
                                                {{-- ENABLE: Formulir telah dibuat dan dikirim oleh DLC --}}
                                                <a href="{{ route('penugasan.downloadPdf', $req->penugasan->id_penugasan) }}" class="btn-download-active" title="Unduh Dokumen Formulir Pendaftaran Training Resmi">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i> Unduh Formulir
                                                </a>
                                                <small class="text-success d-block mt-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-check2-all me-1"></i>Siap diunduh
                                                </small>
                                            @else
                                                {{-- DISABLE: Formulir belum dibuat atau belum dikirim oleh DLC --}}
                                                <button type="button" class="btn-download-disabled" disabled title="Formulir belum dikirim oleh DLC">
                                                    <i class="bi bi-file-earmark-pdf"></i> Unduh Formulir
                                                </button>
                                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-hourglass-split me-1"></i>Belum dikirim DLC
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1" role="group">
                                            <button type="button" class="btn-action-icon btn-action-edit" data-bs-toggle="modal" data-bs-target="#modalEditOuthouse{{ $req->id_request_outhouse }}" title="Edit Request">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn-action-icon btn-action-delete" data-bs-toggle="modal" data-bs-target="#modalDeleteOuthouse{{ $req->id_request_outhouse }}" title="Hapus Request">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        {{-- Modal Edit Request OH --}}
                                        <div class="modal fade text-start" id="modalEditOuthouse{{ $req->id_request_outhouse }}" tabindex="-1" aria-labelledby="modalEditOuthouseLabel{{ $req->id_request_outhouse }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
                                                    <form action="{{ route('outhouse.update', $req->id_request_outhouse) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title fs-6 fw-bold" id="modalEditOuthouseLabel{{ $req->id_request_outhouse }}">
                                                                <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Request Training OH ({{ $req->no_request }})
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark small">Judul Training <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control form-control-smooth" name="judul_training" value="{{ old('judul_training', $req->judul_training) }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark small">Deskripsi Training <span class="text-danger">*</span></label>
                                                                <textarea class="form-control form-control-smooth" name="deskripsi_training" rows="3" required>{{ old('deskripsi_training', $req->deskripsi_training) }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark small">Reason / Alasan Kebutuhan <span class="text-danger">*</span></label>
                                                                <textarea class="form-control form-control-smooth" name="reason" rows="3" required>{{ old('reason', $req->reason) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary btn-sm px-4 rounded-3">
                                                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Delete Request OH --}}
                                        <div class="modal fade text-start" id="modalDeleteOuthouse{{ $req->id_request_outhouse }}" tabindex="-1" aria-labelledby="modalDeleteOuthouseLabel{{ $req->id_request_outhouse }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
                                                    <form action="{{ route('outhouse.destroy', $req->id_request_outhouse) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-header bg-danger bg-opacity-10 text-danger">
                                                            <h5 class="modal-title fs-6 fw-bold" id="modalDeleteOuthouseLabel{{ $req->id_request_outhouse }}">
                                                                <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Request
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            Apakah Anda yakin ingin menghapus request training <strong>{{ $req->judul_training }}</strong> (No: <code>{{ $req->no_request }}</code>)?
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger btn-sm px-4 rounded-3">
                                                                <i class="bi bi-trash me-1"></i> Ya, Hapus Request
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
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                        <span>Belum ada request training Out House yang diajukan untuk staff ini.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

@if($isTnaActive)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const colorMap = {
        1: 'card-green',
        2: 'card-yellow',
        3: 'card-red',
        4: 'card-blue',
    };

    document.querySelectorAll('.status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const idTraining = this.dataset.training;
            const idStatus = this.value;
            const card = document.getElementById('card-' + idTraining);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch("{{ route('staffTraining.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    id_staff: {{ $staff->id_staff }},
                    id_training: idTraining,
                    id_status: idStatus,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    card.classList.remove('card-gray', 'card-green', 'card-yellow', 'card-red', 'card-blue');
                    card.classList.add(colorMap[idStatus]);
                } else {
                    alert(data.message || 'Gagal update status training.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat menghubungi server.');
            });
        });
    });
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Accordion Expand/Collapse All
    const btnExpand = document.getElementById('btnExpandAllTrainings');
    const btnCollapse = document.getElementById('btnCollapseAllTrainings');

    btnExpand?.addEventListener('click', function () {
        document.querySelectorAll('#accordionTrainingCategories .accordion-collapse').forEach(function (el) {
            bootstrap.Collapse.getOrCreateInstance(el, { toggle: false }).show();
        });
    });

    btnCollapse?.addEventListener('click', function () {
        document.querySelectorAll('#accordionTrainingCategories .accordion-collapse').forEach(function (el) {
            bootstrap.Collapse.getOrCreateInstance(el, { toggle: false }).hide();
        });
    });
});
</script>

@endsection