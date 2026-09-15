@extends('layouts.admindlc')

@section('title', 'Edit Instruktur | Dharma Learning Center')

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
                            <span class="text-muted small">Edit Data</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Edit Instruktur</h1>
                        <p class="text-muted mb-0 small">Perbarui data informasi instruktur atau trainer pelatihan.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('instructor.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Instruktur
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('instructor.update', $instructor->id_instructor) }}" method="POST" class="panel p-4">
            @csrf
            @method('PUT')
            <div class="panel-header border-bottom pb-3 mb-3">
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-card-text me-2 text-primary" aria-hidden="true"></i>
                    <span>Edit Informasi Instruktur</span>
                </h2>
                <p class="text-muted mb-0 small">Kode Instruktur: <strong>{{ $instructor->instructor_code }}</strong></p>
            </div>

            <div class="row g-3">
                {{-- Instructor Code --}}
                <div class="col-12 col-md-5">
                    <label class="form-label fw-semibold" for="instructor_code">
                        Instructor Code <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control font-monospace @error('instructor_code') is-invalid @enderror"
                           id="instructor_code"
                           name="instructor_code"
                           value="{{ old('instructor_code', $instructor->instructor_code) }}"
                           required>
                    <div class="form-text small">Kode unik atau NPK instruktur.</div>
                    @error('instructor_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Instructor Name --}}
                <div class="col-12 col-md-7">
                    <label class="form-label fw-semibold" for="instructor_name">
                        Instructor Name <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('instructor_name') is-invalid @enderror"
                           id="instructor_name"
                           name="instructor_name"
                           value="{{ old('instructor_name', $instructor->instructor_name) }}"
                           required>
                    @error('instructor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Specialization --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="specialization">
                        Bidang Keahlian / Spesialisasi
                    </label>
                    <input type="text"
                           class="form-control @error('specialization') is-invalid @enderror"
                           id="specialization"
                           name="specialization"
                           value="{{ old('specialization', $instructor->specialization) }}">
                    @error('specialization')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="phone">
                        Nomor Telepon
                    </label>
                    <input type="text"
                           class="form-control @error('phone') is-invalid @enderror"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $instructor->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="email">
                        Email
                    </label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email', $instructor->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('instructor.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i> Perbarui Instruktur
                </button>
            </div>
        </form>
    </div>
</main>

@endsection
