@extends('layouts.admindlc')

@section('title', 'Master Training | Dharma Learning Center')

@section('content')
<style>
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
.badge-inhouse {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
.badge-outhouse {
    background-color: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
</style>

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-mortarboard" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Kurikulum Pelatihan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Daftar Training</h1>
                        <p class="text-muted mb-0 small">Katalog seluruh topik pelatihan, kode training, scope pelaksanaan (In House &amp; Out House), mandatory status, dan golongan.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" href="{{ route('training.create') }}">
                        <i class="bi bi-plus-lg"></i> Tambah Training
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Alert --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3 border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Metric Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <a href="{{ route('training.index') }}" class="text-decoration-none">
                    <div class="stat-card {{ !request('scope') ? 'border-primary' : '' }}">
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Total Training Terdaftar</div>
                            <div class="fs-4 fw-bold text-dark">{{ $totalTrainings }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('training.index', ['scope' => 'In House']) }}" class="text-decoration-none">
                    <div class="stat-card {{ request('scope') == 'In House' ? 'border-info' : '' }}">
                        <div class="stat-icon bg-info-subtle text-info">
                            <i class="bi bi-building-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Scope: In House</div>
                            <div class="fs-4 fw-bold text-primary">{{ $inHouseCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('training.index', ['scope' => 'Out House']) }}" class="text-decoration-none">
                    <div class="stat-card {{ request('scope') == 'Out House' ? 'border-warning' : '' }}">
                        <div class="stat-icon bg-warning-subtle text-warning">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Scope: Out House</div>
                            <div class="fs-4 fw-bold text-warning">{{ $outHouseCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <section class="panel">
            <div class="panel-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom mb-3">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-table text-primary me-2" aria-hidden="true"></i>
                        <span>Training List</span>
                    </h2>
                    <p class="text-muted mb-0 small">
                        Daftar silabus pelatihan yang tersedia di sistem
                    </p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    {{-- Selector Entries --}}
                    <div class="table-entries-selector me-sm-2">
                        <span>Show</span>
                        <select class="form-select form-select-sm" data-table-entries="trainingTable">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>

                    {{-- Filter Scope Pill Buttons --}}
                    <div class="btn-group btn-group-sm" role="group" aria-label="Filter Scope">
                        <a href="{{ route('training.index') }}" class="btn {{ !request('scope') ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Semua
                        </a>
                        <a href="{{ route('training.index', ['scope' => 'In House']) }}" class="btn {{ request('scope') == 'In House' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            In House
                        </a>
                        <a href="{{ route('training.index', ['scope' => 'Out House']) }}" class="btn {{ request('scope') == 'Out House' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Out House
                        </a>
                    </div>

                    {{-- Search Form --}}
                    <form action="{{ route('training.index') }}" method="GET" class="d-flex gap-1" style="max-width: 320px;">
                        @if(request('scope'))
                            <input type="hidden" name="scope" value="{{ request('scope') }}">
                        @endif
                        <div class="input-group input-group-sm">
                            <div class="input-group input-group-sm" style="max-width: 280px;">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input class="form-control border-start-0 ps-0" type="search" name="search" placeholder="Cari kode, judul, atau jenis..." value="{{ request('search') }}" aria-label="Search training">
                            </div> 
                        </div>
                        @if(request('search'))
                            <a href="{{ route('training.index', request('scope') ? ['scope' => request('scope')] : []) }}" class="btn btn-outline-secondary btn-sm" title="Reset Cari">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary btn-sm px-2.5">Cari</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="trainingTable">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50px;" class="text-center">No</th>
                            <th scope="col" style="min-width: 120px;">Kode Training</th>
                            <th scope="col" style="min-width: 200px;">Judul Training</th>
                            <th scope="col" style="min-width: 110px;" class="text-center">Scope Training</th>
                            <th scope="col" style="min-width: 140px;">Jenis Training</th>
                            <th scope="col" style="min-width: 110px;">Mandatory</th>
                            <th scope="col" style="min-width: 100px;">Golongan</th>
                            <th scope="col" style="min-width: 120px;" class="text-center">Gambar / Detail</th>
                            <th scope="col" class="text-end" style="min-width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trainings as $index => $t)
                        <tr>
                            <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2.5 py-1">
                                    {{ $t->kode_training ?: '-' }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-dark cursor-pointer btn-view-detail"
                                        role="button"
                                        data-id="{{ $t->id_training }}"
                                        data-nama="{{ $t->nama_training }}"
                                        data-kode="{{ $t->kode_training ?: '-' }}"
                                        data-scope="{{ $t->scope_training ?: 'In House' }}"
                                        data-jenis="{{ $t->jenis_training }}"
                                        data-mandatory="{{ $t->mandatory_training ?: '-' }}"
                                        data-gol="{{ $t->gol_training ?: '-' }}"
                                        data-gambar="{{ $t->gambar ? asset('uploads/training/' . $t->gambar) : '' }}"
                                        data-deskripsi="{{ $t->deskripsi_training ?: '' }}"
                                        data-edit-url="{{ route('training.edit', $t->id_training) }}"
                                        title="Klik untuk melihat detail training">
                                    {{ $t->nama_training }}
                                </strong>
                            </td>
                            <td class="text-center">
                                @if($t->scope_training == 'Out House')
                                    <span class="badge badge-outhouse rounded-pill px-2.5 py-1">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Out House
                                    </span>
                                @else
                                    <span class="badge badge-inhouse rounded-pill px-2.5 py-1">
                                        <i class="bi bi-building-check me-1"></i> In House
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1">{{ $t->jenis_training }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $t->mandatory_training ?: '-' }}</span>
                            </td>
                            <td>{{ $t->gol_training ?: '-' }}</td>
                            <td class="text-center">
                                @if($t->gambar)
                                    <button type="button" 
                                            class="btn btn-sm p-0 border rounded overflow-hidden shadow-2xs btn-view-detail" 
                                            data-id="{{ $t->id_training }}"
                                            data-nama="{{ $t->nama_training }}"
                                            data-kode="{{ $t->kode_training ?: '-' }}"
                                            data-scope="{{ $t->scope_training ?: 'In House' }}"
                                            data-jenis="{{ $t->jenis_training }}"
                                            data-mandatory="{{ $t->mandatory_training ?: '-' }}"
                                            data-gol="{{ $t->gol_training ?: '-' }}"
                                            data-gambar="{{ asset('uploads/training/' . $t->gambar) }}"
                                            data-deskripsi="{{ $t->deskripsi_training ?: '' }}"
                                            data-edit-url="{{ route('training.edit', $t->id_training) }}"
                                            title="Lihat Gambar &amp; Detail Training">
                                        <img src="{{ asset('uploads/training/' . $t->gambar) }}" alt="{{ $t->nama_training }}" style="width: 36px; height: 36px; object-fit: cover;">
                                    </button>
                                @elseif($t->deskripsi_training)
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1 btn-view-detail py-1 px-2"
                                            data-id="{{ $t->id_training }}"
                                            data-nama="{{ $t->nama_training }}"
                                            data-kode="{{ $t->kode_training ?: '-' }}"
                                            data-scope="{{ $t->scope_training ?: 'In House' }}"
                                            data-jenis="{{ $t->jenis_training }}"
                                            data-mandatory="{{ $t->mandatory_training ?: '-' }}"
                                            data-gol="{{ $t->gol_training ?: '-' }}"
                                            data-gambar=""
                                            data-deskripsi="{{ $t->deskripsi_training ?: '' }}"
                                            data-edit-url="{{ route('training.edit', $t->id_training) }}"
                                            title="Lihat Deskripsi Detail">
                                        <i class="bi bi-card-text text-primary"></i> <span style="font-size: 0.72rem;">Detail</span>
                                    </button>
                                @else
                                    <span class="text-muted small" title="Belum ada gambar / detail">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-outline-info btn-sm btn-view-detail" 
                                            data-id="{{ $t->id_training }}"
                                            data-nama="{{ $t->nama_training }}"
                                            data-kode="{{ $t->kode_training ?: '-' }}"
                                            data-scope="{{ $t->scope_training ?: 'In House' }}"
                                            data-jenis="{{ $t->jenis_training }}"
                                            data-mandatory="{{ $t->mandatory_training ?: '-' }}"
                                            data-gol="{{ $t->gol_training ?: '-' }}"
                                            data-gambar="{{ $t->gambar ? asset('uploads/training/' . $t->gambar) : '' }}"
                                            data-deskripsi="{{ $t->deskripsi_training ?: '' }}"
                                            data-edit-url="{{ route('training.edit', $t->id_training) }}"
                                            title="Lihat Detail &amp; Gambar Training">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('training.edit', $t->id_training) }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('training.destroy', $t->id_training) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus training ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                Belum ada data training yang sesuai kriteria pencarian/filter.
                                @if(request('search') || request('scope'))
                                    <div class="mt-2">
                                        <a href="{{ route('training.index') }}" class="btn btn-outline-primary btn-sm">Reset Filter</a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination-footer" data-table-pagination="trainingTable">
                <p class="table-pagination-info"></p>
                <div class="pagination-container"></div>
            </div>
        </section>
    </div>
</main>

{{-- Modal Detail & Gambar Training --}}
<div class="modal fade text-start" id="modalTrainingDetail" tabindex="-1" aria-labelledby="modalTrainingDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
            <div class="modal-header bg-light border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 font-monospace fs-6 px-2.5 py-1" id="modalDetailKode">-</span>
                    <h5 class="modal-title fs-6 fw-bold text-dark mb-0" id="modalDetailNama">Detail Training</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    {{-- Media/Gambar Training --}}
                    <div class="col-md-5">
                        <div id="modalDetailImageWrapper" class="p-2 border rounded-3 bg-light d-flex flex-column align-items-center justify-content-center" style="min-height: 250px;">
                            <img id="modalDetailImage" src="#" alt="Poster Training" class="img-fluid rounded shadow-xs mb-2 d-none" style="max-height: 280px; width: 100%; object-fit: contain;">
                            <div id="modalDetailNoImage" class="text-muted p-4 text-center">
                                <i class="bi bi-image fs-1 d-block text-secondary opacity-50 mb-2"></i>
                                <span class="small d-block">Belum ada gambar/brosur silabus yang diunggah untuk training ini.</span>
                            </div>
                            <a id="modalDetailImageLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary d-none w-100 mt-2">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Gambar Penuh
                            </a>
                        </div>
                    </div>
                    {{-- Detail & Deskripsi Training --}}
                    <div class="col-md-7 d-flex flex-column">
                        <div class="d-flex flex-wrap gap-1.5 mb-3">
                            <span class="badge" id="modalDetailScope">-</span>
                            <span class="badge bg-secondary-subtle text-secondary border" id="modalDetailJenis">-</span>
                            <span class="badge bg-light text-dark border" id="modalDetailMandatory">-</span>
                            <span class="badge bg-light text-muted border" id="modalDetailGol">-</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-2 pb-2 border-bottom">
                            <i class="bi bi-card-text me-1 text-primary"></i> Deskripsi &amp; Silabus Materi
                        </h6>
                        <div class="p-3 bg-light rounded-3 border flex-grow-1" style="min-height: 160px; max-height: 240px; overflow-y: auto;">
                            <p class="mb-0 text-secondary small" id="modalDetailDeskripsi" style="white-space: pre-line;">-</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top d-flex justify-content-between py-2.5 px-4">
                <a id="modalDetailEditBtn" href="#" class="btn btn-primary btn-sm px-3 rounded-3 d-inline-flex align-items-center gap-1">
                    <i class="bi bi-pencil"></i> Edit Training
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalTrainingDetail');
    if (!modalEl) return;
    const modal = new bootstrap.Modal(modalEl);

    const elNama = document.getElementById('modalDetailNama');
    const elKode = document.getElementById('modalDetailKode');
    const elScope = document.getElementById('modalDetailScope');
    const elJenis = document.getElementById('modalDetailJenis');
    const elMandatory = document.getElementById('modalDetailMandatory');
    const elGol = document.getElementById('modalDetailGol');
    const elDeskripsi = document.getElementById('modalDetailDeskripsi');
    const elImage = document.getElementById('modalDetailImage');
    const elNoImage = document.getElementById('modalDetailNoImage');
    const elImageLink = document.getElementById('modalDetailImageLink');
    const elEditBtn = document.getElementById('modalDetailEditBtn');

    document.querySelectorAll('.btn-view-detail').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const d = this.dataset;

            elNama.textContent = d.nama || 'Detail Training';
            elKode.textContent = d.kode || '-';

            // Scope badge
            if (d.scope === 'Out House') {
                elScope.className = 'badge badge-outhouse rounded-pill px-2.5 py-1';
                elScope.innerHTML = '<i class="bi bi-box-arrow-up-right me-1"></i> Out House';
            } else {
                elScope.className = 'badge badge-inhouse rounded-pill px-2.5 py-1';
                elScope.innerHTML = '<i class="bi bi-building-check me-1"></i> In House';
            }

            elJenis.textContent = d.jenis || '-';
            elMandatory.textContent = 'Mandatory: ' + (d.mandatory || '-');
            elGol.textContent = 'Gol: ' + (d.gol || '-');

            // Description
            if (d.deskripsi && d.deskripsi.trim() !== '') {
                elDeskripsi.textContent = d.deskripsi;
                elDeskripsi.classList.remove('text-muted', 'fst-italic');
            } else {
                elDeskripsi.textContent = 'Belum ada deskripsi materi atau silabus detail yang ditambahkan untuk training ini.';
                elDeskripsi.classList.add('text-muted', 'fst-italic');
            }

            // Image
            if (d.gambar && d.gambar.trim() !== '') {
                elImage.src = d.gambar;
                elImage.classList.remove('d-none');
                elNoImage.classList.add('d-none');
                elImageLink.href = d.gambar;
                elImageLink.classList.remove('d-none');
            } else {
                elImage.src = '#';
                elImage.classList.add('d-none');
                elNoImage.classList.remove('d-none');
                elImageLink.href = '#';
                elImageLink.classList.add('d-none');
            }

            // Edit button url
            if (d.editUrl) {
                elEditBtn.href = d.editUrl;
                elEditBtn.classList.remove('d-none');
            } else {
                elEditBtn.classList.add('d-none');
            }

            modal.show();
        });
    });
});
</script>
@endsection
