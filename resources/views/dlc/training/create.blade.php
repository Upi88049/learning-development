@extends('layouts.admindlc')

@section('title', 'Tambah Training | Dharma Learning Center')

@section('content')

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
                            <span class="text-muted small">Tambah Baru</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Tambah Training Baru</h1>
                        <p class="text-muted mb-0 small">Tambahkan topik pelatihan baru ke dalam kurikulum pembelajaran.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('training.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Training
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <section class="row g-3">
            <div class="col-12 col-xl-12">
                <form action="{{ route('training.store') }}" method="POST" class="panel p-4">
                    @csrf
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-card-heading me-2" aria-hidden="true"></i><span>Informasi Training</span></h2>
                            <p class="text-muted mb-0">Lengkapi informasi jenis, judul, mandatory status, dan golongan training.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="kode_training">Kode Training <span class="text-danger">*</span></label>
                            <input class="form-control font-monospace" id="kode_training" name="kode_training" type="text" value="{{ old('kode_training') }}" placeholder="Contoh: TRN-040" required>
                            <small class="text-muted d-block mt-1">Masukkan kode unik training secara manual.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="scope_training">Scope Training <span class="text-danger">*</span></label>
                            <select class="form-select" id="scope_training" name="scope_training" required>
                                <option value="In House" {{ old('scope_training', 'In House') == 'In House' ? 'selected' : '' }}>In House</option>
                                <option value="Out House" {{ old('scope_training') == 'Out House' ? 'selected' : '' }}>Out House</option>
                            </select>
                            <small class="text-muted d-block mt-1">Pilih jenis lingkup penyelenggaraan training.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="nama_training">Judul / Nama Training <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_training" name="nama_training" type="text" value="{{ old('nama_training') }}" placeholder="Contoh: DLTP 3, Communication Skill" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="jenis_training">Jenis Training <span class="text-danger">*</span></label>
                            <input class="form-control" id="jenis_training" name="jenis_training" type="text" value="{{ old('jenis_training') }}" placeholder="Contoh: Mandatory Training, Technical Training" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="mandatory_training">Mandatory Training (Opsional)</label>
                            <input class="form-control" id="mandatory_training" name="mandatory_training" type="text" value="{{ old('mandatory_training') }}" placeholder="Contoh: SH, DH, TL, SF">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="gol_training">Golongan Training (Opsional)</label>
                            <input class="form-control" id="gol_training" name="gol_training" type="text" value="{{ old('gol_training') }}" placeholder="Contoh: 3E - 4E, 1A - 2B">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a class="btn btn-outline-secondary" href="{{ route('training.index') }}">Batal</a>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Training
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>

@endsection
