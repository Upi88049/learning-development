@extends('layouts.admindlc')

@section('title', 'Detail Training Staff | Learning & Development')

@section('content')
<style>
/* ========== MODERN MINI CARDS (MATCHING USERDETAIL) ========== */
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
.card-green span { color: rgba(255, 255, 255, 0.85); }

.card-yellow {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    border-color: #b45309;
    color: #ffffff;
}
.card-yellow strong { color: #ffffff; font-weight: 700; }
.card-yellow span { color: rgba(255, 255, 255, 0.85); }

.card-red {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border-color: #b91c1c;
    color: #ffffff;
}
.card-red strong { color: #ffffff; font-weight: 700; }
.card-red span { color: rgba(255, 255, 255, 0.85); }

.card-blue {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-color: #1d4ed8;
    color: #ffffff;
}
.card-blue strong { color: #ffffff; font-weight: 700; }
.card-blue span { color: rgba(255, 255, 255, 0.85); }

.card-gray {
    background: #ffffff;
    border-color: #e2e8f0;
    color: #1e293b;
}
.card-gray strong { color: #0f172a; font-weight: 700; }
.card-gray span { color: #64748b; }

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
    background-color: #f8fafc;
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
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('member-list') }}">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Member List
                    </a>
                </div>
            </div>
        </div>

        {{-- Status Legend Chips --}}
        <section class="panel p-3 mb-4">
            <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                <i class="bi bi-palette-fill text-primary"></i>
                <h6 class="mb-0 fw-bold text-dark">Keterangan Warna Status Training</h6>
            </div>
            <div class="row g-2 pt-1">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-success"></span>
                        <span>Sudah Terlaksana</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-warning"></span>
                        <span>Mandatory Training</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-danger"></span>
                        <span>Tidak Hadir Saat Training</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="legend-chip w-100">
                        <span class="legend-dot-circle bg-primary"></span>
                        <span>In House Training Ingin Diikuti</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Training Sections dynamically grouped by jenis_training in Accordion --}}
        @php
            $groupedTrainings = $trainings->groupBy('jenis_training');
        @endphp

        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-collection-play-fill text-primary fs-5"></i>
                    <h5 class="mb-0 fw-bold text-dark">Modul Training</h5>
                    <span class="badge bg-light text-secondary border">
                        {{ count($groupedTrainings) }} Kategori &bull; {{ count($trainings) }} Total
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
                                        
                                        {{-- Dropdown Status Training --}}
                                        <select class="form-select form-select-sm mt-3 status-select" data-training="{{ $t->id_training }}">
                                            <option value="" {{ !$record ? 'selected' : '' }} disabled>- Pilih Status -</option>
                                            <option value="1" {{ $record && $record->id_status == 1 ? 'selected' : '' }}>Sudah Terlaksana</option>
                                            <option value="2" {{ $record && $record->id_status == 2 ? 'selected' : '' }}>Mandatory Training</option>
                                            <option value="3" {{ $record && $record->id_status == 3 ? 'selected' : '' }}>Tidak Hadir</option>
                                            <option value="4" {{ $record && $record->id_status == 4 ? 'selected' : '' }}>In House Training</option>
                                        </select>
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

        {{-- ========== SECTION REQUEST TRAINING OUT HOUSE (DLC VIEW) ========== --}}
        <section class="row g-3 mt-2">
            <div class="col-12">
                <div class="panel p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-box-arrow-up-right me-1 text-primary"></i>
                                <span>Permohonan Training Out House (OH)</span>
                            </h2>
                            <p class="text-muted mb-0 small">
                                Daftar request training Out House untuk staff: <strong>{{ $staff->nama_staff }} ({{ $staff->npk_staff }})</strong>
                            </p>
                        </div>
                        <a href="{{ route('outhouse.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list-check me-1"></i> Kelola Semua Request OH
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th scope="col" style="min-width: 140px;">No. Request</th>
                                    <th scope="col" style="min-width: 150px;">Diajukan Oleh</th>
                                    <th scope="col" style="min-width: 180px;">Judul Training</th>
                                    <th scope="col" style="min-width: 200px;">Deskripsi Training</th>
                                    <th scope="col" style="min-width: 160px;">Reason</th>
                                    <th scope="col" style="min-width: 140px;">Status</th>
                                    <th scope="col" style="min-width: 160px;">Dokumen Formulir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outhouseRequests ?? [] as $req)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">{{ $req->no_request }}</span>
                                        <small class="text-muted d-block mt-1">{{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '-' }}</small>
                                    </td>
                                    <td>
                                        @if($req->immediateManager)
                                            <span class="fw-semibold text-primary">{{ $req->immediateManager->nama_staff }}</span>
                                            <small class="text-muted d-block">({{ $req->immediateManager->npk_staff }})</small>
                                        @elseif($req->staff && $req->staff->immediateManager)
                                            <span>{{ $req->staff->immediateManager->nama_staff }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $req->judul_training }}</td>
                                    <td><small class="text-muted">{{ $req->deskripsi_training }}</small></td>
                                    <td><small class="text-muted">{{ $req->reason }}</small></td>
                                    <td>
                                        @if($req->status === 'Pending')
                                            <span class="badge-pill-soft badge-pending"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                        @elseif($req->status === 'Verified by DLC')
                                            <span class="badge-pill-soft badge-verified"><i class="bi bi-patch-check me-1"></i>Verified by DLC</span>
                                        @elseif($req->status === 'Approve')
                                            <span class="badge-pill-soft badge-approve"><i class="bi bi-check-circle me-1"></i>Approve</span>
                                        @elseif($req->status === 'Rejected With Reason')
                                            <span class="badge-pill-soft badge-rejected"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                                            @if($req->alasan_reject)
                                                <div class="mt-1 small text-danger">
                                                    <strong>Alasan:</strong> {{ $req->alasan_reject }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">{{ $req->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->status === 'Verified by DLC' || $req->status === 'Approve')
                                            @if($req->penugasan)
                                                @if($req->penugasan->is_sent)
                                                    <a href="{{ route('penugasan.downloadPdf', $req->penugasan->id_penugasan) }}" class="btn btn-success btn-sm d-inline-flex align-items-center" title="Download Form (Terkirim ke IM)">
                                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh PDF
                                                    </a>
                                                    <small class="text-success d-block mt-1" style="font-size: 0.72rem;">
                                                        <i class="bi bi-check2-all me-1"></i>Terkirim ke IM
                                                    </small>
                                                @else
                                                    <form action="{{ route('penugasan.sendToIm', $req->penugasan->id_penugasan) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim dokumen formulir ini ke akun Immediate Manager?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm" title="Kirim Dokumen ke Immediate Manager">
                                                            <i class="bi bi-send me-1"></i> Kirim ke IM
                                                        </button>
                                                    </form>
                                                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                                                        Belum dikirim
                                                    </small>
                                                @endif
                                            @else
                                                <a href="{{ route('penugasan.create', ['from_request' => $req->id_request_outhouse]) }}" class="btn btn-outline-success btn-sm" title="Buat Formulir Pendaftaran Training">
                                                    <i class="bi bi-file-earmark-plus me-1"></i> Buat Form
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                        Belum ada permohonan training Out House untuk staff ini.
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
                    alert('Gagal memperbarui status training.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat menghubungi server.');
            });
        });
    });

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
