@extends('layouts.admindlc')

@section('content')
<style>
.metric-card-link {
  display: block;
  width: 100%;
  text-decoration: none;
  color: inherit;
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
}

.metric-card-link:hover,
.metric-card-link:focus {
  text-decoration: none;
  color: inherit;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}

.metric-card-link:active {
  transform: translateY(0);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.metric-card-link:focus-visible {
  box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.35);
}
</style>
<main class="admin-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Heading --}}
        <div class="page-heading mb-4">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Request Training Out House (OH)</h1>
                    <p class="text-muted mb-0">Daftar permohonan training Out House yang diajukan oleh Immediate Manager untuk staff masing-masing.</p>
                </div>
            </div>
        </div>

        {{-- Feedback Alert --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Metric Cards --}}
        <!-- ==========Total Request========== -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="metric-card-link card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block">Total Request</span>
                            <span class="fs-4 fw-bold text-dark">{{ $totalAll }}</span>
                        </div>
                        <span class="p-2 rounded-3 bg-light text-primary fs-4"><i class="bi bi-collection"></i></span>
                    </div>
                </div>
            </div>
            <!-- ==========Total Request========== -->
            <!-- ==========Pending========== -->
            <div class="col-6 col-md-3">
                <div class="metric-card-link card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block">Pending</span>
                            <span class="fs-4 fw-bold text-warning">{{ $countPending }}</span>
                        </div>
                        <span class="p-2 rounded-3 bg-warning-subtle text-warning fs-4"><i class="bi bi-hourglass-split"></i></span>
                    </div>
                </div>
            </div>
            <!-- ==========Pending========== -->
            <!-- ==========Verified by DLC========== -->
            <div class="col-6 col-md-3">
                <div class="metric-card-link card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block">Verified by DLC</span>
                            <span class="fs-4 fw-bold text-info">{{ $countVerified }}</span>
                        </div>
                        <span class="p-2 rounded-3 bg-info-subtle text-info fs-4"><i class="bi bi-patch-check"></i></span>
                    </div>
                </div>
            </div>
            <!-- ==========Verified by DLC========== -->
            <!-- ==========Approve========== -->
            <div class="col-6 col-md-3">
                <div class="metric-card-link card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block">Approve</span>
                            <span class="fs-4 fw-bold text-success">{{ $countApproved }}</span>
                        </div>
                        <span class="p-2 rounded-3 bg-success-subtle text-success fs-4"><i class="bi bi-check2-circle"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- ==========Approve========== -->

        {{-- Main Panel --}}
        <div class="panel p-4">
            {{-- Filter & Search Form --}}
            <form action="{{ route('outhouse.index') }}" method="GET" class="row g-3 align-items-end mb-4 pb-3 border-bottom">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="No. Request / Nama Staff / Judul Training...">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Filter Status</label>
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                        <option value="Pending" {{ $selectedStatus === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Verified by DLC" {{ $selectedStatus === 'Verified by DLC' ? 'selected' : '' }}>Verified by DLC</option>
                        <option value="Approve" {{ $selectedStatus === 'Approve' ? 'selected' : '' }}>Approve</option>
                        <option value="Rejected With Reason" {{ $selectedStatus === 'Rejected With Reason' ? 'selected' : '' }}>Rejected With Reason</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-filter me-1"></i> Terapkan Filter
                    </button>
                    @if($search || $selectedStatus !== 'all')
                    <a href="{{ route('outhouse.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                    @endif
                </div>
            </form>

            {{-- Table of Requests --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="min-width: 140px;">No. Request</th>
                            <th scope="col" style="min-width: 80px;">NPK</th>
                            <th scope="col" style="min-width: 150px;">Nama Staff</th>
                            <th scope="col" style="min-width: 130px;">Divisi</th>
                            <th scope="col" style="min-width: 130px;">Department</th>
                            <th scope="col" style="min-width: 140px;">Immediate Manager</th>
                            <th scope="col" style="min-width: 160px;">Judul Training</th>
                            <th scope="col" style="min-width: 200px;">Deskripsi Training</th>
                            <th scope="col" style="min-width: 180px;">Reason</th>
                            <th scope="col" style="min-width: 140px;">Status</th>
                            <th scope="col" class="text-end" style="min-width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">{{ $req->no_request }}</span>
                                <small class="text-muted d-block mt-1">{{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '-' }}</small>
                            </td>
                            <td class="fw-semibold">{{ $req->staff ? $req->staff->npk_staff : '-' }}</td>
                            <td>
                                <strong class="text-dark">{{ $req->staff ? $req->staff->nama_staff : '-' }}</strong>
                            </td>
                            <td>{{ $req->staff && $req->staff->divisi ? $req->staff->divisi->nama_divisi : '-' }}</td>
                            <td>{{ $req->staff && $req->staff->department ? $req->staff->department->nama_department : '-' }}</td>
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
                            <td>
                                <div style="max-width: 240px; white-space: normal;">
                                    {{ $req->deskripsi_training }}
                                </div>
                            </td>
                            <td>
                                <div style="max-width: 220px; white-space: normal;">
                                    {{ $req->reason }}
                                </div>
                            </td>
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
                                        <div class="mt-1 small text-danger" style="max-width: 180px; white-space: normal;">
                                            <strong>Alasan:</strong> {{ $req->alasan_reject }}
                                        </div>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">{{ $req->status }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    @if($req->status === 'Verified by DLC' || $req->status === 'Approve')
                                    <a href="{{ route('penugasan.create', ['from_request' => $req->id_request_outhouse]) }}" class="btn btn-outline-success btn-sm" title="Buat Formulir Pendaftaran & Penugasan Training">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $req->id_request_outhouse }}" title="Ubah Status Request">
                                        <i class="bi bi-sliders"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDeleteDlc{{ $req->id_request_outhouse }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- Modal Ubah Status DLC --}}
                                <div class="modal fade text-start" id="modalStatus{{ $req->id_request_outhouse }}" tabindex="-1" aria-labelledby="modalStatusLabel{{ $req->id_request_outhouse }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('outhouse.updateStatus', $req->id_request_outhouse) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalStatusLabel{{ $req->id_request_outhouse }}">
                                                        <i class="bi bi-check2-square me-2 text-primary"></i>Kelola Status Request Training OH
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="p-3 bg-light rounded mb-3">
                                                        <div class="row g-2 small">
                                                            <div class="col-6"><strong>No. Request:</strong> {{ $req->no_request }}</div>
                                                            <div class="col-6"><strong>Staff:</strong> {{ $req->staff ? $req->staff->nama_staff : '-' }}</div>
                                                            <div class="col-12"><strong>Judul Training:</strong> {{ $req->judul_training }}</div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Pilih Status Request <span class="text-danger">*</span></label>
                                                        <select class="form-select status-action-select" name="status" id="statusSelect{{ $req->id_request_outhouse }}" data-id="{{ $req->id_request_outhouse }}" required>
                                                            <option value="Pending" {{ $req->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="Verified by DLC" {{ $req->status === 'Verified by DLC' ? 'selected' : '' }}>Verified by DLC</option>
                                                            <option value="Approve" {{ $req->status === 'Approve' ? 'selected' : '' }}>Approve</option>
                                                            <option value="Rejected With Reason" {{ $req->status === 'Rejected With Reason' ? 'selected' : '' }}>Rejected With Reason</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 reject-reason-box {{ $req->status === 'Rejected With Reason' ? '' : 'd-none' }}" id="rejectBox{{ $req->id_request_outhouse }}">
                                                        <label class="form-label fw-semibold text-danger">Alasan Penolakan (Reject Reason) <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="alasan_reject" id="rejectReason{{ $req->id_request_outhouse }}" rows="3" placeholder="Tuliskan alasan mengapa request training ini ditolak...">{{ old('alasan_reject', $req->alasan_reject) }}</textarea>
                                                        <div class="form-text text-muted">Alasan penolakan akan ditampilkan kepada Immediate Manager.</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-save me-1"></i> Simpan Status
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Delete DLC --}}
                                <div class="modal fade text-start" id="modalDeleteDlc{{ $req->id_request_outhouse }}" tabindex="-1" aria-labelledby="modalDeleteDlcLabel{{ $req->id_request_outhouse }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('outhouse.destroyDlc', $req->id_request_outhouse) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger" id="modalDeleteDlcLabel{{ $req->id_request_outhouse }}">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus DLC
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Hapus data request training <strong>{{ $req->judul_training }}</strong> untuk staff <strong>{{ $req->staff ? $req->staff->nama_staff : '' }}</strong> (No: <code>{{ $req->no_request }}</code>)?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash me-1"></i> Ya, Hapus
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
                            <td colspan="11" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada permohonan Request Training Out House yang sesuai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 pt-3 border-top text-muted small">
                Total {{ count($requests) }} data request training ditampilkan.
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dynamic display of reject reason textarea
    document.querySelectorAll('.status-action-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const reqId = this.dataset.id;
            const rejectBox = document.getElementById('rejectBox' + reqId);
            const rejectTextarea = document.getElementById('rejectReason' + reqId);

            if (this.value === 'Rejected With Reason') {
                rejectBox.classList.remove('d-none');
                rejectTextarea.setAttribute('required', 'required');
            } else {
                rejectBox.classList.add('d-none');
                rejectTextarea.removeAttribute('required');
            }
        });
    });
});
</script>
@endsection
