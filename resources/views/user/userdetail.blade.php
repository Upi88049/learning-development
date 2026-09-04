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
                        Divisi: <strong>{{ $staff->divisi ? $staff->divisi->nama_divisi : '-' }}</strong> | 
                        Department: <strong>{{ $staff->department ? $staff->department->nama_department : '-' }}</strong> | 
                        Level: <strong>{{ $staff->levelJabatan ? $staff->levelJabatan->kode_level_jabatan : '-' }}</strong> | 
                        Umur: <strong>{{ $staff->umur ?: '-' }}</strong>
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

        {{-- Training Panels dynamically grouped in Accordion --}}
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
                </div>
                @empty
                <div class="panel p-4 text-center text-muted">
                    Belum ada data training yang terdaftar.
                </div>
                @endforelse
            </div>
        </div>

        {{-- ========== SECTION REQUEST TRAINING OUT HOUSE (OH) ========== --}}
        <section class="row g-3 mt-4">
            <div class="col-12">
                <div class="panel p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-box-arrow-up-right me-2 text-primary"></i>
                                <span>Request Training Out House (OH)</span>
                            </h2>
                            <p class="text-muted mb-0 small">
                                Form pengajuan permohonan training Out House untuk staff: <strong>{{ $staff->nama_staff }} ({{ $staff->npk_staff }})</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Form Input Request OH --}}
                    <form action="{{ route('outhouse.store') }}" method="POST" class="p-3 bg-light border rounded mb-4">
                        @csrf
                        <input type="hidden" name="id_staff" value="{{ $staff->id_staff }}">
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="judul_training" class="form-label fw-semibold">Judul Training <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="judul_training" name="judul_training" placeholder="Contoh: Pelatihan Sertifikasi BNSP, Advanced Data Analytics, dll." required>
                            </div>

                            <div class="col-md-6">
                                <label for="deskripsi_training" class="form-label fw-semibold">Deskripsi Training <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="deskripsi_training" name="deskripsi_training" rows="3" placeholder="Uraikan ringkasan materi, lembaga/vendor penyelenggara, atau silabus..." required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="reason" class="form-label fw-semibold">Reason / Alasan Kebutuhan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Jelaskan alasan bisnis, urgensi tugas kerja, atau kompetensi yang ingin ditingkatkan..." required></textarea>
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-send me-1"></i> Ajukan Request Training OH
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Riwayat Request Training OH --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 170px;">No. Request</th>
                                    <th scope="col">Judul Training</th>
                                    <th scope="col">Deskripsi Training</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col" style="width: 150px;">Status</th>
                                    <th scope="col" style="width: 170px;">Dokumen Formulir</th>
                                    <th scope="col" class="text-end" style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outhouseRequests ?? [] as $req)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">{{ $req->no_request }}</span>
                                        <small class="text-muted d-block mt-1">{{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '' }}</small>
                                    </td>
                                    <td class="fw-semibold">{{ $req->judul_training }}</td>
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
                                    <td>
                                        @if($req->status === 'Verified by DLC' || $req->status === 'Approve')
                                            @if($req->penugasan && $req->penugasan->is_sent)
                                                {{-- ENABLE: Formulir telah dibuat dan dikirim oleh DLC --}}
                                                <a href="{{ route('penugasan.downloadPdf', $req->penugasan->id_penugasan) }}" class="btn btn-success btn-sm d-inline-flex align-items-center" title="Unduh Dokumen Formulir Pendaftaran Training Resmi">
                                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh Formulir
                                                </a>
                                                <small class="text-success d-block mt-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-check-circle me-1"></i>Siap diunduh
                                                </small>
                                            @else
                                                {{-- DISABLE: Formulir belum dibuat atau belum dikirim oleh DLC --}}
                                                <button type="button" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center" disabled title="Formulir belum dikirim oleh DLC">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Formulir
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
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditOuthouse{{ $req->id_request_outhouse }}" title="Edit Request">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDeleteOuthouse{{ $req->id_request_outhouse }}" title="Hapus Request">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        {{-- Modal Edit Request OH --}}
                                        <div class="modal fade text-start" id="modalEditOuthouse{{ $req->id_request_outhouse }}" tabindex="-1" aria-labelledby="modalEditOuthouseLabel{{ $req->id_request_outhouse }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('outhouse.update', $req->id_request_outhouse) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalEditOuthouseLabel{{ $req->id_request_outhouse }}">
                                                                <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Request Training OH ({{ $req->no_request }})
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Judul Training <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="judul_training" value="{{ old('judul_training', $req->judul_training) }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Deskripsi Training <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="deskripsi_training" rows="3" required>{{ old('deskripsi_training', $req->deskripsi_training) }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Reason / Alasan Kebutuhan <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="reason" rows="3" required>{{ old('reason', $req->reason) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
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
                                                <div class="modal-content">
                                                    <form action="{{ route('outhouse.destroy', $req->id_request_outhouse) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-danger" id="modalDeleteOuthouseLabel{{ $req->id_request_outhouse }}">
                                                                <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Request
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus request training <strong>{{ $req->judul_training }}</strong> (No: <code>{{ $req->no_request }}</code>)?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-1"></i>
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