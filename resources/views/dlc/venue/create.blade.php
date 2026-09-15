@extends('layouts.admindlc')

@section('title', 'Tambah Venue | Dharma Learning Center')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        {{-- Hero Header Card --}}
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Master Data</span>
                            <span class="text-muted small">Tambah Baru</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Tambah Venue Baru</h1>
                        <p class="text-muted mb-0 small">Tambahkan ruang training internal atau fasilitas lokasi pelatihan eksternal.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('venue.index') }}">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Venue
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

        <form action="{{ route('venue.store') }}" method="POST" class="panel p-4">
            @csrf
            <div class="panel-header border-bottom pb-3 mb-3">
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-card-text me-2 text-primary" aria-hidden="true"></i>
                    <span>Informasi Venue</span>
                </h2>
                <p class="text-muted mb-0 small">Lengkapi data kode venue, nama tempat, dan tipe venue (Internal/External).</p>
            </div>

            <div class="row g-3">
                {{-- Venue Code --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="venue_code">
                        Venue Code <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control font-monospace @error('venue_code') is-invalid @enderror"
                           id="venue_code"
                           name="venue_code"
                           value="{{ old('venue_code', $suggestedCode) }}"
                           placeholder="Contoh: RT-005"
                           required>
                    <div class="form-text small">Kode unik ruangan atau tempat pelatihan.</div>
                    @error('venue_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Venue Name --}}
                <div class="col-12 col-md-5">
                    <label class="form-label fw-semibold" for="venue_name">
                        Venue Name <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('venue_name') is-invalid @enderror"
                           id="venue_name"
                           name="venue_name"
                           value="{{ old('venue_name') }}"
                           placeholder="Contoh: Ruang Training 2 / DOJO"
                           required>
                    @error('venue_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Venue Type --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" for="venue_type">
                        Venue Type <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('venue_type') is-invalid @enderror" id="venue_type" name="venue_type" required>
                        <option value="Internal" {{ old('venue_type') == 'Internal' ? 'selected' : '' }}>Internal</option>
                        <option value="External" {{ old('venue_type') == 'External' ? 'selected' : '' }}>External</option>
                    </select>
                    @error('venue_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('venue.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Venue
                </button>
            </div>
        </form>
    </div>
</main>

@endsection
