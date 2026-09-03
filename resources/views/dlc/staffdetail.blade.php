@extends('layouts.admindlc')

@section('title', 'Detail Training Staff | Learning & Development')

@section('content')
<style>
/* ==========CARD========== */
.mini-card {
    border-radius: 10px;
    padding: 12px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.card-green {
    background-color: #198754;
    border-color: #e9f7ef;
}
.card-green strong { color: #b7e4c7; }

.card-yellow {
    background-color: #d1a300;
    border-color: #fff9e6;
}
.card-yellow strong { color: #ffe69c; }

.card-red {
    background-color: #dc3545;
    border-color: #fdecec;
}
.card-red strong { color: #f5b8b8; }

.card-blue {
    background-color: #0d6efd;
    border-color: #e7f1ff;
}
.card-blue strong { color: #b6d4fe; }

.card-gray {
    background-color: #6c757d;
    border-color: #f1f2f3;
}
.card-gray strong { color: #dcdcdc; }

/* ==========DROPDOWN CARD========== */
.mini-card select.form-select {
    background-color: rgba(255, 255, 255, 0.15);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.4);
    font-size: 0.75rem;
}
.mini-card select.form-select option {
    color: #000;
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
        {{-- Page Heading & Profile Info --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span>
                <div>
                    <p class="eyebrow mb-1">Staff Training Profile</p>
                    <h1 class="h3 mb-1">{{ $staff->nama_staff }} <span class="text-muted fs-5">({{ $staff->npk_staff }})</span></h1>
                    <p class="text-muted mb-0 small">
                        Divisi: <strong>{{ $staff->divisi ? $staff->divisi->nama_divisi : '-' }}</strong> | 
                        Department: <strong>{{ $staff->department ? $staff->department->nama_department : '-' }}</strong> | 
                        Level: <strong>{{ $staff->levelJabatan ? $staff->levelJabatan->kode_level_jabatan : '-' }}</strong> | 
                        Umur: <strong>{{ $staff->umur ?: '-' }}</strong> | 
                        Manager: <strong>{{ $staff->immediateManager ? $staff->immediateManager->nama_staff : '-' }}</strong>
                    </p>
                </div>
            </div>

            <div class="heading-actions">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('member-list') }}">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Member List
                </a>
            </div>
        </div>

        {{-- Keterangan / Legend --}}
        <section class="row g-3 mb-3">
            <div class="col-12">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-clock-history" aria-hidden="true"></i><span>Keterangan Status Training</span></h2>
                        </div>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item"><span class="activity-dot bg-success"></span><div><p class="mb-1 fw-semibold">Sudah Terlaksana</p></div></div>
                        <div class="activity-item"><span class="activity-dot bg-warning"></span><div><p class="mb-1 fw-semibold">Mandatory Training yang direkomendasikan untuk karyawan yang bersangkutan</p></div></div>
                        <div class="activity-item"><span class="activity-dot bg-danger"></span><div><p class="mb-1 fw-semibold">Didaftarkan training tetapi tidak hadir ketika training berlangsung</p></div></div>
                        <div class="activity-item"><span class="activity-dot bg-primary"></span><div><p class="mb-1 fw-semibold">In House Training yang ingin diikuti</p></div></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Training Sections dynamically grouped by jenis_training in Accordion --}}
        @php
            $groupedTrainings = $trainings->groupBy('jenis_training');
        @endphp

        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <span class="text-muted small fw-semibold">
                    <i class="bi bi-layers me-1"></i> Modul Training ({{ count($groupedTrainings) }} Kategori, {{ count($trainings) }} Total Training)
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
                <div class="accordion-item border shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
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
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 position-absolute" style="right: 5rem;">
                                {{ count($items) }} Training
                            </span>
                        </button>
                    </h2>
                    <div id="{{ $collapseId }}" 
                         class="accordion-collapse collapse" 
                         aria-labelledby="{{ $headingId }}">
                        <div class="accordion-body p-3 bg-light border-top">
                            <div class="row g-3">
                                @foreach ($items as $t)
                                @php
                                    $record = $staffTrainings->get($t->id_training);
                                    $colorClass = $record ? ($statusColor[$record->id_status] ?? 'card-gray') : 'card-gray';
                                @endphp
                                <div class="col-md-3 col-sm-6">
                                    <div class="mini-card {{ $colorClass }}" id="card-{{ $t->id_training }}">
                                        <span style="color: #ffffff; font-size: 0.75rem; display: block;">{{ $t->mandatory_training ?: '-' }}</span>
                                        <strong class="d-block my-1">{{ $t->nama_training }}</strong>
                                        <span style="color: #ffffff; font-size: 0.75rem; display: block;">{{ $t->gol_training ?: '-' }}</span>
                                        
                                        {{-- Dropdown Status Training --}}
                                        <select class="form-select form-select-sm mt-2 status-select" data-training="{{ $t->id_training }}">
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
                <div class="panel p-4 text-center text-muted">
                    Belum ada data training yang terdaftar di master data.
                </div>
                @endforelse
            </div>
        </div>

        {{-- ========== SECTION REQUEST TRAINING OUT HOUSE (DLC VIEW) ========== --}}
        <section class="row g-3 mt-4">
            <div class="col-12">
                <div class="panel p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-box-arrow-up-right me-2 text-primary"></i>
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
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 160px;">No. Request</th>
                                    <th scope="col">Diajukan Oleh</th>
                                    <th scope="col">Judul Training</th>
                                    <th scope="col">Deskripsi Training</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col" style="width: 140px;">Status</th>
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
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                        @elseif($req->status === 'Verified by DLC')
                                            <span class="badge bg-info text-white"><i class="bi bi-patch-check me-1"></i>Verified by DLC</span>
                                        @elseif($req->status === 'Approve')
                                            <span class="badge bg-success text-white"><i class="bi bi-check-circle me-1"></i>Approve</span>
                                        @elseif($req->status === 'Rejected With Reason')
                                            <span class="badge bg-danger text-white"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                                            @if($req->alasan_reject)
                                                <div class="mt-1 small text-danger">
                                                    <strong>Alasan:</strong> {{ $req->alasan_reject }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">{{ $req->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
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
