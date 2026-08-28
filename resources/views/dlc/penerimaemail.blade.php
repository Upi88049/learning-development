@extends('layouts.admindlc')

@section('title', 'Email Notifikasi | Learning & Development')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-envelope-at" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Email Notifikasi</h1>
                    <p class="text-muted mb-0">Konfigurasi alamat email penerima notifikasi TNA.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <section class="row g-3">
            <div class="col-12 col-xl-12">
                <form action="{{ route('penerima-email.store') }}" method="POST" class="panel p-4">
                    @csrf
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-ui-checks-grid me-2" aria-hidden="true"></i>
                                <span>Daftar Email Penerima</span>
                            </h2>
                            <p class="text-muted mb-0">Masukkan satu email per baris. Email-email ini akan menerima notifikasi TNA.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="recipients">Daftar Email Penerima (1 Email per baris)</label>
                            <textarea class="form-control" id="recipients" name="recipients" rows="8" placeholder="contoh1@dharma.com&#10;contoh2@dharma.com" required>{{ old('recipients', $recipients) }}</textarea>
                            <small class="text-muted d-block mt-1">Gunakan baris baru (Enter) untuk memisahkan antar alamat email.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>

@endsection