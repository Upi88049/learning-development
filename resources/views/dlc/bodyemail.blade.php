@extends('layouts.admindlc')

@section('title', 'Body Email Notifikasi | Dharma Learning Center')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-card-text"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Setting Notifikasi</span>
                            <span class="text-muted small">Template Pesan</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Pengaturan Body Email</h1>
                        <p class="text-muted mb-0 small">Atur daftar penerima, subjek, dan template konten pesan email notifikasi TNA bagi Immediate Manager.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('periode-tna') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Periode TNA
                    </a>
                    <a href="{{ route('penerima-email') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-people me-1"></i> Kelola Penerima
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-3">
            <!-- ========== KOLOM DAFTAR PENERIMA (KONFIGURASI PREVIOUS) ========== -->
            <div class="col-12 col-xl-4">
                <div class="panel h-100 p-4">
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-people me-2" aria-hidden="true"></i>
                            <span>Daftar Penerima (Immediate Manager)</span>
                        </h2>
                        <p class="text-muted small mb-0">Alamat email yang disimpan dari halaman Konfigurasi Penerima Email.</p>
                    </div>

                    <div class="penerima-list mt-3">
                        @forelse($recipientsList as $email)
                        <div class="d-flex align-items-center p-2 mb-2 bg-light border rounded">
                            <i class="bi bi-person-check text-primary me-2 fs-5"></i>
                            <div>
                                <strong class="d-block text-dark small">{{ $email }}</strong>
                                <small class="text-muted">Target Notifikasi</small>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-warning text-center small mb-0">
                            Belum ada konfigurasi email penerima. 
                            <a href="{{ route('penerima-email') }}" class="alert-link">Atur di sini</a>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('penerima-email') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i> Edit Penerima
                        </a>
                    </div>
                </div>
            </div>

            <!-- ========== FORM SUBJEK & ISI BODY EMAIL ========== -->
            <div class="col-12 col-xl-8">
                <form action="{{ route('body-email.store') }}" method="POST" class="panel p-4">
                    @csrf
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-envelope-paper me-2" aria-hidden="true"></i>
                            <span>Form Subjek &amp; Isi Email</span>
                        </h2>
                        <p class="text-muted mb-0">Isi email ditujukan kepada Immediate Manager berisi link ke dashboard untuk melihat staff dan training staff-nya.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="subject">Subjek Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $subject) }}" placeholder="Subjek email..." required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="body">Isi Body Email <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="body" name="body" rows="10" required>{{ old('body', $body) }}</textarea>
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i> Pastikan tautan ke dashboard Immediate Manager disertakan: 
                                <code>http://localhost/learningDevelopment/public/users</code>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Konfigurasi Body Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection