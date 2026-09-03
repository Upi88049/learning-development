@extends('layouts.admindlc')

@section('content')
<main class="admin-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Heading --}}
        <div class="page-heading mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Formulir Pendaftaran &amp; Penugasan Training</h1>
                    <p class="text-muted mb-0">Kelola dan unduh dokumen resmi Formulir Pendaftaran &amp; Penugasan Training (Form 013/WI-).</p>
                </div>
            </div>
            <div>
                <a href="{{ route('penugasan.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Buat Form Penugasan Baru
                </a>
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

        {{-- Main Panel --}}
        <div class="panel p-4">
            {{-- Filter & Search --}}
            <form action="{{ route('penugasan.index') }}" method="GET" class="row g-3 align-items-end mb-4 pb-3 border-bottom">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Nama Training / Divisi / Tempat Training...">
                    </div>
                </div>

                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-filter me-1"></i> Cari Dokumen
                    </button>
                    @if($search)
                    <a href="{{ route('penugasan.index') }}" class="btn btn-outline-secondary" title="Reset Pencarian">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 60px;">No.</th>
                            <th scope="col">Nama Training</th>
                            <th scope="col">Jenis Training</th>
                            <th scope="col">SubCo / Divisi</th>
                            <th scope="col">Peserta</th>
                            <th scope="col">Total Biaya Investasi</th>
                            <th scope="col">Tempat &amp; Tanggal</th>
                            <th scope="col">Tanggal Dibuat</th>
                            <th scope="col" class="text-end" style="width: 170px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penugasanList as $index => $item)
                        @php
                            $peserta = $item->peserta;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark d-block">{{ $item->nama_training }}</strong>
                                <small class="text-muted">{{ $item->no_form }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->jenis_training }}</span>
                            </td>
                            <td>
                                <div>{{ $item->sub_co }}</div>
                                <small class="text-muted">Divisi: {{ $item->divisi ?: '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold">{{ $item->jumlah_peserta }} Peserta</span>
                                @if(!empty($peserta))
                                    <div class="mt-1 text-muted" style="max-width: 180px; font-size: 0.75rem;">
                                        {{ implode(', ', array_slice(array_column($peserta, 'nama'), 0, 2)) }}
                                        @if(count($peserta) > 2) ... @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-success">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</span>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">(@ Rp {{ number_format($item->biaya_per_peserta, 0, ',', '.') }})</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->tempat_tanggal_training ?: '-' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</small>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('penugasan.previewPdf', $item->id_penugasan) }}" target="_blank" class="btn btn-outline-info btn-sm" title="Preview PDF">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('penugasan.downloadPdf', $item->id_penugasan) }}" class="btn btn-outline-success btn-sm" title="Download PDF">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('penugasan.edit', $item->id_penugasan) }}" class="btn btn-outline-primary btn-sm" title="Edit Form">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDeletePenugasan{{ $item->id_penugasan }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- Modal Delete Penugasan --}}
                                <div class="modal fade text-start" id="modalDeletePenugasan{{ $item->id_penugasan }}" tabindex="-1" aria-labelledby="modalDeletePenugasanLabel{{ $item->id_penugasan }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('penugasan.destroy', $item->id_penugasan) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger" id="modalDeletePenugasanLabel{{ $item->id_penugasan }}">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Dokumen
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus Formulir Penugasan Training <strong>{{ $item->nama_training }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">
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
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>
                                Belum ada formulir pendaftaran &amp; penugasan training yang dibuat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 pt-3 border-top text-muted small">
                Total {{ count($penugasanList) }} formulir penugasan training tercatat.
            </div>
        </div>
    </div>
</main>
@endsection
