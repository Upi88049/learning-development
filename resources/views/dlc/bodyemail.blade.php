@extends('layouts.admindlc')

@section('title', 'Body Email Notifikasi | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-card-text" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Pengaturan Body Email</h1>
                    <p class="text-muted mb-0">Atur penerima, subjek, dan konten body email notifikasi untuk Immediate Manager.</p>
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