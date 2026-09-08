@extends('layouts.admindlc')

@section('title', 'Edit Department | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Department</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Edit Department</h1>
                        <p class="text-muted mb-0 small">Perbarui data divisi induk atau nama department.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('department.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Department
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
            <div class="col-12 col-xl-6">
                <form action="{{ route('department.update', $department->id_department) }}" method="POST" class="panel p-4">
                    @csrf
                    @method('PUT')
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-card-heading me-2 text-primary" aria-hidden="true"></i><span>Ubah Informasi Department</span></h2>
                            <p class="text-muted mb-0 small">Perbarui divisi dan nama department berikut.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="id_divisi">Divisi Induk</label>
                            <select class="form-select" id="id_divisi" name="id_divisi">
                                <option value="">-- Tanpa Divisi (N/A) --</option>
                                @foreach($divisi as $div)
                                    <option value="{{ $div->id_divisi }}" {{ old('id_divisi', $department->id_divisi) == $div->id_divisi ? 'selected' : '' }}>
                                        {{ $div->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="nama_department">Nama Department <span class="text-danger">*</span></label>
                            <input class="form-control" id="nama_department" name="nama_department" type="text" value="{{ old('nama_department', $department->nama_department) }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a class="btn btn-outline-secondary" href="{{ route('department.index') }}">Batal</a>
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
