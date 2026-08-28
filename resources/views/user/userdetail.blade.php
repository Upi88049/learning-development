@extends('layouts.admin')

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
        {{-- Page Heading --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span>
                <div>
                    <p class="eyebrow mb-1">Immediate Manager View</p>
                    <h1 class="h3 mb-1">{{ $staff->nama_staff }} <span class="text-muted fs-5">({{ $staff->npk_staff }})</span></h1>
                    <p class="text-muted mb-0 small">
                        Department: <strong>{{ $staff->department ? $staff->department->nama_department : '-' }}</strong> | 
                        Level: <strong>{{ $staff->levelJabatan ? $staff->levelJabatan->kode_level_jabatan : '-' }}</strong> | 
                        Umur: <strong>{{ $staff->umur ? $staff->umur.' tahun' : '-' }}</strong>
                    </p>
                </div>
            </div>

            <div class="heading-actions">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('users') }}">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Daftar Staff
                </a>
            </div>
        </div>

        {{-- Status Periode TNA Notice --}}
        @if(!$isTnaActive)
            <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                <div>
                    <strong class="d-block">Periode Pengisian TNA Sedang Ditutup / Berakhir</strong>
                    <small>
                        Periode TNA: <strong>{{ $tnaStartDate ? \Carbon\Carbon::parse($tnaStartDate)->format('d M Y') : '-' }}</strong> s/d <strong>{{ $tnaEndDate ? \Carbon\Carbon::parse($tnaEndDate)->format('d M Y') : '-' }}</strong>.
                        Karena saat ini berada di luar periode aktif, Anda hanya dapat <strong>melihat (view only)</strong> status training staff.
                    </small>
                </div>
            </div>
        @else
            <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
                <div>
                    <strong class="d-block">Periode TNA Aktif ({{ \Carbon\Carbon::parse($tnaStartDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tnaEndDate)->format('d M Y') }})</strong>
                    <small>Sebagai Immediate Manager, Anda dapat memilih status <strong>In House Training</strong> untuk diajukan bagi staff Anda.</small>
                </div>
            </div>
        @endif

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
                        <div class="activity-item"><span class="activity-dot bg-primary"></span><div><p class="mb-1 fw-semibold">In House Training yang ingin diikuti (Dapat dipilih oleh Immediate Manager)</p></div></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Training Panels dynamically grouped --}}
        @php
            $groupedTrainings = $trainings->groupBy('jenis_training');
        @endphp

        @forelse ($groupedTrainings as $jenis => $items)
        <section class="row g-3 mb-3">
            <div class="col-12">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-journal-bookmark me-2" aria-hidden="true"></i><span>{{ $jenis ?: 'Training Lainnya' }}</span></h2>
                        </div>
                        <span class="badge bg-light text-dark border">{{ count($items) }} Training</span>
                    </div>
                    <div class="row g-3 p-3">
                        @foreach ($items as $t)
                        @php
                            $record = $staffTrainings->get($t->id_training);
                            $colorClass = $record ? ($statusColor[$record->id_status] ?? 'card-gray') : 'card-gray';
                            $statusId = $record ? $record->id_status : null;
                        @endphp
                        <div class="col-md-3 col-sm-6">
                            <div class="mini-card {{ $colorClass }}" id="card-{{ $t->id_training }}">
                                <span style="color: #ffffff; font-size: 0.75rem; display: block;">{{ $t->mandatory_training ?: '-' }}</span>
                                <strong class="d-block my-1">{{ $t->nama_training }}</strong>
                                <span style="color: #ffffff; font-size: 0.75rem; display: block;">{{ $t->gol_training ?: '-' }}</span>
                                
                                {{-- Dropdown Status Training (Immediate Manager logic) --}}
                                @if(!$isTnaActive)
                                    {{-- Read-only mode saat TNA ditutup --}}
                                    <div class="mt-2 pt-1 border-top border-light border-opacity-25 small text-white-50">
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
                                    <select class="form-select form-select-sm mt-2 status-select" data-training="{{ $t->id_training }}" {{ in_array($statusId, [1, 2, 3]) ? 'disabled' : '' }}>
                                        @if(in_array($statusId, [1, 2, 3]))
                                            @if($statusId == 1) <option selected disabled>Sudah Terlaksana (Ditentukan DLC)</option>
                                            @elseif($statusId == 2) <option selected disabled>Mandatory Training (Ditentukan DLC)</option>
                                            @elseif($statusId == 3) <option selected disabled>Tidak Hadir (Ditentukan DLC)</option>
                                            @endif
                                        @else
                                            <option value="" {{ !$record ? 'selected' : '' }} disabled>- Pilih Status -</option>
                                            <option value="4" {{ $statusId == 4 ? 'selected' : '' }}>In House Training</option>
                                        @endif
                                    </select>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @empty
        <section class="row g-3">
            <div class="col-12">
                <div class="panel p-4 text-center text-muted">
                    Belum ada data training yang terdaftar.
                </div>
            </div>
        </section>
        @endforelse

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

@endsection