@extends('layouts.admindlc')

@section('title', 'Edit Training | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Edit Kurikulum</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Edit Data Training</h1>
                        <p class="text-muted mb-0 small">Perbarui judul, mandatory status, atau golongan training.</p>
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
                <form action="{{ route('training.update', $training->id_training) }}" method="POST" class="panel p-4">
                    @csrf
                    @method('PUT')
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-card-heading me-2" aria-hidden="true"></i><span>Ubah Informasi Training</span></h2>
                            <p class="text-muted mb-0">Perbarui data training berikut.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="kode_training">Kode Training <span class="text-muted small">(Opsional)</span></label>
                            <input class="form-control font-monospace" id="kode_training" name="kode_training" type="text" value="{{ old('kode_training', $training->kode_training) }}" placeholder="Contoh: TRN-001">
                            <small class="text-muted d-block mt-1">Kode identifikasi unik untuk silabus training.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="scope_training">Scope Training <span class="text-danger">*</span></label>
                            <select class="form-select" id="scope_training" name="scope_training" required>
                                <option value="In House" {{ old('scope_training', $training->scope_training) == 'In House' ? 'selected' : '' }}>In House</option>
                                <option value="Out House" {{ old('scope_training', $training->scope_training) == 'Out House' ? 'selected' : '' }}>Out House</option>
                            </select>
                            <small class="text-muted d-block mt-1">Lingkup penyelenggaraan training (In House atau Out House).</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="nama_training">Judul / Nama Training <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_training" name="nama_training" type="text" value="{{ old('nama_training', $training->nama_training) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="jenis_training">Jenis Training <span class="text-danger">*</span></label>
                            <input class="form-control" id="jenis_training" name="jenis_training" type="text" value="{{ old('jenis_training', $training->jenis_training) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="mandatory_training">Mandatory Training (Opsional)</label>
                            <input class="form-control" id="mandatory_training" name="mandatory_training" type="text" value="{{ old('mandatory_training', $training->mandatory_training) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="gol_training">Golongan Training (Opsional)</label>
                            <input class="form-control" id="gol_training" name="gol_training" type="text" value="{{ old('gol_training', $training->gol_training) }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a class="btn btn-outline-secondary" href="{{ route('training.index') }}">Batal</a>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>

@endsection
