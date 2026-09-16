@extends('layouts.admindlc')

@section('title', 'Training Event (COE) | Dharma Learning Center')

@section('content')
<style>
.stat-chip {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    transition: all 0.2s ease;
}
.stat-chip:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
}
.stat-chip-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
.table-training-event {
    width: 100%;
    margin-bottom: 0;
}
.table-training-event thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 14px;
    border-bottom: 1.5px solid #e2e8f0;
}
.table-training-event tbody td {
    padding: 13px 14px;
    vertical-align: middle;
    font-size: 0.875rem;
    border-bottom: 1px solid #f1f5f9;
}
.table-training-event tbody tr:hover td {
    background: #f8fafc;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Hero Header --}}
        <div class="hero-header-card mb-4 p-4 bg-white rounded-4 border shadow-sm">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Calendar Of Event</span>
                            <span class="text-muted small">Detail Pelaksanaan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Daftar Training Event</h1>
                        <p class="text-muted mb-0 small">Manajemen detail pelaksanaan pelatihan: peserta TNA + atasan, peserta Non-TNA + PIC Subco, trainer, evaluasi, dan ruangan.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('coe.training-event.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2">
                        <i class="bi bi-plus-circle-fill"></i> Buat Training Event
                    </a>
                    <a href="{{ route('coe.kalender.index') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2">
                        <i class="bi bi-calendar-week"></i> Buka Kalender
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Metrics Row --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-chip">
                    <div class="stat-chip-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Training Event</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-chip">
                    <div class="stat-chip-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Penyelenggara Internal</div>
                        <div class="fs-4 fw-bold text-dark">{{ $internalCount }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-chip">
                    <div class="stat-chip-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-globe"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Penyelenggara External</div>
                        <div class="fs-4 fw-bold text-dark">{{ $externalCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Panel --}}
        <div class="bg-white rounded-4 border shadow-sm p-4">
            {{-- Filter & Search --}}
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <form action="{{ route('coe.training-event.index') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                    <select name="tipe" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        <option value="Internal" {{ request('tipe') == 'Internal' ? 'selected' : '' }}>Internal</option>
                        <option value="External" {{ request('tipe') == 'External' ? 'selected' : '' }}>External</option>
                    </select>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari trainer, topik, penyelenggara, ruangan..." value="{{ request('search') }}">
                        @if(request('search') || request('tipe'))
                        <a href="{{ route('coe.training-event.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>
                <div class="text-muted small">
                    Menampilkan <strong>{{ $trainingEvents->count() }}</strong> dari <strong>{{ $trainingEvents->total() }}</strong> event
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-training-event">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Event Kalender</th>
                            <th>Penyelenggara</th>
                            <th>Trainer &amp; Kelas</th>
                            <th>Ruangan</th>
                            <th>Tipe Evaluasi &amp; Soal</th>
                            <th>Peserta TNA</th>
                            <th>Peserta Non-TNA</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trainingEvents as $idx => $te)
                        <tr>
                            <td>{{ $trainingEvents->firstItem() + $idx }}</td>
                            <td>
                                @if($te->coeEvent)
                                <div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 font-monospace">
                                        {{ $te->coeEvent->no_event }}
                                    </span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border ms-1">
                                        {{ $te->coeEvent->batch_training }}
                                    </span>
                                </div>
                                <div class="fw-bold text-dark mt-1">{{ $te->coeEvent->nama_training }}</div>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar-event me-1"></i>{{ $te->coeEvent->tanggal_per_batch->translatedFormat('d F Y') }}
                                </div>
                                @else
                                <span class="text-muted small fst-italic">- Mandiri (Tidak Terhubung) -</span>
                                @endif
                            </td>
                            <td>
                                @if($te->tipe_penyelenggara == 'Internal')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-building me-1"></i>Internal
                                </span>
                                @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                    <i class="bi bi-globe me-1"></i>External
                                </span>
                                @endif
                                <div class="fw-semibold text-dark mt-1">{{ $te->nama_penyelenggara ?: '-' }}</div>
                            </td>
                            <td>
                                <div><i class="bi bi-person-badge text-primary me-1"></i><strong>{{ $te->trainer ?: '-' }}</strong></div>
                                @if($te->manager_class)
                                <div class="text-muted small mt-0.5"><i class="bi bi-person-gear me-1"></i>Class Mgr: {{ $te->manager_class }}</div>
                                @endif
                            </td>
                            <td>
                                @if($te->ruangan)
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-geo-alt text-danger me-1"></i>{{ $te->ruangan }}
                                </span>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-muted">Eval:</span> <strong>{{ $te->tipe_evaluasi ?: '-' }}</strong>
                                </div>
                                <div class="small text-muted">
                                    Soal: <strong>{{ $te->tipe_soal ?: '-' }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    {{ $te->total_peserta_tna }} Orang
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                    {{ $te->total_peserta_non_tna }} Orang
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('coe.training-event.edit', $te->id_training_event) }}" class="btn btn-sm btn-outline-primary p-1 px-2" title="Edit Training Event">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('coe.training-event.destroy', $te->id_training_event) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Training Event ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada konfigurasi data Training Event.
                                <div class="mt-2">
                                    <a href="{{ route('coe.training-event.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Buat Training Event Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($trainingEvents->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $trainingEvents->links() }}
            </div>
            @endif
        </div>

    </div>
</main>
@endsection
