@extends('layouts.admindlc')

@section('title', 'Periode TNA | Dharma Learning Center')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="hero-header-card mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Setting Sistem</span>
                            <span class="text-muted small">Training Needs Analysis</span>
                        </div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Periode TNA</h1>
                        <p class="text-muted mb-0 small">Pengaturan jadwal pembukaan periode pengisian TNA bagi Immediate Manager dan pengiriman notifikasi email.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($isTnaActive)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold">
                            <i class="bi bi-check-circle-fill me-1"></i> Periode Sedang Aktif
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill fw-semibold">
                            <i class="bi bi-dash-circle me-1"></i> Periode Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>   
        </div>

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

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show my-3" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <section class="row g-3">
            {{-- Panel Input Periode TNA --}}
            <div class="col-12 col-xl-6">
                <form action="{{ route('periode-tna.savePeriod') }}" method="POST" class="panel p-4 h-100">
                    @csrf
                    <div class="panel-header border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-calendar-check me-2 text-primary" aria-hidden="true"></i>
                                <span>Input Periode TNA</span>
                            </h2>
                            <p class="text-muted mb-0 small">Tentukan rentang tanggal pembukaan pengisian TNA.</p>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded border mb-3">
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-info-circle text-primary me-1"></i> <strong>Aturan Pengisian TNA:</strong>
                        </small>
                        <ul class="small text-muted mb-0 ps-3">
                            <li><strong>Immediate Manager</strong> hanya dapat mengisi status <em>In House Training</em> pada rentang tanggal aktif ini.</li>
                            <li>Jika melewati <strong>Tanggal End</strong>, Immediate Manager hanya dapat <strong>melihat (view only)</strong> status training staff.</li>
                            <li>Akun <strong>DLC</strong> dapat mengubah seluruh status training kapan saja.</li>
                        </ul>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="tna_start_date">Tanggal Start <span class="text-danger">*</span></label>
                            <input class="form-control" id="tna_start_date" name="tna_start_date" type="date" value="{{ old('tna_start_date', $tnaStartDate) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="tna_end_date">Tanggal End <span class="text-danger">*</span></label>
                            <input class="form-control" id="tna_end_date" name="tna_end_date" type="date" value="{{ old('tna_end_date', $tnaEndDate) }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Periode
                        </button>
                    </div>
                </form>
            </div>

            {{-- Panel Aksi & Notifikasi Email --}}
            <div class="col-12 col-xl-6">
                <div class="panel h-100 p-4">
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-send-check me-2 text-primary" aria-hidden="true"></i>
                            <span>Aksi &amp; Notifikasi Email</span>
                        </h2>
                        <p class="text-muted mb-0">Kirim email notifikasi pembukaan TNA ke Immediate Manager.</p>
                    </div>

                    <div class="mb-4">
                        <div class="p-3 bg-light border rounded">
                            <p class="mb-1 fw-semibold text-dark"><i class="bi bi-envelope-at text-primary me-2"></i>Konfigurasi Notifikasi Email:</p>
                            <small class="text-muted d-block mb-2">Terdapat <strong>{{ $recipientsCount }}</strong> alamat email penerima terdaftar.</small>
                            <div class="d-flex gap-2">
                                <a href="{{ route('penerima-email') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-people me-1"></i> Penerima Email
                                </a>
                                <a href="{{ route('body-email') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-card-text me-1"></i> Body Email
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2 pt-2">
                        <form action="{{ route('periode-tna.sendEmail') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-primary px-3" type="submit">
                                <i class="bi bi-send me-1" aria-hidden="true"></i> Send Email
                            </button>
                        </form>

                        <form action="{{ route('periode-tna.closeTna') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menutup periode TNA sekarang?')">
                            @csrf
                            <button class="btn btn-outline-danger px-3" type="submit">
                                <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Close TNA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection