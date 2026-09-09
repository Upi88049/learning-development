@extends('layouts.admin')

@section('title', 'Detail Training Staff | Learning & Development')

@section('content')
<style>
/* ========== MODERN MINI CARDS (HARMONIZED WITH DLC STAFFDETAIL) ========== */
.mini-card {
    border-radius: 12px;
    padding: 14px;
    border: 1px solid transparent;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 145px;
}
.mini-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
}

.card-green {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-color: #047857;
    color: #ffffff;
}
.card-green strong { color: #ffffff; font-weight: 700; }
.card-green span, .card-green .card-subtext { color: rgba(255, 255, 255, 0.85); }

.card-yellow {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    border-color: #b45309;
    color: #ffffff;
}
.card-yellow strong { color: #ffffff; font-weight: 700; }
.card-yellow span, .card-yellow .card-subtext { color: rgba(255, 255, 255, 0.85); }

.card-red {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border-color: #b91c1c;
    color: #ffffff;
}
.card-red strong { color: #ffffff; font-weight: 700; }
.card-red span, .card-red .card-subtext { color: rgba(255, 255, 255, 0.85); }

.card-blue {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-color: #1d4ed8;
    color: #ffffff;
}
.card-blue strong { color: #ffffff; font-weight: 700; }
.card-blue span, .card-blue .card-subtext { color: rgba(255, 255, 255, 0.85); }

.card-gray {
    /* ==========START CARD WARNA PUTIH========== */
    /* background: #ffffff;
    border-color: #e2e8f0;
    color: #1e293b; */
    /* ==========END CARD WARNA PUTIH========== */
    background: linear-gradient(145deg, #475569 0%, #334155 100%);
    border-color: rgba(255, 255, 255, 0.15);
    color: #ffffff;
}
.card-gray strong { color: #0f172a; font-weight: 700; }
.card-gray span, .card-gray .card-subtext { color: #64748b; }

/* Status dropdown in mini-card */
.mini-card select.form-select {
    background-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.4);
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 8px;
    backdrop-filter: blur(8px);
}
.card-gray select.form-select {
    background-color: #ffffff;
    color: #334155;
    border-color: #cbd5e1;
}
.mini-card select.form-select option {
    color: #0f172a;
    background: #ffffff;
}

/* Legend Chips */
.legend-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #334155;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}
.legend-dot-circle {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Download Action Buttons & Out House styling */
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

.btn-download-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 8px;
    padding: 6px 14px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.btn-download-active:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
}

.btn-download-disabled {
    background-color: #f1f5f9;
    color: #94a3b8 !important;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 14px;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: not-allowed;
    opacity: 0.85;
}

.btn-action-icon {
    width: 32px;
    height: 32px;
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

        {{-- Profile Hero Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.75rem; flex-shrink: 0;">
                        <i class="bi bi-person-lines-fill" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Staff Profile</span>
                            <span class="badge bg-light text-dark border font-monospace">{{ $staff->npk_staff }}</span>
                            @if($staff->levelJabatan)
                                <span class="badge bg-info text-dark">{{ $staff->levelJabatan->kode_level_jabatan }}</span>
                            @endif
                            <span class="badge bg-secondary-subtle text-secondary border">Immediate Manager View</span>
                        </div>
                        <h1 class="h3 mb-2 fw-bold text-dark">{{ $staff->nama_staff }}</h1>
                        
                        <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-diagram-3 text-secondary"></i>
                                Divisi: <strong class="text-dark">{{ $staff->divisi ? $staff->divisi->nama_divisi : '-' }}</strong>
                            </span>
                            <span class="text-muted opacity-50">&bull;</span>
                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-building text-secondary"></i>
                                Dept: <strong class="text-dark">{{ $staff->department ? $staff->department->nama_department : '-' }}</strong>
                            </span>
                            <span class="text-muted opacity-50">&bull;</span>
                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-calendar3 text-secondary"></i>
                                Umur: <strong class="text-dark">{{ $staff->umur ? $staff->umur . ' Tahun' : '-' }}</strong>
                            </span>
                            <span class="text-muted opacity-50">&bull;</span>
                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-person-badge text-secondary"></i>
                                Manager: <strong class="text-dark">{{ $staff->immediateManager ? $staff->immediateManager->nama_staff : '-' }}</strong>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('users') }}">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Daftar Staff
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

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

        {{-- Status Legend Chips --}}
        <section class="panel p-3 mb-4">
            <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                <i class="bi bi-palette-fill text-primary"></i>
                <h6 class="mb-0 fw-bold text-dark">Keterangan Warna Status Training</h6>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-2 pt-1">
                <div class="col">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-success"></span>
                        <span>Sudah Terlaksana</span>
                    </div>
                </div>
                <div class="col">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-warning"></span>
                        <span>Mandatory Training</span>
                    </div>
                </div>
                <div class="col">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-danger"></span>
                        <span>Tidak Hadir</span>
                    </div>
                </div>
                <div class="col">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-primary"></span>
                        <span>In House Training</span>
                    </div>
                </div>
                <div class="col">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle" style="background-color: #94a3b8;"></span>
                        <span>Belum Mengikuti</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Training Sections dynamically grouped by jenis_training in Accordion (Khusus In House) --}}
        @php
            $inHouseTrainings = $trainings->filter(function ($t) {
                return ($t->scope_training ?? 'In House') !== 'Out House';
            });
            $groupedTrainings = $inHouseTrainings->groupBy('jenis_training');
        @endphp

        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-collection-play-fill text-primary fs-5"></i>
                    <h5 class="mb-0 fw-bold text-dark">Modul Training (In House)</h5>
                    <span class="badge bg-light text-secondary border">
                        {{ count($groupedTrainings) }} Kategori &bull; {{ count($inHouseTrainings) }} Total
                    </span>
                </div>
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
                <div class="accordion-item shadow-xs mb-3">
                    <h2 class="accordion-header" id="{{ $headingId }}">
                        <button class="accordion-button collapsed py-3 px-4 bg-white fw-bold pe-5" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#{{ $collapseId }}" 
                                aria-expanded="false" 
                                aria-controls="{{ $collapseId }}">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-journal-bookmark-fill text-primary fs-5"></i>
                                <span class="fs-6 text-dark">{{ $jenis ?: 'Training Lainnya' }}</span>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 position-absolute" style="right: 4.5rem;">
                                {{ count($items) }} Training
                            </span>
                        </button>
                    </h2>
                    <div id="{{ $collapseId }}" 
                         class="accordion-collapse collapse" 
                         aria-labelledby="{{ $headingId }}">
                        <div class="accordion-body p-3 bg-light bg-opacity-50">
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
                                                    <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.68rem;">{{ $t->kode_training }}</span>
                                                @else
                                                    <span></span>
                                                @endif
                                                <span class="badge {{ $t->scope_training == 'Out House' ? 'bg-warning-subtle text-warning border' : 'bg-info-subtle text-info border' }}" style="font-size: 0.68rem;">{{ $t->scope_training ?: 'In House' }}</span>
                                            </div>
                                            <span style="font-size: 0.75rem; display: block;" class="text-uppercase fw-semibold">{{ $t->mandatory_training ?: '-' }}</span>
                                            <strong class="d-block my-1.5 fs-6">{{ $t->nama_training }}</strong>
                                            <span style="font-size: 0.75rem; display: block;">Gol: {{ $t->gol_training ?: '-' }}</span>
                                        </div>
                                        
                                        {{-- Status Selection / Display (Immediate Manager logic) --}}
                                        @if(!$isTnaActive)
                                            {{-- Read-only mode saat TNA ditutup --}}
                                            <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 small">
                                                <span class="card-subtext">Status:</span> 
                                                <strong>
                                                    @if($statusId == 1) Sudah Terlaksana
                                                    @elseif($statusId == 2) Mandatory Training
                                                    @elseif($statusId == 3) Tidak Hadir
                                                    @elseif($statusId == 4) In House Training
                                                    @else Belum Mengikuti
                                                    @endif
                                                </strong>
                                            </div>
                                        @else
                                            {{-- Active TNA Mode: Status 1,2,3 dari DLC disabled; IM hanya boleh pilih In House Training (4) atau Belum Mengikuti (0) --}}
                                            <select class="form-select form-select-sm mt-3 status-select" data-training="{{ $t->id_training }}" {{ in_array($statusId, [1, 2, 3]) ? 'disabled' : '' }}>
                                                @if(in_array($statusId, [1, 2, 3]))
                                                    @if($statusId == 1) <option selected disabled>Sudah Terlaksana (DLC)</option>
                                                    @elseif($statusId == 2) <option selected disabled>Mandatory (DLC)</option>
                                                    @elseif($statusId == 3) <option selected disabled>Tidak Hadir (DLC)</option>
                                                    @endif
                                                @else
                                                    <option value="0" {{ !$record || $statusId != 4 ? 'selected' : '' }}>- Belum Mengikuti -</option>
                                                    <option value="4" {{ $record && $statusId == 4 ? 'selected' : '' }}>In House Training</option>
                                                @endif
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="panel p-5 text-center text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                    Belum ada data training yang terdaftar di master data.
                </div>
                @endforelse
            </div>
        </div>

        {{-- ========== SECTION REQUEST TRAINING OUT HOUSE (OH) ========== --}}
        <section class="row g-3 mt-2">
            <div class="col-12">
                <div class="panel p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-box-arrow-up-right me-1 text-primary"></i>
                                <span>Request Training Out House (OH)</span>
                            </h2>
                            <p class="text-muted mb-0 small">
                                Form pengajuan permohonan training Out House untuk staff: <strong>{{ $staff->nama_staff }} ({{ $staff->npk_staff }})</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Form Input Request OH --}}
                    <form action="{{ route('outhouse.store') }}" method="POST" class="p-3 bg-light bg-opacity-50 border rounded-3 mb-4">
                        @csrf
                        <input type="hidden" name="id_staff" value="{{ $staff->id_staff }}">
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="judul_training" class="form-label fw-semibold text-dark small mb-1">Judul Training <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="judul_training" name="judul_training" placeholder="Contoh: Pelatihan Sertifikasi BNSP, Advanced Data Analytics, dll." required>
                            </div>

                            <div class="col-md-6">
                                <label for="deskripsi_training" class="form-label fw-semibold text-dark small mb-1">Deskripsi Training <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="deskripsi_training" name="deskripsi_training" rows="3" placeholder="Uraikan ringkasan materi, lembaga/vendor penyelenggara, atau silabus..." required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="reason" class="form-label fw-semibold text-dark small mb-1">Reason / Alasan Kebutuhan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Jelaskan alasan bisnis, urgensi tugas kerja, atau kompetensi yang ingin ditingkatkan..." required></textarea>
                            </div>

                            <div class="col-12 text-end pt-1">
                                <button type="submit" class="btn btn-primary btn-sm px-3 py-2 rounded-2 shadow-xs d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-send"></i> Ajukan Request Training OH
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Riwayat Request Training OH --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th scope="col" style="min-width: 140px;">No. Request</th>
                                    <th scope="col" style="min-width: 180px;">Judul Training</th>
                                    <th scope="col" style="min-width: 200px;">Deskripsi Training</th>
                                    <th scope="col" style="min-width: 160px;">Reason</th>
                                    <th scope="col" style="min-width: 140px;">Status</th>
                                    <th scope="col" style="min-width: 160px;">Dokumen Formulir</th>
                                    <th scope="col" class="text-end" style="min-width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outhouseRequests ?? [] as $req)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">{{ $req->no_request }}</span>
                                        <small class="text-muted d-block mt-1">{{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '' }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block">{{ $req->judul_training }}</strong>
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
                                                <a href="{{ route('penugasan.downloadPdf', $req->penugasan->id_penugasan) }}" class="btn-download-active" title="Unduh Dokumen Formulir Pendaftaran Training Resmi">
                                                    <i class="bi bi-file-earmark-pdf-fill"></i> Unduh Formulir
                                                </a>
                                                <small class="text-success d-block mt-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-check2-all me-1"></i>Siap diunduh
                                                </small>
                                            @else
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
                                                                <input type="text" class="form-control" name="judul_training" value="{{ old('judul_training', $req->judul_training) }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark small">Deskripsi Training <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="deskripsi_training" rows="3" required>{{ old('deskripsi_training', $req->deskripsi_training) }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark small">Reason / Alasan Kebutuhan <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="reason" rows="3" required>{{ old('reason', $req->reason) }}</textarea>
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
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                        Belum ada request training Out House yang diajukan untuk staff ini.
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
                    card.classList.add(colorMap[idStatus] || 'card-gray');
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